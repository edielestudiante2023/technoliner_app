<?php

use App\Models\ArticuloBlogModel;
use App\Models\BlogCategoriaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class BlogPublicoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    private function crearArticulo(array $overrides = []): array
    {
        $categoriaModel = new BlogCategoriaModel();
        $categoriaId    = $categoriaModel->insert(['nombre' => 'Guías', 'slug' => 'guias', 'activo' => 1], true);

        $articuloModel = new ArticuloBlogModel();
        $data          = array_merge([
            'categoria_id'   => $categoriaId,
            'titulo'         => 'Artículo de prueba',
            'slug'           => 'articulo-de-prueba',
            'contenido_html' => '<p>Contenido de prueba</p>',
            'publicado'      => 0,
            'publicado_at'   => null,
        ], $overrides);

        $id = $articuloModel->insert($data, true);

        return $articuloModel->find($id);
    }

    public function testArticuloDespublicadoNoApareceEnListado(): void
    {
        $this->crearArticulo(['publicado' => 0]);

        $result = $this->withSession()->get('blog');

        $result->assertDontSee('Artículo de prueba');
    }

    public function testArticuloDespublicadoDaNotFoundEnDetalle(): void
    {
        $this->crearArticulo(['publicado' => 0]);

        // Sin un 404 override configurado, CodeIgniter relanza la excepción en vez
        // de convertirla en una respuesta 404 dentro de una petición simulada.
        $this->expectException(PageNotFoundException::class);

        $this->withSession()->get('blog/articulo-de-prueba');
    }

    public function testArticuloPublicadoApareceEnListadoYDetalle(): void
    {
        $this->crearArticulo([
            'publicado'    => 1,
            'publicado_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        ]);

        $listado = $this->withSession()->get('blog');
        $listado->assertSee('Artículo de prueba');

        $detalle = $this->withSession()->get('blog/articulo-de-prueba');
        $detalle->assertOK();
        $detalle->assertSee('Contenido de prueba');
    }

    public function testArticuloProgramadoAFuturoNoApareceAun(): void
    {
        $this->crearArticulo([
            'publicado'    => 1,
            'publicado_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
        ]);

        $result = $this->withSession()->get('blog');

        $result->assertDontSee('Artículo de prueba');
    }
}
