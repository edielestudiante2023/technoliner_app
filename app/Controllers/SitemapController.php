<?php

namespace App\Controllers;

use App\Models\ArticuloBlogModel;
use App\Models\ProductoModel;

class SitemapController extends BaseController
{
    public function index()
    {
        $articulos = (new ArticuloBlogModel())->publicados()->findAll();
        $productos = (new ProductoModel())->publicosQuery()->findAll();

        $urls = [
            ['loc' => site_url('/'), 'lastmod' => date('Y-m-d')],
            ['loc' => site_url('blog'), 'lastmod' => date('Y-m-d')],
            ['loc' => site_url('productos'), 'lastmod' => date('Y-m-d')],
            ['loc' => site_url('politica-tratamiento-datos'), 'lastmod' => '2026-08-13'],
        ];

        foreach ($articulos as $articulo) {
            $urls[] = [
                'loc'     => site_url('blog/' . $articulo['slug']),
                'lastmod' => date('Y-m-d', strtotime($articulo['updated_at'])),
            ];
        }

        foreach ($productos as $producto) {
            $urls[] = [
                'loc'     => site_url('productos/' . $producto['slug']),
                'lastmod' => date('Y-m-d', strtotime($producto['updated_at'])),
            ];
        }

        return $this->response
            ->setContentType('application/xml')
            ->setBody(view('sitemap', ['urls' => $urls]));
    }
}
