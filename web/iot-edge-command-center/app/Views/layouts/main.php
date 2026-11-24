
<?php
/**
 * Layout compartilhado do IoT-Edge Command Center.
 *
 * Seções utilizadas pelas demais views:
 * - title
 * - styles
 * - content
 * - scripts
 */

$currentSegment = service('uri')->getSegment(1) ?? '';

$tituloPagina = trim((string) $this->renderSection('title'));

if ($tituloPagina === '') {
    $tituloPagina = 'Painel';
}

// Dados do usuário autenticado pelo CodeIgniter Shield.
$usuario = auth()->user();

$usuarioNome = 'Visitante';
$usuarioEmail = '';
$usuarioPerfil = 'Visitante';

if ($usuario !== null) {
    // Identificação da conta.
    $usuarioNome = trim((string) ($usuario->username ?? ''));

    if ($usuarioNome === '') {
        $usuarioNome = $usuario->getEmail() ?? 'Usuário';
    }

    $usuarioEmail = $usuario->getEmail() ?? '';

    // Identificação do grupo/perfil.
    if ($usuario->inGroup('administrador')) {
        $usuarioPerfil = 'Administrador';
    } elseif ($usuario->inGroup('operador')) {
        $usuarioPerfil = 'Operador';
    } elseif ($usuario->inGroup('visualizador')) {
        $usuarioPerfil = 'Visualizador';
    } else {
        $usuarioPerfil = 'Usuário';
    }
}

