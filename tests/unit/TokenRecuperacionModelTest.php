<?php

use App\Models\RolModel;
use App\Models\TokenRecuperacionModel;
use App\Models\UsuarioModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class TokenRecuperacionModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';

    private int $usuarioId;

    protected function setUp(): void
    {
        parent::setUp();

        $rolModel = new RolModel();
        $rolId    = $rolModel->insert(['codigo' => 'administrador', 'nombre' => 'Administrador', 'activo' => 1], true);

        $usuarioModel    = new UsuarioModel();
        $this->usuarioId = $usuarioModel->insert([
            'rol_id'        => $rolId,
            'nombre'        => 'Usuario de Prueba',
            'email'         => 'prueba@technoliner.co',
            'password_hash' => password_hash('ClaveSegura1234', PASSWORD_DEFAULT),
            'activo'        => 1,
        ], true);
    }

    public function testTokenVigenteSeEncuentra(): void
    {
        $tokenModel = new TokenRecuperacionModel();
        $token      = $tokenModel->crearParaUsuario($this->usuarioId, '127.0.0.1', 60);

        $registro = $tokenModel->encontrarVigentePorToken($token);

        $this->assertNotNull($registro);
        $this->assertSame($this->usuarioId, (int) $registro['usuario_id']);
    }

    public function testTokenExpiradoNoSeEncuentra(): void
    {
        $tokenModel = new TokenRecuperacionModel();
        $token      = $tokenModel->crearParaUsuario($this->usuarioId, '127.0.0.1', -1);

        $registro = $tokenModel->encontrarVigentePorToken($token);

        $this->assertNull($registro);
    }

    public function testTokenUsadoNoSeEncuentra(): void
    {
        $tokenModel = new TokenRecuperacionModel();
        $token      = $tokenModel->crearParaUsuario($this->usuarioId, '127.0.0.1', 60);

        $registro = $tokenModel->encontrarVigentePorToken($token);
        $tokenModel->marcarUsado($registro['id']);

        $this->assertNull($tokenModel->encontrarVigentePorToken($token));
    }
}
