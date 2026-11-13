<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Diagnóstico e Infraestrutura - IoT-Edge Center
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .diagnostic-page {
        --diag-panel: #111b2e;
        --diag-panel-2: #172338;
        --diag-border: rgba(148, 163, 184, .17);
        --diag-text: #e5edf8;
        --diag-muted: #91a2ba;
        --diag-blue: #38bdf8;
        max-width: 1640px;
        margin: 0 auto;
        padding: 26px 22px 40px;
        color: var(--diag-text);
    }
    .diagnostic-page * { box-sizing: border-box; }
    .diagnostic-page .diag-eyebrow { color: var(--diag-blue); font-size: .72rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .diagnostic-page .diag-title { margin: 4px 0 6px; font-size: clamp(1.6rem, 3vw, 2.15rem); font-weight: 800; letter-spacing: -.035em; }
    .diagnostic-page .diag-muted { color: var(--diag-muted); }
    .diagnostic-page .diag-card { height: 100%; background: var(--diag-panel); border: 1px solid var(--diag-border); border-radius: 15px; box-shadow: 0 10px 26px rgba(0,0,0,.12); }
    .diagnostic-page .diag-card-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; padding: 19px 20px 0; }
    .diagnostic-page .diag-card-body { padding: 18px 20px 20px; }
    .diagnostic-page .diag-section-title { margin: 0; font-size: 1rem; font-weight: 750; }
    .diagnostic-page .diag-subtitle { margin-top: 4px; color: var(--diag-muted); font-size: .8rem; }
    .diagnostic-page .diag-note { padding: 11px 13px; color: #dbe5f2; background: rgba(251,191,36,.08); border: 1px solid rgba(251,191,36,.24); border-radius: 10px; font-size: .83rem; line-height: 1.55; }
    .diagnostic-page .diag-pill { display: inline-flex; align-items: center; gap: 6px; border-radius: 999px; padding: 6px 10px; font-size: .73rem; font-weight: 750; white-space: nowrap; }
    .diagnostic-page .pill-demo { background: rgba(251,191,36,.10); border: 1px solid rgba(251,191,36,.22); color: #fcd34d; }
    .diagnostic-page .pill-planned { background: rgba(148,163,184,.11); border: 1px solid rgba(148,163,184,.2); color: #cbd5e1; }
    .diagnostic-page .status-tag { display: inline-flex; align-items: center; gap: 6px; border-radius: 7px; padding: 5px 8px; font-size: .72rem; font-weight: 750; white-space: nowrap; }
    .diagnostic-page .status-demo { color: #fcd34d; background: rgba(251,191,36,.1); }
    .diagnostic-page .status-planned { color: #cbd5e1; background: rgba(148,163,184,.12); }
    .diagnostic-page .status-info { color: #7dd3fc; background: rgba(56,189,248,.1); }
    .diagnostic-page .status-good-demo { color: #6ee7b7; background: rgba(52,211,153,.1); }
    .diagnostic-page .diag-metric { height: 100%; padding: 15px; background: var(--diag-panel-2); border: 1px solid var(--diag-border); border-radius: 11px; }
    .diagnostic-page .diag-metric-label { color: var(--diag-muted); font-size: .75rem; }
    .diagnostic-page .diag-metric-value { margin-top: 7px; font-size: 1.3rem; font-weight: 850; line-height: 1.2; overflow-wrap: anywhere; }
    .diagnostic-page .diag-metric-foot { margin-top: 6px; color: var(--diag-muted); font-size: .72rem; line-height: 1.4; }
    .diagnostic-page .service-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 13px 0; border-bottom: 1px solid var(--diag-border); }
    .diagnostic-page .service-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .diagnostic-page .service-name { font-size: .86rem; font-weight: 750; }
    .diagnostic-page .service-desc { margin-top: 3px; color: var(--diag-muted); font-size: .76rem; line-height: 1.45; }
    .diagnostic-page .terminal-box { min-height: 190px; max-height: 275px; overflow-y: auto; padding: 13px; background: #050811; border: 1px solid rgba(255,255,255,.1); border-radius: 9px; color: #7dd3fc; font: .78rem/1.65 ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; overflow-wrap: anywhere; }
    .diagnostic-page .terminal-line + .terminal-line { margin-top: 6px; }
    .diagnostic-page .terminal-ok { color: #6ee7b7; }
    .diagnostic-page .terminal-warn { color: #fcd34d; }
    .diagnostic-page .table { --bs-table-bg: transparent; --bs-table-color: var(--diag-text); --bs-table-border-color: var(--diag-border); margin-bottom: 0; }
    .diagnostic-page .table thead th { padding: 12px 13px; color: #8fa1ba; font-size: .69rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; white-space: nowrap; }
    .diagnostic-page .table tbody td { padding: 13px; vertical-align: middle; font-size: .81rem; }
    .diagnostic-page .table tbody tr:hover { --bs-table-bg: rgba(148,163,184,.04); }
    .diagnostic-page .diag-code { color: #bae6fd; font: 750 .78rem ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
    .diagnostic-page .diag-small { color: var(--diag-muted); font-size: .73rem; }
    .diagnostic-page .diag-progress { height: 8px; background: #26354b; }
    .diagnostic-page .form-control, .diagnostic-page .form-select { background: #0b1425; color: var(--diag-text); border-color: #30415a; }
    .diagnostic-page .form-control::placeholder { color: #74859d; }
    .diagnostic-page .form-control:focus, .diagnostic-page .form-select:focus { background: #0b1425; color: var(--diag-text); border-color: var(--diag-blue); box-shadow: 0 0 0 .2rem rgba(56,189,248,.12); }
    .diagnostic-page .form-select option { background: #111827; color: #fff; }
    .diagnostic-page .btn-outline-soft { color: #cbd7e7; border: 1px solid #30415a; }
    .diagnostic-page .btn-outline-soft:hover { color: #fff; background: #1a2940; border-color: #425875; }
    .diagnostic-page .diag-empty { padding: 25px 10px; color: var(--diag-muted); text-align: center; }
    @media (max-width: 767.98px) {
        .diagnostic-page { padding: 20px 13px 30px; }
        .diagnostic-page .diag-card-header { padding: 16px 15px 0; }
        .diagnostic-page .diag-card-body { padding: 15px; }
        .diagnostic-page .diag-metric-value { font-size: 1.15rem; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="diagnostic-page">
    <header class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-end gap-3 mb-4">
        <div>
            <div class="diag-eyebrow">Observabilidade do sistema</div>
            <h1 class="diag-title">Diagnóstico e Infraestrutura</h1>
            <p class="diag-muted mb-0">Referências de serviços, mensageria MQTT, áudio e qualidade dos enlaces dos dispositivos.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-soft" type="button" id="refreshDiagButton"><i class="bi bi-arrow-clockwise me-2"></i>Atualizar demonstração</button>
            <a href="<?= site_url('embarcados') ?>" class="btn btn-primary"><i class="bi bi-cpu me-2"></i>Ver dispositivos</a>
        </div>
    </header>

    <div class="diag-note mb-4">
        <i class="bi bi-info-circle-fill text-warning me-2"></i>
        <strong>Modo demonstrativo:</strong> os números de CPU, RAM, latência, mensagens e sinal são exemplos ilustrativos. Esta página não está conectada ao Raspberry Pi, ao broker MQTT, ao Piper TTS ou aos dispositivos. Atualizar gera novas amostras fictícias.
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center mb-4 small diag-muted"><i class="bi bi-clock"></i><span>Última atualização local:</span><strong class="text-light" id="diagLastUpdated">--</strong><span>· Sem coleta de métricas reais.</span></div>

    <section class="row g-3 mb-4" aria-label="Resumo dos serviços">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="diag-card p-3">
                <div class="d-flex justify-content-between align-items-start gap-2"><div><div class="diag-small">SERVIDOR DE BORDA</div><h2 class="h6 fw-bold mt-1 mb-1"><i class="bi bi-cpu text-info me-2"></i>Raspberry Pi 3B+</h2><div class="diag-small">Ollama / processamento visual</div></div><span class="status-tag status-planned">Não conectado</span></div>
                <div class="row g-2 mt-2">
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">CPU · amostra</div><div class="diag-metric-value text-warning" id="edgeCpu">42%</div><div class="diag-metric-foot">Valor ilustrativo</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">RAM · amostra</div><div class="diag-metric-value text-info" id="edgeRam">768 MB</div><div class="diag-metric-foot">Valor ilustrativo</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">SoC · amostra</div><div class="diag-metric-value text-danger" id="edgeTemp">51,2 °C</div><div class="diag-metric-foot">Temperatura fictícia</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">VLM · amostra</div><div class="diag-metric-value text-success" id="edgeFps">14,8 FPS</div><div class="diag-metric-foot">Não é medição atual</div></div></div>
                </div>
                <div class="mt-3"><div class="d-flex justify-content-between small mb-2"><span class="diag-muted">Carga da pipeline visual (demo)</span><strong id="edgePipelineLabel">68%</strong></div><div class="progress diag-progress"><div class="progress-bar bg-info" id="edgePipelineBar" role="progressbar" style="width:68%" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div></div></div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="diag-card p-3">
                <div class="d-flex justify-content-between align-items-start gap-2"><div><div class="diag-small">MENSAGERIA</div><h2 class="h6 fw-bold mt-1 mb-1"><i class="bi bi-diagram-3 text-info me-2"></i>Broker MQTT</h2><div class="diag-small">Protocolo dos dispositivos · integração futura</div></div><span class="status-tag status-planned">Não conectado</span></div>
                <div class="row g-2 mt-2">
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">Latência · amostra</div><div class="diag-metric-value text-success" id="mqttLatency">12 ms</div><div class="diag-metric-foot">Valor fictício</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">Mensagens · amostra</div><div class="diag-metric-value text-info" id="mqttRate">38/s</div><div class="diag-metric-foot">Valor fictício</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">Clientes · amostra</div><div class="diag-metric-value text-warning" id="mqttClients">6</div><div class="diag-metric-foot">Sem clientes consultados</div></div></div>
                    <div class="col-6"><div class="diag-metric"><div class="diag-metric-label">Perda · amostra</div><div class="diag-metric-value text-success" id="mqttDrop">0,0%</div><div class="diag-metric-foot">Não medido pelo broker</div></div></div>
                </div>
                <div class="mt-3 d-flex justify-content-between flex-wrap gap-2 small"><span class="diag-muted">Porta MQTT TCP</span><code class="text-info">1883 (referência)</code><span class="diag-muted">WebSocket</span><code class="text-info">9001 (opcional)</code></div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="diag-card p-3">
                <div class="d-flex justify-content-between align-items-start gap-2"><div><div class="diag-small">SÍNTESE DE ÁUDIO</div><h2 class="h6 fw-bold mt-1 mb-1"><i class="bi bi-volume-up text-warning me-2"></i>Piper TTS / saída embarcada</h2><div class="diag-small">Servidor de síntese e endpoint de reprodução</div></div><span class="status-tag status-planned">Não conectado</span></div>
                <div class="service-row mt-2"><div><div class="service-name">Perfil de referência</div><div class="service-desc">Voz em português brasileiro (configuração ilustrativa)</div></div><span class="status-tag status-info">pt_BR</span></div>
                <div class="service-row"><div><div class="service-name">Formato de áudio</div><div class="service-desc">Parâmetro de referência da integração</div></div><span class="status-tag status-info">22.050 Hz</span></div>
                <div class="service-row"><div><div class="service-name">Endpoint de áudio</div><div class="service-desc">Dispositivo demonstrativo</div></div><span class="diag-code">AUD-001</span></div>
                <div class="service-row"><div><div class="service-name">Reprodução real</div><div class="service-desc">Sem confirmação física consultada</div></div><span class="status-tag status-planned">Indisponível</span></div>
                <div class="diag-note mt-3">O processo Piper, a preparação da mensagem e a reprodução no nó serão estados separados quando a integração for implementada.</div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
            <div class="diag-card"><div class="diag-card-header"><div><h2 class="diag-section-title">Serviços e componentes</h2><div class="diag-subtitle">Estado da integração nesta versão.</div></div></div>
                <div class="diag-card-body">
                    <div class="service-row"><div><div class="service-name"><i class="bi bi-hdd-network text-info me-2"></i>API web / CodeIgniter</div><div class="service-desc">Páginas demonstrativas da aplicação.</div></div><span class="status-tag status-info">Interface local</span></div>
                    <div class="service-row"><div><div class="service-name"><i class="bi bi-database text-info me-2"></i>MySQL · iot_edge_center</div><div class="service-desc">A persistência desta página ainda não foi integrada.</div></div><span class="status-tag status-planned">Pendente</span></div>
                    <div class="service-row"><div><div class="service-name"><i class="bi bi-broadcast text-info me-2"></i>MQTT / Mosquitto</div><div class="service-desc">A página não abre conexão nem publica tópicos.</div></div><span class="status-tag status-planned">Sem conexão</span></div>
                    <div class="service-row"><div><div class="service-name"><i class="bi bi-cpu text-info me-2"></i>ESP32 / LoRa</div><div class="service-desc">Telemetria dos dispositivos não é consultada nesta view.</div></div><span class="status-tag status-planned">Sem coleta</span></div>
                    <div class="service-row"><div><div class="service-name"><i class="bi bi-camera-video text-info me-2"></i>Câmeras / drone</div><div class="service-desc">Fluxos de vídeo e telemetria externa não estão conectados.</div></div><span class="status-tag status-planned">Sem ingestão</span></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7">
            <div class="diag-card"><div class="diag-card-header"><div><h2 class="diag-section-title">Conectividade dos dispositivos</h2><div class="diag-subtitle">Amostras fictícias; RSSI/SNR não são estimativas de distância.</div></div><span class="diag-pill pill-demo">Valores simulados</span></div>
                <div class="diag-card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-5"><label for="deviceSearch" class="visually-hidden">Buscar dispositivo</label><input id="deviceSearch" class="form-control form-control-sm" type="search" placeholder="Buscar código, hardware ou local..."></div>
                        <div class="col-6 col-md-3"><label for="linkFilter" class="visually-hidden">Filtrar comunicação</label><select id="linkFilter" class="form-select form-select-sm"><option value="">Todos os meios</option><option>Wi-Fi / MQTT</option><option>Ethernet</option><option>LoRa / contingência</option><option>Rede IP / vídeo</option><option>Link do drone</option></select></div>
                        <div class="col-6 col-md-4"><label for="deviceStatusFilter" class="visually-hidden">Filtrar situação</label><select id="deviceStatusFilter" class="form-select form-select-sm"><option value="">Todas as situações</option><option>Exemplo estático</option><option>Não exposto</option><option>Planejado</option></select></div>
                    </div>
                    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Dispositivo / hardware</th><th>Local atual</th><th>Comunicação</th><th>RSSI / SNR</th><th>Último contato</th><th>Latência</th></tr></thead><tbody id="deviceConnectivityBody"></tbody></table></div>
                    <p class="diag-small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>As métricas de rádio só devem ser exibidas quando forem fornecidas pela interface de comunicação correspondente.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="diag-card mb-4">
        <div class="diag-card-header"><div><h2 class="diag-section-title">Console demonstrativo</h2><div class="diag-subtitle">Exemplos locais, não logs coletados de um broker real.</div></div><button type="button" class="btn btn-sm btn-outline-soft" id="appendLogButton"><i class="bi bi-plus-circle me-1"></i>Acrescentar exemplo</button></div>
        <div class="diag-card-body"><div class="terminal-box" id="diagTerminal" aria-live="polite"><div class="terminal-line">[demo] painel inicializado; nenhuma conexão externa foi aberta.</div><div class="terminal-line terminal-warn">[demo] broker MQTT aguardando integração do serviço de mensageria.</div><div class="terminal-line">[demo] dispositivo=EMB-GAS-001 local=LOC-SALA-CTRL (exemplo estático).</div><div class="terminal-line terminal-ok">[demo] configuração de áudio=22.050 Hz; reprodução não confirmada.</div></div></div>
    </section>
    <footer class="d-flex flex-column flex-md-row justify-content-between gap-2 pt-1"><span class="diag-small">IoT-Edge Center · Diagnóstico e Infraestrutura</span><span class="diag-small">Modo demonstrativo · Nenhuma telemetria real consultada</span></footer>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
    'use strict';
    const sampleDevices = [
        {id:'EMB-GAS-001',hardware:'Heltec LoRa32 V3 / MQ-4',location:'LOC-SALA-CTRL · Sala de controle',link:'Wi-Fi / MQTT',signal:'-58 dBm (Wi-Fi)',snr:'n/d',heartbeat:'Exemplo estático · 2 s',latency:'14 ms*',status:'Exemplo estático'},
        {id:'AUD-001',hardware:'Endpoint de áudio embarcado',location:'LOC-SALA-CTRL · Sala de controle',link:'Wi-Fi / MQTT',signal:'-54 dBm (Wi-Fi)',snr:'n/d',heartbeat:'Exemplo estático · 1 s',latency:'11 ms*',status:'Exemplo estático'},
        {id:'REL-001',hardware:'Controlador de relé / ESP32',location:'LOC-ZONA-TECH · Zona técnica',link:'Ethernet',signal:'n/d (IP)',snr:'n/d',heartbeat:'Planejado',latency:'n/d',status:'Planejado'},
        {id:'CAM-001',hardware:'Câmera IP',location:'LOC-CORREDOR-N · Corredor norte',link:'Rede IP / vídeo',signal:'Não exposto',snr:'Não exposto',heartbeat:'Planejado',latency:'n/d',status:'Não exposto'},
        {id:'DRONE-001',hardware:'DJI Mini 4 Pro',location:'LOC-EXT-01 · Área externa',link:'Link do drone',signal:'Não exposto',snr:'Não exposto',heartbeat:'Planejado',latency:'n/d',status:'Não exposto'}
    ];
    const logExamples = [
        '[demo] integração MySQL pendente; nenhum registro foi consultado.',
        '[demo] broker MQTT não conectado por esta página.',
        '[demo] RSSI e SNR não estimam distância física.',
        '[demo] comando solicitado não equivale a execução física confirmada.'
    ];
    let logIndex = 0;
    const byId = id => document.getElementById(id);
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));

    function updateClock() {
        const el = byId('diagLastUpdated');
        if (el) el.textContent = new Date().toLocaleString('pt-BR');
    }

    function renderConnectivity() {
        const searchEl = byId('deviceSearch');
        const linkEl = byId('linkFilter');
        const statusEl = byId('deviceStatusFilter');
        const tbody = byId('deviceConnectivityBody');
        if (!searchEl || !linkEl || !statusEl || !tbody) return;
        const query = searchEl.value.trim().toLowerCase();
        const link = linkEl.value;
        const state = statusEl.value;
        const filtered = sampleDevices.filter(device => {
            const text = [device.id,device.hardware,device.location,device.link,device.signal,device.snr].join(' ').toLowerCase();
            return text.includes(query) && (!link || device.link === link) && (!state || device.status === state);
        });
        if (!filtered.length) {
            tbody.innerHTML = '<tr><td colspan="6"><div class="diag-empty">Nenhum dispositivo corresponde aos filtros.</div></td></tr>';
            return;
        }
        tbody.innerHTML = filtered.map(device => `
            <tr>
                <td><div class="diag-code">${escapeHtml(device.id)}</div><div class="diag-small mt-1">${escapeHtml(device.hardware)}</div></td>
                <td><span class="small">${escapeHtml(device.location)}</span></td>
                <td><span class="small">${escapeHtml(device.link)}</span><div class="diag-small">${escapeHtml(device.status)}</div></td>
                <td><div class="small">${escapeHtml(device.signal)}</div><div class="diag-small">SNR: ${escapeHtml(device.snr)}</div></td>
                <td><span class="small">${escapeHtml(device.heartbeat)}</span></td>
                <td><span class="small">${escapeHtml(device.latency)}</span></td>
            </tr>`).join('');
    }

    function appendLog(message) {
        const terminal = byId('diagTerminal');
        if (!terminal) return;
        const line = document.createElement('div');
        line.className = 'terminal-line';
        line.textContent = `[${new Date().toLocaleTimeString('pt-BR')}] ${message}`;
        terminal.appendChild(line);
        while (terminal.children.length > 30) terminal.removeChild(terminal.firstChild);
        terminal.scrollTop = terminal.scrollHeight;
    }

    function refreshDemo() {
        updateClock();
        const cpu = 30 + Math.floor(Math.random() * 45);
        const ram = 640 + Math.floor(Math.random() * 300);
        const temperature = (45 + Math.random() * 12).toFixed(1);
        const fps = (10 + Math.random() * 7).toFixed(1);
        const latency = 8 + Math.floor(Math.random() * 30);
        const rate = 15 + Math.floor(Math.random() * 40);
        const clients = Math.floor(Math.random() * 9);
        const drop = (Math.random() * 0.8).toFixed(1);
        const pipeline = 35 + Math.floor(Math.random() * 55);
        byId('edgeCpu').textContent = `${cpu}%`;
        byId('edgeRam').textContent = `${ram} MB`;
        byId('edgeTemp').textContent = `${temperature.replace('.',',')} °C`;
        byId('edgeFps').textContent = `${fps.replace('.',',')} FPS`;
        byId('mqttLatency').textContent = `${latency} ms`;
        byId('mqttRate').textContent = `${rate}/s`;
        byId('mqttClients').textContent = clients;
        byId('mqttDrop').textContent = `${drop.replace('.',',')}%`;
        byId('edgePipelineLabel').textContent = `${pipeline}%`;
        const bar = byId('edgePipelineBar');
        bar.style.width = `${pipeline}%`;
        bar.setAttribute('aria-valuenow', pipeline);
        appendLog(logExamples[logIndex++ % logExamples.length]);
        alert('Amostras fictícias atualizadas. Nenhum serviço ou dispositivo real foi consultado.');
    }

    byId('refreshDiagButton')?.addEventListener('click', refreshDemo);
    byId('deviceSearch')?.addEventListener('input', renderConnectivity);
    byId('linkFilter')?.addEventListener('change', renderConnectivity);
    byId('deviceStatusFilter')?.addEventListener('change', renderConnectivity);
    byId('appendLogButton')?.addEventListener('click', () => appendLog(logExamples[logIndex++ % logExamples.length]));
    updateClock();
    renderConnectivity();
})();
</script>
<?= $this->endSection() ?>
