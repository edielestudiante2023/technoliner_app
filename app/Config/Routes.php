<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index', ['filter' => 'honeypot']);
$routes->get('blog', 'BlogController::index');
$routes->get('blog/(:segment)', 'BlogController::ver/$1');
$routes->get('productos', 'ProductoController::index');
$routes->get('productos/(:segment)', 'ProductoController::ver/$1');
$routes->get('clientes', 'ClienteController::index');
$routes->get('politica-tratamiento-datos', 'PoliticaController::index');
$routes->post('contacto', 'ContactoController::guardar', ['filter' => 'honeypot']);
$routes->get('sitemap.xml', 'SitemapController::index');

$routes->group('admin', static function (RouteCollection $routes): void {
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::attemptLogin');
    $routes->post('logout', 'Admin\AuthController::logout');
    $routes->get('recuperar', 'Admin\AuthController::recuperar');
    $routes->post('recuperar', 'Admin\AuthController::attemptRecuperar');
    $routes->get('restablecer/(:any)', 'Admin\AuthController::restablecer/$1');
    $routes->post('restablecer/(:any)', 'Admin\AuthController::attemptRestablecer/$1');
});

$routes->group('admin', ['filter' => 'adminauth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('contactos', 'Admin\ContactosController::index');
    $routes->get('usuarios', 'Admin\UsuariosController::index');
    $routes->get('usuarios/nuevo', 'Admin\UsuariosController::nuevo');
    $routes->post('usuarios', 'Admin\UsuariosController::crear');
    $routes->get('usuarios/(:num)/editar', 'Admin\UsuariosController::editar/$1');
    $routes->post('usuarios/(:num)', 'Admin\UsuariosController::actualizar/$1');
    $routes->post('usuarios/(:num)/estado', 'Admin\UsuariosController::cambiarEstado/$1');

    $routes->get('blog/categorias', 'Admin\BlogCategoriasController::index');
    $routes->get('blog/categorias/nuevo', 'Admin\BlogCategoriasController::nuevo');
    $routes->post('blog/categorias', 'Admin\BlogCategoriasController::crear');
    $routes->get('blog/categorias/(:num)/editar', 'Admin\BlogCategoriasController::editar/$1');
    $routes->post('blog/categorias/(:num)', 'Admin\BlogCategoriasController::actualizar/$1');
    $routes->post('blog/categorias/(:num)/estado', 'Admin\BlogCategoriasController::cambiarEstado/$1');

    $routes->get('blog/articulos', 'Admin\ArticulosController::index');
    $routes->get('blog/articulos/nuevo', 'Admin\ArticulosController::nuevo');
    $routes->post('blog/articulos', 'Admin\ArticulosController::crear');
    $routes->get('blog/articulos/(:num)/editar', 'Admin\ArticulosController::editar/$1');
    $routes->post('blog/articulos/(:num)', 'Admin\ArticulosController::actualizar/$1');
    $routes->post('blog/articulos/(:num)/publicar', 'Admin\ArticulosController::publicar/$1');
    $routes->post('blog/articulos/(:num)/despublicar', 'Admin\ArticulosController::despublicar/$1');

    $routes->get('productos/categorias', 'Admin\ProductoCategoriasController::index');
    $routes->get('productos/categorias/nuevo', 'Admin\ProductoCategoriasController::nuevo');
    $routes->post('productos/categorias', 'Admin\ProductoCategoriasController::crear');
    $routes->get('productos/categorias/(:num)/editar', 'Admin\ProductoCategoriasController::editar/$1');
    $routes->post('productos/categorias/(:num)', 'Admin\ProductoCategoriasController::actualizar/$1');
    $routes->post('productos/categorias/(:num)/estado', 'Admin\ProductoCategoriasController::cambiarEstado/$1');

    $routes->get('productos', 'Admin\ProductosController::index');
    $routes->get('productos/nuevo', 'Admin\ProductosController::nuevo');
    $routes->post('productos', 'Admin\ProductosController::crear');
    $routes->get('productos/(:num)/editar', 'Admin\ProductosController::editar/$1');
    $routes->post('productos/(:num)', 'Admin\ProductosController::actualizar/$1');
    $routes->post('productos/(:num)/estado', 'Admin\ProductosController::cambiarEstado/$1');
    $routes->post('productos/(:num)/destacado', 'Admin\ProductosController::cambiarDestacado/$1');
    $routes->post('productos/(:num)/especificaciones', 'Admin\ProductosController::agregarEspecificacion/$1');
    $routes->post('productos/(:num)/especificaciones/(:num)/eliminar', 'Admin\ProductosController::eliminarEspecificacion/$1/$2');
    $routes->post('productos/(:num)/imagenes', 'Admin\ProductosController::subirImagen/$1');
    $routes->post('productos/(:num)/imagenes/(:num)/principal', 'Admin\ProductosController::marcarImagenPrincipal/$1/$2');
    $routes->post('productos/(:num)/imagenes/(:num)/eliminar', 'Admin\ProductosController::eliminarImagen/$1/$2');

    $routes->get('clientes', 'Admin\ClientesController::index');
    $routes->get('clientes/nuevo', 'Admin\ClientesController::nuevo');
    $routes->post('clientes', 'Admin\ClientesController::crear');
    $routes->get('clientes/(:num)/editar', 'Admin\ClientesController::editar/$1');
    $routes->post('clientes/(:num)', 'Admin\ClientesController::actualizar/$1');
    $routes->post('clientes/(:num)/estado', 'Admin\ClientesController::cambiarEstado/$1');
    $routes->post('clientes/(:num)/eliminar', 'Admin\ClientesController::eliminar/$1');
});
