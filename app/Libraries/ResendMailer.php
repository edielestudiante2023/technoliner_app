<?php

namespace App\Libraries;

use Config\Services;

/**
 * Envoltorio mínimo sobre la API HTTP de Resend.
 *
 * Si RESEND_API_KEY no está configurada (entornos locales sin cuenta
 * de Resend todavía), send() devuelve success=false con un error
 * explicativo en lugar de lanzar una excepción, para que el llamador
 * decida cómo continuar (por ejemplo, registrar el enlace en el log).
 */
class ResendMailer
{
    public function send(string $to, string $subject, string $textBody, ?string $replyTo = null): array
    {
        $apiKey = env('RESEND_API_KEY');
        $fromEmail = env('MAIL_FROM_EMAIL', 'notificaciones@correo.technoliner.co');
        $fromName  = env('MAIL_FROM_NAME', 'Technoliner Web');

        if (empty($apiKey)) {
            return ['success' => false, 'error' => 'RESEND_API_KEY no está configurada.'];
        }

        $payload = [
            'from'    => "{$fromName} <{$fromEmail}>",
            'to'      => [$to],
            'subject' => $subject,
            'text'    => $textBody,
        ];

        if ($replyTo !== null) {
            $payload['reply_to'] = $replyTo;
        }

        try {
            $client   = Services::curlrequest(['timeout' => 10]);
            $response = $client->post('https://api.resend.com/emails', [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ],
                'json'            => $payload,
                'http_errors'     => false,
            ]);

            $status = $response->getStatusCode();

            if ($status >= 200 && $status < 300) {
                return ['success' => true, 'error' => null];
            }

            return ['success' => false, 'error' => "Resend respondió {$status}: " . $response->getBody()];
        } catch (\Throwable $e) {
            log_message('error', 'Resend send() falló: {msg}', ['msg' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
