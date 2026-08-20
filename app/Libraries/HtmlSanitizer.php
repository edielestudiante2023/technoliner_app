<?php

namespace App\Libraries;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitiza HTML generado por el editor del panel (artículos y productos)
 * antes de guardarlo, para evitar XSS almacenado.
 */
class HtmlSanitizer
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', WRITEPATH . 'cache/htmlpurifier');
        $config->set('HTML.Allowed', 'p,br,strong,em,u,s,ul,ol,li,a[href|title|target|rel],h2,h3,h4,blockquote,img[src|alt|width|height],table,thead,tbody,tr,th,td');
        $config->set('HTML.TargetBlank', true);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);

        if (! is_dir(WRITEPATH . 'cache/htmlpurifier')) {
            mkdir(WRITEPATH . 'cache/htmlpurifier', 0755, true);
        }

        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitizar(string $html): string
    {
        return $this->purifier->purify($html);
    }
}
