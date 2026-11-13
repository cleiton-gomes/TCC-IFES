<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Automações e Operações - IoT-Edge Center
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .operations-page {
        --ops-bg: #0b1120;
        --ops-panel: #111b2e;
        --ops-panel-2: #172338;
        --ops-border: rgba(148, 163, 184, .17);
        --ops-text: #e5edf8;
        --ops-muted: #91a2ba;
        --ops-blue: #38bdf8;
        color: var(--ops-text);
        padding: 26px 22px 38px;
        max-width: 1640px;
        margin: 0 auto;
    }

    .operations-page * { box-sizing: border-box; }
    .operations-page .ops-eyebrow { color: var(--ops-blue); text-transform: uppercase; letter-spacing: .14em; font-size: .72rem; font-weight: 800; }
    .operations-page .ops-title { font-size: clamp(1.6rem, 3vw, 2.15rem); font-weight: 800; letter-spacing: -.035em; margin: 4px 0 6px; }
    .operations-page .ops-muted { color: var(--ops-muted); }
    .operations-page .ops-panel { background: var(--ops-panel); border: 1px solid var(--ops-border); border-radius: 15px; box-shadow: 0 10px 26px rgba(0,0,0,.12); }
    .operations-page .ops-panel-header { padding: 18px 19px 0; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .operations-page .ops-panel-body { padding: 18px 19px 19px; }
    .operations-page .ops-section-title { margin: 0; font-size: 1rem; font-weight: 750; }
    .operations-page .ops-subtitle { color: var(--ops-muted); font-size: .8rem; margin-top: 4px; }
    .operations-page .ops-metric { height: 100%; padding: 17px; }
    .operations-page .ops-metric-top { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
    .operations-page .ops-metric-label { color: var(--ops-muted); font-size:.82rem; }
    .operations-page .ops-metric-number { font-size:1.9rem; font-weight:850; letter-spacing:-.04em; line-height:1.2; margin:12px 0 6px; }
    .operations-page .ops-metric-foot { color:var(--ops-muted); font-size:.76rem; }
    .operations-page .ops-metric-icon { width:40px; height:40px; display:grid; place-items:center; border-radius:12px; background:rgba(56,189,248,.1); color:var(--metric-color,#38bdf8); font-size:1.12rem; }
    .operations-page .ops-demo-note { background:rgba(251,191,36,.08); border:1px solid rgba(251,191,36,.24); color:#dbe5f2; border-radius:10px; padding:10px 13px; font-size:.83rem; }
    .operations-page .ops-form-control, .operations-page .form-control, .operations-page .form-select { background:#0b1425; color:var(--ops-text); border-color:#30415a; }
    .operations-page .form-control::placeholder { color:#74859d; }
    .operations-page .form-control:focus, .operations-page .form-select:focus { background:#0b1425; color:var(--ops-text); border-color:var(--ops-blue); box-shadow:0 0 0 .2rem rgba(56,189,248,.12); }
    .operations-page .form-select option { background:#111827; color:#fff; }
    .operations-page .btn-outline-soft { color:#cbd7e7; border:1px solid #30415a; }
    .operations-page .btn-outline-soft:hover { color:#fff; background:#1a2940; border-color:#425875; }
    .operations-page .ops-tabs { display:flex; flex-wrap:wrap; gap:7px; padding:7px; background:#0b1425; border:1px solid var(--ops-border); border-radius:12px; }
    .operations-page .ops-tabs .nav-link { color:#a9bad0; border-radius:8px; padding:10px 14px; font-size:.86rem; font-weight:700; }
    .operations-page .ops-tabs .nav-link.active { color:#f8fafc; background:#164e73; box-shadow:inset 0 0 0 1px rgba(56,189,248,.22); }
    .operations-page .ops-table { --bs-table-bg:transparent; --bs-table-color:var(--ops-text); --bs-table-border-color:var(--ops-border); margin:0; }
    .operations-page .ops-table thead th { color:#8fa1ba; font-size:.7rem; text-transform:uppercase; letter-spacing:.065em; white-space:nowrap; padding:12px 13px; border-bottom:1px solid var(--ops-border); }
    .operations-page .ops-table tbody td { padding:13px; vertical-align:middle; font-size:.83rem; }
    .operations-page .ops-table tbody tr:hover { --bs-table-bg:rgba(148,163,184,.035); }
    .operations-page .ops-code { font-family:ui-monospace,SFMono-Regular,Menlo,monospace; font-size:.8rem; font-weight:800; color:#bae6fd; }
    .operations-page .ops-badge { display:inline-flex; align-items:center; gap:6px; border-radius:7px; padding:5px 8px; font-size:.72rem; line-height:1.2; font-weight:750; white-space:nowrap; }
    .operations-page .badge-green { color:#6ee7b7; background:rgba(52,211,153,.1); }
    .operations-page .badge-yellow { color:#fcd34d; background:rgba(251,191,36,.1); }
    .operations-page .badge-red { color:#fda4af; background:rgba(251,113,133,.1); }
    .operations-page .badge-blue { color:#7dd3fc; background:rgba(56,189,248,.1); }
    .operations-page .badge-gray { color:#cbd5e1; background:rgba(148,163,184,.12); }
    .operations-page .rule-card,
    .operations-page .scene-card { padding:16px; border:1px solid var(--ops-border); background:var(--ops-panel-2); border-radius:12px; height:100%; }
    .operations-page .rule-card.disabled,
    .operations-page .scene-card.disabled { opacity:.72; }
    .operations-page .scene-card { display:flex; flex-direction:column; gap:10px; }
    .operations-page .scene-target-pill { display:inline-flex; align-items:flex-start; gap:7px; border:1px solid rgba(56,189,248,.2); background:rgba(56,189,248,.07); border-radius:9px; padding:8px 10px; color:#dbeafe; font-size:.76rem; line-height:1.4; }
    .operations-page .scene-target-pill strong { color:#7dd3fc; white-space:nowrap; }
    .operations-page .scene-voice-preview { border:1px solid rgba(52,211,153,.2); background:rgba(52,211,153,.055); border-radius:9px; padding:10px 11px; color:#d1fae5; font-size:.79rem; line-height:1.5; overflow-wrap:anywhere; }
    .operations-page .scene-voice-preview .voice-title { color:#6ee7b7; text-transform:uppercase; letter-spacing:.06em; font-size:.68rem; font-weight:800; margin-bottom:5px; }
    .operations-page .scene-card .action-list { margin-top:2px; }
    .operations-page .form-check-input { background-color:#0b1425; border-color:#425875; }
    .operations-page .form-check-input:checked { background-color:#0284c7; border-color:#0284c7; }
    .operations-page .form-section-label { color:#7dd3fc; font-size:.78rem; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
    .operations-page .config-hint { color:var(--ops-muted); font-size:.75rem; line-height:1.45; }
    .operations-page .rule-card-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    .operations-page .rule-name { font-size:.96rem; font-weight:800; }
    .operations-page .rule-description { font-size:.79rem; color:var(--ops-muted); line-height:1.5; margin-top:6px; }
    .operations-page .condition-box { background:#0b1425; border:1px solid var(--ops-border); border-radius:8px; padding:10px 11px; margin:13px 0; font-family:ui-monospace,SFMono-Regular,Menlo,monospace; color:#bae6fd; font-size:.79rem; overflow-wrap:anywhere; }
    .operations-page .action-list { display:grid; gap:8px; }
    .operations-page .action-line { display:flex; align-items:flex-start; gap:8px; font-size:.79rem; color:#cbd5e1; }
    .operations-page .action-line i { color:#7dd3fc; margin-top:2px; }
    .operations-page .mini-stat { padding:12px; border-radius:10px; border:1px solid var(--ops-border); background:rgba(11,20,37,.7); }
    .operations-page .mini-stat-label { color:var(--ops-muted); font-size:.72rem; }
    .operations-page .mini-stat-value { font-size:1rem; font-weight:800; margin-top:5px; }
    .operations-page .ops-empty { color:var(--ops-muted); padding:28px 12px; text-align:center; }
    .operations-page .audio-message { display:flex; align-items:flex-start; gap:12px; padding:13px 0; border-bottom:1px solid var(--ops-border); }
    .operations-page .audio-message:last-child { border-bottom:0; padding-bottom:0; }
    .operations-page .audio-icon { flex:0 0 38px; width:38px; height:38px; display:grid; place-items:center; border-radius:11px; background:rgba(56,189,248,.1); color:var(--ops-blue); }
    .operations-page .audio-message-title { font-weight:750; font-size:.85rem; }
    .operations-page .audio-message-text { color:#cbd5e1; font-size:.8rem; line-height:1.5; margin-top:4px; overflow-wrap:anywhere; }
    .operations-page .audio-message-meta { color:var(--ops-muted); font-size:.72rem; margin-top:6px; }
    .operations-page .switch-label { font-size:.78rem; color:#cbd5e1; }
    .operations-page .ops-footer-note { color:#718198; font-size:.76rem; }
    .operations-page .modal-content { background:#111827; color:var(--ops-text); border:1px solid rgba(255,255,255,.14); border-radius:14px; }
    .operations-page .btn-close-white { filter:invert(1) grayscale(100%) brightness(200%); }

    /*
     * Os modais ficam fora de .operations-page para que o Bootstrap
     * controle corretamente a posição e a camada de sobreposição.
     * Por isso, os estilos deles precisam usar a classe própria .ops-modal.
     */
    .ops-modal {
        color-scheme: dark;
        color: #e5edf8;
    }
    .ops-modal .modal-dialog,
    .ops-modal .modal-content {
        color: #e5edf8 !important;
    }
    .ops-modal .modal-content {
        background: #111827 !important;
        border: 1px solid rgba(148,163,184,.28) !important;
        border-radius: 14px;
        box-shadow: 0 24px 80px rgba(0,0,0,.48);
    }
    .ops-modal .modal-header,
    .ops-modal .modal-body,
    .ops-modal .modal-footer {
        background: #111827;
        color: #e5edf8 !important;
        border-color: rgba(148,163,184,.22) !important;
    }
    .ops-modal .modal-title {
        color: #f8fafc !important;
        font-weight: 800;
    }
    .ops-modal .modal-body {
        padding: 20px;
    }
    .ops-modal .form-label,
    .ops-modal label {
        color: #cbd5e1 !important;
    }
    .ops-modal .config-hint,
    .ops-modal .ops-muted,
    .ops-modal .form-text,
    .ops-modal small {
        color: #9aabc1 !important;
    }
    .ops-modal .form-section-label {
        color: #7dd3fc !important;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .ops-modal .form-control,
    .ops-modal .form-select,
    .ops-modal textarea,
    .ops-modal input:not([type="checkbox"]):not([type="radio"]):not([type="file"]) {
        background-color: #0b1425 !important;
        color: #e5edf8 !important;
        border-color: #3a4b63 !important;
    }
    .ops-modal .form-control::placeholder,
    .ops-modal textarea::placeholder,
    .ops-modal input::placeholder {
        color: #8496ae !important;
        opacity: 1;
    }
    .ops-modal .form-control:focus,
    .ops-modal .form-select:focus,
    .ops-modal textarea:focus,
    .ops-modal input:focus {
        background-color: #0b1425 !important;
        color: #f8fafc !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 .2rem rgba(56,189,248,.16) !important;
    }
    .ops-modal .form-select option {
        background: #111827;
        color: #e5edf8;
    }
    .ops-modal .mini-stat {
        display: flex;
        min-height: 62px;
        color: #e5edf8 !important;
        background: #0b1425 !important;
        border: 1px solid rgba(148,163,184,.23) !important;
        border-radius: 10px;
        padding: 12px;
        cursor: pointer;
    }
    .ops-modal .mini-stat strong,
    .ops-modal .mini-stat span {
        color: #e5edf8;
    }
    .ops-modal .mini-stat small {
        color: #9aabc1 !important;
        line-height: 1.4;
    }
    .ops-modal .form-check-input {
        flex: 0 0 auto;
        background-color: #0b1425;
        border-color: #64748b;
        margin-right: 2px;
    }
    .ops-modal .form-check-input:checked {
        background-color: #0284c7;
        border-color: #0284c7;
    }
    .ops-modal .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: .9;
    }
    .ops-modal .btn-outline-soft {
        color: #d6e2f1;
        border-color: #52647c;
    }
    .ops-modal .btn-outline-soft:hover {
        color: #fff;
        background: #26364d;
    }
    .ops-modal .modal-footer .btn:not(.btn-info):not(.btn-primary) {
        color: #d6e2f1;
    }
    @media (max-width:767.98px) {
        .operations-page { padding:20px 13px 30px; }
        .operations-page .ops-panel-header { padding:16px 15px 0; }
        .operations-page .ops-panel-body { padding:15px; }
        .operations-page .ops-metric { padding:14px; }
        .operations-page .ops-metric-number { font-size:1.6rem; }
        .operations-page .ops-tabs { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .operations-page .ops-tabs .nav-link { text-align:center; padding:10px 8px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="operations-page">
    <header class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-end gap-3 mb-4">
        <div>
            <div class="ops-eyebrow">Orquestração operacional</div>
            <h1 class="ops-title">Automações e Operações</h1>
            <p class="ops-muted mb-0">Regras, execuções, comandos e mensagens vocais em um único painel.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-soft" id="refreshOperationsButton">
                <i class="bi bi-arrow-clockwise me-2"></i>Atualizar demonstração
            </button>
            <button type="button" class="btn btn-info fw-semibold text-dark" data-bs-toggle="modal" data-bs-target="#newAudioModal">
                <i class="bi bi-megaphone me-2"></i>Preparar mensagem de áudio
            </button>
        </div>
    </header>

    <div class="ops-demo-note mb-4">
        <i class="bi bi-info-circle-fill text-warning me-2"></i>
        <strong>Ambiente demonstrativo:</strong> as regras, execuções, comandos e mensagens abaixo são fictícios. As ações desta tela alteram apenas o estado local do navegador; não publicam MQTT, não enviam áudio e não confirmam atuação física.
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3 small ops-muted">
        <i class="bi bi-clock"></i><span>Atualização local do painel:</span>
        <strong class="text-light" id="lastOperationsUpdated">--</strong>
        <span class="ms-sm-2">Os dados são temporários e serão restaurados ao recarregar a página.</span>
    </div>

    <section class="row g-3 mb-4" aria-label="Indicadores de operação">
        <div class="col-6 col-md-4 col-xl"><div class="ops-panel ops-metric" style="--metric-color:#38bdf8"><div class="ops-metric-top"><span class="ops-metric-label">Cenas cadastradas</span><span class="ops-metric-icon"><i class="bi bi-layers"></i></span></div><div class="ops-metric-number" id="metricScenes">3</div><div class="ops-metric-foot">Sequências acionáveis manualmente</div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="ops-panel ops-metric" style="--metric-color:#a78bfa"><div class="ops-metric-top"><span class="ops-metric-label">Regras cadastradas</span><span class="ops-metric-icon"><i class="bi bi-diagram-3"></i></span></div><div class="ops-metric-number" id="metricRules">3</div><div class="ops-metric-foot"><span id="metricRulesEnabled">2</span> habilitadas na demonstração</div></div></div>
        <div class="col-6 col-md-4 col-xl"><div class="ops-panel ops-metric" style="--metric-color:#fbbf24"><div class="ops-metric-top"><span class="ops-metric-label">Execuções</span><span class="ops-metric-icon"><i class="bi bi-play-circle"></i></span></div><div class="ops-metric-number" id="metricRuns">3</div><div class="ops-metric-foot">Histórico ilustrativo de processamento</div></div></div>
        <div class="col-6 col-md-6 col-xl"><div class="ops-panel ops-metric" style="--metric-color:#34d399"><div class="ops-metric-top"><span class="ops-metric-label">Comandos</span><span class="ops-metric-icon"><i class="bi bi-terminal"></i></span></div><div class="ops-metric-number" id="metricCommands">4</div><div class="ops-metric-foot">Nenhum comando enviado ao hardware</div></div></div>
        <div class="col-6 col-md-6 col-xl"><div class="ops-panel ops-metric" style="--metric-color:#fb7185"><div class="ops-metric-top"><span class="ops-metric-label">Mensagens de áudio</span><span class="ops-metric-icon"><i class="bi bi-soundwave"></i></span></div><div class="ops-metric-number" id="metricAudio">3</div><div class="ops-metric-foot">Textos preparados para síntese simulada</div></div></div>
    </section>

    <ul class="nav ops-tabs mb-4" id="operationsTabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="scenes-tab" data-bs-toggle="tab" data-bs-target="#scenes-pane" type="button" role="tab" aria-controls="scenes-pane" aria-selected="true"><i class="bi bi-layers me-2"></i>Cenas</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules-pane" type="button" role="tab" aria-controls="rules-pane" aria-selected="false"><i class="bi bi-diagram-3 me-2"></i>Regras</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="runs-tab" data-bs-toggle="tab" data-bs-target="#runs-pane" type="button" role="tab" aria-controls="runs-pane" aria-selected="false"><i class="bi bi-play-circle me-2"></i>Execuções</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="commands-tab" data-bs-toggle="tab" data-bs-target="#commands-pane" type="button" role="tab" aria-controls="commands-pane" aria-selected="false"><i class="bi bi-terminal me-2"></i>Comandos</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio-pane" type="button" role="tab" aria-controls="audio-pane" aria-selected="false"><i class="bi bi-soundwave me-2"></i>Áudio / TTS</button></li>
    </ul>

    <div class="tab-content" id="operationsTabContent">
        <!-- ABA CENAS -->
        <section class="tab-pane fade show active" id="scenes-pane" role="tabpanel" aria-labelledby="scenes-tab" tabindex="0">
            <div class="ops-panel mb-4">
                <div class="ops-panel-header">
                    <div><h2 class="ops-section-title">Cenas e sequências manuais</h2><div class="ops-subtitle">Agrupe ações em um roteiro reutilizável que o operador pode iniciar sob demanda.</div></div>
                    <div class="d-flex flex-wrap gap-2"><button type="button" class="btn btn-sm btn-outline-soft" id="resetScenesButton"><i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar exemplos</button><button type="button" class="btn btn-sm btn-info text-dark fw-semibold" id="newSceneButton"><i class="bi bi-plus-lg me-1"></i>Nova cena</button></div>
                </div>
                <div class="ops-panel-body">
                    <div class="ops-demo-note mb-3"><strong>Como usar:</strong> crie uma sequência de ações para uma situação. O botão “Simular cena” representa a execução manual; uma regra também poderá ser vinculada a uma cena para iniciá-la quando sua condição for satisfeita.</div>
                    <div class="row g-3" id="scenesGrid"></div>
                </div>
            </div>
            <div class="ops-panel"><div class="ops-panel-header"><div><h2 class="ops-section-title">Cena, regra e execução</h2><div class="ops-subtitle">As três peças têm responsabilidades diferentes.</div></div></div><div class="ops-panel-body"><div class="row g-3"><div class="col-12 col-lg-4"><div class="mini-stat h-100"><div class="fw-bold text-info"><i class="bi bi-hand-index-thumb me-2"></i>Cena</div><p class="ops-muted small mt-2 mb-0">Um conjunto ordenado de ações que o operador pode iniciar manualmente, como registrar evento, preparar áudio e solicitar sinalização.</p></div></div><div class="col-12 col-lg-4"><div class="mini-stat h-100"><div class="fw-bold text-info"><i class="bi bi-diagram-3 me-2"></i>Regra</div><p class="ops-muted small mt-2 mb-0">Uma condição automática. Quando a condição acontece, a regra pode iniciar suas próprias ações ou disparar uma cena existente.</p></div></div><div class="col-12 col-lg-4"><div class="mini-stat h-100"><div class="fw-bold text-info"><i class="bi bi-list-check me-2"></i>Execução</div><p class="ops-muted small mt-2 mb-0">O registro de uma tentativa, com o resultado de cada etapa e destinatário. Solicitar uma ação não confirma que o hardware a executou.</p></div></div></div></div></div>
        </section>

        <!-- ABA REGRAS -->
        <section class="tab-pane fade" id="rules-pane" role="tabpanel" aria-labelledby="rules-tab" tabindex="0">
            <div class="ops-panel mb-4">
                <div class="ops-panel-header"><div><h2 class="ops-section-title">Regras de automação</h2><div class="ops-subtitle">Configure gatilho, condição, origem e resposta. A avaliação permanece simulada nesta versão.</div></div><div class="d-flex flex-wrap gap-2"><button type="button" class="btn btn-sm btn-outline-soft" id="resetRulesButton"><i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar exemplos</button><button type="button" class="btn btn-sm btn-info text-dark fw-semibold" id="newRuleButton"><i class="bi bi-plus-lg me-1"></i>Nova regra</button></div></div>
                <div class="ops-panel-body">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6"><label for="ruleSearch" class="visually-hidden">Buscar regra</label><input id="ruleSearch" class="form-control form-control-sm" type="search" placeholder="Buscar por código, nome ou condição..."></div>
                        <div class="col-6 col-md-3"><label for="ruleStatusFilter" class="visually-hidden">Estado da regra</label><select id="ruleStatusFilter" class="form-select form-select-sm"><option value="">Todos os estados</option><option value="enabled">Habilitada</option><option value="disabled">Desabilitada</option></select></div>
                        <div class="col-6 col-md-3"><label for="ruleSeverityFilter" class="visually-hidden">Severidade da regra</label><select id="ruleSeverityFilter" class="form-select form-select-sm"><option value="">Todas as severidades</option><option value="Crítica">Crítica</option><option value="Alta">Alta</option><option value="Média">Média</option></select></div>
                    </div>
                    <div class="row g-3" id="rulesGrid"></div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-xl-7"><div class="ops-panel h-100"><div class="ops-panel-header"><div><h2 class="ops-section-title">Fluxo de referência</h2><div class="ops-subtitle">Separação entre condição, execução, ação e destinatário.</div></div></div><div class="ops-panel-body"><div class="row g-2 text-center"><div class="col-6 col-md-3"><div class="mini-stat"><i class="bi bi-activity fs-4 text-info"></i><div class="mini-stat-label mt-2">1 · Gatilho</div><div class="small fw-bold mt-1">Leitura / evento</div></div></div><div class="col-6 col-md-3"><div class="mini-stat"><i class="bi bi-diagram-3 fs-4 text-primary"></i><div class="mini-stat-label mt-2">2 · Regra</div><div class="small fw-bold mt-1">Condição validada</div></div></div><div class="col-6 col-md-3"><div class="mini-stat"><i class="bi bi-list-check fs-4 text-warning"></i><div class="mini-stat-label mt-2">3 · Ações</div><div class="small fw-bold mt-1">Etapas rastreáveis</div></div></div><div class="col-6 col-md-3"><div class="mini-stat"><i class="bi bi-check2-circle fs-4 text-success"></i><div class="mini-stat-label mt-2">4 · Resultado</div><div class="small fw-bold mt-1">Por destinatário</div></div></div></div><p class="ops-muted small mt-3 mb-0">O envio de um comando não comprova execução física. Na integração real, confirmação, timeout e falha precisam ser registrados separadamente.</p></div></div></div>
                <div class="col-12 col-xl-5"><div class="ops-panel h-100"><div class="ops-panel-header"><div><h2 class="ops-section-title">Destinatários de referência</h2><div class="ops-subtitle">Códigos alinhados às telas Dispositivos e Locais.</div></div></div><div class="ops-panel-body"><div class="d-flex justify-content-between gap-3 py-2 border-bottom border-secondary border-opacity-10"><div><div class="ops-code">EMB-GAS-001</div><div class="ops-muted small">Nó MQ-4 · LOC-SALA-CTRL</div></div><span class="ops-badge badge-blue">MQTT / Wi-Fi</span></div><div class="d-flex justify-content-between gap-3 py-2 border-bottom border-secondary border-opacity-10"><div><div class="ops-code">AUD-001</div><div class="ops-muted small">Áudio / síntese vocal · LOC-SALA-CTRL</div></div><span class="ops-badge badge-blue">MQTT</span></div><div class="d-flex justify-content-between gap-3 py-2"><div><div class="ops-code">REL-001</div><div class="ops-muted small">Atuador genérico · LOC-ZONA-TECH</div></div><span class="ops-badge badge-yellow">Ação física não confirmada</span></div></div></div></div>
            </div>
        </section>

        <!-- ABA EXECUÇÕES -->
        <section class="tab-pane fade" id="runs-pane" role="tabpanel" aria-labelledby="runs-tab" tabindex="0">
            <div class="ops-panel mb-4"><div class="ops-panel-header"><div><h2 class="ops-section-title">Histórico de execuções</h2><div class="ops-subtitle">Cada execução tem um resultado global e detalhes por ação e destinatário.</div></div><span class="ops-badge badge-yellow">Somente demonstração</span></div><div class="ops-panel-body"><div class="table-responsive"><table class="table ops-table"><thead><tr><th>Execução</th><th>Regra / gatilho</th><th>Início</th><th>Resultado global</th><th>Resumo das etapas</th></tr></thead><tbody id="runsTableBody"></tbody></table></div></div></div>
            <div class="ops-panel"><div class="ops-panel-header"><div><h2 class="ops-section-title">Detalhamento da execução</h2><div class="ops-subtitle">Selecione uma execução para visualizar o resultado de cada ação simulada.</div></div></div><div class="ops-panel-body" id="runDetailPanel"><div class="ops-empty"><i class="bi bi-list-check fs-3 d-block mb-2"></i>Selecione uma execução na tabela acima.</div></div></div>
        </section>

        <!-- ABA COMANDOS -->
        <section class="tab-pane fade" id="commands-pane" role="tabpanel" aria-labelledby="commands-tab" tabindex="0">
            <div class="ops-panel mb-4"><div class="ops-panel-header"><div><h2 class="ops-section-title">Comandos registrados</h2><div class="ops-subtitle">Solicitações demonstrativas, sem publicação MQTT ou confirmação do dispositivo.</div></div><button type="button" class="btn btn-sm btn-outline-soft" id="newCommandButton"><i class="bi bi-plus-lg me-1"></i>Registrar simulação</button></div><div class="ops-panel-body"><div class="row g-2 mb-3"><div class="col-12 col-md-5"><label for="commandSearch" class="visually-hidden">Buscar comando</label><input id="commandSearch" class="form-control form-control-sm" type="search" placeholder="Buscar ID, dispositivo ou ação..."></div><div class="col-6 col-md-3"><label for="commandTypeFilter" class="visually-hidden">Tipo de comando</label><select id="commandTypeFilter" class="form-select form-select-sm"><option value="">Todos os tipos</option><option value="Atuação">Atuação</option><option value="Áudio">Áudio</option><option value="Diagnóstico">Diagnóstico</option></select></div><div class="col-6 col-md-4"><label for="commandStateFilter" class="visually-hidden">Estado de comando</label><select id="commandStateFilter" class="form-select form-select-sm"><option value="">Todos os estados</option><option value="Simulado">Simulado</option><option value="Aguardando confirmação">Aguardando confirmação</option><option value="Falha simulada">Falha simulada</option></select></div></div><div class="table-responsive"><table class="table ops-table"><thead><tr><th>Comando</th><th>Destinatário</th><th>Tipo / payload resumido</th><th>Estado</th><th>Registrado em</th><th>Ação local</th></tr></thead><tbody id="commandsTableBody"></tbody></table></div></div></div>
            <div class="ops-demo-note"><i class="bi bi-shield-exclamation me-2 text-warning"></i>Não existe nesta página cliente MQTT conectado ao broker. O botão de simulação apenas acrescenta um registro à lista temporária do navegador.</div>
        </section>

        <!-- ABA AUDIO -->
        <section class="tab-pane fade" id="audio-pane" role="tabpanel" aria-labelledby="audio-tab" tabindex="0">
            <div class="row g-3">
                <div class="col-12 col-xl-5"><div class="ops-panel h-100"><div class="ops-panel-header"><div><h2 class="ops-section-title">Preparar mensagem vocal</h2><div class="ops-subtitle">Texto e destinatário ficam no estado local desta demonstração.</div></div></div><div class="ops-panel-body"><form id="audioForm"><div class="mb-3"><label for="audioTitle" class="form-label small">Nome / finalidade</label><input id="audioTitle" class="form-control form-control-sm" maxlength="100" placeholder="Ex.: Aviso de segurança" required></div><div class="mb-3"><label for="audioTargetMode" class="form-label small">Endereçamento</label><select id="audioTargetMode" class="form-select form-select-sm"><option value="unicast">Dispositivo específico</option><option value="local">Local (simulação de grupo)</option></select></div><div class="mb-3" id="audioDeviceTargetWrap"><label for="audioDeviceTarget" class="form-label small">Dispositivo de destino</label><select id="audioDeviceTarget" class="form-select form-select-sm"><option value="AUD-001">AUD-001 — Áudio (LOC-SALA-CTRL)</option><option value="EMB-GAS-001">EMB-GAS-001 — Nó de gás (LOC-SALA-CTRL)</option></select></div><div class="mb-3 d-none" id="audioLocalTargetWrap"><label for="audioLocalTarget" class="form-label small">Local de destino</label><select id="audioLocalTarget" class="form-select form-select-sm"><option value="LOC-SALA-CTRL">Sala de controle</option><option value="LOC-CORREDOR-N">Corredor norte</option><option value="LOC-ZONA-TECH">Zona técnica</option><option value="LOC-EXT-01">Área externa</option></select><div class="form-text text-secondary">O endereçamento por local não resolve dispositivos reais nesta versão.</div></div><div class="mb-3"><label for="audioText" class="form-label small">Texto para síntese</label><textarea id="audioText" class="form-control form-control-sm" rows="4" maxlength="300" placeholder="Digite uma mensagem breve para síntese vocal..." required>Atenção: área restrita. Siga as orientações da equipe responsável.</textarea><div class="d-flex justify-content-between mt-1"><span class="form-text text-secondary">Máximo de 300 caracteres.</span><span class="form-text text-secondary" id="audioCharCount">72/300</span></div></div><div class="d-grid gap-2"><button type="submit" class="btn btn-info fw-semibold text-dark"><i class="bi bi-file-earmark-music me-2"></i>Preparar áudio demonstrativo</button><button type="button" class="btn btn-outline-soft btn-sm" id="clearAudioFormButton">Limpar formulário</button></div></form><div class="ops-demo-note mt-3"><i class="bi bi-info-circle me-2"></i>Preparar uma mensagem não gera arquivo de áudio e não significa que houve reprodução física.</div></div></div></div>
                <div class="col-12 col-xl-7"><div class="ops-panel h-100"><div class="ops-panel-header"><div><h2 class="ops-section-title">Mensagens preparadas</h2><div class="ops-subtitle">Lista de textos demonstrativos disponíveis para futuras integrações.</div></div><span class="ops-badge badge-blue" id="audioListCount">3 registros</span></div><div class="ops-panel-body"><div class="row g-2 mb-2"><div class="col-12 col-md-8"><label for="audioSearch" class="visually-hidden">Buscar mensagem</label><input id="audioSearch" class="form-control form-control-sm" type="search" placeholder="Buscar por título, texto ou destinatário..."></div><div class="col-12 col-md-4"><label for="audioStatusFilter" class="visually-hidden">Filtrar mensagens</label><select id="audioStatusFilter" class="form-select form-select-sm"><option value="">Todos os registros</option><option value="Preparada">Preparada</option><option value="Arquivada">Arquivada</option></select></div></div><div id="audioMessagesList"></div></div></div></div>
            </div>
        </section>
    </div>

    <footer class="d-flex flex-column flex-md-row justify-content-between gap-2 pt-4"><span class="ops-footer-note">IoT-Edge Center · Automações e operações</span><span class="ops-footer-note">Modo demonstrativo · Sem MQTT real · Sem persistência</span></footer>
</main>

<!-- Modal para criar cena demonstrativa -->
<div class="modal fade ops-modal" id="newSceneModal" tabindex="-1" aria-labelledby="newSceneModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-secondary border-opacity-25">
                <div>
                    <h5 class="modal-title fw-bold" id="newSceneModalTitle"><i class="bi bi-layers me-2 text-info"></i>Criar cena / sequência</h5>
                    <div class="config-hint mt-1">Escolha os dispositivos envolvidos, escreva o aviso vocal e marque as etapas extras da sequência.</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="newSceneForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-7">
                            <label for="sceneName" class="form-label small">Nome da cena</label>
                            <input id="sceneName" class="form-control" maxlength="100" placeholder="Ex.: Alerta geral de gás" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="sceneLocation" class="form-label small">Local de referência</label>
                            <select id="sceneLocation" class="form-select">
                                <option value="LOC-SALA-CTRL">LOC-SALA-CTRL — Sala de controle</option>
                                <option value="LOC-CORREDOR-N">LOC-CORREDOR-N — Corredor norte</option>
                                <option value="LOC-ZONA-TECH">LOC-ZONA-TECH — Zona técnica</option>
                                <option value="LOC-EXT-01">LOC-EXT-01 — Área externa</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="sceneDescription" class="form-label small">Finalidade da cena</label>
                            <textarea id="sceneDescription" class="form-control" rows="2" maxlength="240" placeholder="Explique quando o operador usaria esta sequência." required></textarea>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4 mb-2">
                        <div>
                            <div class="form-section-label">Dispositivos envolvidos na cena</div>
                            <div class="config-hint">Marque todos os dispositivos que a sequência deve incluir. Cada tipo tem uma ação apropriada.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-soft" id="selectAllSceneDevices"><i class="bi bi-check2-all me-1"></i>Marcar todos</button>
                            <button type="button" class="btn btn-sm btn-outline-soft" id="clearSceneDevices">Limpar seleção</button>
                        </div>
                    </div>

                    <div class="row g-2" id="sceneDeviceTargetList">
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneDeviceTarget" value="EMB-GAS-001">
                                <span><strong>EMB-GAS-001 — Nó de gás</strong><small class="d-block ops-muted">Solicitar leitura/diagnóstico; não é um acionamento físico.</small></span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneDeviceTarget" value="AUD-001">
                                <span><strong>AUD-001 — Áudio / TTS</strong><small class="d-block ops-muted">Associar o texto vocal informado abaixo à sequência.</small></span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneDeviceTarget" value="REL-001">
                                <span><strong>REL-001 — Atuador / relé</strong><small class="d-block ops-muted">Solicitar acionamento da saída de sinalização; simulação sem atuação física.</small></span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneDeviceTarget" value="CAM-001">
                                <span><strong>CAM-001 — Câmera</strong><small class="d-block ops-muted">Registrar solicitação de captura/consulta de imagem; não captura vídeo real.</small></span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneDeviceTarget" value="DRONE-001">
                                <span><strong>DRONE-001 — Drone de inspeção</strong><small class="d-block ops-muted">Incluir etapa de inspeção no roteiro; nenhum comando de voo será enviado.</small></span>
                            </label>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-12">
                            <label for="sceneVoiceText" class="form-label small">Texto que será falado (TTS)</label>
                            <textarea id="sceneVoiceText" class="form-control" rows="3" maxlength="300" placeholder="Ex.: Atenção: situação de alerta. Afaste-se da área e aguarde orientações."></textarea>
                            <div class="d-flex flex-wrap justify-content-between gap-2 mt-1">
                                <small class="form-text text-secondary">Se preencher este texto, marque também AUD-001. O texto será associado à cena, mas não será reproduzido nesta demonstração.</small>
                                <small class="form-text text-secondary" id="sceneVoiceCharCount">0/300</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-label mt-4 mb-2">Etapas adicionais da sequência</div>
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneAction" value="event" checked>
                                <span><strong>Registrar ocorrência</strong><small class="d-block ops-muted">Criar registro demonstrativo no fluxo.</small></span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mini-stat d-flex gap-2 align-items-start h-100">
                                <input class="form-check-input mt-1" type="checkbox" name="sceneAction" value="diagnostic">
                                <span><strong>Notificar diagnóstico</strong><small class="d-block ops-muted">Adicionar uma etapa informativa.</small></span>
                            </label>
                        </div>
                    </div>

                    <div class="ops-demo-note mt-3"><i class="bi bi-info-circle me-2"></i>O botão “Marcar todos” apenas seleciona os dispositivos da lista. Câmeras, sensores, áudio, relé e drone têm ações distintas; nada será transmitido a eles nesta versão.</div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-dark fw-semibold"><i class="bi bi-check2-circle me-1"></i>Criar cena demonstrativa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para criar regra demonstrativa -->
<div class="modal fade ops-modal" id="newRuleModal" tabindex="-1" aria-labelledby="newRuleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content"><div class="modal-header border-secondary border-opacity-25"><div><h5 class="modal-title fw-bold" id="newRuleModalTitle"><i class="bi bi-diagram-3 me-2 text-info"></i>Criar regra de automação</h5><div class="config-hint mt-1">Configure a condição que dispara a regra e a resposta que será simulada.</div></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button></div>
        <form id="newRuleForm"><div class="modal-body">
            <div class="row g-3"><div class="col-12 col-md-7"><label for="newRuleName" class="form-label small">Nome da regra</label><input id="newRuleName" class="form-control" maxlength="100" placeholder="Ex.: Temperatura acima do limite" required></div><div class="col-6 col-md-3"><label for="newRuleSeverity" class="form-label small">Severidade</label><select id="newRuleSeverity" class="form-select"><option>Crítica</option><option selected>Alta</option><option>Média</option></select></div><div class="col-6 col-md-2"><label for="newRuleCategory" class="form-label small">Categoria</label><select id="newRuleCategory" class="form-select"><option>Segurança</option><option>Conectividade</option><option>Videomonitoramento</option><option>Ambiental</option><option>Operacional</option></select></div>
            <div class="col-12"><label for="newRuleDescription" class="form-label small">Descrição</label><textarea id="newRuleDescription" class="form-control" rows="2" maxlength="240" placeholder="Descreva o objetivo da regra." required></textarea></div>
            <div class="col-12"><div class="form-section-label">Quando a regra deve disparar?</div></div>
            <div class="col-12 col-md-4"><label for="newRuleMetric" class="form-label small">Variável / evento</label><select id="newRuleMetric" class="form-select"><option value="gas_level">Leitura bruta do sensor de gás</option><option value="tempo_sem_contato">Tempo sem contato</option><option value="evento_visual">Evento visual</option><option value="rssi_dbm">RSSI (qualidade do sinal)</option></select></div>
            <div class="col-5 col-md-2"><label for="newRuleOperator" class="form-label small">Operador</label><select id="newRuleOperator" class="form-select"><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="=">=</option><option value="<">&lt;</option><option value="<=">&lt;=</option></select></div>
            <div class="col-7 col-md-3"><label for="newRuleValue" class="form-label small">Valor / referência</label><input id="newRuleValue" class="form-control" value="2500" maxlength="80" required></div>
            <div class="col-12 col-md-3"><label for="newRuleSource" class="form-label small">Origem</label><select id="newRuleSource" class="form-select"><option value="EMB-GAS-001">EMB-GAS-001 — Sensor de gás</option><option value="CAM-001">CAM-001 — Câmera</option><option value="REL-001">REL-001 — Atuador</option><option value="AUD-001">AUD-001 — Áudio</option><option value="DRONE-001">DRONE-001 — Drone</option></select></div>
            <div class="col-12 col-md-6"><label for="newRuleLocation" class="form-label small">Local relacionado</label><select id="newRuleLocation" class="form-select"><option value="LOC-SALA-CTRL">LOC-SALA-CTRL — Sala de controle</option><option value="LOC-CORREDOR-N">LOC-CORREDOR-N — Corredor norte</option><option value="LOC-ZONA-TECH">LOC-ZONA-TECH — Zona técnica</option><option value="LOC-EXT-01">LOC-EXT-01 — Área externa</option></select></div>
            <div class="col-12 col-md-6"><label for="newRuleScene" class="form-label small">Cena vinculada (opcional)</label><select id="newRuleScene" class="form-select"><option value="">Usar somente as ações selecionadas abaixo</option></select><div class="config-hint mt-1">Se selecionar uma cena, a regra usará a sequência de ações dela.</div></div>
            <div class="col-12"><div class="form-section-label mt-1 mb-2">Ações da regra (quando não usar uma cena)</div></div>
            <div class="col-12 col-md-6"><label class="mini-stat d-flex gap-2 align-items-start"><input class="form-check-input mt-1" type="checkbox" name="ruleAction" value="event" checked><span><strong>Registrar ocorrência</strong><small class="d-block ops-muted">Incluir evento demonstrativo</small></span></label></div><div class="col-12 col-md-6"><label class="mini-stat d-flex gap-2 align-items-start"><input class="form-check-input mt-1" type="checkbox" name="ruleAction" value="audio"><span><strong>Preparar aviso vocal (AUD-001)</strong><small class="d-block ops-muted">Sem transmissão física</small></span></label></div><div class="col-12 col-md-6"><label class="mini-stat d-flex gap-2 align-items-start"><input class="form-check-input mt-1" type="checkbox" name="ruleAction" value="relay"><span><strong>Solicitar sinalização (REL-001)</strong><small class="d-block ops-muted">Sem confirmação física</small></span></label></div><div class="col-12 col-md-6"><label class="mini-stat d-flex gap-2 align-items-start"><input class="form-check-input mt-1" type="checkbox" name="ruleAction" value="diagnostic"><span><strong>Notificar diagnóstico</strong><small class="d-block ops-muted">Ação informativa local</small></span></label></div>
            </div><div class="ops-demo-note mt-3"><i class="bi bi-shield-exclamation me-2"></i>Esta regra será criada somente no navegador. Ela não observa telemetria real nem é executada automaticamente.</div>
        </div><div class="modal-footer border-secondary border-opacity-25"><button type="button" class="btn btn-outline-soft" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-info text-dark fw-semibold">Criar regra demonstrativa</button></div></form></div></div>
</div>

<!-- Modal de novo comando demonstrativo -->
<div class="modal fade ops-modal" id="newCommandModal" tabindex="-1" aria-labelledby="newCommandModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header border-secondary border-opacity-25"><h5 class="modal-title fw-bold" id="newCommandModalTitle"><i class="bi bi-terminal me-2 text-info"></i>Registrar comando demonstrativo</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button></div><form id="newCommandForm"><div class="modal-body"><div class="mb-3"><label for="newCommandTarget" class="form-label small">Destinatário</label><select id="newCommandTarget" class="form-select" required><option value="REL-001">REL-001 — Atuador (LOC-ZONA-TECH)</option><option value="AUD-001">AUD-001 — Áudio (LOC-SALA-CTRL)</option><option value="EMB-GAS-001">EMB-GAS-001 — Nó de gás (LOC-SALA-CTRL)</option></select></div><div class="mb-3"><label for="newCommandType" class="form-label small">Tipo</label><select id="newCommandType" class="form-select"><option value="Atuação">Atuação</option><option value="Áudio">Áudio</option><option value="Diagnóstico">Diagnóstico</option></select></div><div class="mb-3"><label for="newCommandDescription" class="form-label small">Ação / observação</label><input id="newCommandDescription" class="form-control" maxlength="160" placeholder="Ex.: Solicitar ativação do indicador" required></div></div><div class="modal-footer border-secondary border-opacity-25"><button type="button" class="btn btn-outline-soft" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-info text-dark fw-semibold">Registrar simulação</button></div></form></div></div>
</div>

<!-- Modal compacto para preparar áudio desde o cabeçalho -->
<div class="modal fade ops-modal" id="newAudioModal" tabindex="-1" aria-labelledby="newAudioModalTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header border-secondary border-opacity-25"><h5 class="modal-title fw-bold" id="newAudioModalTitle"><i class="bi bi-megaphone me-2 text-info"></i>Preparar mensagem de áudio</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button></div><div class="modal-body"><p class="ops-muted small mb-3">Use o formulário completo na aba Áudio / TTS para definir texto e destinatário. Este atalho leva diretamente àquela área.</p><button type="button" class="btn btn-info text-dark w-100 fw-semibold" id="goToAudioTabButton"><i class="bi bi-arrow-right-circle me-2"></i>Abrir Áudio / TTS</button></div></div></div></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
    'use strict';

    const initialScenes = [
        {
            id: 'SCN-GAS-001', name: 'Resposta a alerta de gás', location: 'LOC-SALA-CTRL', enabled: true,
            description: 'Sequência de demonstração para tratar uma leitura elevada do sensor de gás.',
            actions: ['Registrar ocorrência de gás'],
            targets: [
                {id:'EMB-GAS-001', type:'Diagnóstico', action:'Solicitar leitura/diagnóstico do nó'},
                {id:'AUD-001', type:'Áudio', action:'Preparar mensagem vocal para síntese'},
                {id:'REL-001', type:'Atuação', action:'Solicitar acionamento da sinalização'}
            ],
            voiceText: 'Atenção: leitura de gás acima do limite de referência. Afaste-se da área e aguarde orientações.'
        },
        {
            id: 'SCN-PERIM-002', name: 'Aviso de perímetro', location: 'LOC-EXT-01', enabled: true,
            description: 'Sequência demonstrativa para responder a um evento no perímetro externo.',
            actions: ['Registrar ocorrência de perímetro'],
            targets: [
                {id:'CAM-001', type:'Diagnóstico', action:'Solicitar captura/consulta de quadro da câmera'},
                {id:'AUD-001', type:'Áudio', action:'Preparar mensagem vocal para síntese'},
                {id:'REL-001', type:'Atuação', action:'Solicitar acionamento da sinalização'}
            ],
            voiceText: 'Atenção: perímetro monitorado. Afaste-se da área sinalizada e aguarde a equipe responsável.'
        },
        {
            id: 'SCN-INS-003', name: 'Registrar inspeção', location: 'LOC-CORREDOR-N', enabled: true,
            description: 'Roteiro demonstrativo para documentar uma inspeção e avisar o diagnóstico.',
            actions: ['Registrar ocorrência de inspeção', 'Notificar painel de diagnóstico'],
            targets: [
                {id:'CAM-001', type:'Diagnóstico', action:'Solicitar captura/consulta de quadro da câmera'},
                {id:'DRONE-001', type:'Diagnóstico', action:'Registrar etapa de inspeção; sem comando de voo'}
            ],
            voiceText: ''
        }
    ];

    const initialRules = [
        {
            id: 'RULE-GAS-001', name: 'Alerta de concentração de gás', category: 'Segurança', severity: 'Crítica', enabled: true,
            description: 'Gera uma ocorrência quando a leitura bruta simulada ultrapassa o limite de referência.',
            condition: 'gas_level > 2500', source: 'EMB-GAS-001', location: 'LOC-SALA-CTRL', sceneId: 'SCN-GAS-001',
            actions: ['Registrar evento EVT-GAS-0001', 'Preparar mensagem para AUD-001', 'Solicitar sinalização em REL-001']
        },
        {
            id: 'RULE-LINK-002', name: 'Perda de comunicação do nó', category: 'Conectividade', severity: 'Alta', enabled: true,
            description: 'Representa uma regra de diagnóstico para um dispositivo sem atualização no intervalo esperado.',
            condition: 'tempo_sem_contato > limite_configurado', source: 'EMB-GAS-001', location: 'LOC-SALA-CTRL',
            actions: ['Registrar evento de conectividade', 'Incluir aviso no painel de diagnóstico']
        },
        {
            id: 'RULE-EPI-003', name: 'Análise visual de EPI', category: 'Videomonitoramento', severity: 'Média', enabled: false,
            description: 'Regra ilustrativa para tratar um evento de análise visual enviado por uma integração futura.',
            condition: 'evento_visual = EPI_AUSENTE', source: 'CAM-001', location: 'LOC-CORREDOR-N',
            actions: ['Registrar evento visual', 'Preparar aviso vocal para operador']
        }
    ];

    let rules = initialRules.map(rule => ({...rule, actions: [...rule.actions]}));
    let scenes = cloneScenes();
    let runs = [
        {id:'RUN-20261022-003', ruleId:'RULE-GAS-001', trigger:'gas_level = 2780 (simulado)', started:'10:42:18', result:'Simulação concluída', detail:[{action:'Registrar evento EVT-GAS-0001', recipient:'Sistema web', result:'Registrado localmente'},{action:'Preparar mensagem vocal',recipient:'AUD-001',result:'Preparação simulada'},{action:'Solicitar sinalização',recipient:'REL-001',result:'Solicitação simulada; sem confirmação física'}]},
        {id:'RUN-20261022-002', ruleId:'RULE-LINK-002', trigger:'tempo sem contato acima do limite (simulado)', started:'10:16:04', result:'Simulação concluída', detail:[{action:'Registrar evento de conectividade',recipient:'Sistema web',result:'Registro demonstrativo'},{action:'Avisar diagnóstico',recipient:'Dashboard',result:'Aviso local'}]},
        {id:'RUN-20261022-001', ruleId:'RULE-EPI-003', trigger:'evento EPI_AUSENTE (exemplo)', started:'09:51:31', result:'Regra desabilitada no momento ilustrativo', detail:[{action:'Avaliar regra',recipient:'RULE-EPI-003',result:'Não executada porque a regra está desabilitada'}]}
    ];
    let commands = [
        {id:'CMD-DEMO-004', target:'REL-001', type:'Atuação', description:'Solicitar ativação da sinalização por condição de gás', state:'Simulado', time:'10:42:18'},
        {id:'CMD-DEMO-003', target:'AUD-001', type:'Áudio', description:'Preparar aviso de segurança para síntese', state:'Simulado', time:'10:42:18'},
        {id:'CMD-DEMO-002', target:'EMB-GAS-001', type:'Diagnóstico', description:'Consultar resumo lógico do nó (exemplo)', state:'Aguardando confirmação', time:'10:16:04'},
        {id:'CMD-DEMO-001', target:'REL-001', type:'Atuação', description:'Solicitação ilustrativa sem ACK recebido', state:'Falha simulada', time:'09:40:12'}
    ];
    let audioMessages = [
        {id:'AUDMSG-003', title:'Aviso de segurança', targetMode:'unicast', target:'AUD-001', text:'Atenção: área restrita. Siga as orientações da equipe responsável.', status:'Preparada', time:'10:42:18'},
        {id:'AUDMSG-002', title:'Aviso de perímetro', targetMode:'local', target:'LOC-EXT-01', text:'Atenção: área monitorada. Afaste-se do perímetro sinalizado.', status:'Preparada', time:'10:05:00'},
        {id:'AUDMSG-001', title:'Mensagem arquivada de teste', targetMode:'unicast', target:'AUD-001', text:'Mensagem de teste do sistema de síntese vocal.', status:'Arquivada', time:'09:30:00'}
    ];

    const ruleStateDefaults = initialRules.map(rule => ({id: rule.id, enabled: rule.enabled}));
    const severityBadge = {'Crítica':'badge-red','Alta':'badge-yellow','Média':'badge-blue'};
    const commandStateBadge = {'Simulado':'badge-blue','Aguardando confirmação':'badge-yellow','Falha simulada':'badge-red'};

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
    }

    function cloneScenes() {
        return initialScenes.map(scene => ({
            ...scene,
            actions: [...(scene.actions || [])],
            targets: (scene.targets || []).map(target => ({...target}))
        }));
    }
    function localTime() { return new Date().toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit',second:'2-digit'}); }
    function idSuffix() { return `${Date.now().toString().slice(-6)}-${Math.floor(Math.random()*90+10)}`; }
    function getRule(ruleId) { return rules.find(rule => rule.id === ruleId) || scenes.find(scene => scene.id === ruleId); }

    function sceneActionsFromForm(formName) {
        const labels = {
            event: 'Registrar ocorrência',
            audio: 'Preparar mensagem vocal para AUD-001',
            relay: 'Solicitar sinalização em REL-001',
            diagnostic: 'Notificar painel de diagnóstico'
        };
        const selected = Array.from(document.querySelectorAll(`input[name="${formName}"]:checked`)).map(input => input.value);
        return selected.map(key => labels[key]).filter(Boolean);
    }

    function selectedSceneTargetsFromForm() {
        const catalog = {
            'EMB-GAS-001': {id:'EMB-GAS-001', type:'Diagnóstico', action:'Solicitar leitura/diagnóstico do nó'},
            'AUD-001': {id:'AUD-001', type:'Áudio', action:'Preparar mensagem vocal para síntese'},
            'REL-001': {id:'REL-001', type:'Atuação', action:'Solicitar acionamento da sinalização'},
            'CAM-001': {id:'CAM-001', type:'Diagnóstico', action:'Solicitar captura/consulta de quadro da câmera'},
            'DRONE-001': {id:'DRONE-001', type:'Diagnóstico', action:'Registrar etapa de inspeção; sem comando de voo'}
        };
        return Array.from(document.querySelectorAll('input[name="sceneDeviceTarget"]:checked'))
            .map(input => catalog[input.value])
            .filter(Boolean)
            .map(target => ({...target}));
    }

    function renderScenes() {
        const grid = document.getElementById('scenesGrid');
        if (!grid) return;
        if (!scenes.length) {
            grid.innerHTML = '<div class="col-12"><div class="ops-empty"><i class="bi bi-layers fs-3 d-block mb-2"></i>Nenhuma cena cadastrada. Use “Nova cena” para criar a primeira sequência.</div></div>';
            return;
        }
        grid.innerHTML = scenes.map(scene => {
            const targets = Array.isArray(scene.targets) ? scene.targets : [];
            const targetMarkup = targets.length
                ? `<div><div class="form-section-label mb-2">Dispositivos selecionados</div><div class="d-flex flex-wrap gap-2">${targets.map(target => `<span class="scene-target-pill"><strong>${escapeHTML(target.id)}</strong><span>${escapeHTML(target.action)}</span></span>`).join('')}</div></div>`
                : '<div class="ops-muted small">Nenhum dispositivo selecionado; a cena executa somente as etapas gerais.</div>';
            const voiceMarkup = scene.voiceText
                ? `<div class="scene-voice-preview"><div class="voice-title"><i class="bi bi-soundwave me-1"></i>Texto vocal associado à cena</div>“${escapeHTML(scene.voiceText)}”</div>`
                : '';
            return `
            <div class="col-12 col-lg-6 col-xxl-4"><article class="scene-card ${scene.enabled ? '' : 'disabled'}">
                <div class="d-flex justify-content-between align-items-start gap-2"><div><div class="ops-code">${escapeHTML(scene.id)}</div><div class="rule-name mt-1">${escapeHTML(scene.name)}</div></div><span class="ops-badge ${scene.enabled ? 'badge-green' : 'badge-gray'}">${scene.enabled ? 'Disponível' : 'Desabilitada'}</span></div>
                <div class="d-flex flex-wrap gap-2"><span class="ops-badge badge-blue">Sequência manual</span><span class="ops-badge badge-gray">${escapeHTML(scene.location)}</span></div>
                <p class="rule-description mb-0">${escapeHTML(scene.description)}</p>
                ${targetMarkup}
                ${voiceMarkup}
                <div><div class="form-section-label mb-2">Etapas gerais</div><div class="action-list">${(scene.actions || []).map((action, index) => `<div class="action-line"><i class="bi bi-arrow-return-right"></i><span>${index + 1}. ${escapeHTML(action)}</span></div>`).join('') || '<div class="ops-muted small">Sem etapas gerais.</div>'}</div></div>
                <div class="d-flex gap-2 mt-auto pt-2"><button type="button" class="btn btn-sm btn-info text-dark fw-semibold flex-grow-1" data-run-scene="${escapeHTML(scene.id)}" ${scene.enabled ? '' : 'disabled'}><i class="bi bi-play-fill me-1"></i>Simular cena</button><button type="button" class="btn btn-sm btn-outline-soft" data-toggle-scene="${escapeHTML(scene.id)}" aria-label="${scene.enabled ? 'Desabilitar' : 'Habilitar'} cena">${scene.enabled ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-play-fill"></i>'}</button></div>
            </article></div>`;
        }).join('');
        grid.querySelectorAll('[data-run-scene]').forEach(button => button.addEventListener('click', () => simulateScene(button.dataset.runScene)));
        grid.querySelectorAll('[data-toggle-scene]').forEach(button => button.addEventListener('click', () => toggleScene(button.dataset.toggleScene)));
        populateRuleSceneOptions();
    }

    function populateRuleSceneOptions() {
        const select = document.getElementById('newRuleScene');
        if (!select) return;
        const current = select.value;
        select.innerHTML = '<option value="">Usar somente as ações selecionadas abaixo</option>' + scenes.map(scene => `<option value="${escapeHTML(scene.id)}">${escapeHTML(scene.id)} — ${escapeHTML(scene.name)}</option>`).join('');
        if (scenes.some(scene => scene.id === current)) select.value = current;
    }

    function toggleScene(sceneId) {
        const scene = scenes.find(item => item.id === sceneId);
        if (!scene) return;
        scene.enabled = !scene.enabled;
        renderScenes(); updateMetrics();
    }

    function updateSceneVoiceCharCount() {
        const field = document.getElementById('sceneVoiceText');
        const counter = document.getElementById('sceneVoiceCharCount');
        if (field && counter) counter.textContent = `${field.value.length}/300`;
    }

    function createScene(event) {
        event.preventDefault();
        const name = document.getElementById('sceneName').value.trim();
        const description = document.getElementById('sceneDescription').value.trim();
        const location = document.getElementById('sceneLocation').value;
        const actions = sceneActionsFromForm('sceneAction');
        const targets = selectedSceneTargetsFromForm();
        const voiceText = document.getElementById('sceneVoiceText').value.trim();
        const hasAudioTarget = targets.some(target => target.id === 'AUD-001');

        if (!name || !description) { alert('Informe o nome e a finalidade da cena.'); return; }
        if (!actions.length && !targets.length) { alert('Selecione pelo menos uma etapa geral ou um dispositivo para a cena.'); return; }
        if (hasAudioTarget && !voiceText) { alert('Você selecionou AUD-001. Escreva o texto que será associado à mensagem vocal.'); document.getElementById('sceneVoiceText').focus(); return; }
        if (voiceText && !hasAudioTarget) { alert('Você escreveu um texto vocal. Marque AUD-001 na lista de dispositivos para associar o texto à cena.'); return; }

        const scene = {
            id: `SCN-DEMO-${idSuffix()}`,
            name, description, location, actions, targets,
            voiceText, enabled: true
        };
        scenes.unshift(scene);
        renderScenes(); updateMetrics();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('newSceneModal')).hide();
        document.getElementById('newSceneForm').reset();
        updateSceneVoiceCharCount();
        document.getElementById('scenes-tab').click();
        alert('Cena criada no navegador. Dispositivos marcados e texto vocal foram associados à sequência, mas nenhum comando real foi enviado. Os dados serão perdidos ao recarregar a página.');
    }

    function registerSceneOutputs(scene) {
        const targets = Array.isArray(scene.targets) ? scene.targets : [];
        targets.forEach(target => {
            commands.unshift({
                id: `CMD-DEMO-${idSuffix()}`,
                target: target.id,
                type: target.type,
                description: `${target.action} — cena ${scene.name}`,
                state: 'Simulado',
                time: localTime()
            });
        });

        const audioTarget = targets.find(target => target.id === 'AUD-001');
        if (audioTarget && scene.voiceText) {
            audioMessages.unshift({
                id: `AUDMSG-${idSuffix()}`,
                title: `Cena: ${scene.name}`,
                targetMode: 'unicast',
                target: 'AUD-001',
                text: scene.voiceText,
                status: 'Preparada',
                time: localTime()
            });
        }
    }

    function getSceneExecutionDetails(scene) {
        const detail = (scene.actions || []).map((action, index) => {
            let recipient = 'Sistema web';
            if (action.includes('AUD-001') || action.toLowerCase().includes('vocal')) recipient = 'AUD-001';
            else if (action.includes('REL-001') || action.toLowerCase().includes('sinalização')) recipient = 'REL-001';
            else if (action.toLowerCase().includes('diagnóstico')) recipient = 'Painel de diagnóstico';
            return {action, recipient, result: index === 0 ? 'Etapa simulada no navegador' : 'Etapa ilustrativa; nenhum dispositivo foi acionado'};
        });

        (scene.targets || []).forEach(target => {
            detail.push({
                action: target.action,
                recipient: target.id,
                result: target.id === 'AUD-001' && scene.voiceText
                    ? 'Texto associado à mensagem demonstrativa; áudio não reproduzido'
                    : 'Solicitação simulada; sem comando MQTT e sem confirmação física'
            });
            if (target.id === 'AUD-001' && scene.voiceText) {
                detail.push({
                    action: `Texto vocal previsto: “${scene.voiceText}”`,
                    recipient: 'AUD-001',
                    result: 'Texto armazenado apenas nesta demonstração; não foi gerado áudio'
                });
            }
        });
        return detail;
    }

    function simulateScene(sceneId) {
        const scene = scenes.find(item => item.id === sceneId);
        if (!scene || !scene.enabled) return;
        const runId = `RUN-DEMO-${idSuffix()}`;
        const detail = getSceneExecutionDetails(scene);
        runs.unshift({id:runId,ruleId:scene.id,trigger:`Acionamento manual da cena ${scene.id} (simulado)`,started:localTime(),result:'Simulação concluída',detail});
        registerSceneOutputs(scene);
        renderRuns(); renderCommands(); renderAudioMessages(); updateMetrics(); renderRunDetails(runId);
        bootstrap.Tab.getOrCreateInstance(document.getElementById('runs-tab')).show();
    }

    function registerRule(event) {
        event.preventDefault();
        const name = document.getElementById('newRuleName').value.trim();
        const description = document.getElementById('newRuleDescription').value.trim();
        const severity = document.getElementById('newRuleSeverity').value;
        const category = document.getElementById('newRuleCategory').value;
        const metric = document.getElementById('newRuleMetric').value;
        const operator = document.getElementById('newRuleOperator').value;
        const value = document.getElementById('newRuleValue').value.trim();
        const source = document.getElementById('newRuleSource').value;
        const location = document.getElementById('newRuleLocation').value;
        const sceneId = document.getElementById('newRuleScene').value;
        if (!name || !description || !value) { alert('Preencha nome, descrição e valor da condição.'); return; }
        if (metric !== 'evento_visual' && !Number.isFinite(Number(value))) { alert('Para esta variável, informe um valor numérico.'); return; }
        const scene = scenes.find(item => item.id === sceneId);
        let actions = scene ? [...scene.actions] : sceneActionsFromForm('ruleAction');
        const sceneHasTargets = Boolean(scene && Array.isArray(scene.targets) && scene.targets.length > 0);
        if (!actions.length && !sceneHasTargets) { alert('Selecione pelo menos uma ação ou vincule uma cena com etapas/dispositivos selecionados.'); return; }
        if (metric === 'evento_visual' && !['=', '!='].includes(operator)) { alert('Para evento visual, utilize o operador de igualdade.'); return; }
        const condition = `${metric} ${operator} ${value}`;
        rules.unshift({id:`RULE-DEMO-${idSuffix()}`,name,category,severity,enabled:true,description,condition,source,location,sceneId:scene ? scene.id : '',actions});
        renderRules(); updateMetrics();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('newRuleModal')).hide();
        document.getElementById('newRuleForm').reset();
        document.getElementById('newRuleValue').value = '2500';
        document.getElementById('newRuleScene').value = '';
        document.getElementById('rules-tab').click();
        alert('Regra demonstrativa criada no navegador. Ela ainda não monitora dados reais, não será persistida e não dispara automaticamente.');
    }

    function renderRules() {
        const query = document.getElementById('ruleSearch').value.trim().toLowerCase();
        const state = document.getElementById('ruleStatusFilter').value;
        const severity = document.getElementById('ruleSeverityFilter').value;
        const filtered = rules.filter(rule => {
            const text = [rule.id, rule.name, rule.category, rule.description, rule.condition, rule.source, rule.location].join(' ').toLowerCase();
            return text.includes(query) && (!state || (state === 'enabled' ? rule.enabled : !rule.enabled)) && (!severity || rule.severity === severity);
        });
        const grid = document.getElementById('rulesGrid');
        if (!filtered.length) {
            grid.innerHTML = '<div class="col-12"><div class="ops-empty"><i class="bi bi-search fs-3 d-block mb-2"></i>Nenhuma regra corresponde aos filtros.</div></div>';
            return;
        }
        grid.innerHTML = filtered.map(rule => `
            <div class="col-12 col-lg-6 col-xxl-4"><article class="rule-card ${rule.enabled ? '' : 'disabled'}">
                <div class="rule-card-top"><div><div class="ops-code">${escapeHTML(rule.id)}</div><div class="rule-name mt-1">${escapeHTML(rule.name)}</div></div><span class="ops-badge ${rule.enabled ? 'badge-green' : 'badge-gray'}">${rule.enabled ? 'Habilitada' : 'Desabilitada'}</span></div>
                <div class="d-flex flex-wrap gap-2 mt-2"><span class="ops-badge ${severityBadge[rule.severity] || 'badge-gray'}">${escapeHTML(rule.severity)}</span><span class="ops-badge badge-gray">${escapeHTML(rule.category)}</span></div>
                <p class="rule-description">${escapeHTML(rule.description)}</p>
                <div class="condition-box"><span class="ops-muted">SE</span> ${escapeHTML(rule.condition)}</div>
                <div class="d-flex justify-content-between gap-2 mb-2"><span class="ops-muted small">Origem</span><span class="ops-code">${escapeHTML(rule.source)}</span></div>
                <div class="d-flex justify-content-between gap-2 mb-2"><span class="ops-muted small">Local</span><span class="small text-end">${escapeHTML(rule.location)}</span></div>
                ${rule.sceneId ? `<div class="d-flex justify-content-between gap-2 mb-2"><span class="ops-muted small">Cena vinculada</span><span class="ops-code">${escapeHTML(rule.sceneId)}</span></div>` : ''}
                <div class="action-list mb-3">${rule.actions.map((action, index) => `<div class="action-line"><i class="bi bi-check2-square"></i><span>${index + 1}. ${escapeHTML(action)}</span></div>`).join('')}</div>
                <div class="d-flex gap-2 mt-auto"><button type="button" class="btn btn-sm btn-info text-dark fw-semibold flex-grow-1" data-run-rule="${escapeHTML(rule.id)}" ${rule.enabled ? '' : 'disabled'}><i class="bi bi-play-fill me-1"></i>Simular execução</button><button type="button" class="btn btn-sm btn-outline-soft" data-toggle-rule="${escapeHTML(rule.id)}" aria-label="${rule.enabled ? 'Desabilitar' : 'Habilitar'} regra">${rule.enabled ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-play-fill"></i>'}</button></div>
            </article></div>`).join('');
        grid.querySelectorAll('[data-run-rule]').forEach(button => button.addEventListener('click', () => simulateRule(button.dataset.runRule)));
        grid.querySelectorAll('[data-toggle-rule]').forEach(button => button.addEventListener('click', () => toggleRule(button.dataset.toggleRule)));
    }

    function renderRuns() {
        const tbody = document.getElementById('runsTableBody');
        if (!runs.length) { tbody.innerHTML = '<tr><td colspan="5"><div class="ops-empty">Nenhuma execução demonstrativa.</div></td></tr>'; renderRunDetails(null); return; }
        tbody.innerHTML = runs.map(run => {
            const rule = getRule(run.ruleId);
            return `<tr><td><button class="btn btn-link p-0 text-decoration-none ops-code" data-view-run="${escapeHTML(run.id)}">${escapeHTML(run.id)}</button></td><td><div class="fw-semibold">${escapeHTML(rule ? rule.name : run.ruleId)}</div><div class="ops-muted small">${escapeHTML(run.trigger)}</div></td><td>${escapeHTML(run.started)}</td><td><span class="ops-badge badge-blue">${escapeHTML(run.result)}</span></td><td>${run.detail.length} etapa(s) ilustrativa(s)</td></tr>`;
        }).join('');
        tbody.querySelectorAll('[data-view-run]').forEach(button => button.addEventListener('click', () => renderRunDetails(button.dataset.viewRun)));
    }

    function renderRunDetails(runId) {
        const panel = document.getElementById('runDetailPanel');
        const run = runs.find(item => item.id === runId);
        if (!run) { panel.innerHTML = '<div class="ops-empty"><i class="bi bi-list-check fs-3 d-block mb-2"></i>Selecione uma execução na tabela acima para inspecionar as etapas.</div>'; return; }
        panel.innerHTML = `<div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3"><div><div class="ops-code">${escapeHTML(run.id)}</div><h3 class="h6 fw-bold mt-1 mb-1">${escapeHTML((getRule(run.ruleId) || {}).name || run.ruleId)}</h3><div class="ops-muted small">Gatilho demonstrativo: ${escapeHTML(run.trigger)}</div></div><span class="ops-badge badge-yellow">Simulação local</span></div><div class="table-responsive"><table class="table ops-table"><thead><tr><th>Etapa</th><th>Destinatário / sistema</th><th>Resultado ilustrativo</th></tr></thead><tbody>${run.detail.map((item,index) => `<tr><td><span class="ops-muted">${index+1}.</span> ${escapeHTML(item.action)}</td><td>${escapeHTML(item.recipient)}</td><td>${escapeHTML(item.result)}</td></tr>`).join('')}</tbody></table></div><p class="ops-muted small mt-3 mb-0">Este detalhamento é temporário no navegador e não representa um ACK real de hardware.</p>`;
    }

    function renderCommands() {
        const query = document.getElementById('commandSearch').value.trim().toLowerCase();
        const type = document.getElementById('commandTypeFilter').value;
        const state = document.getElementById('commandStateFilter').value;
        const filtered = commands.filter(command => [command.id,command.target,command.type,command.description,command.state].join(' ').toLowerCase().includes(query) && (!type || command.type === type) && (!state || command.state === state));
        const tbody = document.getElementById('commandsTableBody');
        if (!filtered.length) { tbody.innerHTML = '<tr><td colspan="6"><div class="ops-empty">Nenhum comando corresponde aos filtros.</div></td></tr>'; return; }
        tbody.innerHTML = filtered.map(command => `<tr><td><span class="ops-code">${escapeHTML(command.id)}</span></td><td><span class="fw-semibold">${escapeHTML(command.target)}</span></td><td><div>${escapeHTML(command.type)}</div><div class="ops-muted small">${escapeHTML(command.description)}</div></td><td><span class="ops-badge ${commandStateBadge[command.state] || 'badge-gray'}">${escapeHTML(command.state)}</span></td><td>${escapeHTML(command.time)}</td><td><button type="button" class="btn btn-sm btn-outline-soft" data-inspect-command="${escapeHTML(command.id)}"><i class="bi bi-info-circle me-1"></i>Detalhes</button></td></tr>`).join('');
        tbody.querySelectorAll('[data-inspect-command]').forEach(button => button.addEventListener('click', () => {
            const command = commands.find(item => item.id === button.dataset.inspectCommand);
            if (!command) return;
            alert(`COMANDO DEMONSTRATIVO\n\nID: ${command.id}\nDestino: ${command.target}\nTipo: ${command.type}\nDescrição: ${command.description}\nEstado: ${command.state}\n\nNenhuma mensagem MQTT foi enviada.`);
        }));
    }

    function targetLabel(message) {
        if (message.targetMode === 'local') return `${message.target} · local/grupo demonstrativo`;
        const names = {'AUD-001':'Áudio / síntese vocal','EMB-GAS-001':'Nó de monitoramento de gás','REL-001':'Atuador genérico'};
        return `${message.target} · ${names[message.target] || 'dispositivo'}`;
    }

    function renderAudioMessages() {
        const query = document.getElementById('audioSearch').value.trim().toLowerCase();
        const status = document.getElementById('audioStatusFilter').value;
        const filtered = audioMessages.filter(message => [message.id,message.title,message.target,message.text].join(' ').toLowerCase().includes(query) && (!status || message.status === status));
        document.getElementById('audioListCount').textContent = `${filtered.length} registro(s)`;
        const list = document.getElementById('audioMessagesList');
        if (!filtered.length) { list.innerHTML = '<div class="ops-empty"><i class="bi bi-search fs-3 d-block mb-2"></i>Nenhuma mensagem corresponde aos filtros.</div>'; return; }
        list.innerHTML = filtered.map(message => `<article class="audio-message"><div class="audio-icon"><i class="bi bi-soundwave fs-5"></i></div><div class="flex-grow-1 min-w-0"><div class="d-flex flex-wrap align-items-center justify-content-between gap-2"><div class="audio-message-title">${escapeHTML(message.title)}</div><span class="ops-badge ${message.status === 'Preparada' ? 'badge-green' : 'badge-gray'}">${escapeHTML(message.status)}</span></div><div class="ops-code mt-1">${escapeHTML(message.id)}</div><div class="audio-message-text">${escapeHTML(message.text)}</div><div class="audio-message-meta">Destino: ${escapeHTML(targetLabel(message))} · ${escapeHTML(message.time)}</div><div class="d-flex flex-wrap gap-2 mt-2"><button type="button" class="btn btn-sm btn-outline-soft" data-copy-audio="${escapeHTML(message.id)}"><i class="bi bi-copy me-1"></i>Copiar texto</button><button type="button" class="btn btn-sm btn-outline-soft" data-archive-audio="${escapeHTML(message.id)}" ${message.status === 'Arquivada' ? 'disabled' : ''}><i class="bi bi-archive me-1"></i>Arquivar</button></div></div></article>`).join('');
        list.querySelectorAll('[data-copy-audio]').forEach(button => button.addEventListener('click', () => {
            const message = audioMessages.find(item => item.id === button.dataset.copyAudio);
            if (!message) return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(message.text).then(() => alert('Texto copiado. Nenhum áudio foi gerado ou transmitido.')).catch(() => window.prompt('Copie o texto da mensagem:', message.text));
            } else window.prompt('Copie o texto da mensagem:', message.text);
        }));
        list.querySelectorAll('[data-archive-audio]').forEach(button => button.addEventListener('click', () => {
            const message = audioMessages.find(item => item.id === button.dataset.archiveAudio);
            if (!message) return;
            message.status = 'Arquivada'; renderAudioMessages(); updateMetrics();
        }));
    }

    function updateMetrics() {
        document.getElementById('metricScenes').textContent = scenes.length;
        document.getElementById('metricRules').textContent = rules.length;
        document.getElementById('metricRulesEnabled').textContent = rules.filter(rule => rule.enabled).length;
        document.getElementById('metricRuns').textContent = runs.length;
        document.getElementById('metricCommands').textContent = commands.length;
        document.getElementById('metricAudio').textContent = audioMessages.length;
    }

    function toggleRule(ruleId) {
        const rule = getRule(ruleId);
        if (!rule) return;
        rule.enabled = !rule.enabled;
        renderRules(); updateMetrics();
    }

    function simulateRule(ruleId) {
        const rule = getRule(ruleId);
        if (!rule || !rule.enabled) return;
        const runId = `RUN-DEMO-${idSuffix()}`;
        const linkedScene = rule.sceneId ? scenes.find(scene => scene.id === rule.sceneId) : null;
        const actionsToRun = linkedScene ? linkedScene.actions : rule.actions;
        const detail = linkedScene ? getSceneExecutionDetails(linkedScene) : actionsToRun.map((action,index) => {
            let recipient = 'Sistema web';
            if (action.includes('AUD-001') || action.toLowerCase().includes('vocal')) recipient = 'AUD-001';
            else if (action.includes('REL-001') || action.toLowerCase().includes('sinalização')) recipient = 'REL-001';
            return {action, recipient, result: index === 0 ? 'Etapa simulada no navegador' : 'Solicitação ilustrativa; sem confirmação do dispositivo'};
        });
        runs.unshift({id:runId, ruleId:rule.id, trigger:`${rule.condition} (gatilho manual de demonstração)`, started:localTime(), result:'Simulação concluída', detail});
        if (linkedScene) {
            registerSceneOutputs(linkedScene);
        } else {
            if (actionsToRun.some(action => action.includes('AUD-001') || action.toLowerCase().includes('vocal'))) {
                commands.unshift({id:`CMD-DEMO-${idSuffix()}`,target:'AUD-001',type:'Áudio',description:`Ação da regra ${rule.id} (somente simulação)`,state:'Simulado',time:localTime()});
            }
            if (actionsToRun.some(action => action.includes('REL-001') || action.toLowerCase().includes('sinalização'))) {
                commands.unshift({id:`CMD-DEMO-${idSuffix()}`,target:'REL-001',type:'Atuação',description:`Ação da regra ${rule.id} (somente simulação)`,state:'Simulado',time:localTime()});
            }
        }
        renderRuns(); renderCommands(); renderAudioMessages(); updateMetrics(); renderRunDetails(runId);
        const runsTab = document.getElementById('runs-tab');
        bootstrap.Tab.getOrCreateInstance(runsTab).show();
    }

    function newCommand() {
        const form = document.getElementById('newCommandForm');
        form.reset();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('newCommandModal')).show();
    }

    function registerCommand(event) {
        event.preventDefault();
        const target = document.getElementById('newCommandTarget').value;
        const type = document.getElementById('newCommandType').value;
        const description = document.getElementById('newCommandDescription').value.trim();
        if (!description) return;
        commands.unshift({id:`CMD-DEMO-${idSuffix()}`,target,type,description,state:'Simulado',time:localTime()});
        renderCommands(); updateMetrics();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('newCommandModal')).hide();
        document.getElementById('commands-tab').click();
        alert('Registro demonstrativo criado no navegador. Nenhum comando foi enviado ao broker MQTT ou ao hardware.');
    }

    function updateAudioTargetVisibility() {
        const isLocal = document.getElementById('audioTargetMode').value === 'local';
        document.getElementById('audioDeviceTargetWrap').classList.toggle('d-none', isLocal);
        document.getElementById('audioLocalTargetWrap').classList.toggle('d-none', !isLocal);
    }

    function updateAudioCharCount() {
        const text = document.getElementById('audioText').value;
        document.getElementById('audioCharCount').textContent = `${text.length}/300`;
    }

    function prepareAudio(event) {
        event.preventDefault();
        const title = document.getElementById('audioTitle').value.trim();
        const targetMode = document.getElementById('audioTargetMode').value;
        const target = targetMode === 'local' ? document.getElementById('audioLocalTarget').value : document.getElementById('audioDeviceTarget').value;
        const text = document.getElementById('audioText').value.trim();
        if (!title || !text) { alert('Informe um título e o texto da mensagem.'); return; }
        const message = {id:`AUDMSG-${idSuffix()}`,title,targetMode,target,text,status:'Preparada',time:localTime()};
        audioMessages.unshift(message);
        renderAudioMessages(); updateMetrics();
        document.getElementById('audioTitle').value = '';
        document.getElementById('audioText').value = '';
        updateAudioCharCount();
        alert('Mensagem registrada como preparada na demonstração. Não foi gerado arquivo de áudio nem feita transmissão.');
    }

    function resetScenes() {
        scenes = cloneScenes();
        renderScenes(); renderRules(); updateMetrics();
        alert('Cenas demonstrativas restauradas. As cenas criadas nesta sessão foram removidas.');
    }

    function resetRules() {
        rules = initialRules.map(rule => ({...rule, actions:[...rule.actions]}));
        renderRules(); updateMetrics();
    }

    function resetOperationsDemo() {
        scenes = cloneScenes();
        rules = initialRules.map(rule => ({...rule, actions:[...rule.actions]}));
        runs = [
            {id:'RUN-20261022-003',ruleId:'RULE-GAS-001',trigger:'gas_level = 2780 (simulado)',started:'10:42:18',result:'Simulação concluída',detail:[{action:'Registrar evento EVT-GAS-0001',recipient:'Sistema web',result:'Registrado localmente'},{action:'Preparar mensagem vocal',recipient:'AUD-001',result:'Preparação simulada'},{action:'Solicitar sinalização',recipient:'REL-001',result:'Solicitação simulada; sem confirmação física'}]},
            {id:'RUN-20261022-002',ruleId:'RULE-LINK-002',trigger:'tempo sem contato acima do limite (simulado)',started:'10:16:04',result:'Simulação concluída',detail:[{action:'Registrar evento de conectividade',recipient:'Sistema web',result:'Registro demonstrativo'},{action:'Avisar diagnóstico',recipient:'Dashboard',result:'Aviso local'}]},
            {id:'RUN-20261022-001',ruleId:'RULE-EPI-003',trigger:'evento EPI_AUSENTE (exemplo)',started:'09:51:31',result:'Regra desabilitada no momento ilustrativo',detail:[{action:'Avaliar regra',recipient:'RULE-EPI-003',result:'Não executada porque a regra está desabilitada'}]}
        ];
        commands = [
            {id:'CMD-DEMO-004',target:'REL-001',type:'Atuação',description:'Solicitar ativação da sinalização por condição de gás',state:'Simulado',time:'10:42:18'},
            {id:'CMD-DEMO-003',target:'AUD-001',type:'Áudio',description:'Preparar aviso de segurança para síntese',state:'Simulado',time:'10:42:18'},
            {id:'CMD-DEMO-002',target:'EMB-GAS-001',type:'Diagnóstico',description:'Consultar resumo lógico do nó (exemplo)',state:'Aguardando confirmação',time:'10:16:04'},
            {id:'CMD-DEMO-001',target:'REL-001',type:'Atuação',description:'Solicitação ilustrativa sem ACK recebido',state:'Falha simulada',time:'09:40:12'}
        ];
        audioMessages = [
            {id:'AUDMSG-003',title:'Aviso de segurança',targetMode:'unicast',target:'AUD-001',text:'Atenção: área restrita. Siga as orientações da equipe responsável.',status:'Preparada',time:'10:42:18'},
            {id:'AUDMSG-002',title:'Aviso de perímetro',targetMode:'local',target:'LOC-EXT-01',text:'Atenção: área monitorada. Afaste-se do perímetro sinalizado.',status:'Preparada',time:'10:05:00'},
            {id:'AUDMSG-001',title:'Mensagem arquivada de teste',targetMode:'unicast',target:'AUD-001',text:'Mensagem de teste do sistema de síntese vocal.',status:'Arquivada',time:'09:30:00'}
        ];
        renderScenes(); renderRules(); renderRuns(); renderCommands(); renderAudioMessages(); updateMetrics();
        document.getElementById('lastOperationsUpdated').textContent = new Date().toLocaleString('pt-BR');
        alert('Dados demonstrativos restaurados. Nenhum sistema externo foi consultado.');
    }

    function initOperations() {
        document.getElementById('ruleSearch').addEventListener('input', renderRules);
        document.getElementById('ruleStatusFilter').addEventListener('change', renderRules);
        document.getElementById('ruleSeverityFilter').addEventListener('change', renderRules);
        document.getElementById('commandSearch').addEventListener('input', renderCommands);
        document.getElementById('commandTypeFilter').addEventListener('change', renderCommands);
        document.getElementById('commandStateFilter').addEventListener('change', renderCommands);
        document.getElementById('audioSearch').addEventListener('input', renderAudioMessages);
        document.getElementById('audioStatusFilter').addEventListener('change', renderAudioMessages);
        document.getElementById('audioTargetMode').addEventListener('change', updateAudioTargetVisibility);
        document.getElementById('audioText').addEventListener('input', updateAudioCharCount);
        document.getElementById('audioForm').addEventListener('submit', prepareAudio);
        document.getElementById('newCommandForm').addEventListener('submit', registerCommand);
        document.getElementById('newCommandButton').addEventListener('click', newCommand);
        document.getElementById('newSceneButton').addEventListener('click', () => { document.getElementById('newSceneForm').reset(); updateSceneVoiceCharCount(); bootstrap.Modal.getOrCreateInstance(document.getElementById('newSceneModal')).show(); });
        document.getElementById('newRuleButton').addEventListener('click', () => { document.getElementById('newRuleForm').reset(); document.getElementById('newRuleValue').value = '2500'; populateRuleSceneOptions(); bootstrap.Modal.getOrCreateInstance(document.getElementById('newRuleModal')).show(); });
        document.getElementById('newSceneForm').addEventListener('submit', createScene);
        document.getElementById('newRuleForm').addEventListener('submit', registerRule);
        document.getElementById('selectAllSceneDevices').addEventListener('click', () => {
            document.querySelectorAll('input[name="sceneDeviceTarget"]').forEach(input => { input.checked = true; });
        });
        document.getElementById('clearSceneDevices').addEventListener('click', () => {
            document.querySelectorAll('input[name="sceneDeviceTarget"]').forEach(input => { input.checked = false; });
            document.getElementById('sceneVoiceText').value = '';
            updateSceneVoiceCharCount();
        });
        document.getElementById('sceneVoiceText').addEventListener('input', updateSceneVoiceCharCount);
        document.getElementById('resetScenesButton').addEventListener('click', resetScenes);
        document.getElementById('resetRulesButton').addEventListener('click', resetRules);
        document.getElementById('refreshOperationsButton').addEventListener('click', resetOperationsDemo);
        document.getElementById('clearAudioFormButton').addEventListener('click', () => { document.getElementById('audioForm').reset(); updateAudioTargetVisibility(); updateAudioCharCount(); });
        document.getElementById('goToAudioTabButton').addEventListener('click', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('newAudioModal')).hide();
            bootstrap.Tab.getOrCreateInstance(document.getElementById('audio-tab')).show();
            document.getElementById('audioTitle').focus();
        });
        document.getElementById('commands-tab').addEventListener('shown.bs.tab', () => document.getElementById('lastOperationsUpdated').textContent = new Date().toLocaleString('pt-BR'));
        renderScenes(); renderRules(); renderRuns(); renderCommands(); renderAudioMessages(); updateMetrics(); updateAudioTargetVisibility(); updateAudioCharCount(); updateSceneVoiceCharCount();
        document.getElementById('lastOperationsUpdated').textContent = new Date().toLocaleString('pt-BR');
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initOperations);
    else initOperations();
})();
</script>
<?= $this->endSection() ?>
