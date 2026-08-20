<?php

use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class UsuarioModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';

    private int $rolId;

    protected function setUp(): void
    {
        parent::setUp();

        $rolModel    = new RolModel();
        $this->rolId = $rolModel->insert([
            'codigo'  => 'administrador',
            'nombre'  => 'Administrador',
            'activo'  => 1,
        ], true);
    }

    private function crearUsuario(array $overrides = []): int
    {
        $usuarioModel = new UsuarioModel();

        return $usuarioModel->insert(array_merge([
            'rol_id'        => $this->rolId,
            'nombre'        => 'Usuario de Prueba',
            'email'         => 'Prueba@Technoliner.co',
            'password_hash' => password_hash('ClaveSegura1234', PASSWORD_DEFAULT),
            'activo'        => 1,
        ], $overrides), true);
    }

    public function testEmailSeAlmacenaEnMinusculas(): void
    {
        $this->crearUsuario();

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->findByEmail('PRUEBA@technoliner.co');

        $this->assertNotNull($usuario);
        $this->assertSame('prueba@technoliner.co', $usuario['email']);
    }

    public function testEmailDuplicadoFallaValidacion(): void
    {
        $this->crearUsuario();

        $usuarioModel = new UsuarioModel();
        $resultado    = $usuarioModel->insert([
            'rol_id'        => $this->rolId,
            'nombre'        => 'Otro Usuario',
            'email'         => 'prueba@technoliner.co',
            'password_hash' => password_hash('OtraClaveSegura1234', PASSWORD_DEFAULT),
            'activo'        => 1,
        ]);

        $this->assertFalse($resultado);
        $this->assertArrayHasKey('email', $usuarioModel->errors());
    }

    public function testCuentaSoloAdministradoresActivos(): void
    {
        $this->crearUsuario(['email' => 'activo@technoliner.co', 'activo' => 1]);
        $this->crearUsuario(['email' => 'inactivo@technoliner.co', 'activo' => 0]);

        $usuarioModel = new UsuarioModel();

        $this->assertSame(1, $usuarioModel->countAdministradoresActivos());
    }
}
