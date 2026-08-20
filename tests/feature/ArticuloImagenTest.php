<?php

use App\Models\ArticuloBlogModel;
use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ArticuloImagenTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    private string $imagenValida;
    private string $archivoFalso;
    private array $sesionAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imagenValida = sys_get_temp_dir() . '/technoliner_test_valida_' . uniqid() . '.png';
        $im = imagecreatetruecolor(10, 10);
        imagepng($im, $this->imagenValida);
        imagedestroy($im);

        $this->archivoFalso = sys_get_temp_dir() . '/technoliner_test_falso_' . uniqid() . '.png';
        file_put_contents($this->archivoFalso, '<?php echo "hack"; ?>');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->imagenValida);
        @unlink($this->archivoFalso);
        service('superglobals')->setFilesArray([]);
    }

    private function loguearAdmin(): void
    {
        $rolId = (new RolModel())->insert(['codigo' => 'administrador', 'nombre' => 'Administrador', 'activo' => 1], true);

        $usuarioId = (new UsuarioModel())->insert([
            'rol_id'        => $rolId,
            'nombre'        => 'Admin de Prueba',
            'email'         => 'admin@technoliner.co',
            'password_hash' => password_hash('ClaveSegura1234', PASSWORD_DEFAULT),
            'activo'        => 1,
        ], true);

        $this->sesionAdmin = [
            'admin_usuario_id' => $usuarioId,
            'admin_nombre'     => 'Admin de Prueba',
            'admin_email'      => 'admin@technoliner.co',
        ];
    }

    private function simularArchivoSubido(string $ruta): void
    {
        service('superglobals')->setFilesArray([
            'imagen' => [
                'name'     => 'foto.png',
                'type'     => 'image/png',
                'tmp_name' => $ruta,
                'error'    => UPLOAD_ERR_OK,
                'size'     => filesize($ruta),
            ],
        ]);
    }

    private function simularCampoImagenVacio(): void
    {
        // Así llega un <input type="file"> real cuando el usuario no elige archivo:
        // el campo está presente en $_FILES pero con error UPLOAD_ERR_NO_FILE.
        service('superglobals')->setFilesArray([
            'imagen' => [
                'name'     => '',
                'type'     => '',
                'tmp_name' => '',
                'error'    => UPLOAD_ERR_NO_FILE,
                'size'     => 0,
            ],
        ]);
    }

    public function testArchivoPhpDisfrazadoDePngEsRechazado(): void
    {
        $this->loguearAdmin();
        $this->simularArchivoSubido($this->archivoFalso);

        $this->withSession($this->sesionAdmin)->post('admin/blog/articulos', [
            'titulo'         => 'Artículo con imagen falsa',
            'contenido_html' => '<p>Contenido</p>',
        ]);

        $articuloModel = new ArticuloBlogModel();
        $this->assertSame(0, $articuloModel->countAllResults());
    }

    public function testImagenValidaPasaLaValidacionDeArchivo(): void
    {
        // No se puede probar move_uploaded_file() end-to-end aquí: PHP exige que el
        // archivo provenga de una subida HTTP real, algo que una petición simulada
        // de FeatureTestTrait no puede producir. Se verifica en su lugar que las
        // reglas de validación (is_image/mime_in/ext_in/max_size) aceptan una
        // imagen real, que es la parte de la lógica que sí controlamos.
        $this->simularArchivoSubido($this->imagenValida);

        $request = service('incomingrequest', config('App'), false);
        Config\Services::injectMock('request', $request);

        // Instancia nueva (no compartida): FileRules fija el request en el
        // constructor, así que una instancia ya existente seguiría apuntando
        // a la petición de un test anterior.
        $validation = service('validation', null, false);
        $validation->setRules([
            'imagen' => 'is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]|ext_in[imagen,jpg,jpeg,png,webp]|max_size[imagen,5120]',
        ]);

        $this->assertTrue($validation->withRequest($request)->run());
    }

    public function testArticuloSinImagenSeCreaCorrectamente(): void
    {
        $this->loguearAdmin();
        $this->simularCampoImagenVacio();

        $this->withSession($this->sesionAdmin)->post('admin/blog/articulos', [
            'titulo'         => 'Artículo sin imagen',
            'contenido_html' => '<p>Contenido</p>',
        ]);

        $articuloModel = new ArticuloBlogModel();
        $articulo      = $articuloModel->first();

        $this->assertNotNull($articulo);
        $this->assertNull($articulo['imagen_portada']);
    }
}
