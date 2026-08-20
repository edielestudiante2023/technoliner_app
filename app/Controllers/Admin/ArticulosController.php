<?php

namespace App\Controllers\Admin;

use App\Libraries\HtmlSanitizer;
use App\Libraries\SlugGenerator;
use App\Models\ArticuloBlogModel;
use App\Models\BlogCategoriaModel;

class ArticulosController extends BaseAdminController
{
    private const IMAGENES_PATH = FCPATH . 'uploads/blog/';

    public function index()
    {
        $model = new ArticuloBlogModel();

        $articulos = $model
            ->select('articulos_blog.*, blog_categorias.nombre as categoria_nombre')
            ->join('blog_categorias', 'blog_categorias.id = articulos_blog.categoria_id', 'left')
            ->orderBy('articulos_blog.created_at', 'DESC')
            ->findAll();

        $contenido = view('admin/blog/articulos/index', ['articulos' => $articulos]);

        return view('admin/layout', [
            'titulo'    => 'Artículos del blog',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $contenido = view('admin/blog/articulos/form', [
            'modo'       => 'crear',
            'categorias' => (new BlogCategoriaModel())->activas(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'        => 'Nuevo artículo',
            'usuario'       => $this->usuarioActual(),
            'contenido'     => $contenido,
            'extraHead'     => $this->assetsEditor('css'),
            'extraScripts'  => $this->assetsEditor('js'),
        ]);
    }

    public function crear()
    {
        if (! $this->validate($this->reglasArticulo())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model  = new ArticuloBlogModel();
        $titulo = $this->request->getPost('titulo');

        $imagenNombre = $this->procesarImagen();

        $data = [
            'categoria_id'    => $this->request->getPost('categoria_id') ?: null,
            'autor_id'        => $this->usuarioActual()['id'],
            'titulo'          => $titulo,
            'slug'            => SlugGenerator::generar($model, $titulo),
            'extracto'        => $this->request->getPost('extracto'),
            'contenido_html'  => (new HtmlSanitizer())->sanitizar((string) $this->request->getPost('contenido_html')),
            'imagen_portada'  => $imagenNombre,
            'imagen_alt'      => $this->request->getPost('imagen_alt'),
            'seo_titulo'      => $this->request->getPost('seo_titulo'),
            'seo_descripcion' => $this->request->getPost('seo_descripcion'),
            'destacado'       => $this->request->getPost('destacado') ? 1 : 0,
            'publicado'       => 0,
        ];

        $model->insert($data);

        return redirect()->to('/admin/blog/articulos')->with('mensaje', 'Artículo creado como borrador.');
    }

    public function editar(int $id)
    {
        $model    = new ArticuloBlogModel();
        $articulo = $model->find($id);

        if (! $articulo) {
            return redirect()->to('/admin/blog/articulos')->with('error', 'Artículo no encontrado.');
        }

        $contenido = view('admin/blog/articulos/form', [
            'modo'       => 'editar',
            'articulo'   => $articulo,
            'categorias' => (new BlogCategoriaModel())->activas(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'        => 'Editar artículo',
            'usuario'       => $this->usuarioActual(),
            'contenido'     => $contenido,
            'extraHead'     => $this->assetsEditor('css'),
            'extraScripts'  => $this->assetsEditor('js'),
        ]);
    }

    public function actualizar(int $id)
    {
        $model    = new ArticuloBlogModel();
        $articulo = $model->find($id);

        if (! $articulo) {
            return redirect()->to('/admin/blog/articulos')->with('error', 'Artículo no encontrado.');
        }

        if (! $this->validate($this->reglasArticulo())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $titulo = $this->request->getPost('titulo');

        $data = [
            'categoria_id'    => $this->request->getPost('categoria_id') ?: null,
            'titulo'          => $titulo,
            'extracto'        => $this->request->getPost('extracto'),
            'contenido_html'  => (new HtmlSanitizer())->sanitizar((string) $this->request->getPost('contenido_html')),
            'imagen_alt'      => $this->request->getPost('imagen_alt'),
            'seo_titulo'      => $this->request->getPost('seo_titulo'),
            'seo_descripcion' => $this->request->getPost('seo_descripcion'),
            'destacado'       => $this->request->getPost('destacado') ? 1 : 0,
        ];

        if ($titulo !== $articulo['titulo']) {
            $data['slug'] = SlugGenerator::generar($model, $titulo, 'slug', $id);
        }

        $imagenNueva = $this->procesarImagen();

        if ($imagenNueva !== null) {
            $this->borrarImagen($articulo['imagen_portada']);
            $data['imagen_portada'] = $imagenNueva;
        }

        $model->update($id, $data);

        return redirect()->to('/admin/blog/articulos')->with('mensaje', 'Artículo actualizado correctamente.');
    }

    public function publicar(int $id)
    {
        $model    = new ArticuloBlogModel();
        $articulo = $model->find($id);

        if (! $articulo) {
            return redirect()->to('/admin/blog/articulos')->with('error', 'Artículo no encontrado.');
        }

        $model->update($id, [
            'publicado'    => 1,
            'publicado_at' => $articulo['publicado_at'] ?? date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/blog/articulos')->with('mensaje', 'Artículo publicado.');
    }

    public function despublicar(int $id)
    {
        $model    = new ArticuloBlogModel();
        $articulo = $model->find($id);

        if (! $articulo) {
            return redirect()->to('/admin/blog/articulos')->with('error', 'Artículo no encontrado.');
        }

        $model->update($id, ['publicado' => 0]);

        return redirect()->to('/admin/blog/articulos')->with('mensaje', 'Artículo despublicado.');
    }

    private function assetsEditor(string $tipo): string
    {
        if ($tipo === 'css') {
            return '<link rel="stylesheet" href="' . base_url('assets/vendor/quill/quill.snow.css') . '">';
        }

        return '<script src="' . base_url('assets/vendor/quill/quill.min.js') . '"></script>';
    }

    private function reglasArticulo(): array
    {
        return [
            'titulo'         => 'required|max_length[255]',
            'contenido_html' => 'required',
            // Nota: "permit_empty" no funciona con reglas de archivo (el campo nunca está en
            // $data, solo en $_FILES), así que se saltaría la validación siempre. Las reglas de
            // archivo de CodeIgniter ya tratan UPLOAD_ERR_NO_FILE como válido, así que un campo
            // de imagen vacío pasa igual sin necesidad de "permit_empty".
            'imagen'         => 'is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]|ext_in[imagen,jpg,jpeg,png,webp]|max_size[imagen,5120]',
        ];
    }

    private function procesarImagen(): ?string
    {
        $file = $this->request->getFile('imagen');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE || $file->hasMoved()) {
            return null;
        }

        $valido = ENVIRONMENT === 'testing' ? $file->getError() === UPLOAD_ERR_OK : $file->isValid();

        if (! $valido) {
            return null;
        }

        if (! is_dir(self::IMAGENES_PATH)) {
            mkdir(self::IMAGENES_PATH, 0755, true);
        }

        $nombre = $file->getRandomName();
        $file->move(self::IMAGENES_PATH, $nombre);

        return $nombre;
    }

    private function borrarImagen(?string $nombre): void
    {
        if (! $nombre) {
            return;
        }

        $ruta = self::IMAGENES_PATH . $nombre;

        if (is_file($ruta)) {
            unlink($ruta);
        }
    }
}
