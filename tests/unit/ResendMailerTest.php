<?php

use App\Libraries\ResendMailer;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\URI;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\Mock\MockCURLRequest;
use Config\Services;

/**
 * @internal
 */
final class ResendMailerTest extends CIUnitTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($_ENV['RESEND_API_KEY'], $_SERVER['RESEND_API_KEY']);
    }

    private function inyectarMockCurl(string $rawHttpResponse): void
    {
        $config = config('App');
        $mock   = new MockCURLRequest($config, new URI('https://api.resend.com'), new Response($config));
        $mock->setOutput($rawHttpResponse);

        Services::injectMock('curlrequest', $mock);
    }

    public function testSinApiKeyDevuelveErrorSinIntentarConexion(): void
    {
        $_ENV['RESEND_API_KEY'] = '';

        $resultado = (new ResendMailer())->send('destino@example.com', 'Asunto', 'Cuerpo');

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('RESEND_API_KEY', $resultado['error']);
    }

    public function testRespuestaExitosaDeResendDevuelveSuccess(): void
    {
        $_ENV['RESEND_API_KEY'] = 'clave_de_prueba';
        $this->inyectarMockCurl("HTTP/1.1 200 OK\r\nContent-Type: application/json\r\n\r\n" . json_encode(['id' => 'abc123']));

        $resultado = (new ResendMailer())->send('destino@example.com', 'Asunto', 'Cuerpo', 'visitante@example.com');

        $this->assertTrue($resultado['success']);
        $this->assertNull($resultado['error']);
    }

    public function testRespuestaDeErrorDeResendDevuelveFailure(): void
    {
        $_ENV['RESEND_API_KEY'] = 'clave_de_prueba';
        $this->inyectarMockCurl("HTTP/1.1 422 Unprocessable Entity\r\nContent-Type: application/json\r\n\r\n" . json_encode(['message' => 'Datos inválidos']));

        $resultado = (new ResendMailer())->send('destino@example.com', 'Asunto', 'Cuerpo');

        $this->assertFalse($resultado['success']);
        $this->assertStringContainsString('422', $resultado['error']);
    }
}
