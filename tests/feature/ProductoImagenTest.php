<?php

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoImagenModel;
use App\Models\ProductoModel;
use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class ProductoImagenTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    private string $imagenValida;
    private string $archivoFalso;
    private array $sesionAdmin;
    private int $productoId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imagenValida = sys_get_temp_dir() . '/technoliner_prod_valida_' . uniqid() . '.png';
        $im = imagecreatetruecolor(10, 10);
        imagepng($im, $this->imagenValida);
        imagedestroy($im);

        $this->archivoFalso = sys_get_temp_dir() . '/technoliner_prod_falso_' . uniqid() . '.png';
        file_put_contents($this->archivoFalso, '<?php echo "hack"; ?>');

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

        $categoriaId = (new ProductoCategoriaModel())->insert([
            'nombre' => 'Categoría de prueba',
            'slug'   => 'categoria-de-prueba',
            'activo' => 1,
        ], true);

        $this->productoId = (new ProductoModel())->insert([
            'categoria_id' => $categoriaId,
            'nombre'       => 'Producto de prueba',
            'slug'         => 'producto-de-prueba',
            'activo'       => 0,
        ], true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->imagenValida);
        @unlink($this->archivoFalso);
        service('superglobals')->setFilesArray([]);
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

    public function testArchivoPhpDisfrazadoDePngEsRechazado(): void
    {
        $this->simularArchivoSubido($this->archivoFalso);

        $this->withSession($this->sesionAdmin)->post('admin/productos/' . $this->productoId . '/imagenes', [
            'alt_text' => 'foto',
        ]);

        $imagenModel = new ProductoImagenModel();
        $this->assertSame(0, $imagenModel->where('producto_id', $this->productoId)->countAllResults());
    }

    public function testSinArchivoNoAgregaImagen(): void
    {
        service('superglobals')->setFilesArray([
            'imagen' => [
                'name' => '', 'type' => '', 'tmp_name' => '', 'error' => UPLOAD_ERR_NO_FILE, 'size' => 0,
            ],
        ]);

        $this->withSession($this->sesionAdmin)->post('admin/productos/' . $this->productoId . '/imagenes', [
            'alt_text' => 'foto',
        ]);

        $imagenModel = new ProductoImagenModel();
        $this->assertSame(0, $imagenModel->where('producto_id', $this->productoId)->countAllResults());
    }

    public function testImagenValidaPasaLaValidacionDeArchivo(): void
    {
        $this->simularArchivoSubido($this->imagenValida);

        $request = service('incomingrequest', config('App'), false);
        Config\Services::injectMock('request', $request);

        $validation = service('validation', null, false);
        $validation->setRules([
            'imagen' => 'is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]|ext_in[imagen,jpg,jpeg,png,webp]|max_size[imagen,5120]',
        ]);

        $this->assertTrue($validation->withRequest($request)->run());
    }
}
