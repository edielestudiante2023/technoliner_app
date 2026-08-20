<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenRecuperacionModel extends Model
{
    protected $table         = 'tokens_recuperacion';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'usuario_id',
        'token_hash',
        'expires_at',
        'used_at',
        'requested_ip',
        'created_at',
    ];

    public function crearParaUsuario(int $usuarioId, string $ip, int $minutosVigencia = 60): string
    {
        $token = bin2hex(random_bytes(32));

        $this->insert([
            'usuario_id'   => $usuarioId,
            'token_hash'   => hash('sha256', $token),
            'expires_at'   => date('Y-m-d H:i:s', time() + ($minutosVigencia * 60)),
            'requested_ip' => $ip,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    public function encontrarVigentePorToken(string $token): ?array
    {
        $hash = hash('sha256', $token);

        return $this->where('token_hash', $hash)
            ->where('used_at', null)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->first();
    }

    public function marcarUsado(int $id): bool
    {
        return $this->update($id, ['used_at' => date('Y-m-d H:i:s')]);
    }
}
