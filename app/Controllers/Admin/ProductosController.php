<?php

namespace App\Controllers\Admin;

use App\Libraries\HtmlSanitizer;
use App\Libraries\SlugGenerator;
use App\Models\ProductoCategoriaModel;
use App\Models\ProductoEspecificacionModel;
use App\Models\ProductoImagenModel;
use App\Models\ProductoModel;

class ProductosController extends BaseAdminController
{
    private const IMAGENES_PATH = FCPATH . 'uploads/productos/';

    public function index()
    {
        $model = new ProductoModel();

        $productos = $model
            ->select('productos.*, producto_categorias.nombre as categoria_nombre')
            ->join('producto_categorias', 'producto_categorias.id = productos.categoria_id')
            ->orderBy('productos.created_at', 'DESC')
            ->findAll();

        $contenido = view('admin/productos/index', ['productos' => $productos]);

        return view('admin/layout', [
            'titulo'    => 'Productos',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $contenido = view('admin/productos/form', [
            'modo'       => 'crear',
            'categorias' => (new ProductoCategoriaModel())->activas(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'       => 'Nuevo producto',
            'usuario'      => $this->usuarioActual(),
            'contenido'    => $contenido,
            'extraHead'    => $this->assetsEditor('css'),
            'extraScripts' => $this->assetsEditor('js'),
        ]);
    }

    public function crear()
    {
        if (! $this->validate($this->reglasProducto())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model  = new ProductoModel();
        $nombre = $this->request->getPost('nombre');

        $model->insert([
            'categoria_id'     => (int) $this->request->getPost('categoria_id'),
            'creado_por'       => $this->usuarioActual()['id'],
            'nombre'           => $nombre,
            'slug'             => SlugGenerator::generar($model, $nombre, 'slug'),
            'sku'              => $this->request->getPost('sku'),
            'resumen'          => $this->request->getPost('resumen'),
            'descripcion_html' => (new HtmlSanitizer())->sanitizar((string) $this->request->getPost('descripcion_html')),
            'seo_titulo'       => $this->request->getPost('seo_titulo'),
            'seo_descripcion'  => $this->request->getPost('seo_descripcion'),
            'activo'           => 0,
            'destacado'        => 0,
        ]);

        return redirect()->to('/admin/productos')->with('mensaje', 'Producto creado como inactivo. Agrega imágenes y especificaciones antes de publicarlo.');
    }

    public function editar(int $id)
    {
        $producto = (new ProductoModel())->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        $contenido = view('admin/productos/form', [
            'modo'             => 'editar',
            'producto'         => $producto,
            'categorias'       => (new ProductoCategoriaModel())->activas(),
            'especificaciones' => (new ProductoEspecificacionModel())->porProducto($id),
            'imagenes'         => (new ProductoImagenModel())->porProducto($id),
            'errors'           => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'       => 'Editar producto',
            'usuario'      => $this->usuarioActual(),
            'contenido'    => $contenido,
            'extraHead'    => $this->assetsEditor('css'),
            'extraScripts' => $this->assetsEditor('js'),
        ]);
    }

    public function actualizar(int $id)
    {
        $model    = new ProductoModel();
        $producto = $model->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        if (! $this->validate($this->reglasProducto())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nombre = $this->request->getPost('nombre');
        $data   = [
            'categoria_id'     => (int) $this->request->getPost('categoria_id'),
            'nombre'           => $nombre,
            'sku'              => $this->request->getPost('sku'),
            'resumen'          => $this->request->getPost('resumen'),
            'descripcion_html' => (new HtmlSanitizer())->sanitizar((string) $this->request->getPost('descripcion_html')),
            'seo_titulo'       => $this->request->getPost('seo_titulo'),
            'seo_descripcion'  => $this->request->getPost('seo_descripcion'),
        ];

        if ($nombre !== $producto['nombre']) {
            $data['slug'] = SlugGenerator::generar($model, $nombre, 'slug', $id);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $model    = new ProductoModel();
        $producto = $model->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        $model->update($id, ['activo' => (int) $producto['activo'] === 1 ? 0 : 1]);

        return redirect()->to('/admin/productos')->with('mensaje', 'Estado actualizado.');
    }

    public function cambiarDestacado(int $id)
    {
        $model    = new ProductoModel();
        $producto = $model->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        $model->update($id, ['destacado' => (int) $producto['destacado'] === 1 ? 0 : 1]);

        return redirect()->to('/admin/productos')->with('mensaje', 'Estado actualizado.');
    }

    public function agregarEspecificacion(int $id)
    {
        $producto = (new ProductoModel())->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        $rules = [
            'nombre' => 'required|max_length[120]',
            'valor'  => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/productos/' . $id . '/editar')->with('errors', $this->validator->getErrors());
        }

        $model = new ProductoEspecificacionModel();

        $existe = $model->where('producto_id', $id)->where('nombre', $this->request->getPost('nombre'))->countAllResults();

        if ($existe > 0) {
            return redirect()->to('/admin/productos/' . $id . '/editar')->with('error', 'Ya existe una especificación con ese nombre para este producto.');
        }

        $model->insert([
            'producto_id' => $id,
            'nombre'      => $this->request->getPost('nombre'),
            'valor'       => $this->request->getPost('valor'),
            'unidad'      => $this->request->getPost('unidad'),
            'orden'       => (int) $model->where('producto_id', $id)->countAllResults(),
        ]);

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Especificación agregada.');
    }

    public function eliminarEspecificacion(int $id, int $especId)
    {
        (new ProductoEspecificacionModel())->where('producto_id', $id)->delete($especId);

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Especificación eliminada.');
    }

    public function subirImagen(int $id)
    {
        $producto = (new ProductoModel())->find($id);

        if (! $producto) {
            return redirect()->to('/admin/productos')->with('error', 'Producto no encontrado.');
        }

        $file = $this->request->getFile('imagen');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return redirect()->to('/admin/productos/' . $id . '/editar')->with('error', 'Selecciona una imagen.');
        }

        $rules = [
            'imagen' => 'is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]|ext_in[imagen,jpg,jpeg,png,webp]|max_size[imagen,5120]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/productos/' . $id . '/editar')->with('errors', $this->validator->getErrors());
        }

        $valido = ENVIRONMENT === 'testing' ? $file->getError() === UPLOAD_ERR_OK : $file->isValid();

        if (! $valido || $file->hasMoved()) {
            return redirect()->to('/admin/productos/' . $id . '/editar')->with('error', 'No se pudo procesar la imagen.');
        }

        if (! is_dir(self::IMAGENES_PATH)) {
            mkdir(self::IMAGENES_PATH, 0755, true);
        }

        $nombreOriginal = $file->getClientName();
        $mimeType       = $file->getMimeType();
        $tamanoBytes    = $file->getSize();
        $nombreArchivo  = $file->getRandomName();

        $file->move(self::IMAGENES_PATH, $nombreArchivo);

        $imagenModel = new ProductoImagenModel();
        $esPrimera   = $imagenModel->where('producto_id', $id)->countAllResults() === 0;

        $nuevaId = $imagenModel->insert([
            'producto_id'     => $id,
            'ruta'            => $nombreArchivo,
            'nombre_original' => $nombreOriginal,
            'mime_type'       => $mimeType,
            'tamano_bytes'    => $tamanoBytes,
            'alt_text'        => $this->request->getPost('alt_text'),
            'es_principal'    => $esPrimera ? 1 : 0,
            'orden'           => $imagenModel->where('producto_id', $id)->countAllResults(),
        ], true);

        if ($esPrimera) {
            $imagenModel->marcarComoPrincipal($nuevaId, $id);
        }

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Imagen agregada.');
    }

    public function marcarImagenPrincipal(int $id, int $imagenId)
    {
        (new ProductoImagenModel())->marcarComoPrincipal($imagenId, $id);

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Imagen principal actualizada.');
    }

    public function eliminarImagen(int $id, int $imagenId)
    {
        $model  = new ProductoImagenModel();
        $imagen = $model->where('producto_id', $id)->find($imagenId);

        if ($imagen) {
            $ruta = self::IMAGENES_PATH . $imagen['ruta'];

            if (is_file($ruta)) {
                unlink($ruta);
            }

            $model->delete($imagenId);

            if ((int) $imagen['es_principal'] === 1) {
                $siguiente = $model->where('producto_id', $id)->orderBy('orden', 'ASC')->first();

                if ($siguiente) {
                    $model->marcarComoPrincipal($siguiente['id'], $id);
                }
            }
        }

        return redirect()->to('/admin/productos/' . $id . '/editar')->with('mensaje', 'Imagen eliminada.');
    }

    private function assetsEditor(string $tipo): string
    {
        if ($tipo === 'css') {
            return '<link rel="stylesheet" href="' . base_url('assets/vendor/quill/quill.snow.css') . '">';
        }

        return '<script src="' . base_url('assets/vendor/quill/quill.min.js') . '"></script>';
    }

    private function reglasProducto(): array
    {
        return [
            'categoria_id' => 'required|is_natural_no_zero',
            'nombre'       => 'required|max_length[180]',
        ];
    }
}
