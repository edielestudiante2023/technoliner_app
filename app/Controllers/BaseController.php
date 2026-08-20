<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    /**
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function datosEmpresa(): array
    {
        return [
            'nombre'        => 'Technoliner SAS',
            'eslogan'       => 'Protege lo esencial, preserva la calidad.',
            'descripcion'   => 'Soluciones de empaque seguras y sostenibles para tu industria.',
            'nit'           => '901.404.716-8',
            'direccion'     => 'Cl. 28 Sur # 51 A - 79, Barrio Alcalá, Bogotá D.C.',
            'telefono'      => '+57 312 430 4623',
            'whatsapp'      => '+57 312 430 4623',
            'whatsapp_link' => '573124304623',
            'correo'        => 'gerencia.technoliner@gmail.com',
        ];
    }
}
