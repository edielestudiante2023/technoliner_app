<?php

use App\Libraries\HtmlSanitizer;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class HtmlSanitizerTest extends CIUnitTestCase
{
    public function testEliminaEtiquetasScript(): void
    {
        $resultado = (new HtmlSanitizer())->sanitizar('<p>Hola</p><script>alert(1)</script>');

        $this->assertStringNotContainsString('<script', $resultado);
        $this->assertStringContainsString('<p>Hola</p>', $resultado);
    }

    public function testEliminaAtributosDeEventos(): void
    {
        $resultado = (new HtmlSanitizer())->sanitizar('<img src="x" onerror="alert(1)">');

        $this->assertStringNotContainsString('onerror', $resultado);
    }

    public function testConservaEtiquetasPermitidas(): void
    {
        $resultado = (new HtmlSanitizer())->sanitizar('<h2>Título</h2><p>Texto <strong>fuerte</strong></p><ul><li>Item</li></ul>');

        $this->assertStringContainsString('<h2>Título</h2>', $resultado);
        $this->assertStringContainsString('<strong>fuerte</strong>', $resultado);
        $this->assertStringContainsString('<li>Item</li>', $resultado);
    }

    public function testEliminaIframe(): void
    {
        $resultado = (new HtmlSanitizer())->sanitizar('<iframe src="https://evil.example"></iframe><p>seguro</p>');

        $this->assertStringNotContainsString('<iframe', $resultado);
    }
}
