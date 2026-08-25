<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class SeoPublicoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';

    public function testInicioUsaUrlCanonicaLimpiaYH1Descriptivo(): void
    {
        $result = $this->withSession()->get('/');
        $body    = $result->response()->getBody();
        $baseUrl = rtrim(config('App')->baseURL, '/');

        $result->assertOK();
        $this->assertStringContainsString('<link rel="canonical" href="' . $baseUrl . '/">', $body);
        $this->assertStringContainsString('<meta name="robots" content="index, follow">', $body);
        $this->assertStringContainsString('<h1>Liners y soluciones de sellado para envases</h1>', $body);
        $this->assertStringNotContainsString($baseUrl . '/index.php', $body);
    }

    public function testCatalogoUsaH1YCanonicalPropio(): void
    {
        $result = $this->withSession()->get('productos');
        $body    = $result->response()->getBody();
        $baseUrl = rtrim(config('App')->baseURL, '/');

        $result->assertOK();
        $this->assertStringContainsString('<link rel="canonical" href="' . $baseUrl . '/productos">', $body);
        $this->assertStringContainsString('<h1>Catálogo de liners y sellos para envases</h1>', $body);
    }

    public function testFiltroDeCategoriaNoSeIndexaHastaTenerLandingPropia(): void
    {
        $result = $this->withSession()->get('productos?categoria=sellos-induccion');
        $body    = $result->response()->getBody();
        $baseUrl = rtrim(config('App')->baseURL, '/');

        $result->assertOK();
        $this->assertStringContainsString('<meta name="robots" content="noindex, follow">', $body);
        $this->assertStringContainsString('<link rel="canonical" href="' . $baseUrl . '/productos">', $body);
    }

    public function testBlogUsaH1YCanonicalPropio(): void
    {
        $result = $this->withSession()->get('blog');
        $body    = $result->response()->getBody();
        $baseUrl = rtrim(config('App')->baseURL, '/');

        $result->assertOK();
        $this->assertStringContainsString('<link rel="canonical" href="' . $baseUrl . '/blog">', $body);
        $this->assertStringContainsString('<h1>Guías técnicas de liners y sellado de envases</h1>', $body);
    }

    public function testAdministradorEnviaNoIndexEnHtmlYCabecera(): void
    {
        $result = $this->withSession()->get('admin/login');
        $body   = $result->response()->getBody();

        $result->assertOK();
        $result->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $body);
    }

    public function testSitemapSoloPublicaUrlsCanonicasLimpias(): void
    {
        $result = $this->withSession()->get('sitemap.xml');
        $body    = $result->response()->getBody();
        $baseUrl = rtrim(config('App')->baseURL, '/');

        $result->assertOK();
        $this->assertStringContainsString('<loc>' . $baseUrl . '/productos</loc>', $body);
        $this->assertStringNotContainsString('index.php', $body);
    }

    public function testRobotsDeclaraElSitemapDeProduccion(): void
    {
        $robots = file_get_contents(PUBLICPATH . 'robots.txt');

        $this->assertIsString($robots);
        $this->assertStringContainsString('Sitemap: https://technoliner.co/sitemap.xml', $robots);
    }
}
