
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --bg: #0b1120;
        --panel: #111b2e;
        --panel2: #172338;
        --border: rgba(148,163,184,.17);
        --text: #e5edf8;
        --muted: #91a2ba;
        --accent: #38bdf8;
        --green: #34d399;
        --yellow: #fbbf24;
        --red: #fb7185;
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        min-height: 100vh;
        background: var(--bg);
        color: var(--text);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    a { color: inherit; text-decoration: none; }

    .navbar-dark-custom {
        background-color: #111827;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .page-wrap {
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px 24px 40px;
    }

    .eyebrow {
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: .14em;
        font-size: .72rem;
        font-weight: 800;
    }

    .page-title {
        font-size: clamp(1.65rem,3vw,2.2rem);
        font-weight: 750;
        letter-spacing: -.035em;
        margin: 4px 0 6px;
    }

    .muted { color: var(--muted); }

    .panel {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,.12);
    }

    .metric-card { padding: 19px; height: 100%; }

    .metric-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
    }

    .metric-label { font-size: .85rem; color: var(--muted); }

    .metric-number {
        font-size: 2rem;
        line-height: 1.2;
        font-weight: 800;
        margin: 12px 0 7px;
        letter-spacing: -.04em;
    }

    .metric-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        color: var(--metric-color,var(--accent));
        background: rgba(56,189,248,.10);
        font-size: 1.15rem;
    }

    .metric-foot { color: var(--muted); font-size: .78rem; }
    .metric-foot strong { color: var(--text); font-weight: 650; }

    .section-title { font-size: 1rem; font-weight: 720; margin: 0; }
    .section-subtitle { font-size: .8rem; color: var(--muted); margin-top: 4px; }

    .panel-head {
        padding: 20px 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .panel-body { padding: 18px 20px 20px; }

    .form-select, .form-control {
        background-color: #0b1425;
        color: var(--text);
        border-color: #2a3950;
    }

    .form-select:focus, .form-control:focus {
        background-color: #0b1425;
        color: var(--text);
        border-color: var(--accent);
        box-shadow: 0 0 0 .2rem rgba(56,189,248,.13);
    }

    .form-control::placeholder { color: #74859d; }

    .btn-outline-soft { color: #cbd7e7; border: 1px solid #30415a; }

    .btn-outline-soft:hover {
        color: white;
        background: #1a2940;
        border-color: #425875;
    }

    .btn-primary { background: #0284c7; border-color: #0284c7; }

    .btn-primary:hover { background: #0369a1; border-color: #0369a1; }

    .demo-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 999px;
        color: #fcd34d;
        background: rgba(251,191,36,.10);
        border: 1px solid rgba(251,191,36,.24);
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: .74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-online { color: #6ee7b7; background: rgba(52,211,153,.10); }
    .status-warning { color: #fcd34d; background: rgba(251,191,36,.10); }
    .status-offline { color: #fda4af; background: rgba(251,113,133,.10); }
    .status-unknown { color: #cbd5e1; background: rgba(148,163,184,.12); }

    .table {
        --bs-table-bg: transparent;
        --bs-table-color: var(--text);
        --bs-table-border-color: var(--border);
        margin-bottom: 0;
    }

    .table thead th {
        color: #8fa1ba;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .07em;
        font-weight: 750;
        white-space: nowrap;
        border-bottom-width: 1px;
        padding: 12px 14px;
    }

    .table tbody td { padding: 14px; vertical-align: middle; font-size: .84rem; }
    .table tbody tr:hover { --bs-table-bg: rgba(148,163,184,.035); }

    .device-row { cursor: pointer; transition: background-color .15s ease; }
    .device-row:hover { --bs-table-bg: rgba(56,189,248,.055) !important; }
    .device-row.selected {
        --bs-table-bg: rgba(56,189,248,.09) !important;
        box-shadow: inset 3px 0 0 var(--accent);
    }

    .device-expand-icon {
        display: inline-block;
        color: var(--accent);
        transition: transform .2s ease;
        margin-left: 5px;
    }

    .device-row.selected .device-expand-icon { transform: rotate(180deg); }

    .device-detail-row > td {
        padding: 0 !important;
        border-top: 0;
        background: rgba(7,15,29,.85);
    }

    .device-detail-panel {
        padding: 20px;
        border-top: 1px solid rgba(56,189,248,.25);
        border-bottom: 1px solid var(--border);
    }

    .detail-card {
        height: 100%;
        padding: 15px;
        background: var(--panel2);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .detail-label { color: var(--muted); font-size: .76rem; margin-bottom: 5px; }
    .detail-value { font-size: .95rem; font-weight: 700; overflow-wrap: anywhere; }
    .detail-description { color: var(--muted); font-size: .78rem; line-height: 1.5; }

    .detail-icon {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 14px;
        background: rgba(56,189,248,.1);
        color: var(--accent);
        font-size: 1.6rem;
    }

    .device-detail-chart { position: relative; height: 230px; min-width: 0; }
    .device-id { font-weight: 750; font-size: .84rem; }
    .device-name { color: var(--muted); font-size: .75rem; margin-top: 3px; }

    .type-icon {
        width: 34px;
        height: 34px;
        display: inline-grid;
        place-items: center;
        border-radius: 10px;
        background: var(--panel2);
        margin-right: 9px;
        color: #7dd3fc;
        flex-shrink: 0;
    }

    .small-link { color: #7dd3fc; font-size: .8rem; }
    .small-link:hover { color: #bae6fd; }

    .chart-wrap { height: 250px; position: relative; }
    .chart-wrap-short { height: 215px; position: relative; }

    .legend-list { display: grid; gap: 13px; }

    .legend-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        font-size: .84rem;
    }

    .legend-label { display: inline-flex; align-items: center; gap: 9px; color: #cbd5e1; }
    .legend-swatch { width: 9px; height: 9px; border-radius: 3px; display: inline-block; }

    .alert-item {
        display: flex;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 1px solid var(--border);
    }

    .alert-item:last-child { border-bottom: 0; padding-bottom: 0; }

    .alert-icon {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: grid;
        place-items: center;
        border-radius: 10px;
    }

    .alert-icon.warning { color: var(--yellow); background: rgba(251,191,36,.1); }
    .alert-icon.danger { color: var(--red); background: rgba(251,113,133,.1); }
    .alert-icon.info { color: var(--accent); background: rgba(56,189,248,.1); }
    .alert-title { font-size: .84rem; font-weight: 700; }
    .alert-desc { color: var(--muted); font-size: .77rem; margin-top: 3px; line-height: 1.45; }
    .alert-time { color: #73849c; font-size: .72rem; margin-top: 5px; }
    .footer-note { color: #718198; font-size: .76rem; }
    .empty-state { color: var(--muted); padding: 28px 12px; text-align: center; }

    @media (max-width: 767.98px) {
        .page-wrap { padding: 21px 14px 30px; }
        .panel-head { padding: 17px 16px 0; }
        .panel-body { padding: 16px; }
        .metric-card { padding: 16px; }
        .metric-number { font-size: 1.7rem; }
        .chart-wrap { height: 220px; }
        .header-actions { width: 100%; }
        .header-actions .btn { flex: 1; }
        .device-detail-panel { padding: 13px; }
        .device-detail-chart { height: 200px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="page-wrap">

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <div class="eyebrow">Central de operações</div>
            <h1 class="page-title">Dashboard</h1>
            <p class="muted mb-0">
                Visão integrada dos dispositivos, locais, telemetria e ocorrências da arquitetura IoT-Edge.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 header-actions">
            <button class="btn btn-outline-soft" id="refreshButton" type="button">
                <i class="bi bi-arrow-clockwise me-2"></i>Atualizar demonstração
            </button>
            <a class="btn btn-outline-soft" href="<?= site_url('locais') ?>">
                <i class="bi bi-geo-alt me-2"></i>Ver locais
            </a>
            <a class="btn btn-primary" href="<?= site_url('embarcados') ?>">
                <i class="bi bi-hdd-network me-2"></i>Gerenciar dispositivos
            </a>
        </div>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <span class="muted small"><i class="bi bi-clock me-1"></i>Última atualização:</span>
        <span class="small fw-semibold" id="lastUpdated">--</span>
        <span class="text-secondary mx-1">•</span>
        <span class="small muted">Dados fictícios para validar a interface; sem consulta ao hardware ou ao banco de dados.</span>
    </div>

    <section class="row g-3 mb-4" aria-label="Indicadores gerais">
        <div class="col-6 col-xl-3">
            <div class="panel metric-card" style="--metric-color:#38bdf8;">
                <div class="metric-top">
                    <div class="metric-label">Dispositivos cadastrados</div>
                    <span class="metric-icon"><i class="bi bi-cpu"></i></span>
                </div>
                <div class="metric-number" id="deviceCount">5</div>
                <div class="metric-foot">
                    <strong id="onlineCount">3</strong> online na simulação ·
                    <strong id="offlineCount">0</strong> offline
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel metric-card" style="--metric-color:#a78bfa;">
                <div class="metric-top">
                    <div class="metric-label">Séries de telemetria</div>
                    <span class="metric-icon"><i class="bi bi-activity"></i></span>
                </div>
                <div class="metric-number" id="sensorCount">3</div>
                <div class="metric-foot">gas_level, RSSI e SNR · dados simulados</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel metric-card" style="--metric-color:#34d399;">
                <div class="metric-top">
                    <div class="metric-label">Atuadores cadastrados</div>
                    <span class="metric-icon"><i class="bi bi-toggles"></i></span>
                </div>
                <div class="metric-number" id="actuatorCount">2</div>
                <div class="metric-foot">A atuação física precisa de confirmação do dispositivo</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel metric-card" style="--metric-color:#fbbf24;">
                <div class="metric-top">
                    <div class="metric-label">Alertas em aberto</div>
                    <span class="metric-icon"><i class="bi bi-exclamation-triangle"></i></span>
                </div>
                <div class="metric-number" id="alertCount">3</div>
                <div class="metric-foot">Inclui avisos simulados e dados desatualizados</div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="panel h-100">
                <div class="panel-head">
                    <div>
                        <h2 class="section-title">Histórico de leituras</h2>
                        <div class="section-subtitle">Exemplo de telemetria simulada ao longo do tempo</div>
                    </div>

                    <div class="d-flex flex-wrap gap-2" style="min-width:0;">
                        <div style="width:160px;max-width:100%;">
                            <label class="visually-hidden" for="sensorChartSelect">Sensor do gráfico</label>
                            <select class="form-select form-select-sm" id="sensorChartSelect">
                                <option value="gas">Leitura MQ-4 (ADC)</option>
                                <option value="rssi">Qualidade do sinal (RSSI)</option>
                                <option value="snr">Relação sinal/ruído (SNR)</option>
                            </select>
                        </div>

                        <div style="width:140px;max-width:100%;">
                            <label class="visually-hidden" for="deviceChartSelect">Dispositivo de origem</label>
                            <select class="form-select form-select-sm" id="deviceChartSelect">
                                <option value="EMB-GAS-001">EMB-GAS-001</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="chart-wrap">
                        <canvas id="readingsChart" aria-label="Gráfico de leituras simuladas" role="img"></canvas>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">
                        <span class="footer-note">
                            <i class="bi bi-info-circle me-1"></i>Valores ilustrativos, não operacionais.
                        </span>
                        <span class="footer-note" id="chartUnit">Unidade: valor relativo</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <div class="panel-head">
                    <div>
                        <h2 class="section-title">Estado dos dispositivos</h2>
                        <div class="section-subtitle">Distribuição demonstrativa por estado</div>
                    </div>
                    <i class="bi bi-pie-chart text-info fs-5"></i>
                </div>

                <div class="panel-body">
                    <div class="chart-wrap-short">
                        <canvas id="statusChart" aria-label="Distribuição simulada dos estados" role="img"></canvas>
                    </div>
                    <div class="legend-list mt-3" id="statusLegend"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h2 class="section-title">Dispositivos monitorados</h2>
                        <div class="section-subtitle">Inventário demonstrativo · selecione uma linha para consultar detalhes</div>
                    </div>
                    <a class="small-link" href="<?= site_url('embarcados') ?>">
                        Abrir cadastro <i class="bi bi-arrow-up-right ms-1"></i>
                    </a>
                </div>

                <div class="panel-body pb-0">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-lg-4">
                            <label class="visually-hidden" for="deviceSearch">Buscar dispositivo</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:#0b1425;color:#91a2ba;border-color:#2a3950;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input class="form-control" id="deviceSearch" type="search"
                                    placeholder="Buscar código, nome, modelo ou local...">
                            </div>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="visually-hidden" for="typeFilter">Filtrar classe</label>
                            <select class="form-select" id="typeFilter">
                                <option value="">Todas as classes</option>
                                <option value="Nó sensor">Nó sensor</option>
                                <option value="Atuador">Atuador</option>
                                <option value="Câmera">Câmera IP</option>
                                <option value="Drone">Drone / VANT</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label class="visually-hidden" for="locationFilter">Filtrar local</label>
                            <select class="form-select" id="locationFilter">
                                <option value="">Todos os locais</option>
                                <option value="LOC-SALA-CTRL">Sala de Controle</option>
                                <option value="LOC-CORREDOR-N">Corredor Norte</option>
                                <option value="LOC-ZONA-TECH">Zona Técnica</option>
                                <option value="LOC-EXT-01">Área Externa</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label class="visually-hidden" for="statusFilter">Filtrar estado</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Todos os estados</option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                                <option value="Desatualizado">Desatualizado</option>
                                <option value="Desconhecido">Desconhecido</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Dispositivo</th>
                                <th scope="col">Classe / telemetria</th>
                                <th scope="col">Local atual</th>
                                <th scope="col">Comunicação</th>
                                <th scope="col">Estado demonstrativo</th>
                                <th scope="col">Último contato ilustrativo</th>
                            </tr>
                        </thead>
                        <tbody id="deviceTableBody"></tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 align-items-sm-center p-3">
                    <span class="footer-note" id="tableSummary">Carregando demonstração...</span>
                    <a href="<?= site_url('gemeo') ?>" class="btn btn-sm btn-outline-soft">
                        <i class="bi bi-boxes me-2"></i>Visualizar gêmeo digital
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel h-100">
                <div class="panel-head">
                    <div>
                        <h2 class="section-title">Alertas e eventos recentes</h2>
                        <div class="section-subtitle">Exemplos para demonstrar a interface</div>
                    </div>
                    <span class="badge text-bg-warning" id="alertBadge">3 abertos</span>
                </div>

                <div class="panel-body" id="alertsList">
                    <div class="alert-item">
                        <div class="alert-icon warning"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="alert-title">Leitura desatualizada</div>
                            <div class="alert-desc">EMB-GAS-001 — gas_level acima da referência ilustrativa na Sala de Controle.</div>
                            <div class="alert-time">Há 2 min · EVT-GAS-0001 · Simulado</div>
                        </div>
                    </div>

                    <div class="alert-item">
                        <div class="alert-icon danger"><i class="bi bi-wifi-off"></i></div>
                        <div>
                            <div class="alert-title">Dispositivo sem comunicação</div>
                            <div class="alert-desc">REL-001 — estado físico do relé ainda não confirmado na Zona Técnica.</div>
                            <div class="alert-time">Há 5 min · Simulado</div>
                        </div>
                    </div>

                    <div class="alert-item">
                        <div class="alert-icon info"><i class="bi bi-broadcast"></i></div>
                        <div>
                            <div class="alert-title">Evento de telemetria recebido</div>
                            <div class="alert-desc">DRONE-001 — telemetria demonstrativa desatualizada na Área Externa.</div>
                            <div class="alert-time">Há 8 min · Simulado</div>
                        </div>
                    </div>
                </div>

                <div class="px-3 pb-3">
                    <a href="<?= site_url('diagnostico') ?>" class="small-link">
                        Consultar diagnóstico <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12">
            <div class="panel h-100">
                <div class="panel-head">
                    <div>
                        <h2 class="section-title">Resumo da comunicação</h2>
                        <div class="section-subtitle">Distribuição demonstrativa por tecnologia de comunicação</div>
                    </div>
                    <i class="bi bi-diagram-2 text-info fs-5"></i>
                </div>

                <div class="panel-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-semibold">Wi-Fi (MQTT)</span>
                        <span class="small muted">2 dispositivos</span>
                    </div>
                    <div class="progress mb-3" style="height:8px;background:#26354b;">
                        <div class="progress-bar" style="width:40%;background:#38bdf8;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-semibold">Ethernet</span>
                        <span class="small muted">1 dispositivo</span>
                    </div>
                    <div class="progress mb-3" style="height:8px;background:#26354b;">
                        <div class="progress-bar" style="width:20%;background:#818cf8;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-semibold">LoRa via gateway</span>
                        <span class="small muted">1 dispositivo</span>
                    </div>
                    <div class="progress" style="height:8px;background:#26354b;">
                        <div class="progress-bar" style="width:20%;background:#34d399;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <span class="small fw-semibold">Link móvel / drone</span>
                        <span class="small muted">1 dispositivo</span>
                    </div>
                    <div class="progress" style="height:8px;background:#26354b;">
                        <div class="progress-bar" style="width:20%;background:#fbbf24;"></div>
                    </div>

                    <p class="footer-note mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        As quantidades são demonstrativas. O enlace LoRa pode chegar ao serviço IP/MQTT por um gateway; o drone usa seu próprio enlace móvel. Nenhuma dessas conexões está sendo consultada nesta página.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 pt-2">
        <span class="footer-note">IoT-Edge Center · Protótipo para Desenvolvimento Web</span>
        <span class="footer-note">Modo de demonstração · Sem conexão com hardware ou backend</span>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
(() => {
    'use strict';

    // Conjunto fictício alinhado aos códigos utilizados nas telas Dispositivos e Locais.
    // Os estados, contatos e endereços são demonstrativos e não provam conectividade real.
    const devices = [
        {
            id: "EMB-GAS-001",
            name: "Nó de monitoramento de gás MQ-4",
            type: "Nó sensor",
            sensors: "MQ-4 · gas_level, RSSI e SNR",
            transport: "LoRa → Gateway / MQTT",
            ip: "—",
            status: "Online",
            lastSeen: "Há 18 s (simulado)",
            icon: "bi-activity",
            zone: "Prédio A / Andar 01 / Sala de Controle",
            locationCode: "LOC-SALA-CTRL",
            locationName: "Sala de Controle",
            protocol: "LoRa 915 MHz / MQTT demonstrativo",
            sensorKeys: ["gas", "rssi", "snr"]
        },
        {
            id: "AUD-001",
            name: "Módulo de áudio e síntese vocal",
            type: "Atuador",
            sensors: "Saída de áudio / TTS",
            transport: "Wi-Fi / MQTT",
            ip: "192.168.20.15 (fictício)",
            status: "Online",
            lastSeen: "Há 24 s (simulado)",
            icon: "bi-volume-up",
            zone: "Prédio A / Andar 01 / Sala de Controle",
            locationCode: "LOC-SALA-CTRL",
            locationName: "Sala de Controle",
            protocol: "MQTT demonstrativo",
            sensorKeys: [],
            actuatorState: "Saída de áudio preparada na simulação"
        },
        {
            id: "REL-001",
            name: "Módulo de relé de controle",
            type: "Atuador",
            sensors: "Estado físico depende de confirmação",
            transport: "Wi-Fi / MQTT",
            ip: "192.168.20.17 (fictício)",
            status: "Desconhecido",
            lastSeen: "Sem confirmação",
            icon: "bi-toggles",
            zone: "Prédio A / Andar 01 / Zona Técnica",
            locationCode: "LOC-ZONA-TECH",
            locationName: "Zona Técnica",
            protocol: "MQTT demonstrativo",
            sensorKeys: [],
            actuatorState: "Estado não confirmado"
        },
        {
            id: "CAM-001",
            name: "Câmera IP do corredor norte",
            type: "Câmera",
            sensors: "Vídeo / fluxo IP",
            transport: "Ethernet / IP",
            ip: "192.168.30.21 (fictício)",
            status: "Online",
            lastSeen: "Há 12 s (simulado)",
            icon: "bi-camera-video",
            zone: "Prédio A / Andar 01 / Corredor Norte",
            locationCode: "LOC-CORREDOR-N",
            locationName: "Corredor Norte",
            protocol: "Rede IP; stream não conectado nesta demonstração",
            sensorKeys: []
        },
        {
            id: "DRONE-001",
            name: "Drone de inspeção móvel",
            type: "Drone",
            sensors: "Telemetria / vídeo (planejado para integração)",
            transport: "Link móvel próprio",
            ip: "Não aplicável nesta demonstração",
            status: "Desatualizado",
            lastSeen: "Há 8 min (simulado)",
            icon: "bi-airplane",
            zone: "Área Externa de Inspeção",
            locationCode: "LOC-EXT-01",
            locationName: "Área Externa",
            protocol: "Integração móvel planejada; sem conexão real",
            sensorKeys: []
        }
    ];

    const statusClass = {
        "Online": "status-online",
        "Offline": "status-offline",
        "Desatualizado": "status-warning",
        "Desconhecido": "status-unknown"
    };

    const sensorInfo = {
        gas: {
            label: "Leitura bruta MQ-4 (gas_level)",
            unit: "contagem ADC simulada",
            color: "#38bdf8",
            nodes: ["EMB-GAS-001"],
            threshold: "Referência demonstrativa: 2500 unidades ADC. Não é ppm e não equivale a uma concentração calibrada.",
            history: { "EMB-GAS-001": [2180, 2240, 2310, 2390, 2510, 2670, 2780, 2710] }
        },
        rssi: {
            label: "RSSI do enlace LoRa",
            unit: "dBm (simulado)",
            color: "#a78bfa",
            nodes: ["EMB-GAS-001"],
            threshold: "RSSI representa potência recebida; não fornece distância direta.",
            history: { "EMB-GAS-001": [-91, -87, -82, -79, -76, -73, -75, -72] }
        },
        snr: {
            label: "SNR do enlace LoRa",
            unit: "dB (simulado)",
            color: "#34d399",
            nodes: ["EMB-GAS-001"],
            threshold: "SNR representa a relação entre sinal e ruído; valores dependem do ambiente de rádio.",
            history: { "EMB-GAS-001": [-8, -6, -4, -2, 1, 3, 2, 4] }
        }
    };

    const chartLabels = [
        "14:00", "14:05", "14:10", "14:15",
        "14:20", "14:25", "14:30", "14:35"
    ];

    let readingsChart = null;
    let statusChart = null;
    let deviceDetailChart = null;
    let selectedDeviceId = null;

    function escapeHTML(value) {
        return String(value ?? "").replace(/[&<>"']/g, char => ({
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;"
        }[char]));
    }

    function getDevice(id) {
        return devices.find(device => device.id === id);
    }

    function getDeviceLatestValue(device, sensorKey) {
        const values = sensorInfo[sensorKey]?.history?.[device.id];
        if (!values || values.length === 0) return null;
        return values[values.length - 1];
    }

    function getDeviceSensorKeys(device) {
        return (device.sensorKeys || []).filter(key =>
            sensorInfo[key] && sensorInfo[key].history[device.id]
        );
    }

    function populateHistoryDeviceOptions() {
        const sensorSelect = document.getElementById("sensorChartSelect");
        const deviceSelect = document.getElementById("deviceChartSelect");

        if (!sensorSelect || !deviceSelect) return;

        const info = sensorInfo[sensorSelect.value];
        const previousNode = deviceSelect.value;

        const availableNodes = info.nodes.filter(id => {
            const device = getDevice(id);
            return device && info.history[id];
        });

        deviceSelect.innerHTML = availableNodes.map(id => {
            return `<option value="${escapeHTML(id)}">${escapeHTML(id)}</option>`;
        }).join("");

        if (availableNodes.includes(previousNode)) {
            deviceSelect.value = previousNode;
        } else if (availableNodes.length > 0) {
            deviceSelect.value = availableNodes[0];
        }

        updateHistoryChart();
    }

    function updateHistoryChart() {
        const sensorKey = document.getElementById("sensorChartSelect").value;
        const nodeId = document.getElementById("deviceChartSelect").value;
        const info = sensorInfo[sensorKey];
        const device = getDevice(nodeId);

        if (!info || !device || !info.history[nodeId] || !readingsChart) return;

        readingsChart.data.datasets[0].label = `${info.label} (${device.id})`;
        readingsChart.data.datasets[0].data = info.history[nodeId];
        readingsChart.data.datasets[0].borderColor = info.color;
        readingsChart.data.datasets[0].backgroundColor = info.color + "20";
        readingsChart.update();

        document.getElementById("chartUnit").textContent =
            `Unidade demonstrativa: ${info.unit}`;
    }

    function createReadingsChart() {
        readingsChart = new Chart(document.getElementById("readingsChart"), {
            type: "line",
            data: {
                labels: chartLabels,
                datasets: [{
                    label: "",
                    data: [],
                    borderColor: "#38bdf8",
                    backgroundColor: "rgba(56,189,248,.12)",
                    borderWidth: 2,
                    pointRadius: 2.5,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: .35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#172338",
                        titleColor: "#e5edf8",
                        bodyColor: "#cbd5e1",
                        borderColor: "#30415a",
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { color: "rgba(148,163,184,.08)" },
                        ticks: { color: "#91a2ba", maxTicksLimit: 8 }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { color: "rgba(148,163,184,.10)" },
                        ticks: { color: "#91a2ba" }
                    }
                }
            }
        });

        populateHistoryDeviceOptions();
    }

    function detailSensorMarkup(device) {
        const sensorKeys = getDeviceSensorKeys(device);

        if (sensorKeys.length === 0) {
            return `
                <div class="reading-detail">
                    <div class="detail-label">Telemetria de sensores</div>
                    <div class="detail-value">Nenhum histórico de sensor disponível</div>
                </div>`;
        }

        const cards = sensorKeys.map(key => {
            const info = sensorInfo[key];
            const latest = getDeviceLatestValue(device, key);
            const currentAvailable = device.status === "Online" && latest !== null;
            const displayedValue = currentAvailable
                ? `${latest} ${info.unit}`
                : "Sem leitura atual confirmada";

            return `
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">${escapeHTML(info.label)}</div>
                        <div class="detail-value">${escapeHTML(displayedValue)}</div>
                        <div class="detail-description mt-2">${escapeHTML(info.threshold)}</div>
                    </div>
                </div>`;
        }).join("");

        return `
            <div class="row g-3 mb-3">${cards}</div>
            <div class="detail-card">
                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    <div>
                        <div class="section-title">Histórico temporal do nó</div>
                        <div class="section-subtitle">Série fictícia associada a ${escapeHTML(device.id)}</div>
                    </div>
                    <span class="demo-pill">Simulado</span>
                </div>
                <div class="device-detail-chart">
                    <canvas id="deviceDetailChart" aria-label="Histórico demonstrativo do sensor" role="img"></canvas>
                </div>
            </div>`;
    }

    function detailActuatorMarkup(device) {
        const isUnknown = device.status === "Desconhecido";
        const isOffline = device.status === "Offline";
        const stateText = isUnknown || isOffline
            ? "Estado físico não confirmado"
            : (device.actuatorState || "Pronto na simulação");

        return `
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="detail-card d-flex align-items-center gap-3">
                        <div class="detail-icon"><i class="bi bi-toggles"></i></div>
                        <div>
                            <div class="detail-label">Estado do atuador</div>
                            <div class="detail-value">${escapeHTML(stateText)}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">Comportamento operacional</div>
                        <div class="detail-value">Ações remotas simuladas</div>
                    </div>
                </div>
            </div>`;
    }

    function detailCameraMarkup(device) {
        const isOffline = device.status === "Offline";

        return `
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">Prévia de Vídeo</div>
                        <div class="detail-value">${isOffline ? "Feed indisponível (Offline)" : "Transmissão RTSP simulada"}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">Parâmetros de Rede</div>
                        <div class="detail-value">IP: ${escapeHTML(device.ip)} · ${escapeHTML(device.zone)}</div>
                    </div>
                </div>
            </div>`;
    }

    function detailDroneMarkup() {
        return `
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">Telemetria de Voo</div>
                        <div class="detail-value">DJI Mini 4 Pro · Estado demonstrativo</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="detail-card">
                        <div class="detail-label">Missão Atual</div>
                        <div class="detail-value">Missão não confirmada; integração com o sistema do drone ainda não está conectada.</div>
                    </div>
                </div>
            </div>`;
    }

    function detailGatewayMarkup(device) {
        return `
            <div class="row g-3">
                <div class="col-12">
                    <div class="detail-card">
                        <div class="detail-label">Infraestrutura de Comunicação</div>
                        <div class="detail-value">Gateway LoRa / Ethernet (${escapeHTML(device.ip)})</div>
                    </div>
                </div>
            </div>`;
    }

    function getDeviceDetailContent(device) {
        if (device.type === "Nó sensor" || device.type === "Controlador") {
            return detailSensorMarkup(device);
        }
        if (device.type === "Atuador") return detailActuatorMarkup(device);
        if (device.type === "Câmera") return detailCameraMarkup(device);
        if (device.type === "Drone") return detailDroneMarkup(device);
        return detailGatewayMarkup(device);
    }

    function renderDevices() {
        const query = document.getElementById("deviceSearch").value.trim().toLowerCase();
        const type = document.getElementById("typeFilter").value;
        const status = document.getElementById("statusFilter").value;

        const filtered = devices.filter(device => {
            const matchesQuery = [
                device.id, device.name, device.type, device.sensors, device.transport,
                device.locationCode, device.locationName, device.zone
            ].some(value => String(value ?? "").toLowerCase().includes(query));

            const location = document.getElementById("locationFilter").value;

            return matchesQuery &&
                (!type || device.type === type) &&
                (!location || device.locationCode === location) &&
                (!status || device.status === status);
        });

        const tbody = document.getElementById("deviceTableBody");

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-search fs-3 d-block mb-2"></i>
                            Nenhum dispositivo corresponde aos filtros.
                        </div>
                    </td>
                </tr>`;
        } else {
            tbody.innerHTML = filtered.map(device => {
                const isSelected = selectedDeviceId === device.id;

                let html = `
                    <tr class="device-row ${isSelected ? "selected" : ""}"
                        data-device-id="${escapeHTML(device.id)}" tabindex="0" role="button">
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="type-icon"><i class="bi ${escapeHTML(device.icon)}"></i></span>
                                <div>
                                    <div class="device-id">
                                        ${escapeHTML(device.id)}
                                        <i class="bi bi-chevron-down device-expand-icon"></i>
                                    </div>
                                    <div class="device-name">${escapeHTML(device.name)}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${escapeHTML(device.type)}</div>
                            <div class="device-name">${escapeHTML(device.sensors)}</div>
                        </td>
                        <td>
                            <div>${escapeHTML(device.locationName)}</div>
                            <div class="device-name">${escapeHTML(device.locationCode)}</div>
                        </td>
                        <td>
                            <div>${escapeHTML(device.transport)}</div>
                            <div class="device-name">IP: ${escapeHTML(device.ip)}</div>
                        </td>
                        <td>
                            <span class="status ${statusClass[device.status] || "status-unknown"}">
                                <span class="live-dot"></span>${escapeHTML(device.status)}
                            </span>
                        </td>
                        <td>
                            <span>${escapeHTML(device.lastSeen)}</span>
                            <div class="device-name">Simulado</div>
                        </td>
                    </tr>`;

                if (isSelected) {
                    html += `
                        <tr class="device-detail-row">
                            <td colspan="6">
                                <div class="device-detail-panel">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                        <div>
                                            <div class="eyebrow">${escapeHTML(device.type)}</div>
                                            <h3 class="section-title" style="font-size:1.15rem;margin-top:2px;">
                                                ${escapeHTML(device.id)} — ${escapeHTML(device.name)}
                                            </h3>
                                            <div class="section-subtitle">
                                                Local: ${escapeHTML(device.zone)} · Protocolo: ${escapeHTML(device.protocol)}
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-soft btn-sm close-detail-button" type="button">
                                            <i class="bi bi-x-lg"></i> Fechar
                                        </button>
                                    </div>
                                    ${getDeviceDetailContent(device)}
                                </div>
                            </td>
                        </tr>`;
                }

                return html;
            }).join("");
        }

        document.getElementById("tableSummary").textContent =
            `Exibindo ${filtered.length} de ${devices.length} dispositivos demonstrativos.`;

        attachRowListeners();

        if (selectedDeviceId) {
            initDeviceDetailChart(selectedDeviceId);
        }
    }

    function attachRowListeners() {
        document.querySelectorAll(".device-row").forEach(row => {
            row.addEventListener("click", () => {
                toggleDeviceDetail(row.getAttribute("data-device-id"));
            });

            row.addEventListener("keydown", event => {
                if (event.key === "Enter" || event.key === " ") {
                    event.preventDefault();
                    toggleDeviceDetail(row.getAttribute("data-device-id"));
                }
            });
        });

        document.querySelectorAll(".close-detail-button").forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();
                closeDeviceDetail();
            });
        });
    }

    function toggleDeviceDetail(id) {
        selectedDeviceId = selectedDeviceId === id ? null : id;
        renderDevices();
    }

    function closeDeviceDetail() {
        selectedDeviceId = null;
        renderDevices();
    }

    function initDeviceDetailChart(deviceId) {
        const canvas = document.getElementById("deviceDetailChart");
        if (!canvas) return;

        const device = getDevice(deviceId);
        if (!device) return;

        const sensorKeys = getDeviceSensorKeys(device);
        if (sensorKeys.length === 0) return;

        const info = sensorInfo[sensorKeys[0]];
        const values = info.history[deviceId] || [];

        if (deviceDetailChart) {
            deviceDetailChart.destroy();
        }

        deviceDetailChart = new Chart(canvas, {
            type: "line",
            data: {
                labels: chartLabels,
                datasets: [{
                    label: `${info.label} (${device.id})`,
                    data: values,
                    borderColor: info.color,
                    backgroundColor: info.color + "18",
                    borderWidth: 2,
                    pointRadius: 3,
                    fill: true,
                    tension: .35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#172338",
                        titleColor: "#e5edf8",
                        bodyColor: "#cbd5e1",
                        borderColor: "#30415a",
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { color: "rgba(148,163,184,.08)" },
                        ticks: { color: "#91a2ba", maxTicksLimit: 8 }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { color: "rgba(148,163,184,.10)" },
                        ticks: { color: "#91a2ba" }
                    }
                }
            }
        });
    }

    function createStatusChart() {
        const labels = ["Online", "Offline", "Desatualizado", "Desconhecido"];
        const colors = ["#34d399", "#fb7185", "#fbbf24", "#94a3b8"];
        const counts = labels.map(status =>
            devices.filter(device => device.status === status).length
        );

        statusChart = new Chart(document.getElementById("statusChart"), {
            type: "doughnut",
            data: {
                labels,
                datasets: [{
                    data: counts,
                    backgroundColor: colors,
                    borderColor: "#111b2e",
                    borderWidth: 4,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "70%",
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#172338",
                        titleColor: "#e5edf8",
                        bodyColor: "#cbd5e1",
                        borderColor: "#30415a",
                        borderWidth: 1
                    }
                }
            }
        });

        document.getElementById("statusLegend").innerHTML =
            labels.map((label, index) => `
                <div class="legend-row">
                    <span class="legend-label">
                        <span class="legend-swatch" style="background:${colors[index]}"></span>
                        ${escapeHTML(label)}
                    </span>
                    <strong>${counts[index]}</strong>
                </div>
            `).join("");
    }

    function updateMetrics() {
        const online = devices.filter(device => device.status === "Online").length;
        const offline = devices.filter(device => device.status === "Offline").length;
        const telemetrySeries = Object.keys(sensorInfo).length;
        const actuators = devices.filter(device => device.type === "Atuador").length;
        const openAlerts = 3; // Três ocorrências fictícias apresentadas na lista lateral.

        document.getElementById("deviceCount").textContent = devices.length;
        document.getElementById("onlineCount").textContent = online;
        document.getElementById("offlineCount").textContent = offline;
        document.getElementById("sensorCount").textContent = telemetrySeries;
        document.getElementById("actuatorCount").textContent = actuators;
        document.getElementById("alertCount").textContent = openAlerts;
        document.getElementById("alertBadge").textContent = `${openAlerts} abertos`;
    }

    function refreshDemo() {
        document.getElementById("lastUpdated").textContent =
            new Date().toLocaleString("pt-BR");

        renderDevices();
        updateMetrics();

        const labels = ["Online", "Offline", "Desatualizado", "Desconhecido"];
        const counts = labels.map(status =>
            devices.filter(device => device.status === status).length
        );

        statusChart.data.datasets[0].data = counts;
        statusChart.update();

        document.getElementById("statusLegend").innerHTML =
            statusChart.data.labels.map((label, index) => `
                <div class="legend-row">
                    <span class="legend-label">
                        <span class="legend-swatch"
                            style="background:${statusChart.data.datasets[0].backgroundColor[index]}"></span>
                        ${escapeHTML(label)}
                    </span>
                    <strong>${counts[index]}</strong>
                </div>
            `).join("");

        console.info("Painel demonstrativo recalculado. Nenhum dispositivo real foi consultado.");
    }

    function initDashboard() {
        document.getElementById("lastUpdated").textContent =
            new Date().toLocaleString("pt-BR");

        document.getElementById("deviceSearch").addEventListener("input", renderDevices);
        document.getElementById("typeFilter").addEventListener("change", renderDevices);
        document.getElementById("locationFilter").addEventListener("change", renderDevices);
        document.getElementById("statusFilter").addEventListener("change", renderDevices);
        document.getElementById("sensorChartSelect").addEventListener("change", populateHistoryDeviceOptions);
        document.getElementById("deviceChartSelect").addEventListener("change", updateHistoryChart);
        document.getElementById("refreshButton").addEventListener("click", refreshDemo);

        renderDevices();
        createReadingsChart();
        createStatusChart();
        updateMetrics();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initDashboard);
    } else {
        initDashboard();
    }
})();
</script>
<?= $this->endSection() ?>
