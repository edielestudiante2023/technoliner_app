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

        $blogLastmod      = $this->ultimaModificacion($articulos, '2026-08-13');
        $productosLastmod = $this->ultimaModificacion($productos, '2026-08-20');
        $inicioLastmod    = $this->ultimaModificacion(array_merge($articulos, $productos), '2026-08-25');

        $urls = [
            ['loc' => site_url('/'), 'lastmod' => $inicioLastmod],
            ['loc' => site_url('blog'), 'lastmod' => $blogLastmod],
            ['loc' => site_url('productos'), 'lastmod' => $productosLastmod],
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

    /**
     * @param list<array<string, mixed>> $registros
     */
    private function ultimaModificacion(array $registros, string $fallback): string
    {
        $ultima = strtotime($fallback);

        foreach ($registros as $registro) {
            if (empty($registro['updated_at'])) {
                continue;
            }

            $timestamp = strtotime((string) $registro['updated_at']);

            if ($timestamp !== false) {
                $ultima = max($ultima, $timestamp);
            }
        }

        return date('Y-m-d', $ultima);
    }
}
