<?php

namespace App\Controllers\Admin;

use App\Models\ClienteModel;

class ClientesController extends BaseAdminController
{
    private const LOGOS_PATH = FCPATH . 'uploads/clientes/';

    public function index()
    {
        $model = new ClienteModel();

        $contenido = view('admin/clientes/index', [
            'clientes' => $model->orderBy('orden', 'ASC')->orderBy('nombre', 'ASC')->findAll(),
        ]);

        return view('admin/layout', [
            'titulo'    => 'Clientes',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $contenido = view('admin/clientes/form', [
            'modo'   => 'crear',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Nuevo cliente',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function crear()
    {
        if (! $this->validate($this->reglasCliente(true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $logoNombre = $this->procesarLogo();

        (new ClienteModel())->insert([
            'nombre'   => $this->request->getPost('nombre'),
            'logo'     => $logoNombre,
            'logo_alt' => $this->request->getPost('logo_alt'),
            'orden'    => (int) ($this->request->getPost('orden') ?? 0),
            'activo'   => 1,
        ]);

        return redirect()->to('/admin/clientes')->with('mensaje', 'Cliente creado correctamente.');
    }

    public function editar(int $id)
    {
        $model   = new ClienteModel();
        $cliente = $model->find($id);

        if (! $cliente) {
            return redirect()->to('/admin/clientes')->with('error', 'Cliente no encontrado.');
        }

        $contenido = view('admin/clientes/form', [
            'modo'    => 'editar',
            'cliente' => $cliente,
            'errors'  => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Editar cliente',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function actualizar(int $id)
    {
        $model   = new ClienteModel();
        $cliente = $model->find($id);

        if (! $cliente) {
            return redirect()->to('/admin/clientes')->with('error', 'Cliente no encontrado.');
        }

        if (! $this->validate($this->reglasCliente(false))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'logo_alt' => $this->request->getPost('logo_alt'),
            'orden'    => (int) ($this->request->getPost('orden') ?? 0),
        ];

        $logoNuevo = $this->procesarLogo();

        if ($logoNuevo !== null) {
            $this->borrarLogo($cliente['logo']);
            $data['logo'] = $logoNuevo;
        }

        $model->update($id, $data);

        return redirect()->to('/admin/clientes')->with('mensaje', 'Cliente actualizado correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $model   = new ClienteModel();
        $cliente = $model->find($id);

        if (! $cliente) {
            return redirect()->to('/admin/clientes')->with('error', 'Cliente no encontrado.');
        }

        $model->update($id, ['activo' => (int) $cliente['activo'] === 1 ? 0 : 1]);

        return redirect()->to('/admin/clientes')->with('mensaje', 'Estado actualizado.');
    }

    public function eliminar(int $id)
    {
        $model   = new ClienteModel();
        $cliente = $model->find($id);

        if (! $cliente) {
            return redirect()->to('/admin/clientes')->with('error', 'Cliente no encontrado.');
        }

        $this->borrarLogo($cliente['logo']);
        $model->delete($id);

        return redirect()->to('/admin/clientes')->with('mensaje', 'Cliente eliminado.');
    }

    private function reglasCliente(bool $logoRequerido): array
    {
        $reglaLogo = 'is_image[logo]|mime_in[logo,image/jpeg,image/png,image/webp]|ext_in[logo,jpg,jpeg,png,webp]|max_size[logo,2048]';

        return [
            'nombre' => 'required|max_length[120]',
            'logo'   => $logoRequerido ? 'uploaded[logo]|' . $reglaLogo : $reglaLogo,
        ];
    }

    private function procesarLogo(): ?string
    {
        $file = $this->request->getFile('logo');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE || $file->hasMoved()) {
            return null;
        }

        $valido = ENVIRONMENT === 'testing' ? $file->getError() === UPLOAD_ERR_OK : $file->isValid();

        if (! $valido) {
            return null;
        }

        if (! is_dir(self::LOGOS_PATH)) {
            mkdir(self::LOGOS_PATH, 0755, true);
        }

        $nombre = $file->getRandomName();
        $file->move(self::LOGOS_PATH, $nombre);

        return $nombre;
    }

    private function borrarLogo(?string $nombre): void
    {
        if (! $nombre) {
            return;
        }

        $ruta = self::LOGOS_PATH . $nombre;

        if (is_file($ruta)) {
            unlink($ruta);
        }
    }
}
