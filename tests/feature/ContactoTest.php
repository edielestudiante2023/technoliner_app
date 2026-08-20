<?php

use App\Models\ContactoModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\URI;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\Mock\MockCURLRequest;
use Config\Services;

/**
 * @internal
 */
final class ContactoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    protected function setUp(): void
    {
        parent::setUp();

        // Throttler es un singleton compartido que guarda su propia referencia al
        // caché desde la primera construcción en todo el proceso de PHPUnit, así
        // que un cache()->clean() posterior no lo alcanza. Se inyecta un Throttler
        // nuevo, atado al cache mockeado de este test, para que cada test empiece
        // con el cupo completo.
        Services::injectMock('throttler', new \CodeIgniter\Throttle\Throttler(service('cache')));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($_ENV['RESEND_API_KEY'], $_SERVER['RESEND_API_KEY']);
    }

    private function datosValidos(array $overrides = []): array
    {
        return array_merge([
            'form_rendered_at' => time() - 10,
            'nombre'           => 'Juan Pérez',
            'correo'           => 'juan.perez@example.com',
            'telefono'         => '+57 300 1234567',
            'empresa'          => 'Acme SAS',
            'sector'           => 'Alimentos',
            'producto'         => 'Liners sensitivos',
            'mensaje'          => 'Necesito cotizar liners para envases de 250 ml.',
            'politica'         => '1',
        ], $overrides);
    }

    private function inyectarMockCurlExitoso(): void
    {
        $_ENV['RESEND_API_KEY'] = 'clave_de_prueba';

        $config = config('App');
        $mock   = new MockCURLRequest($config, new URI('https://api.resend.com'), new Response($config));
        $mock->setOutput("HTTP/1.1 200 OK\r\nContent-Type: application/json\r\n\r\n" . json_encode(['id' => 'abc123']));

        Services::injectMock('curlrequest', $mock);
    }

    public function testContactoValidoGuardaExactamenteUnRegistro(): void
    {
        $this->withSession()->post('contacto', $this->datosValidos());

        $model = new ContactoModel();
        $this->assertSame(1, $model->countAllResults());

        $contacto = $model->first();
        $this->assertSame('Juan Pérez', $contacto['nombre']);
        $this->assertSame('juan.perez@example.com', $contacto['email']);
        $this->assertNotNull($contacto['consentimiento_datos_at']);
        $this->assertSame('1.0', $contacto['version_politica']);
    }

    public function testContactoSinNombreNoSeGuarda(): void
    {
        $this->withSession()->post('contacto', $this->datosValidos(['nombre' => '']));

        $model = new ContactoModel();
        $this->assertSame(0, $model->countAllResults());
    }

    public function testContactoSinAceptarPoliticaNoSeGuarda(): void
    {
        $this->withSession()->post('contacto', $this->datosValidos(['politica' => '']));

        $model = new ContactoModel();
        $this->assertSame(0, $model->countAllResults());
    }

    public function testContactoEnviadoAntesDelTiempoMinimoNoSeGuarda(): void
    {
        $this->withSession()->post('contacto', $this->datosValidos(['form_rendered_at' => time()]));

        $model = new ContactoModel();
        $this->assertSame(0, $model->countAllResults());
    }

    public function testHoneypotConContenidoRechazaLaSolicitud(): void
    {
        $this->expectException(CodeIgniter\Honeypot\Exceptions\HoneypotException::class);

        $this->withSession()->post('contacto', $this->datosValidos(['honeypot' => 'relleno-de-bot']));
    }

    public function testContactoSePreservaYRegistraErrorCuandoResendFalla(): void
    {
        // Sin RESEND_API_KEY configurada, el envío falla pero el contacto no se pierde.
        $this->withSession()->post('contacto', $this->datosValidos());

        $model    = new ContactoModel();
        $contacto = $model->first();

        $this->assertNotNull($contacto);
        $this->assertNull($contacto['email_notificado_at']);
        $this->assertNotEmpty($contacto['email_error']);
    }

    public function testContactoSeMarcaNotificadoCuandoResendAcepta(): void
    {
        $this->inyectarMockCurlExitoso();

        $this->withSession()->post('contacto', $this->datosValidos());

        $model    = new ContactoModel();
        $contacto = $model->first();

        $this->assertNotNull($contacto);
        $this->assertNotNull($contacto['email_notificado_at']);
        $this->assertNull($contacto['email_error']);
    }

    public function testRateLimitBloqueaDemasiadasSolicitudes(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->withSession()->post('contacto', $this->datosValidos(['correo' => "contacto{$i}@example.com"]));
        }

        // El sexto intento en el mismo minuto debe ser bloqueado por el throttler.
        $this->withSession()->post('contacto', $this->datosValidos(['correo' => 'contacto6@example.com']));

        $model = new ContactoModel();
        $this->assertSame(5, $model->countAllResults());
    }
}
