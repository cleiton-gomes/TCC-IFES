<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Videomonitoramento e Eventos
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .video-monitor-page {
        min-height: 100vh;
        padding: 1.5rem;
        color: #f8fafc;
    }

    .video-monitor-page .card-dark {
        background-color: #111827;
        color: #f8fafc;
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 12px;
    }

    .video-monitor-page .video-card {
        position: relative;
        background-color: #050811;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .video-monitor-page .video-overlay-top {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 6px;
        z-index: 5;
        pointer-events: none;
    }

    .video-monitor-page .video-overlay-bottom {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        z-index: 5;
        background: rgba(11, 15, 25, 0.82);
        backdrop-filter: blur(6px);
        padding: 6px 10px;
        border-radius: 6px;
    }

    .video-monitor-page .badge-simulation {
        background-color: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
        border: 1px solid rgba(245, 158, 11, 0.35);
        font-weight: 800;
        letter-spacing: .03em;
    }

    .video-monitor-page .page-link-soft {
        color: #bae6fd;
        border: 1px solid rgba(125, 211, 252, .30);
        background: rgba(14, 116, 144, .12);
    }

    .video-monitor-page .page-link-soft:hover {
        color: #fff;
        border-color: rgba(125, 211, 252, .65);
        background: rgba(14, 116, 144, .25);
    }

    .video-monitor-page .vlm-log-container {
        max-height: 480px;
        overflow-y: auto;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.10);
    }

    .video-monitor-page .vlm-log-item {
        padding: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.88rem;
        overflow-wrap: anywhere;
    }

    .video-monitor-page .vlm-log-item:last-child {
        border-bottom: 0;
    }

    .video-monitor-page .vlm-log-item:nth-child(odd) {
        background-color: #111827;
    }

    .video-monitor-page .vlm-log-item:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.035);
    }

    .video-monitor-page .vlm-log-item:hover {
        background-color: rgba(59, 130, 246, 0.12);
    }

    .video-monitor-page .form-control-dark,
    .video-monitor-page .form-select-dark {
        background-color: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #fff;
        border-radius: 8px;
    }

    .video-monitor-page .form-control-dark::placeholder {
        color: #94a3b8;
    }

    .video-monitor-page .form-control-dark:focus,
    .video-monitor-page .form-select-dark:focus {
        background-color: #1e293b;
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
    }

    .video-monitor-page .form-select-dark option {
        background-color: #111827;
        color: #fff;
    }

    .video-monitor-page .modal-content-dark {
        background-color: #111827;
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #f8fafc;
        border-radius: 14px;
    }

    .video-monitor-page .channel-heading {
        min-height: 42px;
    }

    .video-monitor-page .channel-label {
        max-width: 75%;
        overflow-wrap: anywhere;
    }

    .video-monitor-page .telemetry {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        text-align: left;
        font-family: monospace;
        color: #60a5fa;
        text-shadow: 0 0 4px #000;
        z-index: 2;
        pointer-events: none;
    }

    .video-monitor-page .demo-note {
        padding: 10px 12px;
        color: #cbd5e1;
        background-color: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: 8px;
        font-size: 0.82rem;
    }

    .video-monitor-page .snapshot-status {
        font-size: 0.82rem;
        color: #94a3b8;
    }

    @media (max-width: 767.98px) {
        .video-monitor-page {
            padding: 1rem;
        }

        .video-monitor-page .channel-heading {
            align-items: flex-start !important;
            flex-direction: column;
        }

        .video-monitor-page .video-overlay-top {
            top: 7px;
            left: 7px;
            right: 7px;
        }

        .video-monitor-page .video-overlay-bottom {
            bottom: 7px;
            left: 7px;
            right: 7px;
            padding: 5px 7px;
        }

        .video-monitor-page .video-overlay-bottom small {
            font-size: 0.72rem;
        }

        .video-monitor-page .channel-label {
            max-width: 100%;
            font-size: 0.68rem;
        }
    }

