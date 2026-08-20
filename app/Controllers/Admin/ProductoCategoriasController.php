<?php

namespace App\Controllers\Admin;

use App\Libraries\SlugGenerator;
use App\Models\ProductoCategoriaModel;

class ProductoCategoriasController extends BaseAdminController
{
    public function index()
    {
        $model = new ProductoCategoriaModel();

        $contenido = view('admin/productos/categorias/index', [
            'categorias' => $model->orderBy('parent_id', 'ASC')->orderBy('orden', 'ASC')->findAll(),
        ]);

        return view('admin/layout', [
            'titulo'    => 'Categorías de productos',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $model = new ProductoCategoriaModel();

        $contenido = view('admin/productos/categorias/form', [
            'modo'       => 'crear',
            'principales' => $model->principales(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Nueva categoría de productos',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function crear()
    {
        if (! $this->validate(['nombre' => 'required|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model  = new ProductoCategoriaModel();
        $nombre = $this->request->getPost('nombre');

        $model->insert([
            'parent_id'       => $this->request->getPost('parent_id') ?: null,
            'nombre'          => $nombre,
            'slug'            => SlugGenerator::generar($model, $nombre, 'slug'),
            'descripcion'     => $this->request->getPost('descripcion'),
            'orden'           => (int) ($this->request->getPost('orden') ?? 0),
            'seo_titulo'      => $this->request->getPost('seo_titulo'),
            'seo_descripcion' => $this->request->getPost('seo_descripcion'),
            'activo'          => 1,
        ]);

        return redirect()->to('/admin/productos/categorias')->with('mensaje', 'Categoría creada correctamente.');
    }

    public function editar(int $id)
    {
        $model     = new ProductoCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/productos/categorias')->with('error', 'Categoría no encontrada.');
        }

        $contenido = view('admin/productos/categorias/form', [
            'modo'        => 'editar',
            'categoria'   => $categoria,
            'principales' => array_filter($model->principales(), static fn ($c) => (int) $c['id'] !== $id),
            'errors'      => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Editar categoría de productos',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function actualizar(int $id)
    {
        $model     = new ProductoCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/productos/categorias')->with('error', 'Categoría no encontrada.');
        }

        if (! $this->validate(['nombre' => 'required|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $parentId = $this->request->getPost('parent_id') ?: null;

        if ($parentId !== null && (int) $parentId === $id) {
            return redirect()->back()->withInput()->with('error', 'Una categoría no puede ser su propia categoría padre.');
        }

        $nombre = $this->request->getPost('nombre');
        $data   = [
            'parent_id'       => $parentId,
            'nombre'          => $nombre,
            'descripcion'     => $this->request->getPost('descripcion'),
            'orden'           => (int) ($this->request->getPost('orden') ?? 0),
            'seo_titulo'      => $this->request->getPost('seo_titulo'),
            'seo_descripcion' => $this->request->getPost('seo_descripcion'),
        ];

        if ($nombre !== $categoria['nombre']) {
            $data['slug'] = SlugGenerator::generar($model, $nombre, 'slug', $id);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/productos/categorias')->with('mensaje', 'Categoría actualizada correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $model     = new ProductoCategoriaModel();
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to('/admin/productos/categorias')->with('error', 'Categoría no encontrada.');
        }

        $model->update($id, ['activo' => (int) $categoria['activo'] === 1 ? 0 : 1]);

        return redirect()->to('/admin/productos/categorias')->with('mensaje', 'Estado actualizado.');
    }
}
