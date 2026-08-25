<?php

namespace App\Controllers;

use App\Models\ArticuloBlogModel;
use App\Models\BlogCategoriaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BlogController extends BaseController
{
    public function index()
    {
        $model = new ArticuloBlogModel();
        $pagina = max(1, (int) ($this->request->getGet('page') ?? 1));

        $articulos = $model->publicados()
            ->select('articulos_blog.*, blog_categorias.nombre as categoria_nombre, blog_categorias.slug as categoria_slug')
            ->join('blog_categorias', 'blog_categorias.id = articulos_blog.categoria_id', 'left')
            ->paginate(9);

        $canonical = site_url('blog');
        $titulo    = 'Guías técnicas de liners y sellado de envases | Technoliner';

        if ($pagina > 1) {
            $canonical .= '?page=' . $pagina;
            $titulo     = 'Guías técnicas de liners — Página ' . $pagina . ' | Technoliner';
        }

        return view('blog/index', [
            'empresa'    => $this->datosEmpresa(),
            'articulos'  => $articulos,
            'pager'      => $model->pager,
            'categorias'  => (new BlogCategoriaModel())->activas(),
            'titulo'      => $titulo,
            'descripcion' => 'Guías de sellado, materiales y seguridad para empaques industriales.',
            'canonical'   => $canonical,
            'robots'      => 'index, follow',
        ]);
    }

    public function ver(string $slug)
    {
        $model    = new ArticuloBlogModel();
        $articulo = $model->select('articulos_blog.*, blog_categorias.nombre as categoria_nombre, blog_categorias.slug as categoria_slug')
            ->join('blog_categorias', 'blog_categorias.id = articulos_blog.categoria_id', 'left')
            ->where('articulos_blog.publicado', 1)
            ->where('articulos_blog.publicado_at <=', date('Y-m-d H:i:s'))
            ->where('articulos_blog.slug', $slug)
            ->first();

        if (! $articulo) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('blog/detalle', [
            'empresa'     => $this->datosEmpresa(),
            'articulo'    => $articulo,
            'titulo'      => ($articulo['seo_titulo'] ?: $articulo['titulo']) . ' — Blog Technoliner',
            'descripcion' => $articulo['seo_descripcion'] ?: $articulo['extracto'],
            'canonical'   => site_url('blog/' . $articulo['slug']),
            'robots'      => 'index, follow',
        ]);
    }
}
