<?php

namespace App\Controllers;

use App\Models\ProductoCategoriaModel;
use App\Models\ProductoEspecificacionModel;
use App\Models\ProductoImagenModel;
use App\Models\ProductoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductoController extends BaseController
{
    public function index()
    {
        $model = new ProductoModel();

        $categoriaSlug = $this->request->getGet('categoria');
        $pagina        = max(1, (int) ($this->request->getGet('page') ?? 1));
        $consulta      = $model->publicosQuery()->orderBy('productos.destacado', 'DESC')->orderBy('productos.orden', 'ASC');

        if ($categoriaSlug) {
            $consulta->where('producto_categorias.slug', $categoriaSlug);
        }

        $productos = $consulta->paginate(12);

        $canonical = site_url('productos');
        $titulo    = 'Liners y sellos para envases industriales | Technoliner';
        $robots    = 'index, follow';

        if ($categoriaSlug) {
            // Los filtros serán páginas SEO propias en una fase posterior.
            $robots = 'noindex, follow';
        } elseif ($pagina > 1) {
            $canonical .= '?page=' . $pagina;
            $titulo     = 'Liners y sellos para envases — Página ' . $pagina . ' | Technoliner';
        }

        return view('productos/index', [
            'empresa'       => $this->datosEmpresa(),
            'productos'     => $productos,
            'pager'         => $model->pager,
            'categorias'    => (new ProductoCategoriaModel())->activas(),
            'categoriaSlug' => $categoriaSlug,
            'titulo'        => $titulo,
            'descripcion'   => 'Liners y sellos para empaques industriales: sensibles a presión, espumados y por inducción.',
            'canonical'     => $canonical,
            'robots'        => $robots,
        ]);
    }

    public function ver(string $slug)
    {
        $producto = (new ProductoModel())->encontrarPublicoPorSlug($slug);

        if (! $producto) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('productos/detalle', [
            'empresa'          => $this->datosEmpresa(),
            'producto'         => $producto,
            'imagenes'         => (new ProductoImagenModel())->porProducto($producto['id']),
            'especificaciones' => (new ProductoEspecificacionModel())->porProducto($producto['id']),
            'titulo'           => ($producto['seo_titulo'] ?: $producto['nombre']) . ' — Technoliner SAS',
            'descripcion'      => $producto['seo_descripcion'] ?: $producto['resumen'],
            'canonical'        => site_url('productos/' . $producto['slug']),
            'robots'           => 'index, follow',
        ]);
    }
}
