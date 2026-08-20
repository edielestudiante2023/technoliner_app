<?php

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class CatalogoPublicoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    private function crearCategoria(array $overrides = []): int
    {
        $model = new ProductoCategoriaModel();

        return $model->insert(array_merge([
            'nombre' => 'Sellos de prueba',
            'slug'   => 'sellos-de-prueba',
            'activo' => 1,
        ], $overrides), true);
    }

    private function crearProducto(int $categoriaId, array $overrides = []): array
    {
        $model = new ProductoModel();

        $id = $model->insert(array_merge([
            'categoria_id'     => $categoriaId,
            'nombre'           => 'Producto de prueba',
            'slug'             => 'producto-de-prueba',
            'descripcion_html' => '<p>Descripción de prueba</p>',
            'activo'           => 0,
        ], $overrides), true);

        return $model->find($id);
    }

    public function testProductoInactivoNoApareceEnCatalogo(): void
    {
        $categoriaId = $this->crearCategoria();
        $this->crearProducto($categoriaId, ['activo' => 0]);

        $result = $this->withSession()->get('productos');

        $result->assertDontSee('Producto de prueba');
    }

    public function testProductoInactivoDaNotFoundEnDetalle(): void
    {
        $categoriaId = $this->crearCategoria();
        $this->crearProducto($categoriaId, ['activo' => 0]);

        $this->expectException(PageNotFoundException::class);

        $this->withSession()->get('productos/producto-de-prueba');
    }

    public function testProductoActivoApareceEnCatalogoYDetalle(): void
    {
        $categoriaId = $this->crearCategoria();
        $this->crearProducto($categoriaId, ['activo' => 1]);

        $listado = $this->withSession()->get('productos');
        $listado->assertSee('Producto de prueba');

        $detalle = $this->withSession()->get('productos/producto-de-prueba');
        $detalle->assertOK();
        $detalle->assertSee('Descripción de prueba');
    }

    public function testProductoActivoEnCategoriaInactivaNoApareceEnCatalogo(): void
    {
        $categoriaId = $this->crearCategoria(['activo' => 0]);
        $this->crearProducto($categoriaId, ['activo' => 1]);

        $result = $this->withSession()->get('productos');

        $result->assertDontSee('Producto de prueba');
    }

    public function testProductoActivoEnCategoriaInactivaDaNotFoundEnDetalle(): void
    {
        $categoriaId = $this->crearCategoria(['activo' => 0]);
        $this->crearProducto($categoriaId, ['activo' => 1]);

        $this->expectException(PageNotFoundException::class);

        $this->withSession()->get('productos/producto-de-prueba');
    }
}
