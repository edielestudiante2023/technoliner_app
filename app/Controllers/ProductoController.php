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
        $consulta      = $model->publicosQuery()->orderBy('productos.destacado', 'DESC')->orderBy('productos.orden', 'ASC');

        if ($categoriaSlug) {
            $consulta->where('producto_categorias.slug', $categoriaSlug);
        }

        $productos = $consulta->paginate(12);

        return view('productos/index', [
            'empresa'       => $this->datosEmpresa(),
            'productos'     => $productos,
            'pager'         => $model->pager,
            'categorias'    => (new ProductoCategoriaModel())->activas(),
            'categoriaSlug' => $categoriaSlug,
            'titulo'        => 'Catálogo de productos — Technoliner SAS',
            'descripcion'   => 'Liners y sellos para empaques industriales: sensibles a presión, espumados y por inducción.',
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
        ]);
    }
}
