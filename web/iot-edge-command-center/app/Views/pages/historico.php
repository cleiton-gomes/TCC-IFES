<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Histórico e Auditoria - IoT-Edge Center
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  .historico-page {
    --hist-bg: #0b0f19;
    --hist-card: #111827;
    --hist-card-hover: #172033;
    --hist-border: rgba(255,255,255,.09);
    --hist-muted: #94a3b8;
    color: #f8fafc;
    font-family: 'Segoe UI', system-ui, sans-serif;
    min-height: 100%;
  }
  .historico-page .hist-card {
    background: var(--hist-card);
    border: 1px solid var(--hist-border);
    border-radius: 14px;
    color: #f8fafc;
  }
  .historico-page .text-secondary { color: var(--hist-muted) !important; }
  .historico-page .form-control,
  .historico-page .form-select {
    color: #f8fafc;
    background-color: #0d1422;
    border-color: rgba(148,163,184,.25);
  }
  .historico-page .form-control:focus,
  .historico-page .form-select:focus {
    color: #f8fafc;
    background-color: #0d1422;
    border-color: #3b82f6;
    box-shadow: 0 0 0 .2rem rgba(59,130,246,.17);
  }
  .historico-page .form-select option { background: #111827; color: #f8fafc; }
  .historico-page .form-control::placeholder { color: #718096; }
  .historico-page .stat-card { position: relative; overflow: hidden; min-height: 112px; }
  .historico-page .stat-icon {
    width: 43px; height: 43px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 12px; font-size: 1.05rem; flex: 0 0 auto;
  }
  .historico-page .stat-value { font-size: 1.65rem; line-height: 1.2; font-weight: 750; letter-spacing: -.04em; }
  .historico-page .demo-note {
    border: 1px solid rgba(245,158,11,.28); background: rgba(245,158,11,.07);
    color: #cbd5e1; border-radius: 10px; padding: 10px 12px;
  }
  .historico-page .filter-label { color: #cbd5e1; font-size: .78rem; font-weight: 650; margin-bottom: 5px; }
  .historico-page .table-wrap { overflow-x: auto; }
  .historico-page .table { --bs-table-bg: transparent; --bs-table-color: #e2e8f0; --bs-table-border-color: rgba(255,255,255,.07); margin-bottom: 0; }
  .historico-page .table thead th {
    color: #94a3b8; font-size: .72rem; text-transform: uppercase; letter-spacing: .055em;
    font-weight: 700; white-space: nowrap; border-bottom-width: 1px; padding: 13px 12px;
  }
  .historico-page .table tbody td { padding: 13px 12px; vertical-align: middle; font-size: .84rem; }
  .historico-page .table tbody tr:hover { background: rgba(59,130,246,.055); }
  .historico-page .event-title { font-weight: 650; color: #f8fafc; }
  .historico-page .event-detail { color: #94a3b8; font-size: .76rem; margin-top: 3px; max-width: 440px; }
  .historico-page .code-text { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .76rem; color: #cbd5e1; white-space: nowrap; }
  .historico-page .timeline { position: relative; padding-left: 23px; }
  .historico-page .timeline::before { content: ''; position: absolute; left: 7px; top: 9px; bottom: 10px; width: 2px; background: rgba(148,163,184,.18); }
  .historico-page .timeline-item { position: relative; padding: 0 0 19px 12px; }
  .historico-page .timeline-item:last-child { padding-bottom: 0; }
  .historico-page .timeline-dot { position: absolute; left: -22px; top: 4px; width: 13px; height: 13px; border-radius: 50%; border: 2px solid #111827; box-shadow: 0 0 0 2px currentColor; background: currentColor; }
  .historico-page .timeline-time { color: #94a3b8; font-size: .74rem; }
  .historico-page .timeline-heading { font-size: .85rem; font-weight: 650; margin-top: 2px; }
  .historico-page .timeline-detail { font-size: .77rem; color: #94a3b8; margin-top: 3px; }
  .historico-page .type-pill { display: inline-flex; gap: 6px; align-items: center; border-radius: 30px; padding: 4px 8px; font-size: .72rem; font-weight: 650; white-space: nowrap; }
  .historico-page .status-pill { display: inline-flex; align-items: center; gap: 5px; border-radius: 30px; padding: 4px 8px; font-size: .72rem; font-weight: 650; white-space: nowrap; }
  .historico-page .empty-state { padding: 34px 14px; color: #94a3b8; text-align: center; }
  .historico-page .empty-state i { font-size: 1.8rem; margin-bottom: 10px; }
  .historico-page .btn-outline-secondary { border-color: rgba(148,163,184,.28); color: #cbd5e1; }
  .historico-page .btn-outline-secondary:hover { color: #fff; background: #1e293b; }
  .historico-page .result-count { font-size: .79rem; color: #94a3b8; }
  .historico-page .quick-filter { font-size: .76rem; border-radius: 30px; }
  @media (max-width: 767.98px) {
    .historico-page .stat-value { font-size: 1.4rem; }
    .historico-page .stat-card { min-height: 98px; }
    .historico-page .table thead th, .historico-page .table tbody td { padding-left: 10px; padding-right: 10px; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="historico-page container-fluid p-3 p-lg-4">
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="text-info"><i class="fa-solid fa-clock-rotate-left fa-lg"></i></span>
        <h4 class="fw-bold mb-0">Histórico e Auditoria</h4>
      </div>
      <p class="text-secondary small mb-0">Consulte eventos, comandos, movimentações e ações registradas no IoT-Edge Center.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-outline-info btn-sm" id="btnExportCsv">
        <i class="fa-solid fa-file-csv me-1"></i> Exportar CSV
      </button>
      <button type="button" class="btn btn-outline-light btn-sm" id="btnExportJson">
        <i class="fa-solid fa-file-code me-1"></i> Exportar JSON
      </button>
    </div>
  </div>

  <div class="demo-note small mb-3">
    <i class="fa-solid fa-circle-info text-warning me-1"></i>
    <strong>Modo demonstrativo:</strong> os registros abaixo são fictícios e servem para validar a interface e os filtros. Eles não foram consultados de um banco de dados nem representam eventos reais dos dispositivos.
  </div>

  <div class="row g-3 mb-3" id="summaryCards">
    <div class="col-6 col-xl-3">
      <div class="hist-card stat-card p-3 h-100">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div><div class="text-secondary small mb-2">Registros encontrados</div><div class="stat-value" id="statTotal">0</div></div>
          <span class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-list-check"></i></span>
        </div><div class="text-secondary small mt-2">Conforme os filtros aplicados</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="hist-card stat-card p-3 h-100">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div><div class="text-secondary small mb-2">Alertas</div><div class="stat-value text-danger" id="statAlerts">0</div></div>
          <span class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-triangle-exclamation"></i></span>
        </div><div class="text-secondary small mt-2">Eventos classificados como alerta</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="hist-card stat-card p-3 h-100">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div><div class="text-secondary small mb-2">Comandos</div><div class="stat-value text-info" id="statCommands">0</div></div>
          <span class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-terminal"></i></span>
        </div><div class="text-secondary small mt-2">Solicitações demonstrativas</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="hist-card stat-card p-3 h-100">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div><div class="text-secondary small mb-2">Com falha/atenção</div><div class="stat-value text-warning" id="statAttention">0</div></div>
          <span class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-circle-exclamation"></i></span>
        </div><div class="text-secondary small mt-2">Registros que exigem conferência</div>
      </div>
    </div>
  </div>

  <div class="hist-card p-3 p-lg-4 mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h6 class="fw-bold mb-1"><i class="fa-solid fa-filter text-info me-2"></i>Filtros de consulta</h6>
        <div class="text-secondary small">Combine os campos para localizar registros específicos.</div>
      </div>
      <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClearFilters"><i class="fa-solid fa-rotate-left me-1"></i> Limpar filtros</button>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <label for="filterSearch" class="form-label filter-label">Busca livre</label>
        <div class="input-group">
          <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="search" id="filterSearch" class="form-control" placeholder="Evento, código, operador, detalhe...">
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <label for="filterFrom" class="form-label filter-label">Data inicial</label>
        <input type="date" id="filterFrom" class="form-control">
      </div>
      <div class="col-6 col-lg-2">
        <label for="filterTo" class="form-label filter-label">Data final</label>
        <input type="date" id="filterTo" class="form-control">
      </div>
      <div class="col-6 col-lg-2">
        <label for="filterCategory" class="form-label filter-label">Categoria</label>
        <select id="filterCategory" class="form-select">
          <option value="">Todas</option>
          <option value="Alerta">Alerta</option>
          <option value="Comando">Comando</option>
          <option value="Automação">Automação</option>
          <option value="Movimento">Movimento</option>
          <option value="Comunicação">Comunicação</option>
          <option value="Auditoria">Auditoria</option>
        </select>
      </div>
      <div class="col-6 col-lg-2">
        <label for="filterResult" class="form-label filter-label">Resultado</label>
        <select id="filterResult" class="form-select">
          <option value="">Todos</option>
          <option value="Concluído">Concluído</option>
          <option value="Simulado">Simulado</option>
          <option value="Atenção">Atenção</option>
          <option value="Falha simulada">Falha simulada</option>
          <option value="Informativo">Informativo</option>
        </select>
      </div>
      <div class="col-6 col-lg-3">
        <label for="filterDevice" class="form-label filter-label">Dispositivo</label>
        <select id="filterDevice" class="form-select">
          <option value="">Todos os dispositivos</option>
          <option value="EMB-GAS-001">EMB-GAS-001 · Sensor de gás</option>
          <option value="AUD-001">AUD-001 · Áudio/TTS</option>
          <option value="REL-001">REL-001 · Relé/sinalização</option>
          <option value="CAM-001">CAM-001 · Câmera IP</option>
          <option value="DRONE-001">DRONE-001 · Drone de inspeção</option>
          <option value="SISTEMA">SISTEMA · Plataforma</option>
        </select>
      </div>
      <div class="col-6 col-lg-3">
        <label for="filterLocation" class="form-label filter-label">Local</label>
        <select id="filterLocation" class="form-select">
          <option value="">Todos os locais</option>
          <option value="LOC-SALA-CTRL">Sala de controle · LOC-SALA-CTRL</option>
          <option value="LOC-CORREDOR-N">Corredor norte · LOC-CORREDOR-N</option>
          <option value="LOC-ZONA-TECH">Zona técnica · LOC-ZONA-TECH</option>
          <option value="LOC-EXT-01">Área externa · LOC-EXT-01</option>
          <option value="LOC-ANDAR-02">Andar 2 · LOC-ANDAR-02</option>
          <option value="N/A">Não se aplica</option>
        </select>
      </div>
      <div class="col-12 col-lg-6">
        <label class="form-label filter-label d-block">Atalhos</label>
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="btn btn-outline-secondary quick-filter" data-quick="today"><i class="fa-regular fa-calendar me-1"></i>Hoje</button>
          <button type="button" class="btn btn-outline-secondary quick-filter" data-quick="alerts"><i class="fa-solid fa-triangle-exclamation me-1"></i>Somente alertas</button>
          <button type="button" class="btn btn-outline-secondary quick-filter" data-quick="commands"><i class="fa-solid fa-terminal me-1"></i>Somente comandos</button>
          <button type="button" class="btn btn-outline-secondary quick-filter" data-quick="failures"><i class="fa-solid fa-circle-exclamation me-1"></i>Falhas/atenção</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-xl-8">
      <div class="hist-card h-100">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-bottom border-secondary border-opacity-25">
          <div>
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-table-list text-info me-2"></i>Registro de eventos</h6>
            <div class="result-count" id="resultCount">Carregando registros...</div>
          </div>
          <span class="badge bg-secondary bg-opacity-25 border border-secondary text-secondary-emphasis">Dados locais de demonstração</span>
        </div>
        <div class="table-wrap">
          <table class="table align-middle" id="historyTable">
            <thead><tr><th>Data/hora</th><th>Evento</th><th>Dispositivo / local</th><th>Responsável</th><th>Resultado</th></tr></thead>
            <tbody id="historyTableBody"></tbody>
          </table>
          <div class="empty-state d-none" id="emptyState">
            <i class="fa-regular fa-folder-open d-block"></i>
            <div class="fw-semibold text-light">Nenhum registro encontrado</div>
            <div class="small mt-1">Altere os filtros ou limpe a consulta para ver os registros demonstrativos.</div>
          </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-top border-secondary border-opacity-25">
          <span class="text-secondary small">A tabela é ordenada do registro mais recente para o mais antigo.</span>
          <button type="button" class="btn btn-sm btn-outline-secondary" id="btnShowAll"><i class="fa-solid fa-list me-1"></i>Mostrar todos</button>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-4">
      <div class="hist-card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
          <div><h6 class="fw-bold mb-1"><i class="fa-solid fa-timeline text-info me-2"></i>Linha do tempo</h6><div class="text-secondary small">Visão resumida dos resultados filtrados.</div></div>
          <span class="badge bg-primary bg-opacity-25 text-info" id="timelineCount">0 itens</span>
        </div>
        <div class="timeline" id="timelineList"></div>
        <div class="empty-state d-none" id="timelineEmpty"><i class="fa-regular fa-clock d-block"></i><div class="small">Nenhum evento para exibir.</div></div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-12 col-lg-6">
      <div class="hist-card p-3 h-100">
        <h6 class="fw-bold mb-2"><i class="fa-solid fa-shield-halved text-info me-2"></i>O que a auditoria deve registrar?</h6>
        <p class="text-secondary small mb-2">No sistema integrado, cada registro deverá guardar data/hora, tipo do evento, origem, dispositivo, local relacionado, operador ou processo responsável, resultado e identificador de correlação.</p>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-secondary bg-opacity-25 border border-secondary">Quem</span>
          <span class="badge bg-secondary bg-opacity-25 border border-secondary">Quando</span>
          <span class="badge bg-secondary bg-opacity-25 border border-secondary">Onde</span>
          <span class="badge bg-secondary bg-opacity-25 border border-secondary">O quê</span>
          <span class="badge bg-secondary bg-opacity-25 border border-secondary">Resultado</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6">
      <div class="hist-card p-3 h-100">
        <h6 class="fw-bold mb-2"><i class="fa-solid fa-database text-warning me-2"></i>Próxima etapa de integração</h6>
        <p class="text-secondary small mb-0">Esta página ainda não consulta tabelas. Quando a camada de persistência for criada, os dados demonstrativos poderão ser substituídos por consultas ao histórico de eventos, comandos, execuções de automação e movimentações de dispositivos, mantendo os mesmos filtros da interface.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  'use strict';

  // Dados fictícios para prototipação. Não representam leituras reais nem persistidas.
  const demoEvents = [
    { id: 'EVT-DEMO-015', datetime: '2026-10-22T14:32:00', category: 'Alerta', title: 'Nível de gás acima do limite demonstrativo', detail: 'Leitura de exemplo acima do limiar configurado para a interface.', device: 'EMB-GAS-001', deviceName: 'Sensor MQ-4 / Heltec LoRa32 V3', location: 'LOC-SALA-CTRL', locationName: 'Sala de controle', actor: 'Monitoramento simulado', result: 'Atenção' },
    { id: 'EVT-DEMO-014', datetime: '2026-10-22T14:28:00', category: 'Comando', title: 'Solicitação de retorno do drone à base', detail: 'Comando ilustrativo de retorno (RTH), sem envio ao hardware.', device: 'DRONE-001', deviceName: 'Drone de inspeção', location: 'LOC-EXT-01', locationName: 'Área externa', actor: 'Operador demonstrativo', result: 'Simulado' },
    { id: 'EVT-DEMO-013', datetime: '2026-10-22T14:22:00', category: 'Movimento', title: 'Início de ronda local', detail: 'Trajeto de demonstração direcionado à área selecionada para inspeção.', device: 'DRONE-001', deviceName: 'Drone de inspeção', location: 'LOC-CORREDOR-N', locationName: 'Corredor norte', actor: 'Operador demonstrativo', result: 'Simulado' },
    { id: 'EVT-DEMO-012', datetime: '2026-10-22T14:17:00', category: 'Automação', title: 'Cena de aviso selecionada', detail: 'Cena de exemplo preparada para avisar a área técnica.', device: 'SISTEMA', deviceName: 'Plataforma IoT-Edge', location: 'LOC-ZONA-TECH', locationName: 'Zona técnica', actor: 'Operador demonstrativo', result: 'Concluído' },
    { id: 'EVT-DEMO-011', datetime: '2026-10-22T14:12:00', category: 'Comando', title: 'Texto de áudio preparado para transmissão', detail: 'Mensagem de TTS registrada apenas como exemplo de interface.', device: 'AUD-001', deviceName: 'Áudio / TTS', location: 'LOC-SALA-CTRL', locationName: 'Sala de controle', actor: 'Operador demonstrativo', result: 'Simulado' },
    { id: 'EVT-DEMO-010', datetime: '2026-10-22T13:54:00', category: 'Comunicação', title: 'Qualidade de enlace LoRa atualizada', detail: 'Indicadores RSSI/SNR fictícios para representar o histórico de comunicação.', device: 'EMB-GAS-001', deviceName: 'Sensor MQ-4 / Heltec LoRa32 V3', location: 'LOC-SALA-CTRL', locationName: 'Sala de controle', actor: 'Telemetria demonstrativa', result: 'Informativo' },
    { id: 'EVT-DEMO-009', datetime: '2026-10-22T13:45:00', category: 'Comando', title: 'Acionamento de sinalização solicitado', detail: 'Solicitação local ilustrativa; nenhum relé físico foi acionado.', device: 'REL-001', deviceName: 'Relé / sinalização', location: 'LOC-ZONA-TECH', locationName: 'Zona técnica', actor: 'Operador demonstrativo', result: 'Simulado' },
    { id: 'EVT-DEMO-008', datetime: '2026-10-22T12:20:00', category: 'Auditoria', title: 'Sessão de operador iniciada', detail: 'Registro fictício de acesso para compor a demonstração da auditoria.', device: 'SISTEMA', deviceName: 'Plataforma IoT-Edge', location: 'N/A', locationName: 'Não se aplica', actor: 'operador.demo', result: 'Informativo' },
    { id: 'EVT-DEMO-007', datetime: '2026-10-22T11:50:00', category: 'Alerta', title: 'Perda temporária de comunicação simulada', detail: 'Evento de exemplo para demonstrar o filtro de falhas e atenção.', device: 'CAM-001', deviceName: 'Câmera IP', location: 'LOC-CORREDOR-N', locationName: 'Corredor norte', actor: 'Diagnóstico simulado', result: 'Falha simulada' },
    { id: 'EVT-DEMO-006', datetime: '2026-10-21T18:30:00', category: 'Movimento', title: 'Drone retornou à base virtual', detail: 'Fim de uma missão demonstrativa no gêmeo digital.', device: 'DRONE-001', deviceName: 'Drone de inspeção', location: 'LOC-EXT-01', locationName: 'Área externa', actor: 'Operador demonstrativo', result: 'Concluído' },
    { id: 'EVT-DEMO-005', datetime: '2026-10-21T17:10:00', category: 'Automação', title: 'Regra de limite de gás revisada', detail: 'Registro ilustrativo de alteração de configuração.', device: 'SISTEMA', deviceName: 'Plataforma IoT-Edge', location: 'LOC-SALA-CTRL', locationName: 'Sala de controle', actor: 'operador.demo', result: 'Concluído' },
    { id: 'EVT-DEMO-004', datetime: '2026-10-21T16:42:00', category: 'Comando', title: 'Pedido de ativação da câmera', detail: 'Solicitação fictícia para validar a apresentação de comandos.', device: 'CAM-001', deviceName: 'Câmera IP', location: 'LOC-CORREDOR-N', locationName: 'Corredor norte', actor: 'Operador demonstrativo', result: 'Falha simulada' },
    { id: 'EVT-DEMO-003', datetime: '2026-10-21T15:30:00', category: 'Alerta', title: 'Aviso de sensor demonstrativo normalizado', detail: 'Exemplo de registro de encerramento de um alerta.', device: 'EMB-GAS-001', deviceName: 'Sensor MQ-4 / Heltec LoRa32 V3', location: 'LOC-SALA-CTRL', locationName: 'Sala de controle', actor: 'Monitoramento simulado', result: 'Concluído' },
    { id: 'EVT-DEMO-002', datetime: '2026-10-21T14:05:00', category: 'Comunicação', title: 'Dispositivo marcado como disponível', detail: 'Estado de demonstração registrado na camada visual.', device: 'REL-001', deviceName: 'Relé / sinalização', location: 'LOC-ZONA-TECH', locationName: 'Zona técnica', actor: 'Diagnóstico simulado', result: 'Informativo' },
    { id: 'EVT-DEMO-001', datetime: '2026-10-21T09:00:00', category: 'Auditoria', title: 'Verificação inicial do painel', detail: 'Registro ilustrativo de abertura e verificação da interface.', device: 'SISTEMA', deviceName: 'Plataforma IoT-Edge', location: 'N/A', locationName: 'Não se aplica', actor: 'Sistema demonstrativo', result: 'Informativo' }
  ].sort((a, b) => new Date(b.datetime) - new Date(a.datetime));

  const $ = (id) => document.getElementById(id);
  const controls = ['filterSearch', 'filterFrom', 'filterTo', 'filterCategory', 'filterResult', 'filterDevice', 'filterLocation'];
  let forceFailureFilter = false;
  const typeStyles = {
    'Alerta': ['text-danger', 'fa-triangle-exclamation'],
    'Comando': ['text-info', 'fa-terminal'],
    'Automação': ['text-primary', 'fa-gears'],
    'Movimento': ['text-warning', 'fa-route'],
    'Comunicação': ['text-success', 'fa-tower-broadcast'],
    'Auditoria': ['text-secondary', 'fa-shield-halved']
  };
  const resultStyles = {
    'Concluído': 'bg-success bg-opacity-10 border border-success border-opacity-25 text-success',
    'Simulado': 'bg-info bg-opacity-10 border border-info border-opacity-25 text-info',
    'Atenção': 'bg-warning bg-opacity-10 border border-warning border-opacity-25 text-warning',
    'Falha simulada': 'bg-danger bg-opacity-10 border border-danger border-opacity-25 text-danger',
    'Informativo': 'bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-secondary'
  };

  const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
  const formatDate = (value) => {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '—';
    return new Intl.DateTimeFormat('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date);
  };
  const formatTime = (value) => {
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '—';
    return new Intl.DateTimeFormat('pt-BR', { hour: '2-digit', minute: '2-digit' }).format(date);
  };
  const formatDateTime = (value) => `${formatDate(value)} ${formatTime(value)}`;

  function filteredEvents() {
    const query = $('filterSearch').value.trim().toLocaleLowerCase('pt-BR');
    const from = $('filterFrom').value;
    const to = $('filterTo').value;
    const category = $('filterCategory').value;
    const result = $('filterResult').value;
    const device = $('filterDevice').value;
    const location = $('filterLocation').value;

    return demoEvents.filter((event) => {
      const date = event.datetime.slice(0, 10);
      const searchable = [event.id, event.category, event.title, event.detail, event.device, event.deviceName, event.location, event.locationName, event.actor, event.result].join(' ').toLocaleLowerCase('pt-BR');
      return (!query || searchable.includes(query))
        && (!forceFailureFilter || ['Atenção', 'Falha simulada'].includes(event.result))
        && (!from || date >= from)
        && (!to || date <= to)
        && (!category || event.category === category)
        && (!result || event.result === result)
        && (!device || event.device === device)
        && (!location || event.location === location);
    }).sort((a, b) => new Date(b.datetime) - new Date(a.datetime));
  }

  function typeMarkup(category) {
    const style = typeStyles[category] || ['text-secondary', 'fa-circle-info'];
    return `<span class="type-pill bg-secondary bg-opacity-10 border border-secondary border-opacity-25 ${style[0]}"><i class="fa-solid ${style[1]}"></i>${escapeHtml(category)}</span>`;
  }
  function resultMarkup(result) {
    const style = resultStyles[result] || resultStyles.Informativo;
    return `<span class="status-pill ${style}"><i class="fa-solid ${result === 'Concluído' ? 'fa-check-circle' : result === 'Falha simulada' ? 'fa-circle-xmark' : result === 'Atenção' ? 'fa-triangle-exclamation' : result === 'Simulado' ? 'fa-flask' : 'fa-circle-info'}"></i>${escapeHtml(result)}</span>`;
  }

  function renderTable(events) {
    const tbody = $('historyTableBody');
    tbody.innerHTML = events.map((event) => `
      <tr>
        <td><div class="fw-semibold text-nowrap">${escapeHtml(formatDate(event.datetime))}</div><div class="text-secondary small">${escapeHtml(formatTime(event.datetime))}</div><div class="code-text mt-1">${escapeHtml(event.id)}</div></td>
        <td><div class="mb-2">${typeMarkup(event.category)}</div><div class="event-title">${escapeHtml(event.title)}</div><div class="event-detail">${escapeHtml(event.detail)}</div></td>
        <td><div class="code-text">${escapeHtml(event.device)}</div><div class="small mt-1">${escapeHtml(event.deviceName)}</div><div class="text-secondary small mt-1">${escapeHtml(event.locationName)}</div><div class="code-text">${escapeHtml(event.location)}</div></td>
        <td><span class="small">${escapeHtml(event.actor)}</span></td>
        <td>${resultMarkup(event.result)}</td>
      </tr>`).join('');
    $('emptyState').classList.toggle('d-none', events.length !== 0);
    $('historyTable').classList.toggle('d-none', events.length === 0);
    $('resultCount').textContent = `${events.length} ${events.length === 1 ? 'registro encontrado' : 'registros encontrados'} de ${demoEvents.length} demonstrativos`;
  }

  function renderTimeline(events) {
    const timeline = $('timelineList');
    const visible = events.slice(0, 8);
    timeline.innerHTML = visible.map((event) => {
      const style = typeStyles[event.category] || ['text-secondary', 'fa-circle-info'];
      return `<div class="timeline-item ${style[0]}">
        <span class="timeline-dot"></span>
        <div class="timeline-time">${escapeHtml(formatDateTime(event.datetime))} · ${escapeHtml(event.id)}</div>
        <div class="timeline-heading text-light">${escapeHtml(event.title)}</div>
        <div class="timeline-detail">${escapeHtml(event.device)} · ${escapeHtml(event.locationName)}</div>
        <div class="mt-2">${resultMarkup(event.result)}</div>
      </div>`;
    }).join('');
    $('timelineEmpty').classList.toggle('d-none', events.length !== 0);
    timeline.classList.toggle('d-none', events.length === 0);
    $('timelineCount').textContent = `${visible.length} ${visible.length === 1 ? 'item' : 'itens'}`;
    if (events.length > visible.length) {
      timeline.insertAdjacentHTML('beforeend', `<div class="text-secondary small mt-3">Mostrando os 8 eventos mais recentes deste filtro.</div>`);
    }
  }

  function renderSummary(events) {
    $('statTotal').textContent = events.length;
    $('statAlerts').textContent = events.filter((event) => event.category === 'Alerta').length;
    $('statCommands').textContent = events.filter((event) => event.category === 'Comando').length;
    $('statAttention').textContent = events.filter((event) => ['Atenção', 'Falha simulada'].includes(event.result)).length;
  }

  function render() {
    const events = filteredEvents();
    renderSummary(events);
    renderTable(events);
    renderTimeline(events);
  }

  function clearFilters() {
    forceFailureFilter = false;
    controls.forEach((id) => { $(id).value = ''; });
    render();
  }

  function downloadFile(filename, content, mimeType) {
    const blob = new Blob([content], { type: `${mimeType};charset=utf-8;` });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
  }

  function exportCsv() {
    const events = filteredEvents();
    const headers = ['ID', 'Data/Hora', 'Categoria', 'Evento', 'Detalhe', 'Dispositivo', 'Nome do dispositivo', 'Local', 'Nome do local', 'Responsável', 'Resultado'];
    const rows = events.map((event) => [event.id, event.datetime.replace('T', ' '), event.category, event.title, event.detail, event.device, event.deviceName, event.location, event.locationName, event.actor, event.result]);
    const csvCell = (value) => `"${String(value ?? '').replace(/"/g, '""')}"`;
    const csv = '\uFEFF' + [headers, ...rows].map((row) => row.map(csvCell).join(';')).join('\r\n');
    downloadFile('historico-iot-edge-demonstracao.csv', csv, 'text/csv');
  }

  function exportJson() {
    const payload = {
      aviso: 'Dados fictícios de demonstração; não representam registros reais nem persistidos.',
      exportado_em: new Date().toISOString(),
      quantidade: filteredEvents().length,
      registros: filteredEvents()
    };
    downloadFile('historico-iot-edge-demonstracao.json', JSON.stringify(payload, null, 2), 'application/json');
  }

  controls.forEach((id) => $(id).addEventListener(id === 'filterSearch' ? 'input' : 'change', render));
  $('btnClearFilters').addEventListener('click', clearFilters);
  $('btnShowAll').addEventListener('click', clearFilters);
  $('btnExportCsv').addEventListener('click', exportCsv);
  $('btnExportJson').addEventListener('click', exportJson);

  document.querySelectorAll('[data-quick]').forEach((button) => {
    button.addEventListener('click', () => {
      clearFilters();
      const quick = button.dataset.quick;
      if (quick === 'today') {
        // Usa a data mais recente presente no conjunto demonstrativo, não o relógio do servidor.
        $('filterFrom').value = '2026-10-22';
        $('filterTo').value = '2026-10-22';
      } else if (quick === 'alerts') {
        $('filterCategory').value = 'Alerta';
      } else if (quick === 'commands') {
        $('filterCategory').value = 'Comando';
      } else if (quick === 'failures') {
        // Inclui falhas simuladas e itens que exigem atenção; permanece ativo ao combinar filtros.
        forceFailureFilter = true;
      }
      render();
    });
  });

  render();
})();
</script>
<?= $this->endSection() ?>
