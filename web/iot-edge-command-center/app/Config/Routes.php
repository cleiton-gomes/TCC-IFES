
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Página inicial existente.
$routes->get('/', 'Home::index');

// Dashboard do sistema IoT-Edge Center.
$routes->get('dashboard', 'Dashboard::index');

$routes->get('embarcados', 'Embarcados::index');

$routes->get('diagnostico', 'Diagnostico::index');

$routes->get('gemeo', 'Gemeo::index');

$routes->get('videomonitoramento', 'Videomonitoramento::index');

$routes->get('login', 'Login::index');

$routes->get('locais', 'Locais::index');

$routes->get('automacoes', 'Automacoes::index');

$routes->get('historico', 'Historico::index');

