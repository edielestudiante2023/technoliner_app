<?php

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoEspecificacionModel;
use App\Models\ProductoModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class ProductoModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';

    private int $categoriaId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoriaId = (new ProductoCategoriaModel())->insert([
            'nombre' => 'Categoría de prueba',
            'slug'   => 'categoria-de-prueba',
            'activo' => 1,
        ], true);
    }

    private function crearProducto(array $overrides = []): int
    {
        $model = new ProductoModel();

        return $model->insert(array_merge([
            'categoria_id' => $this->categoriaId,
            'nombre'       => 'Producto de prueba',
            'slug'         => 'producto-de-prueba',
            'activo'       => 0,
        ], $overrides), true);
    }

    public function testSkuVacioSeGuardaComoNull(): void
    {
        $id = $this->crearProducto(['sku' => '   ']);

        $producto = (new ProductoModel())->find($id);

        $this->assertNull($producto['sku']);
    }

    public function testSlugDuplicadoFallaValidacion(): void
    {
        $this->crearProducto();

        $model     = new ProductoModel();
        $resultado = $model->insert([
            'categoria_id' => $this->categoriaId,
            'nombre'       => 'Otro producto',
            'slug'         => 'producto-de-prueba',
            'activo'       => 0,
        ]);

        $this->assertFalse($resultado);
        $this->assertArrayHasKey('slug', $model->errors());
    }

    public function testEspecificacionDuplicadaPorProductoFallaEnBaseDeDatos(): void
    {
        $productoId = $this->crearProducto();
        $model      = new ProductoEspecificacionModel();

        $model->insert(['producto_id' => $productoId, 'nombre' => 'Diámetro', 'valor' => '38', 'unidad' => 'mm']);

        $this->expectException(\CodeIgniter\Database\Exceptions\DatabaseException::class);
        $model->insert(['producto_id' => $productoId, 'nombre' => 'Diámetro', 'valor' => '40', 'unidad' => 'mm']);
    }

    public function testProductoInactivoNoApareceEnConsultaPublica(): void
    {
        $this->crearProducto(['activo' => 0]);

        $model = new ProductoModel();

        $this->assertNull($model->encontrarPublicoPorSlug('producto-de-prueba'));
    }

    public function testProductoActivoApareceEnConsultaPublica(): void
    {
        $this->crearProducto(['activo' => 1]);

        $model    = new ProductoModel();
        $producto = $model->encontrarPublicoPorSlug('producto-de-prueba');

        $this->assertNotNull($producto);
        $this->assertSame('Categoría de prueba', $producto['categoria_nombre']);
    }
}
