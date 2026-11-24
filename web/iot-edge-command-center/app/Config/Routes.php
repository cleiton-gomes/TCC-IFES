
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------
// PÁGINA INICIAL PÚBLICA
// --------------------------------------------------

$routes->get('/', 'Home::index');

// --------------------------------------------------
// AUTENTICAÇÃO
// --------------------------------------------------

// Tela personalizada de login.
// Permanece acessível sem autenticação.
$routes->get('login', 'Login::index');

// --------------------------------------------------
// ÁREAS PROTEGIDAS DO IOT-EDGE COMMAND CENTER
// --------------------------------------------------

// Dashboard principal.
$routes->get(
    'dashboard',
    'Dashboard::index',
    ['filter' => ['session', 'permission:dashboard.view']]
);

// Dispositivos e componentes embarcados.
$routes->get(
    'embarcados',
    'Embarcados::index',
    ['filter' => ['session', 'permission:devices.view']]
);

// Diagnóstico e telemetria.
$routes->get(
    'diagnostico',
    'Diagnostico::index',
    ['filter' => ['session', 'permission:telemetry.view']]
);

// Gêmeo digital.
$routes->get(
    'gemeo',
    'Gemeo::index',
    ['filter' => ['session', 'permission:digital_twin.view']]
);

// Videomonitoramento.
$routes->get(
    'videomonitoramento',
    'Videomonitoramento::index',
    ['filter' => ['session', 'permission:video.view']]
);

// Estrutura física e locais.
$routes->get(
    'locais',
    'Locais::index',
    ['filter' => ['session', 'permission:locations.view']]
);

// Regras e automações.
$routes->get(
    'automacoes',
    'Automacoes::index',
    ['filter' => ['session', 'permission:automations.view']]
);

// Histórico da plataforma.
$routes->get(
    'historico',
    'Historico::index',
    ['filter' => ['session', 'permission:history.view']]
);

// --------------------------------------------------
// ROTAS OFICIAIS DO CODEIGNITER SHIELD
// --------------------------------------------------

service('auth')->routes($routes);
