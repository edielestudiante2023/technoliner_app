<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuarios';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'rol_id',
        'nombre',
        'email',
        'password_hash',
        'activo',
        'ultimo_login_at',
        'intentos_fallidos',
        'bloqueado_hasta',
        'password_changed_at',
    ];

    protected $validationRules = [
        'rol_id' => 'required|is_natural_no_zero',
        'nombre' => 'required|max_length[120]',
        'email'  => 'required|valid_email|max_length[190]|is_unique[usuarios.email,id,{id}]',
    ];

    protected $beforeInsert = ['normalizeEmail'];
    protected $beforeUpdate = ['normalizeEmail'];

    protected function normalizeEmail(array $data): array
    {
        if (isset($data['data']['email'])) {
            $data['data']['email'] = mb_strtolower(trim($data['data']['email']));
        }

        return $data;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', mb_strtolower(trim($email)))->first();
    }

    public function countAdministradoresActivos(): int
    {
        return $this->select('usuarios.id')
            ->join('roles', 'roles.id = usuarios.rol_id')
            ->where('usuarios.activo', 1)
            ->where('roles.codigo', 'administrador')
            ->countAllResults();
    }
}
