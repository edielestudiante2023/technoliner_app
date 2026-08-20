<?php

namespace App\Controllers\Admin;

use App\Libraries\SlugGenerator;
use App\Models\BlogCategoriaModel;

class BlogCategoriasController extends BaseAdminController
{
    public function index()
    {
        $model = new BlogCategoriaModel();

        $contenido = view('admin/blog/categorias/index', [
            'categorias' => $model->orderBy('orden', 'ASC')->findAll(),
        ]);

        return view('admin/layout', [
            'titulo'    => 'Categorías del blog',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $contenido = view('admin/blog/categorias/form', [
            'modo'   => 'crear',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Nueva categoría',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function crear()
    {
        $rules = [
            'nombre' => 'required|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new BlogCategoriaModel();
        $nombre = $this->request->getPost('nombre');

        $model->insert([
            'nombre'      => $nombre,
            'slug'        => SlugGenerator::generar($model, $nombre),
            'descripcion' => $this->request->getPost('descripcion'),
            'orden'       => (int) ($this->request->getPost('orden') ?? 0),
            'activo'      => 1,
        ]);

        return redirect()->to('/admin/blog/categorias')->with('mensaje', 'Categoría creada correctamente.');
    }

    public function editar(int $id)
    {
        $model    = new BlogCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/blog/categorias')->with('error', 'Categoría no encontrada.');
        }

        $contenido = view('admin/blog/categorias/form', [
            'modo'      => 'editar',
            'categoria' => $categoria,
            'errors'    => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Editar categoría',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function actualizar(int $id)
    {
        $model     = new BlogCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/blog/categorias')->with('error', 'Categoría no encontrada.');
        }

        $rules = ['nombre' => 'required|max_length[100]'];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nombre = $this->request->getPost('nombre');
        $data   = [
            'nombre'      => $nombre,
            'descripcion' => $this->request->getPost('descripcion'),
            'orden'       => (int) ($this->request->getPost('orden') ?? 0),
        ];

        if ($nombre !== $categoria['nombre']) {
            $data['slug'] = SlugGenerator::generar($model, $nombre, 'slug', $id);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/blog/categorias')->with('mensaje', 'Categoría actualizada correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $model     = new BlogCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/blog/categorias')->with('error', 'Categoría no encontrada.');
        }

        $model->update($id, ['activo' => (int) $categoria['activo'] === 1 ? 0 : 1]);

        return redirect()->to('/admin/blog/categorias')->with('mensaje', 'Estado actualizado.');
    }
}
