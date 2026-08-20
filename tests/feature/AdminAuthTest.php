<?php

use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AdminAuthTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    private int $rolId;

    protected function setUp(): void
    {
        parent::setUp();

        $rolModel    = new RolModel();
        $this->rolId = $rolModel->insert(['codigo' => 'administrador', 'nombre' => 'Administrador', 'activo' => 1], true);
    }

    private function crearUsuario(string $email, string $password, int $activo = 1): int
    {
        $usuarioModel = new UsuarioModel();

        return $usuarioModel->insert([
            'rol_id'        => $this->rolId,
            'nombre'        => 'Usuario de Prueba',
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'activo'        => $activo,
        ], true);
    }

    public function testAccesoNoAutenticadoRedirigeALogin(): void
    {
        $result = $this->withSession()->get('admin');

        $result->assertRedirectTo('/admin/login');
    }

    public function testLoginCorrectoRedirigeAlPanel(): void
    {
        $this->crearUsuario('admin@technoliner.co', 'ClaveSegura1234');

        $result = $this->withSession()->post('admin/login', [
            'email'    => 'admin@technoliner.co',
            'password' => 'ClaveSegura1234',
        ]);

        $result->assertRedirectTo('/admin');
        $result->assertSessionHas('admin_email', 'admin@technoliner.co');
    }

    public function testLoginConContrasenaIncorrectaNoIniciaSesion(): void
    {
        $this->crearUsuario('admin@technoliner.co', 'ClaveSegura1234');

        $result = $this->withSession()->post('admin/login', [
            'email'    => 'admin@technoliner.co',
            'password' => 'claveIncorrecta',
        ]);

        $result->assertSessionMissing('admin_usuario_id');
    }

    public function testLoginDeUsuarioInactivoEsRechazado(): void
    {
        $this->crearUsuario('inactivo@technoliner.co', 'ClaveSegura1234', 0);

        $result = $this->withSession()->post('admin/login', [
            'email'    => 'inactivo@technoliner.co',
            'password' => 'ClaveSegura1234',
        ]);

        $result->assertSessionMissing('admin_usuario_id');
    }

    public function testLogoutCierraLaSesion(): void
    {
        $this->crearUsuario('admin@technoliner.co', 'ClaveSegura1234');

        $this->withSession()->post('admin/login', [
            'email'    => 'admin@technoliner.co',
            'password' => 'ClaveSegura1234',
        ]);

        $result = $this->withSession()->post('admin/logout');

        $result->assertSessionMissing('admin_usuario_id');
    }
}