</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="video-monitor-page">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <div class="text-info text-uppercase small fw-bold mb-1" style="letter-spacing:.14em;">Central de operações</div>
            <h1 class="h3 fw-bold mb-1">Videomonitoramento e eventos</h1>
            <p class="text-secondary mb-0">Canais demonstrativos, eventos visuais e ações preparadas para integração futura.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm page-link-soft" href="<?= site_url('embarcados') ?>"><i class="fa-solid fa-microchip me-1"></i>Dispositivos</a>
            <a class="btn btn-sm page-link-soft" href="<?= site_url('locais') ?>"><i class="fa-solid fa-location-dot me-1"></i>Locais</a>
            <a class="btn btn-sm page-link-soft" href="<?= site_url('dashboard') ?>"><i class="fa-solid fa-chart-pie me-1"></i>Dashboard</a>
        </div>
    </div>

    <div class="demo-note mb-3">
        <i class="fa-solid fa-circle-info text-warning me-1"></i>
        <strong>Modo demonstrativo:</strong>
        os quadros de vídeo, a telemetria do drone, as análises VLM/LLM, os eventos e as ações de TTS/relé são exemplos locais.
        Nenhuma imagem real está sendo recebida, nenhum modelo de IA está sendo executado e nenhum comando é enviado ao hardware.
    </div>

    <div class="row g-4">

        <!-- Área principal de vídeo -->
        <div class="col-lg-8">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 channel-heading">
                <h5 class="fw-bold m-0">
                    <i class="fa-solid fa-display me-2 text-primary"></i>
                    Ingestão Heterogênea de Vídeo
                </h5>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary px-3 py-2">
                        <i class="fa-solid fa-camera me-1"></i>
                        1 câmera cadastrada
                    </span>

                    <span class="badge bg-info text-dark px-3 py-2">
                        <i class="fa-solid fa-plane me-1"></i>
                        1 drone demonstrativo
                    </span>
                </div>
            </div>

            <div class="row g-3">

                <!-- Canal 1: única câmera vinculada ao inventário demonstrativo -->
                <div class="col-md-6">
                    <div class="card-dark p-2">
                        <div class="video-card">

                            <div class="video-overlay-top">
                                <span class="badge bg-dark bg-opacity-75 text-white border border-secondary channel-label">
                                    <i class="fa-solid fa-camera me-1"></i>
                                    CAM-001 | Corredor Norte
                                </span>

                                <span class="badge badge-simulation">● SIMULADO</span>
                            </div>

                            <div
                                style="position:absolute;border:2px solid #ef4444;width:35%;height:50%;top:25%;left:30%;border-radius:4px;pointer-events:none;"
                                aria-label="Área de detecção simulada"
                            >
                                <span class="badge bg-danger position-absolute top-0 start-0 translate-middle-y">
                                    Pessoa (Sem EPI)
                                </span>
                            </div>

                            <i class="fa-solid fa-video fa-3x text-secondary opacity-25"></i>

                            <div class="video-overlay-bottom">
                                <small class="text-secondary">
                                    <i class="fa-solid fa-location-dot me-1 text-warning"></i>
                                    Corredor Norte · LOC-CORREDOR-N
                                </small>

                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-light"
                                        onclick="capturarSnapshot('CAM-001')"
                                        title="Snapshot"
                                        aria-label="Capturar snapshot da câmera 1"
                                    >
                                        <i class="fa-solid fa-camera"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-outline-info"
                                        onclick="dispararTTSAlvo('AUD-001')"
                                        title="Preparar transmissão TTS"
                                        aria-label="Preparar TTS para AUD-001"
                                    >
                                        <i class="fa-solid fa-volume-high"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Canal 2: Drone -->
                <div class="col-md-6">
                    <div class="card-dark p-2 border-primary">
                        <div class="video-card">

                            <div class="video-overlay-top">
                                <span class="badge bg-primary text-white channel-label">
                                    <i class="fa-solid fa-plane me-1"></i>
                                    DRONE-001 | DJI Mini 4 Pro
                                </span>

                                <span class="badge badge-simulation">
                                    <i class="fa-solid fa-battery-three-quarters me-1"></i>
                                    Bateria demo: 84%
                                </span>
                            </div>

                            <div class="telemetry small">
                                <div>ALT: 12,4 m (demo)</div>
                                <div>SPD: 2,1 m/s (demo)</div>
                                <div>GPS: 14 sat. (demo)</div>
                            </div>

                            <i class="fa-solid fa-plane fa-3x text-primary opacity-25"></i>

                            <div class="video-overlay-bottom">
                                <small class="text-info">
                                    <i class="fa-solid fa-route me-1"></i>
                                    Inspeção demonstrativa · Área Externa (LOC-EXT-01)
                                </small>

                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-light"
                                        onclick="capturarSnapshot('DRONE-001')"
                                        title="Snapshot do drone"
                                        aria-label="Capturar snapshot do drone"
                                    >
                                        <i class="fa-solid fa-camera"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-outline-warning"
                                        onclick="simularRetornoDrone()"
                                        title="Simular retorno à base"
                                        aria-label="Simular retorno do drone à base"
                                    >
                                        <i class="fa-solid fa-house"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Canal 3 -->
                <div class="col-md-6">
                    <div class="card-dark p-2">
                        <div class="video-card">

                            <div class="video-overlay-top">
                                <span class="badge bg-dark bg-opacity-75 text-white border border-secondary channel-label">
                                    <i class="fa-solid fa-camera me-1"></i>
                                    CANAL-DEMO-02 | Perímetro Externo (não cadastrado)
                                </span>

                                <span class="badge badge-simulation">● SIMULADO</span>
                            </div>

                            <i class="fa-solid fa-video fa-3x text-secondary opacity-25"></i>

                            <div class="video-overlay-bottom">
                                <small class="text-secondary">
                                    <i class="fa-solid fa-location-dot me-1 text-warning"></i>
                                    Área Externa · LOC-EXT-01
                                </small>

                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-light"
                                        onclick="capturarSnapshot('CANAL-DEMO-02')"
                                        title="Snapshot"
                                        aria-label="Capturar snapshot da câmera 2"
                                    >
                                        <i class="fa-solid fa-camera"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-outline-warning"
                                        onclick="acionarCargaAlvo('REL-001')"
                                        title="Simular acionamento de refletor"
                                        aria-label="Simular acionamento do relé REL-001"
                                    >
                                        <i class="fa-solid fa-bolt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Canal 4 -->
                <div class="col-md-6">
                    <div class="card-dark p-2">
                        <div class="video-card">

                            <div class="video-overlay-top">
                                <span class="badge bg-dark bg-opacity-75 text-white border border-secondary channel-label">
                                    <i class="fa-solid fa-camera me-1"></i>
                                    CANAL-DEMO-03 | Zona Técnica (não cadastrado)
                                </span>

                                <span class="badge badge-simulation">● SIMULADO</span>
                            </div>

                            <i class="fa-solid fa-video fa-3x text-secondary opacity-25"></i>

                            <div class="video-overlay-bottom">
                                <small class="text-secondary">
                                    <i class="fa-solid fa-location-dot me-1 text-warning"></i>
                                    Zona Técnica · LOC-ZONA-TECH
                                </small>

                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-light"
                                        onclick="capturarSnapshot('CANAL-DEMO-03')"
                                        title="Snapshot"
                                        aria-label="Capturar snapshot da câmera 3"
                                    >
                                        <i class="fa-solid fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="snapshot-status mt-3" id="snapshotStatus" aria-live="polite">
                Canais e telemetria são demonstrativos; capturas não geram imagens reais.
            </div>

        </div>

        <!-- Painel lateral -->
        <div class="col-lg-4">

            <!-- Eventos visuais e de telemetria demonstrativos -->
            <div class="card-dark p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <h6 class="fw-bold m-0 text-warning">
                        <i class="fa-solid fa-brain me-2"></i>
                        Eventos visuais e de telemetria
                    </h6>

                    <span class="badge bg-warning text-dark">VLM/LLM simulado</span>
                </div>

                <div class="vlm-log-container mb-3" id="vlmLogBox" aria-live="polite">

                    <div class="vlm-log-item" data-event-date="2026-10-21" data-severity="ALERT">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="text-secondary small fw-semibold">
                                [14:10:02] EVT-EPI-0001 | CAM-001 | LOC-CORREDOR-N | AUD-001
                            </span>
                            <span class="badge bg-danger">Alerta visual simulado</span>
                        </div>
                        <p class="m-0 text-white fw-semibold mt-1">
                            Análise demonstrativa: pessoa identificada sem capacete na área operacional.
                        </p>
                        <small class="text-info d-block mt-1">
                            <i class="fa-solid fa-bullhorn me-1"></i>
                            Ação sugerida: preparar aviso vocal no AUD-001. Nenhum áudio foi reproduzido.
                        </small>
                    </div>

                    <div class="vlm-log-item" data-event-date="2026-10-21" data-severity="INFO">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="text-secondary small fw-semibold">
                                [14:08:45] EVT-DRONE-0001 | DRONE-001 | LOC-EXT-01
                            </span>
                            <span class="badge bg-primary">Inspeção simulada</span>
                        </div>
                        <p class="m-0 text-white fw-semibold mt-1">
                            Inspeção demonstrativa da área externa concluída. Não há telemetria real conectada.
                        </p>
                    </div>

                    <div class="vlm-log-item" data-event-date="2026-10-21" data-severity="ALERT">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="text-secondary small fw-semibold">
                                [14:05:12] EVT-GAS-0001 | EMB-GAS-001 | LOC-SALA-CTRL
                            </span>
                            <span class="badge bg-warning text-dark">Telemetria demonstrativa</span>
                        </div>
                        <p class="m-0 text-white fw-semibold mt-1">
                            gas_level = 2780 (valor bruto simulado), acima da referência ilustrativa configurada.
                        </p>
                        <small class="text-info d-block mt-1">
                            A escala não está calibrada em ppm. O dado não representa uma medição real.
                        </small>
                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm w-100 fw-semibold"
                    data-bs-toggle="modal"
                    data-bs-target="#exportModal"
                >
                    <i class="fa-solid fa-download me-1"></i>
                    Exportar registros demonstrativos
                </button>
            </div>

            <!-- Transmissão vocal -->
            <div class="card-dark p-3">

                <h6 class="fw-bold text-info mb-3">
                    <i class="fa-solid fa-bullhorn me-2"></i>
                    Síntese vocal (simulação)
                </h6>

                <form id="ttsForm" onsubmit="enviarTTSManual(event)">

                    <div class="mb-2">
                        <label for="ttsMode" class="form-label small text-secondary">
                            Modo de Endereçamento
                        </label>

                        <select
                            id="ttsMode"
                            class="form-select form-select-dark form-select-sm"
                            onchange="alternarModoTTS()"
                        >
                            <option value="UNICAST">Unicast (Nó Específico por ID)</option>
                            <option value="MULTICAST">Multicast demonstrativo (por local)</option>
                        </select>
                    </div>

                    <div class="mb-2" id="boxTtsNode">
                        <label for="ttsNodeId" class="form-label small text-secondary">
                            Dispositivo de áudio alvo
                        </label>
                        <select id="ttsNodeId" class="form-select form-select-dark form-select-sm">
                            <option value="AUD-001" data-location="Sala de Controle · LOC-SALA-CTRL" data-type="Módulo de áudio">
                                AUD-001 — Módulo de áudio / Sala de Controle
                            </option>
                        </select>
                        <div class="form-text text-secondary">O protótipo usa somente o destinatário demonstrativo cadastrado para áudio.</div>
                    </div>

                    <div class="mb-2 d-none" id="boxTtsLocation">
                        <label for="ttsLocationTarget" class="form-label small text-secondary">
                            Local de destino (simulação multicast)
                        </label>
                        <select id="ttsLocationTarget" class="form-select form-select-dark form-select-sm">
                            <option value="LOC-SALA-CTRL">Sala de Controle</option>
                            <option value="LOC-CORREDOR-N">Corredor Norte</option>
                            <option value="LOC-ZONA-TECH">Zona Técnica</option>
                            <option value="LOC-EXT-01">Área Externa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ttsText" class="form-label small text-secondary">
                            Mensagem de Voz para Síntese
                        </label>

                        <textarea
                            id="ttsText"
                            class="form-control form-control-dark form-control-sm"
                            rows="3"
                            maxlength="500"
                            placeholder="Digite a instrução vocal..."
                            required
                        >Atenção: Área restrita. Favor se retirar do perímetro.</textarea>

                        <div class="form-text text-secondary">
                            Máximo de 500 caracteres. Texto limitado a 500 caracteres; nada será enviado ao hardware.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info btn-sm w-100 fw-semibold text-dark">
                        <i class="fa-solid fa-paper-plane me-1"></i>
                        Simular preparação da mensagem vocal
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal de exportação -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-dark p-2">

            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold text-primary" id="exportModalTitle">
                    <i class="fa-solid fa-file-export me-2"></i>
                    Exportar eventos demonstrativos
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>
            </div>

            <div class="modal-body">

                <form id="exportForm" onsubmit="executarExportacao(event)">

                    <div class="mb-3">
                        <label for="exportType" class="form-label small fw-semibold">
                            Formato da exportação demonstrativa
                        </label>

                        <select id="exportType" class="form-select form-select-dark" required>
                            <option value="CSV">Relatório Tabular de Logs (CSV)</option>
                            <option value="JSON">Dados Estruturados de Eventos (JSON)</option>
                            <option value="ZIP">Pacote Completo de Evidências (ZIP)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exportFilter" class="form-label small fw-semibold">
                            Filtro de Severidade
                        </label>

                        <select id="exportFilter" class="form-select form-select-dark">
                            <option value="ALL">Todos os Registros (Normal e Alertas)</option>
                            <option value="ALERTS">Somente Alertas Críticos &amp; Disparos TTS/Relé</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="exportStartDate" class="form-label small fw-semibold">
                                Data inicial dos eventos
                            </label>

                            <input
                                type="date"
                                id="exportStartDate"
                                class="form-control form-control-dark"
                                value="2026-10-21"
                                required
                            >
                        </div>

                        <div class="col-6">
                            <label for="exportEndDate" class="form-label small fw-semibold">
                                Data final dos eventos
                            </label>

                            <input
                                type="date"
                                id="exportEndDate"
                                class="form-control form-control-dark"
                                value="2026-10-21"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="fa-solid fa-download me-2"></i>
                        Gerar e Baixar Exportação
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    /**
     * Alterna os campos de destinatário entre unicast e multicast.
     */
    function alternarModoTTS() {
        const mode = document.getElementById('ttsMode').value;
        const boxNode = document.getElementById('boxTtsNode');
        const boxLocation = document.getElementById('boxTtsLocation');

        if (mode === 'UNICAST') {
            boxNode.classList.remove('d-none');
            boxLocation.classList.add('d-none');
        } else {
            boxNode.classList.add('d-none');
            boxLocation.classList.remove('d-none');
        }
    }

    /**
     * Registra uma ação demonstrativa de transmissão TTS.
     * Não envia áudio nem comandos ao hardware.
     */
    function enviarTTSManual(event) {
        event.preventDefault();

        const mode = document.getElementById('ttsMode').value;
        const message = document.getElementById('ttsText').value.trim();
        const logBox = document.getElementById('vlmLogBox');

        if (!message) {
            alert('Digite uma mensagem antes de continuar.');
            return;
        }

        const timeStr = new Date().toLocaleTimeString('pt-BR');
        let headerInfo = '';
        let targetInfo = '';

        if (mode === 'UNICAST') {
            const nodeSelect = document.getElementById('ttsNodeId');
            const selectedOption = nodeSelect.options[nodeSelect.selectedIndex];

            headerInfo = `[${timeStr}] ${nodeSelect.value} | ${
                selectedOption.dataset.location || 'Local não informado'
            } | ${
                selectedOption.dataset.type || 'Dispositivo'
            }`;

            targetInfo = `Destinatário demonstrativo: ${nodeSelect.value}`;
        } else {
            const locationSelect = document.getElementById('ttsLocationTarget');
            const locationCode = locationSelect.value;
            const locationName = locationSelect.options[locationSelect.selectedIndex].text;

            headerInfo = `[${timeStr}] BROADCAST | ${locationCode} | Multicast demonstrativo`;
            targetInfo = `Destinatários hipotéticos vinculados a ${locationName}`;
        }

        // Criamos os elementos com textContent para não interpretar
        // a mensagem digitada pelo operador como código HTML.
        const newLog = document.createElement('div');
        newLog.className = 'vlm-log-item';
        newLog.dataset.eventDate = getLocalDateISO();
        newLog.dataset.severity = 'INFO';

        const headerRow = document.createElement('div');
        headerRow.className = 'd-flex justify-content-between align-items-center gap-2';

        const header = document.createElement('span');
        header.className = 'text-secondary small fw-semibold';
        header.textContent = headerInfo;

        const badge = document.createElement('span');
        badge.className = 'badge bg-info text-dark';
        badge.textContent = 'TTS Manual';

        headerRow.append(header, badge);

        const description = document.createElement('p');
        description.className = 'm-0 text-white fw-semibold mt-1';
        description.textContent = `Transmissão vocal simulada pelo operador. ${targetInfo}.`;

        const textLine = document.createElement('small');
        textLine.className = 'text-info d-block mt-1';
        textLine.textContent = `Texto: "${message}"`;

        newLog.append(headerRow, description, textLine);
        logBox.insertBefore(newLog, logBox.firstChild);

        alert('Transmissão TTS simulada. Nenhum áudio foi enviado ao hardware.');
    }

    /**
     * Prepara o formulário de transmissão com o nó escolhido.
     */
    function dispararTTSAlvo(nodeId) {
        const modeSelect = document.getElementById('ttsMode');
        const nodeSelect = document.getElementById('ttsNodeId');

        modeSelect.value = 'UNICAST';
        alternarModoTTS();
        nodeSelect.value = nodeId;

        if (nodeSelect.value !== nodeId) {
            alert('O dispositivo selecionado não está disponível como destinatário de áudio no protótipo.');
            return;
        }

        document.getElementById('ttsText').focus();
    }

    /**
     * Registra uma intenção de acionamento fictícia para o relé.
     */
    function acionarCargaAlvo(nodeId) {
        alert(
            `[SIMULAÇÃO] Pulso de acionamento do relé solicitado para ${nodeId}. ` +
            'Nenhum comando foi enviado ao dispositivo físico.'
        );
    }

    /**
     * Simula a captura de um snapshot.
     */
    function capturarSnapshot(cameraName) {
        const status = document.getElementById('snapshotStatus');
        const time = new Date().toLocaleTimeString('pt-BR');

        status.textContent =
            `[${time}] Snapshot simulado solicitado para ${cameraName}. ` +
            'Nenhum arquivo de imagem real foi capturado.';

        alert(
            `[SNAPSHOT SIMULADO] Solicitação de captura para ${cameraName}. ` +
            'A integração com o servidor de vídeo ainda não está implementada.'
        );
    }

    /**
     * Simula o comando de retorno do drone à base.
     */
    function simularRetornoDrone() {
        alert(
            '[SIMULAÇÃO] Solicitação de retorno à base (RTH). ' +
            'Nenhum comando foi enviado ao drone.'
        );
    }

    /** Retorna a data local no padrão ISO (AAAA-MM-DD). */
    function getLocalDateISO(date = new Date()) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    /**
     * Exporta os registros demonstrativos visíveis em CSV ou JSON.
     * O formato ZIP é informado como indisponível nesta versão.
     */
    function executarExportacao(event) {
        event.preventDefault();

        const format = document.getElementById('exportType').value;
        const filter = document.getElementById('exportFilter').value;
        const startDate = document.getElementById('exportStartDate').value;
        const endDate = document.getElementById('exportEndDate').value;
        const logBox = document.getElementById('vlmLogBox');

        if (startDate && endDate && startDate > endDate) {
            alert('A data inicial não pode ser posterior à data final.');
            return;
        }

        if (format === 'ZIP') {
            alert(
                'A exportação ZIP ainda não está implementada. ' +
                'Nesta versão, é possível baixar os registros demonstrativos em CSV ou JSON.'
            );
            return;
        }

        const logItems = Array.from(logBox.querySelectorAll('.vlm-log-item'));

        const records = logItems
            .map(item => {
                const header = item.querySelector('.text-secondary');
                const description = item.querySelector('p');
                const details = item.querySelector('small.text-info');

                return {
                    data: item.dataset.eventDate || '',
                    severidade: item.dataset.severity || 'INFO',
                    cabecalho: header ? header.textContent.trim() : '',
                    descricao: description ? description.textContent.trim() : '',
                    detalhes: details ? details.textContent.trim() : ''
                };
            })
            .filter(record => {
                const insideDateRange = (!startDate || !record.data || record.data >= startDate) &&
                    (!endDate || !record.data || record.data <= endDate);
                if (!insideDateRange) return false;
                if (filter === 'ALL') return true;
                return record.severidade === 'ALERT';
            });

        let content;
        let mimeType;
        let extension;

        if (format === 'CSV') {
            const columns = ['data', 'severidade', 'cabecalho', 'descricao', 'detalhes'];

            const escapeCsv = value =>
                '"' + String(value).replace(/"/g, '""') + '"';

            content = [
                columns.join(';'),
                ...records.map(record =>
                    columns.map(column => escapeCsv(record[column])).join(';')
                )
            ].join('\r\n');

            // BOM para facilitar a abertura do CSV no Excel.
            content = '\uFEFF' + content;
            mimeType = 'text/csv;charset=utf-8;';
            extension = 'csv';
        } else {
            content = JSON.stringify({
                modo: 'demonstracao',
                filtro: filter,
                dataInicial: startDate,
                dataFinal: endDate,
                registros: records
            }, null, 2);

            mimeType = 'application/json;charset=utf-8;';
            extension = 'json';
        }

        const blob = new Blob([content], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = `eventos_vlm_demo.${extension}`;

        document.body.appendChild(link);
        link.click();
        link.remove();

        window.setTimeout(() => URL.revokeObjectURL(url), 1000);

        const modalEl = document.getElementById('exportModal');
        const modal = bootstrap.Modal.getInstance(modalEl);

        if (modal) {
            modal.hide();
        }

        alert(
            `Arquivo ${extension.toUpperCase()} demonstrativo gerado com ` +
            `${records.length} registro(s). Os dados são apenas os registros ` +
            'exibidos no navegador; não representam um histórico persistente do sistema.'
        );
    }
</script>
<?= $this->endSection() ?>