// Identifica quais links devem ficar destacados.
$rotasDashboard = ['dashboard'];
$rotasVideo = ['videomonitoramento', 'eventos'];
$rotasDispositivos = ['embarcados', 'dispositivos'];
$rotasLocais = ['locais'];
$rotasAutomacoes = ['automacoes', 'operacoes', 'comandos'];
$rotasGemeo = ['gemeo', 'gemeo_digital'];
$rotasDiagnostico = ['diagnostico'];
$rotasHistorico = ['historico'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($tituloPagina) ?> - IoT-Edge Center</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Estilos globais do IoT-Edge Center -->
    <style>
        :root {
            --iot-bg: #0b0f19;
            --iot-surface: #111827;
            --iot-surface-hover: #1e293b;
            --iot-border: rgba(255, 255, 255, 0.10);
            --iot-text: #f8fafc;
            --iot-text-secondary: #94a3b8;
            --iot-primary: #3b82f6;
            --iot-info: #38bdf8;
        }

        html {
            min-height: 100%;
            background-color: var(--iot-bg);
        }

        body {
            min-height: 100vh;
            margin: 0;
            background-color: var(--iot-bg);
            color: var(--iot-text);
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* =========================================
           NAVBAR PRINCIPAL
        ========================================= */

        .navbar-dark-custom {
            background-color: var(--iot-surface) !important;
            border-bottom: 1px solid var(--iot-border);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.20);
            min-height: 66px;
            z-index: 1030;
        }

        .navbar-dark-custom .container-fluid {
            gap: 8px;
        }

        /* Identidade do sistema */

        .navbar-dark-custom .navbar-brand {
            color: var(--iot-text) !important;
            white-space: nowrap;
            font-size: 1rem;
            letter-spacing: 0.2px;
            flex-shrink: 0;
        }

        .navbar-dark-custom .navbar-brand:hover {
            color: var(--iot-info) !important;
        }

        .navbar-dark-custom .navbar-brand .text-info {
            color: var(--iot-info) !important;
        }

        .brand-icon {
            display: inline-grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background-color: rgba(59, 130, 246, 0.20);
            color: var(--iot-info);
            font-size: 0.85rem;
        }

        /* Links diretos */

        .navbar-dark-custom .navbar-nav {
            gap: 1px;
        }

        .navbar-dark-custom .navbar-nav .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1 !important;
            font-size: 0.82rem;
            font-weight: 500;
            padding: 9px 7px;
            margin: 0;
            border-radius: 7px;
            white-space: nowrap;
            transition:
                color 0.2s ease,
                background-color 0.2s ease;
        }

        .navbar-dark-custom .navbar-nav .nav-link i {
            color: var(--iot-info);
            font-size: 0.82rem;
        }

        .navbar-dark-custom .navbar-nav .nav-link:hover,
        .navbar-dark-custom .navbar-nav .nav-link:focus {
            color: #ffffff !important;
            background-color: rgba(59, 130, 246, 0.12);
        }

        .navbar-dark-custom .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(59, 130, 246, 0.20);
        }

        /* Botão do menu para telas menores */

        .navbar-dark-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.20);
            padding: 6px 9px;
        }

        .navbar-dark-custom .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .navbar-dark-custom .navbar-toggler-icon {
            filter: none;
        }

        /* =========================================
           STATUS E USUÁRIO
        ========================================= */

        .navbar-dark-custom .navbar-actions {
            gap: 9px;
            flex-shrink: 0;
        }

        .navbar-dark-custom .edge-status {
            white-space: nowrap;
            font-size: 0.70rem;
            font-weight: 600;
        }

        .demo-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(245, 158, 11, 0.30);
            border-radius: 7px;
            padding: 7px 9px;
            background-color: rgba(245, 158, 11, 0.08);
            color: #fbbf24;
        }

        .navbar-dark-custom .operator-button {
            color: var(--iot-text) !important;
            background-color: transparent;
            border-color: rgba(255, 255, 255, 0.20) !important;
            white-space: nowrap;
        }

        .navbar-dark-custom .operator-button:hover,
        .navbar-dark-custom .operator-button:focus {
            background-color: var(--iot-surface-hover);
            border-color: var(--iot-primary) !important;
        }

        /* Menu do usuário */

        .navbar-dark-custom .dropdown-menu {
            background-color: var(--iot-surface);
            border: 1px solid var(--iot-border);
            border-radius: 10px;
            min-width: 240px;
        }

        .navbar-dark-custom .dropdown-item {
            color: #cbd5e1;
            padding: 9px 14px;
        }

        .navbar-dark-custom .dropdown-item:hover,
        .navbar-dark-custom .dropdown-item:focus {
            color: #ffffff;
            background-color: var(--iot-surface-hover);
        }

        .navbar-dark-custom .dropdown-header {
            color: var(--iot-text-secondary) !important;
        }

        .navbar-dark-custom .dropdown-divider {
            border-color: var(--iot-border);
        }

        .text-secondary {
            color: var(--iot-text-secondary) !important;
        }

        /* =========================================
           RESPONSIVIDADE
        ========================================= */

        @media (max-width: 1399.98px) and (min-width: 1200px) {
            .navbar-dark-custom .navbar-brand {
                font-size: 0.94rem;
            }

            .navbar-dark-custom .navbar-nav .nav-link {
                font-size: 0.77rem;
                padding: 8px 5px;
            }

            .navbar-dark-custom .navbar-nav .nav-link i {
                font-size: 0.78rem;
            }

            .navbar-dark-custom .navbar-actions {
                gap: 6px;
            }

            .demo-indicator {
                padding: 7px;
            }
        }

        @media (max-width: 1199.98px) {
            .navbar-dark-custom {
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .navbar-dark-custom .navbar-collapse {
                padding-top: 12px;
            }

            .navbar-dark-custom .navbar-nav {
                gap: 3px;
            }

            .navbar-dark-custom .navbar-nav .nav-link {
                justify-content: flex-start;
                font-size: 0.92rem;
                padding: 10px 12px;
                margin: 2px 0;
            }

            .navbar-dark-custom .navbar-actions {
                margin-top: 12px;
                padding: 12px 0 4px;
                border-top: 1px solid var(--iot-border);
                justify-content: flex-start;
                align-items: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-dark-custom .navbar-brand {
                font-size: 0.92rem;
            }

            .navbar-dark-custom .navbar-actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .navbar-dark-custom .edge-status {
                white-space: normal;
            }

            .navbar-dark-custom .dropdown-menu {
                min-width: 0;
                width: 100%;
            }
        }
    </style>

    <!-- Estilos específicos de cada página -->
    <?= $this->renderSection('styles') ?>
</head>

<body>

    <!-- =========================================
         NAVBAR COMPARTILHADA
    ========================================== -->

    <nav class="navbar navbar-expand-xl navbar-dark navbar-dark-custom sticky-top px-3">

        <div class="container-fluid">

            <!-- Identidade do sistema -->

            <a
                class="navbar-brand fw-bold d-flex align-items-center gap-2"
                href="<?= site_url('dashboard') ?>"
                aria-label="Ir para o Dashboard"
            >
                <span class="brand-icon">
                    <i class="fa-solid fa-network-wired"></i>
                </span>

                <span>
                    IoT-Edge <span class="text-info">Center</span>
                </span>
            </a>

            <!-- Botão responsivo -->

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Alternar navegação"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Todos os módulos com acesso direto -->

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">

                    <!-- Dashboard -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasDashboard, true) ? 'active' : '' ?>"
                            href="<?= site_url('dashboard') ?>"
                            <?= in_array($currentSegment, $rotasDashboard, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-chart-pie me-1"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Videomonitoramento e eventos -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasVideo, true) ? 'active' : '' ?>"
                            href="<?= site_url('videomonitoramento') ?>"
                            <?= in_array($currentSegment, $rotasVideo, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-video me-1"></i>
                            Vídeo/Eventos
                        </a>
                    </li>

                    <!-- Dispositivos -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasDispositivos, true) ? 'active' : '' ?>"
                            href="<?= site_url('embarcados') ?>"
                            <?= in_array($currentSegment, $rotasDispositivos, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-microchip me-1"></i>
                            Dispositivos
                        </a>
                    </li>

                    <!-- Locais -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasLocais, true) ? 'active' : '' ?>"
                            href="<?= site_url('locais') ?>"
                            <?= in_array($currentSegment, $rotasLocais, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-map-location-dot me-1"></i>
                            Locais
                        </a>
                    </li>

                    <!-- Automações e operações -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasAutomacoes, true) ? 'active' : '' ?>"
                            href="<?= site_url('automacoes') ?>"
                            <?= in_array($currentSegment, $rotasAutomacoes, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-gears me-1"></i>
                            Automações
                        </a>
                    </li>

                    <!-- Gêmeo digital -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasGemeo, true) ? 'active' : '' ?>"
                            href="<?= site_url('gemeo') ?>"
                            <?= in_array($currentSegment, $rotasGemeo, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-cubes me-1"></i>
                            Gêmeo Digital
                        </a>
                    </li>

                    <!-- Diagnóstico -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasDiagnostico, true) ? 'active' : '' ?>"
                            href="<?= site_url('diagnostico') ?>"
                            <?= in_array($currentSegment, $rotasDiagnostico, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-chart-line me-1"></i>
                            Diagnóstico
                        </a>
                    </li>

                    <!-- Histórico e auditoria -->

                    <li class="nav-item">
                        <a
                            class="nav-link <?= in_array($currentSegment, $rotasHistorico, true) ? 'active' : '' ?>"
                            href="<?= site_url('historico') ?>"
                            <?= in_array($currentSegment, $rotasHistorico, true) ? 'aria-current="page"' : '' ?>
                        >
                            <i class="fa-solid fa-clock-rotate-left me-1"></i>
                            Histórico
                        </a>
                    </li>

                </ul>

                <!-- Status da aplicação e usuário -->

                <div class="navbar-actions d-flex align-items-center">

                    <!-- Indicador demonstrativo -->

                    <span
                        class="demo-indicator edge-status"
                        title="Os dados apresentados ainda são demonstrativos"
                    >
                        <i class="fa-solid fa-flask"></i>
                        Demonstração
                    </span>

                    <!-- Menu do usuário -->

                    <div class="dropdown">

                        <button
                            class="btn btn-outline-secondary btn-sm dropdown-toggle operator-button"
                            type="button"
                            id="menuUsuario"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fa-solid fa-user-gear me-1 text-info"></i>
                            <?= esc($usuarioPerfil) ?>
                        </button>

                        <ul
                            class="dropdown-menu dropdown-menu-end shadow"
                            aria-labelledby="menuUsuario"
                        >

                            <li>
                                <h6 class="dropdown-header">
                                    Sessão atual
                                </h6>
                            </li>

                            <li>
                                <span class="dropdown-item-text text-light">
                                    <i class="fa-solid fa-user me-2 text-info"></i>
                                    <?= esc($usuarioNome) ?>
                                </span>
                            </li>

                            <?php if ($usuarioEmail !== ''): ?>
                                <li>
                                    <span class="dropdown-item-text text-secondary small">
                                        <i class="fa-solid fa-envelope me-2"></i>
                                        <?= esc($usuarioEmail) ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <li>
                                <span class="dropdown-item-text text-secondary small">
                                    <i class="fa-solid fa-shield-halved me-2"></i>
                                    Perfil: <?= esc($usuarioPerfil) ?>
                                </span>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <?php if ($usuario !== null): ?>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="<?= site_url('logout') ?>"
                                    >
                                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                                        Sair
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="<?= site_url('login') ?>"
                                    >
                                        <i class="fa-solid fa-right-to-bracket me-2"></i>
                                        Entrar
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>

                    </div>

                </div>

            </div>
        </div>

    </nav>

    <!-- Conteúdo específico de cada página -->

    <?= $this->renderSection('content') ?>

    <!-- Bootstrap JavaScript -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts específicos de cada página -->

    <?= $this->renderSection('scripts') ?>

</body>
</html>
