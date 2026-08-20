<?php

namespace App\Commands;

use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Crea el primer usuario administrador de forma segura.
 *
 * La contraseña nunca se recibe como argumento de línea de comandos
 * (quedaría en el historial de la shell). Si no se genera una
 * automáticamente, se pide por un prompt interactivo.
 */
class CreateAdminUser extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'admin:create';
    protected $description = 'Crea un usuario administrador inicial.';
    protected $usage       = 'admin:create [--nombre "Nombre"] [--email correo@dominio.com] [--generate-password]';

    protected $options = [
        '--nombre'            => 'Nombre completo del administrador.',
        '--email'             => 'Correo electrónico del administrador.',
        '--generate-password' => 'Genera una contraseña aleatoria segura en lugar de solicitarla por prompt.',
    ];

    public function run(array $params)
    {
        $usuarioModel = new UsuarioModel();
        $rolModel     = new RolModel();

        $rol = $rolModel->where('codigo', 'administrador')->first();

        if (! $rol) {
            CLI::error('El rol "administrador" no existe. Ejecuta primero el seeder de roles (php spark db:seed DatabaseSeeder).');

            return;
        }

        $nombre = CLI::getOption('nombre') ?? CLI::prompt('Nombre completo', null, 'required|max_length[120]');
        $email  = CLI::getOption('email') ?? CLI::prompt('Correo electrónico', null, 'required|valid_email|max_length[190]');
        $email  = mb_strtolower(trim($email));

        if ($usuarioModel->findByEmail($email)) {
            CLI::error("Ya existe un usuario con el correo {$email}.");

            return;
        }

        if (CLI::getOption('generate-password')) {
            $password = $this->generarPasswordSegura();
            CLI::write('Se generó una contraseña aleatoria. Guárdala en un gestor de contraseñas; no volverá a mostrarse.', 'yellow');
        } else {
            $password = CLI::prompt('Contraseña (mínimo 12 caracteres)', null, 'required|min_length[12]');
            $confirmacion = CLI::prompt('Confirma la contraseña', null, 'required');

            if ($password !== $confirmacion) {
                CLI::error('Las contraseñas no coinciden.');

                return;
            }
        }

        $id = $usuarioModel->insert([
            'rol_id'        => $rol['id'],
            'nombre'        => $nombre,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'activo'        => 1,
            'password_changed_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $id) {
            CLI::error('No se pudo crear el usuario:');
            foreach ($usuarioModel->errors() as $error) {
                CLI::error(" - {$error}");
            }

            return;
        }

        CLI::write("Administrador creado correctamente (id {$id}, {$email}).", 'green');

        if (CLI::getOption('generate-password')) {
            CLI::write('Contraseña generada: ' . $password, 'green');
        }
    }

    private function generarPasswordSegura(int $longitud = 16): string
    {
        $alfabeto = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*-_';
        $password = '';

        for ($i = 0; $i < $longitud; $i++) {
            $password .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
        }

        return $password;
    }
}
