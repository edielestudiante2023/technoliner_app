<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table         = 'contactos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'producto_id',
        'nombre',
        'email',
        'telefono',
        'empresa',
        'sector',
        'producto_interes',
        'mensaje',
        'consentimiento_datos_at',
        'version_politica',
        'origen_url',
        'ip_address',
        'user_agent',
        'email_notificado_at',
        'email_error',
        'created_at',
    ];

    protected $validationRules = [
        'nombre'  => 'required|max_length[120]',
        'email'   => 'required|valid_email|max_length[190]',
        'mensaje' => 'required',
    ];

    public function marcarNotificado(int $id): bool
    {
        return $this->update($id, [
            'email_notificado_at' => date('Y-m-d H:i:s'),
            'email_error'         => null,
        ]);
    }

    public function marcarErrorEnvio(int $id, string $error): bool
    {
        return $this->update($id, ['email_error' => mb_substr($error, 0, 500)]);
    }
}
