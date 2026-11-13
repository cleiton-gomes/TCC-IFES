<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Dispositivos
<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<style>
    /* =========================================
       ESTILOS DA PÁGINA
    ========================================= */

    .page-container {
        padding: 1.5rem;
    }

    .card-dark {
        background-color: #111827;
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 12px;
        color: #f8fafc;
    }

    .page-heading {
        color: #f8fafc;
    }

    .text-muted-custom {
        color: #94a3b8;
    }

    .table-dark-custom {
        background-color: #111827 !important;
        color: #f8fafc !important;
        border-color: rgba(255, 255, 255, .1);
        margin-bottom: 0;
    }

    .table-dark-custom th {
        background-color: #111827 !important;
        color: #cbd5e1 !important;
        border-bottom: 2px solid rgba(255, 255, 255, .15);
        white-space: nowrap;
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .035em;
        padding: 13px 11px;
    }

    .table-dark-custom tbody tr:nth-child(odd) td {
        background-color: #111827 !important;
        color: #f8fafc !important;
        border-bottom: 1px solid rgba(255, 255, 255, .05);
    }

    .table-dark-custom tbody tr:nth-child(even) td {
        background-color: rgba(255, 255, 255, .035) !important;
        color: #f8fafc !important;
        border-bottom: 1px solid rgba(255, 255, 255, .05);
    }

    .table-dark-custom tbody tr:hover td {
        background-color: rgba(59, 130, 246, .12) !important;
    }

    .table-dark-custom td {
        vertical-align: middle;
        padding: 12px 11px;
    }

    .form-control-dark,
    .form-select-dark {
        background-color: #1e293b;
        border: 1px solid rgba(255, 255, 255, .15);
        color: #fff;
        border-radius: 8px;
    }

    .form-control-dark::placeholder {
        color: #94a3b8;
    }

    .form-select-dark option {
        background-color: #1e293b;
        color: #fff;
    }

    .form-control-dark:focus,
    .form-select-dark:focus {
        background-color: #1e293b;
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, .25);
    }

    .form-label-custom {
        display: block;
        color: #cbd5e1;
        font-size: .82rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-section-title {
        color: #7dd3fc;
        font-size: .9rem;
        font-weight: 700;
        border-bottom: 1px solid rgba(255, 255, 255, .09);
        padding-bottom: 9px;
        margin-bottom: 15px;
    }

    .form-section-title i {
        margin-right: 7px;
    }

    .field-help {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: .74rem;
        line-height: 1.4;
    }

    .demo-warning {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(245, 158, 11, .09);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, .25);
        border-radius: 8px;
        padding: 8px 11px;
        font-size: .78rem;
        font-weight: 600;
    }

    .demo-note {
        background: rgba(56, 189, 248, .07);
        color: #bae6fd;
        border: 1px solid rgba(56, 189, 248, .18);
        border-radius: 8px;
        padding: 11px 12px;
        font-size: .79rem;
        line-height: 1.5;
    }

    /* Indicadores */

    .stat-card {
        height: 100%;
        background: #111827;
        border: 1px solid rgba(255, 255, 255, .09);
        border-radius: 12px;
        padding: 17px;
    }

    .stat-label {
        color: #94a3b8;
        font-size: .79rem;
        margin-bottom: 8px;
    }

    .stat-value {
        color: #f8fafc;
        font-size: 1.75rem;
        line-height: 1.2;
        font-weight: 750;
    }

    .stat-icon {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(59, 130, 246, .13);
        color: #60a5fa;
        flex-shrink: 0;
    }

    .stat-icon.success {
        background: rgba(34, 197, 94, .12);
        color: #4ade80;
    }

    .stat-icon.warning {
        background: rgba(245, 158, 11, .12);
        color: #fbbf24;
    }

    .stat-icon.secondary {
        background: rgba(148, 163, 184, .12);
        color: #cbd5e1;
    }

    /* Identificação do dispositivo */

    .device-code {
        color: #f8fafc;
        font-size: .83rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .device-name {
        color: #f8fafc;
        font-size: .84rem;
        font-weight: 600;
        margin-top: 3px;
        min-width: 130px;
    }

    .device-description {
        color: #94a3b8;
        font-size: .74rem;
        max-width: 250px;
        white-space: normal;
        overflow-wrap: anywhere;
        margin-top: 4px;
    }

    .device-category {
        display: inline-block;
        border: 1px solid rgba(56, 189, 248, .22);
        color: #7dd3fc;
        background: rgba(56, 189, 248, .08);
        border-radius: 6px;
        padding: 5px 8px;
        font-size: .73rem;
        white-space: nowrap;
    }

    /* Estados demonstrativos */

    .device-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        font-size: .73rem;
        font-weight: 650;
        padding: 5px 8px;
        white-space: nowrap;
    }

    .device-status::before {
        content: "";
        width: 6px;
        height: 6px;
        background: currentColor;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-online {
        color: #4ade80;
        background: rgba(34, 197, 94, .10);
        border: 1px solid rgba(34, 197, 94, .20);
    }

    .status-offline {
        color: #f87171;
        background: rgba(239, 68, 68, .10);
        border: 1px solid rgba(239, 68, 68, .20);
    }

    .status-manutencao {
        color: #fbbf24;
        background: rgba(245, 158, 11, .10);
        border: 1px solid rgba(245, 158, 11, .20);
    }

    .status-desconhecido {
        color: #c4b5fd;
        background: rgba(167, 139, 250, .10);
        border: 1px solid rgba(167, 139, 250, .20);
    }

    .status-inativo {
        color: #94a3b8;
        background: rgba(148, 163, 184, .10);
        border: 1px solid rgba(148, 163, 184, .20);
    }

    .protocol-badge {
        display: inline-block;
        color: #cbd5e1;
        background: rgba(148, 163, 184, .09);
        border: 1px solid rgba(148, 163, 184, .17);
        border-radius: 6px;
        padding: 5px 7px;
        font-size: .73rem;
    }

    .coordinate-code {
        color: #fbbf24;
        font-size: .76rem;
        white-space: nowrap;
    }

    .device-action {
        width: 31px;
        height: 31px;
        display: inline-grid;
        place-items: center;
        padding: 0;
        border-radius: 7px;
    }

    .device-action + .device-action {
        margin-left: 3px;
    }

    .device-empty {
        padding: 42px 20px !important;
        text-align: center;
        color: #94a3b8 !important;
    }

    .device-empty i {
        display: block;
        margin-bottom: 12px;
        color: #64748b;
        font-size: 2rem;
    }

    /* Modal de detalhes */

    .modal-dark .modal-content {
        background-color: #111827;
        color: #f8fafc;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
    }

    .modal-dark .modal-header,
    .modal-dark .modal-footer {
        border-color: rgba(255, 255, 255, .1);
    }

    .modal-dark .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .detail-box {
        height: 100%;
        padding: 13px;
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 9px;
        background: rgba(255, 255, 255, .025);
        overflow-wrap: anywhere;
    }

    .detail-label {
        color: #94a3b8;
        font-size: .74rem;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #f8fafc;
        font-size: .86rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .detail-json {
        color: #a7f3d0;
        background: #0b0f19;
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 8px;
        padding: 12px;
        font-size: .79rem;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        max-height: 300px;
        overflow: auto;
    }

    /* Responsividade */

    @media (max-width: 1199.98px) {
        .page-container {
            padding: 1rem;
        }
    }

    @media (max-width: 767.98px) {
        .page-container {
            padding: .8rem;
        }

        .stat-card {
            padding: 13px;
        }

        .stat-value {
            font-size: 1.45rem;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
        }
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<?php
/*
 * Os locais abaixo são fictícios.
 * Depois da criação de locais.php, esta lista será alimentada
 * pela tabela locais, por meio do controller.
 */
?>

<div class="container-fluid page-container">

    <!-- =========================================
         CABEÇALHO
    ========================================== -->

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="page-heading fw-bold mb-1">
                <i class="fa-solid fa-microchip text-primary me-2"></i>
                Dispositivos
            </h2>

            <p class="text-muted-custom mb-0">
                Cadastro, identificação, localização e gerenciamento dos equipamentos.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">

            <span class="demo-warning">
                <i class="fa-solid fa-flask"></i>
                Dados demonstrativos
            </span>

            <button
                type="button"
                class="btn btn-outline-secondary btn-sm"
                id="btnLimparFormulario"
            >
                <i class="fa-solid fa-eraser me-1"></i>
                Limpar formulário
            </button>

        </div>

    </div>


    <!-- Aviso de demonstração -->

    <div class="demo-note mb-4">
        <i class="fa-solid fa-circle-info me-2"></i>
        Esta tela ainda utiliza dados fictícios. Os cadastros, edições e desativações
        existem apenas na memória da página e não são gravados no MySQL nem enviados
        aos equipamentos.
    </div>


    <!-- =========================================
         INDICADORES
    ========================================== -->

    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Total cadastrado</div>
                        <div class="stat-value" id="statTotal">0</div>
                        <small class="text-muted-custom">Registros demonstrativos</small>
                    </div>

                    <div class="stat-icon">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Marcados online</div>
                        <div class="stat-value text-success" id="statOnline">0</div>
                        <small class="text-muted-custom">Estado fictício</small>
                    </div>

                    <div class="stat-icon success">
                        <i class="fa-solid fa-signal"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Com atenção</div>
                        <div class="stat-value text-warning" id="statAttention">0</div>
                        <small class="text-muted-custom">Offline ou manutenção</small>
                    </div>

                    <div class="stat-icon warning">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Inativos</div>
                        <div class="stat-value" id="statInactive">0</div>
                        <small class="text-muted-custom">Desativados no cadastro</small>
                    </div>

                    <div class="stat-icon secondary">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- =========================================
         FORMULÁRIO E LISTAGEM
    ========================================== -->

    <div class="row g-4">

        <!-- FORMULÁRIO -->

        <div class="col-12 col-xl-4">

            <div class="card-dark p-3 p-lg-4">

                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">

                    <h5 class="fw-bold mb-0 text-primary" id="formTitle">
                        <i class="fa-solid fa-circle-plus me-2"></i>
                        Cadastrar dispositivo
                    </h5>

                    <span
                        class="badge bg-secondary bg-opacity-25 text-secondary"
                        id="formMode"
                    >
                        Novo
                    </span>

                </div>


                <form id="formDispositivo" novalidate>

                    <!-- Código e nome -->

                    <h6 class="form-section-title">
                        <i class="fa-solid fa-id-card"></i>
                        Identificação
                    </h6>

                    <div class="mb-3">

                        <label for="campoCodigo" class="form-label-custom">
                            Código estável *
                        </label>

                        <input
                            type="text"
                            id="campoCodigo"
                            class="form-control form-control-dark"
                            maxlength="80"
                            pattern="[A-Za-z0-9_-]{3,80}"
                            placeholder="Ex.: EMB-GAS-001"
                            required
                        >

                        <small class="field-help">
                            Use letras sem acentos, números, hífen ou sublinhado.
                            O código não poderá ser alterado depois do cadastro.
                        </small>

                    </div>

                    <div class="mb-3">

                        <label for="campoNome" class="form-label-custom">
                            Nome do dispositivo *
                        </label>

                        <input
                            type="text"
                            id="campoNome"
                            class="form-control form-control-dark"
                            maxlength="150"
                            placeholder="Nome apresentado no sistema"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label for="campoCategoria" class="form-label-custom">
                            Categoria *
                        </label>

                        <select
                            id="campoCategoria"
                            class="form-select form-select-dark"
                            required
                        >
                            <option value="">Selecione uma categoria</option>
                            <option value="embarcado">Equipamento embarcado</option>
                            <option value="camera">Câmera</option>
                            <option value="drone">Drone</option>
                            <option value="gateway">Gateway / servidor de borda</option>
                            <option value="outro">Outro</option>
                        </select>

                    </div>


                    <!-- Hardware -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-microchip"></i>
                        Hardware
                    </h6>

                    <div class="mb-3">

                        <label for="nodeHardware" class="form-label-custom">
                            Modelo / placa base *
                        </label>

                        <input
                            type="text"
                            id="nodeHardware"
                            class="form-control form-control-dark"
                            list="listaModelos"
                            maxlength="100"
                            placeholder="Digite ou selecione o modelo"
                            required
                        >

                        <datalist id="listaModelos">
                            <option value="Heltec WiFi LoRa 32 V3 (ESP32-S3)">
                            <option value="Câmera IP RTSP/RTMP">
                            <option value="Drone DJI Mini 4 Pro">
                            <option value="Raspberry Pi 3B+">
                            <option value="W5500 Ethernet Gateway">
                        </datalist>

                    </div>

                    <div class="mb-3">

                        <label for="campoFabricante" class="form-label-custom">
                            Fabricante
                        </label>

                        <input
                            type="text"
                            id="campoFabricante"
                            class="form-control form-control-dark"
                            maxlength="100"
                            placeholder="Fabricante do equipamento"
                        >

                    </div>

                    <div class="mb-3">

                        <label for="nodeFunction" class="form-label-custom">
                            Finalidade demonstrativa
                        </label>

                        <select
                            id="nodeFunction"
                            class="form-select form-select-dark"
                        >
                        </select>

                        <small class="field-help">
                            Campo mantido do protótipo atual. A finalidade de sensores,
                            rádios e atuadores será organizada em componentes quando
                            avançarmos para essa funcionalidade.
                        </small>

                    </div>

                    <div class="mb-3">

                        <label for="campoDescricao" class="form-label-custom">
                            Descrição
                        </label>

                        <textarea
                            id="campoDescricao"
                            class="form-control form-control-dark"
                            rows="3"
                            maxlength="2000"
                            placeholder="Descrição funcional e observações do equipamento"
                        ></textarea>

                    </div>


                    <!-- Comunicação -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-tower-broadcast"></i>
                        Comunicação
                    </h6>

                    <div class="mb-3">

                        <label for="campoProtocolo" class="form-label-custom">
                            Protocolo principal
                        </label>

                        <select
                            id="campoProtocolo"
                            class="form-select form-select-dark"
                        >
                            <option value="">Não definido</option>
                            <option value="MQTT">MQTT</option>
                            <option value="LoRa">LoRa</option>
                            <option value="Ethernet">Ethernet</option>
                            <option value="Wi-Fi">Wi-Fi</option>
                            <option value="HTTP">HTTP / API</option>
                            <option value="RTSP">RTSP</option>
                            <option value="Outro">Outro</option>
                        </select>

                    </div>

                    <div class="mb-3">

                        <label for="campoTopico" class="form-label-custom">
                            Tópico MQTT principal
                        </label>

                        <input
                            type="text"
                            id="campoTopico"
                            class="form-control form-control-dark"
                            maxlength="255"
                            placeholder="iot-edge/devices/EMB-GAS-001/telemetry"
                        >

                    </div>

                    <div class="mb-3">

                        <label for="campoIdentificadorExterno" class="form-label-custom">
                            Identificador externo
                        </label>

                        <input
                            type="text"
                            id="campoIdentificadorExterno"
                            class="form-control form-control-dark"
                            maxlength="150"
                            placeholder="Identificador no equipamento ou sistema externo"
                        >

                    </div>


                    <!-- Localização -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-location-dot"></i>
                        Localização
                    </h6>

                    <div class="mb-3">

                        <label for="campoLocal" class="form-label-custom">
                            Local atual
                        </label>

                        <select
                            id="campoLocal"
                            class="form-select form-select-dark"
                        >
                            <option value="">Sem local definido</option>
                        </select>

                        <small class="field-help">
                            Os locais são fictícios nesta etapa.
                            O cadastro oficial será feito em Locais.
                        </small>

                    </div>


                    <!-- Coordenadas 3D provisórias -->

                    <div class="mb-3">

                        <label class="form-label-custom text-warning">
                            <i class="fa-solid fa-location-crosshairs me-1"></i>
                            Coordenadas 3D demonstrativas
                        </label>

                        <div class="demo-note mb-2">
                            Mantidas da versão anterior para não perder a referência
                            usada pelo gêmeo digital. Ainda precisamos definir seus
                            campos definitivos no banco.
                        </div>

                        <div class="row g-2">

                            <div class="col-4">
                                <label for="coordX" class="form-label-custom">X</label>

                                <input
                                    type="number"
                                    step="0.1"
                                    id="coordX"
                                    class="form-control form-control-dark"
                                    placeholder="X"
                                >
                            </div>

                            <div class="col-4">
                                <label for="coordY" class="form-label-custom">Y</label>

                                <input
                                    type="number"
                                    step="0.1"
                                    id="coordY"
                                    class="form-control form-control-dark"
                                    placeholder="Y"
                                >
                            </div>

                            <div class="col-4">
                                <label for="coordZ" class="form-label-custom">Z</label>

                                <input
                                    type="number"
                                    step="0.1"
                                    id="coordZ"
                                    class="form-control form-control-dark"
                                    placeholder="Z"
                                >
                            </div>

                        </div>

                    </div>


                    <!-- Estado -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-signal"></i>
                        Estado demonstrativo
                    </h6>

                    <div class="mb-4">

                        <label for="campoStatus" class="form-label-custom">
                            Estado operacional
                        </label>

                        <select
                            id="campoStatus"
                            class="form-select form-select-dark"
                            required
                        >
                            <option value="desconhecido">Desconhecido</option>
                            <option value="online">Online (demonstrativo)</option>
                            <option value="offline">Offline (demonstrativo)</option>
                            <option value="manutencao">Em manutenção</option>
                            <option value="inativo">Inativo</option>
                        </select>

                        <small class="field-help">
                            O estado é fictício. Em produção, a comunicação real será
                            responsável por atualizar as informações operacionais.
                        </small>

                    </div>


                    <!-- Configuração avançada -->

                    <div class="mb-4">

                        <button
                            class="btn btn-sm btn-outline-secondary w-100 text-start"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#configuracaoAvancada"
                            aria-expanded="false"
                            aria-controls="configuracaoAvancada"
                        >
                            <i class="fa-solid fa-code me-2"></i>
                            Configuração avançada (JSON)
                            <i class="fa-solid fa-chevron-down float-end mt-1"></i>
                        </button>

                        <div class="collapse mt-3" id="configuracaoAvancada">

                            <label for="campoConfiguracoes" class="form-label-custom">
                                Configurações adicionais
                            </label>

                            <textarea
                                id="campoConfiguracoes"
                                class="form-control form-control-dark"
                                rows="5"
                                spellcheck="false"
                                placeholder='{"frequencia_leitura_segundos": 5}'
                            ></textarea>

                            <small class="field-help">
                                Opcional. Se preenchido, o conteúdo deve ser JSON válido.
                                Na aplicação final, configurações específicas devem
                                preferencialmente ter formulários próprios.
                            </small>

                        </div>

                    </div>


                    <!-- Botões -->

                    <div class="d-flex flex-wrap gap-2">

                        <button
                            type="submit"
                            id="btnSalvar"
                            class="btn btn-primary fw-semibold flex-grow-1"
                        >
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            Salvar dispositivo
                        </button>

                        <button
                            type="button"
                            id="btnCancelar"
                            class="btn btn-outline-secondary d-none"
                        >
                            Cancelar edição
                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- =========================================
             LISTAGEM
        ========================================== -->

        <div class="col-12 col-xl-8">

            <div class="card-dark p-3 p-lg-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="fa-solid fa-list-check me-2 text-primary"></i>
                            Dispositivos cadastrados
                        </h5>

                        <small class="text-muted-custom">
                            Consulte, edite, visualize detalhes ou desative um registro.
                        </small>
                    </div>

                    <span class="badge bg-primary" id="countBadge">
                        0 dispositivos
                    </span>

                </div>


                <!-- Filtros -->

                <div class="row g-2 mb-3">

                    <div class="col-12 col-lg-5">

                        <label for="filtroTexto" class="form-label-custom">
                            Pesquisa
                        </label>

                        <input
                            type="search"
                            id="filtroTexto"
                            class="form-control form-control-dark"
                            placeholder="Código, nome, modelo ou fabricante..."
                        >

                    </div>

                    <div class="col-6 col-lg-3">

                        <label for="filtroCategoria" class="form-label-custom">
                            Categoria
                        </label>

                        <select
                            id="filtroCategoria"
                            class="form-select form-select-dark"
                        >
                            <option value="">Todas</option>
                            <option value="embarcado">Embarcado</option>
                            <option value="camera">Câmera</option>
                            <option value="drone">Drone</option>
                            <option value="gateway">Gateway</option>
                            <option value="outro">Outro</option>
                        </select>

                    </div>

                    <div class="col-6 col-lg-4">

                        <label for="filtroStatus" class="form-label-custom">
                            Estado
                        </label>

                        <select
                            id="filtroStatus"
                            class="form-select form-select-dark"
                        >
                            <option value="">Todos</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="manutencao">Em manutenção</option>
                            <option value="desconhecido">Desconhecido</option>
                            <option value="inativo">Inativo</option>
                        </select>

                    </div>

                    <div class="col-12">

                        <label for="filtroLocal" class="form-label-custom">
                            Local
                        </label>

                        <select
                            id="filtroLocal"
                            class="form-select form-select-dark"
                        >
                            <option value="">Todos os locais</option>
                        </select>

                    </div>

                    <div class="col-12 d-flex justify-content-end">

                        <button
                            type="button"
                            id="btnLimparFiltros"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="fa-solid fa-filter-circle-xmark me-1"></i>
                            Limpar filtros
                        </button>

                    </div>

                </div>


                <!-- Tabela -->

                <div class="table-responsive">

                    <table class="table table-dark-custom align-middle">

                        <thead>
                            <tr>
                                <th>Identificação</th>
                                <th>Categoria</th>
                                <th>Local</th>
                                <th>Protocolo</th>
                                <th>Estado</th>
                                <th>Último contato</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody id="nodeTableBody">
                            <!-- Preenchido pelo JavaScript -->
                        </tbody>

                    </table>

                </div>


                <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">

                    <small class="text-muted-custom" id="resultadoFiltro">
                        Carregando dispositivos demonstrativos...
                    </small>

                    <small class="text-muted-custom">
                        <i class="fa-solid fa-database me-1"></i>
                        Sem persistência no banco nesta etapa
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     MODAL DE DETALHES
========================================== -->

<div
    class="modal fade modal-dark"
    id="modalDetalhes"
    tabindex="-1"
    aria-labelledby="modalDetalhesTitulo"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-bold" id="modalDetalhesTitulo">
                        Detalhes do dispositivo
                    </h5>

                    <small class="text-muted-custom" id="modalDetalhesSubtitulo">
                        Informações demonstrativas
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>

            <div class="modal-body">

                <div class="row g-3" id="conteudoDetalhes">
                    <!-- Preenchido pelo JavaScript -->
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Fechar
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnEditarPelosDetalhes"
                >
                    <i class="fa-solid fa-pen me-2"></i>
                    Editar dispositivo
                </button>

            </div>

        </div>

    </div>
</div>


<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
(() => {
    'use strict';

    /*
     * =========================================
     * DADOS FICTÍCIOS
     * =========================================
     *
     * Os nomes dos campos refletem a proposta da tabela
     * dispositivos sempre que possível.
     *
     * finalidade e posicao3d são elementos do protótipo:
     * ainda não são campos confirmados da tabela dispositivos.
     *
     * Nenhuma alteração é enviada para o MySQL ou MQTT.
     */

    const locais = [
        {
            id: 'LOC-SALA-CTRL',
            nome: 'Sala de controle',
            caminho: 'Prédio A / Andar 1 / Sala de controle'
        },
        {
            id: 'LOC-CORREDOR-N',
            nome: 'Corredor norte',
            caminho: 'Prédio A / Andar 1 / Corredor norte'
        },
        {
            id: 'LOC-HALL-03',
            nome: 'Centro / Hall',
            caminho: 'Prédio A / Andar 3 / Centro / Hall'
        },
        {
            id: 'LOC-EXT-01',
            nome: 'Área externa leste',
            caminho: 'Área externa leste'
        },
        {
            id: 'LOC-PERIMETRO',
            nome: 'Perímetro externo',
            caminho: 'Área externa / Perímetro'
        }
    ];

    let dispositivos = [
        {
            codigo: 'EMB-GAS-001',
            nome: 'Estação de monitoramento de gás',
            descricao: 'Estação demonstrativa para leitura de gás e telemetria.',
            categoria: 'embarcado',
            modelo: 'Heltec WiFi LoRa 32 V3 (ESP32-S3)',
            fabricante: 'Heltec Automation',
            local_id: 'LOC-SALA-CTRL',
            protocolo_principal: 'MQTT',
            topico_mqtt: 'iot-edge/devices/EMB-GAS-001/telemetry',
            identificador_externo: 'ESP32S3-GAS-001',
            status_operacional: 'online',
            ultimo_contato_em: '21/10/2026 14:32 (fictício)',
            configuracoes: {
                intervalo_leitura_segundos: 5,
                limite_gas_demo: 2500,
                comunicacao: 'MQTT',
                radio_alternativo: 'LoRa'
            },
            finalidade: 'Monitoramento de gás e telemetria LoRa',
            posicao3d: { x: 2.0, y: 0.5, z: -1.5 }
        },
        {
            codigo: 'AUD-001',
            nome: 'Módulo de áudio de alerta',
            descricao: 'Equipamento demonstrativo para recepção e reprodução de avisos de áudio.',
            categoria: 'embarcado',
            modelo: 'Heltec WiFi LoRa 32 V3 (ESP32-S3)',
            fabricante: 'Heltec Automation',
            local_id: 'LOC-SALA-CTRL',
            protocolo_principal: 'MQTT',
            topico_mqtt: 'iot-edge/devices/AUD-001/commands',
            identificador_externo: 'ESP32S3-AUDIO-001',
            status_operacional: 'online',
            ultimo_contato_em: '21/10/2026 14:29 (fictício)',
            configuracoes: {
                audio: 'I2S',
                motor_tts: 'Piper',
                taxa_amostragem_hz: 22050,
                modo: 'demonstrativo'
            },
            finalidade: 'Sintetizador de áudio (I2S / Piper TTS)',
            posicao3d: { x: -3.5, y: 1.0, z: 4.2 }
        },
        {
            codigo: 'REL-001',
            nome: 'Módulo de acionamento de carga',
            descricao: 'Equipamento demonstrativo para representar uma saída de atuação.',
            categoria: 'embarcado',
            modelo: 'Heltec WiFi LoRa 32 V3 (ESP32-S3)',
            fabricante: 'Heltec Automation',
            local_id: 'LOC-PERIMETRO',
            protocolo_principal: 'MQTT',
            topico_mqtt: 'iot-edge/devices/REL-001/commands',
            identificador_externo: 'ESP32S3-RELAY-001',
            status_operacional: 'manutencao',
            ultimo_contato_em: '21/10/2026 12:10 (fictício)',
            configuracoes: {
                tipo_saida: 'rele',
                estado_ativo_baixo: true,
                modo: 'demonstrativo'
            },
            finalidade: 'Acionamento de carga (relé/MOSFET)',
            posicao3d: { x: 4.0, y: 3.0, z: -1.5 }
        },
        {
            codigo: 'CAM-001',
            nome: 'Câmera de inspeção',
            descricao: 'Fonte demonstrativa para monitoramento visual do corredor.',
            categoria: 'camera',
            modelo: 'Câmera IP RTSP/RTMP',
            fabricante: 'Fabricante demonstrativo',
            local_id: 'LOC-CORREDOR-N',
            protocolo_principal: 'RTSP',
            topico_mqtt: '',
            identificador_externo: 'CAMERA-IP-001',
            status_operacional: 'offline',
            ultimo_contato_em: '21/10/2026 11:50 (fictício)',
            configuracoes: {
                fluxo: 'RTSP',
                analise_visual: 'demonstrativa'
            },
            finalidade: 'Monitoramento estático de perímetro',
            posicao3d: { x: 0.0, y: 2.5, z: 3.0 }
        },
        {
            codigo: 'DRONE-001',
            nome: 'Drone de inspeção',
            descricao: 'Representação de equipamento móvel para inspeção.',
            categoria: 'drone',
            modelo: 'Drone DJI Mini 4 Pro',
            fabricante: 'DJI',
            local_id: 'LOC-EXT-01',
            protocolo_principal: 'Wi-Fi',
            topico_mqtt: '',
            identificador_externo: 'DRONE-DEMO-001',
            status_operacional: 'desconhecido',
            ultimo_contato_em: null,
            configuracoes: {
                tipo_operacao: 'inspecao',
                telemetria: 'demonstrativa'
            },
            finalidade: 'Inspeção móvel e telemetria',
            posicao3d: { x: 0.0, y: 0.0, z: 0.0 }
        }
    ];


    /*
     * =========================================
     * REFERÊNCIAS DOS ELEMENTOS
     * =========================================
     */

    const form = document.getElementById('formDispositivo');

    const campoCodigo = document.getElementById('campoCodigo');
    const campoNome = document.getElementById('campoNome');
    const campoCategoria = document.getElementById('campoCategoria');
    const campoModelo = document.getElementById('nodeHardware');
    const campoFabricante = document.getElementById('campoFabricante');
    const campoFinalidade = document.getElementById('nodeFunction');
    const campoDescricao = document.getElementById('campoDescricao');
    const campoProtocolo = document.getElementById('campoProtocolo');
    const campoTopico = document.getElementById('campoTopico');
    const campoIdentificadorExterno = document.getElementById('campoIdentificadorExterno');
    const campoLocal = document.getElementById('campoLocal');
    const campoStatus = document.getElementById('campoStatus');
    const campoConfiguracoes = document.getElementById('campoConfiguracoes');

    const campoX = document.getElementById('coordX');
    const campoY = document.getElementById('coordY');
    const campoZ = document.getElementById('coordZ');

    const tabela = document.getElementById('nodeTableBody');

    const filtroTexto = document.getElementById('filtroTexto');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const filtroStatus = document.getElementById('filtroStatus');
    const filtroLocal = document.getElementById('filtroLocal');

    const countBadge = document.getElementById('countBadge');
    const resultadoFiltro = document.getElementById('resultadoFiltro');

    const formTitle = document.getElementById('formTitle');
    const formMode = document.getElementById('formMode');
    const saveButton = document.getElementById('btnSalvar');
    const cancelButton = document.getElementById('btnCancelar');

    const modalDetalhesElement = document.getElementById('modalDetalhes');
    const modalDetalhes = new bootstrap.Modal(modalDetalhesElement);

    let codigoEmEdicao = null;
    let codigoEmDetalhes = null;

    let proximoNumero = 1;


    /*
     * =========================================
     * FUNÇÕES AUXILIARES
     * =========================================
     */

    function escaparHTML(valor) {
        return String(valor ?? '').replace(/[&<>"']/g, caractere => {
            const mapa = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            };

            return mapa[caractere];
        });
    }

    function valorOuTraco(valor) {
        return valor === null || valor === undefined || String(valor).trim() === ''
            ? '—'
            : String(valor);
    }

    function obterLocal(localId) {
        return locais.find(local => local.id === localId) || null;
    }

    function obterNomeLocal(localId) {
        const local = obterLocal(localId);
        return local ? local.nome : 'Sem local definido';
    }

    function obterCaminhoLocal(localId) {
        const local = obterLocal(localId);
        return local ? local.caminho : 'Sem local definido';
    }

    function obterRotuloCategoria(categoria) {
        const rotulos = {
            embarcado: 'Embarcado',
            camera: 'Câmera',
            drone: 'Drone',
            gateway: 'Gateway',
            outro: 'Outro'
        };

        return rotulos[categoria] || 'Outro';
    }

    function obterRotuloStatus(status) {
        const rotulos = {
            online: 'Online (demo)',
            offline: 'Offline (demo)',
            manutencao: 'Em manutenção',
            desconhecido: 'Desconhecido',
            inativo: 'Inativo'
        };

        return rotulos[status] || 'Desconhecido';
    }

    function obterClasseStatus(status) {
        const classes = {
            online: 'status-online',
            offline: 'status-offline',
            manutencao: 'status-manutencao',
            desconhecido: 'status-desconhecido',
            inativo: 'status-inativo'
        };

        return classes[status] || 'status-desconhecido';
    }

    function normalizarTexto(valor) {
        return String(valor ?? '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
    }

    function obterProximoCodigo() {
        let codigo;

        do {
            codigo = `EMB-NOVO-${String(proximoNumero++).padStart(3, '0')}`;
        } while (dispositivos.some(item => item.codigo === codigo));

        return codigo;
    }

    function formatarCoordenadas(posicao) {
        if (!posicao) return 'Não definidas';

        return `(${posicao.x}, ${posicao.y}, ${posicao.z})`;
    }


    /*
     * =========================================
     * POPULAR OS LOCAIS
     * =========================================
     *
     * O campo usa os códigos fictícios dos locais.
     * Na integração futura, será alimentado por locais.php.
     */

    function popularSelectLocais() {
        const selects = [campoLocal, filtroLocal];

        selects.forEach(select => {
            select.replaceChildren();

            const opcaoInicial = document.createElement('option');

            opcaoInicial.value = '';
            opcaoInicial.textContent = select === campoLocal
                ? 'Sem local definido'
                : 'Todos os locais';

            select.appendChild(opcaoInicial);

            locais.forEach(local => {
                const option = document.createElement('option');

                option.value = local.id;
                option.textContent = local.caminho;

                select.appendChild(option);
            });
        });
    }


    /*
     * =========================================
     * FINALIDADES POR MODELO
     * =========================================
     *
     * Mantém a funcionalidade dinâmica que existia
     * no formulário anterior.
     */

    function identificarModelo(modelo) {
        const texto = normalizarTexto(modelo);

        if (texto.includes('heltec') || texto.includes('esp32')) {
            return 'heltec';
        }

        if (texto.includes('camera')) {
            return 'camera';
        }

        if (texto.includes('drone')) {
            return 'drone';
        }

        if (texto.includes('raspberry')) {
            return 'raspberry';
        }

        if (texto.includes('w5500') || texto.includes('gateway')) {
            return 'gateway';
        }

        return 'outro';
    }

    function obterFinalidades(modelo) {
        const tipo = identificarModelo(modelo);

        const opcoes = {
            heltec: [
                'Monitoramento de gás e telemetria LoRa',
                'Sintetizador de áudio (I2S / Piper TTS)',
                'Acionamento de carga (relé/MOSFET)',
                'Comunicação LoRa e MQTT',
                'Monitoramento e atuação'
            ],

            camera: [
                'Monitoramento estático de perímetro',
                'Ingestão de vídeo para análise visual',
                'Monitoramento de área'
            ],

            drone: [
                'Inspeção móvel e telemetria',
                'Patrulhamento de pontos de inspeção',
                'Inspeção visual'
            ],

            raspberry: [
                'Processamento de IA / análise visual',
                'Servidor Piper TTS e orquestrador MQTT',
                'Servidor de borda'
            ],

            gateway: [
                'Gateway de comunicação Ethernet',
                'Conversão entre interfaces de rede',
                'Comunicação e encaminhamento de mensagens'
            ],

            outro: [
                'Monitoramento',
                'Comunicação',
                'Controle / atuação',
                'Outra finalidade demonstrativa'
            ]
        };

        return opcoes[tipo] || opcoes.outro;
    }

    function atualizarOpcoesFinalidade(finalidadeSelecionada = null) {
        const opcoes = obterFinalidades(campoModelo.value);
        campoFinalidade.replaceChildren();

        opcoes.forEach(finalidade => {
            const option = document.createElement('option');

            option.value = finalidade;
            option.textContent = finalidade;

            campoFinalidade.appendChild(option);
        });

        if (finalidadeSelecionada && opcoes.includes(finalidadeSelecionada)) {
            campoFinalidade.value = finalidadeSelecionada;
        }
    }


    /*
     * =========================================
     * ESTATÍSTICAS
     * =========================================
     */

    function atualizarEstatisticas() {
        const total = dispositivos.length;

        const online = dispositivos.filter(
            item => item.status_operacional === 'online'
        ).length;

        const atencao = dispositivos.filter(
            item => ['offline', 'manutencao'].includes(item.status_operacional)
        ).length;

        const inativos = dispositivos.filter(
            item => item.status_operacional === 'inativo'
        ).length;

        document.getElementById('statTotal').textContent = total;
        document.getElementById('statOnline').textContent = online;
        document.getElementById('statAttention').textContent = atencao;
        document.getElementById('statInactive').textContent = inativos;
    }


    /*
     * =========================================
     * FILTROS
     * =========================================
     */

    function obterDispositivosFiltrados() {
        const texto = normalizarTexto(filtroTexto.value);
        const categoria = filtroCategoria.value;
        const status = filtroStatus.value;
        const localId = filtroLocal.value;

        return dispositivos.filter(dispositivo => {
            const textoPesquisa = normalizarTexto([
                dispositivo.codigo,
                dispositivo.nome,
                dispositivo.descricao,
                dispositivo.modelo,
                dispositivo.fabricante,
                dispositivo.protocolo_principal,
                dispositivo.topico_mqtt,
                dispositivo.identificador_externo,
                dispositivo.finalidade,
                obterNomeLocal(dispositivo.local_id)
            ].join(' '));

            const correspondeTexto = texto === '' || textoPesquisa.includes(texto);
            const correspondeCategoria = categoria === '' || dispositivo.categoria === categoria;
            const correspondeStatus = status === '' || dispositivo.status_operacional === status;
            const correspondeLocal = localId === '' || dispositivo.local_id === localId;

            return (
                correspondeTexto &&
                correspondeCategoria &&
                correspondeStatus &&
                correspondeLocal
            );
        });
    }

    function limparFiltros() {
        filtroTexto.value = '';
        filtroCategoria.value = '';
        filtroStatus.value = '';
        filtroLocal.value = '';

        renderizarTabela();
    }


    /*
     * =========================================
     * CONSTRUÇÃO DA TABELA
     * =========================================
     */

    function criarBotaoAcao(icone, classe, acao, codigo, titulo) {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = `btn btn-sm ${classe} device-action`;
        button.dataset.action = acao;
        button.dataset.codigo = codigo;
        button.title = titulo;
        button.setAttribute('aria-label', `${titulo}: ${codigo}`);

        const icon = document.createElement('i');
        icon.className = `fa-solid ${icone}`;

        button.appendChild(icon);

        return button;
    }

    function criarLinhaDispositivo(dispositivo) {
        const tr = document.createElement('tr');
        tr.dataset.codigo = dispositivo.codigo;

        /* Identificação */

        const identificacao = document.createElement('td');

        const codigo = document.createElement('div');
        codigo.className = 'device-code';
        codigo.textContent = dispositivo.codigo;

        const nome = document.createElement('div');
        nome.className = 'device-name';
        nome.textContent = dispositivo.nome;

        const descricao = document.createElement('div');
        descricao.className = 'device-description';
        descricao.textContent = dispositivo.descricao || dispositivo.finalidade || '';

        identificacao.append(codigo, nome);

        if (descricao.textContent !== '') {
            identificacao.appendChild(descricao);
        }

        /* Categoria */

        const categoriaCell = document.createElement('td');

        const categoriaBadge = document.createElement('span');
        categoriaBadge.className = 'device-category';
        categoriaBadge.textContent = obterRotuloCategoria(dispositivo.categoria);

        categoriaCell.appendChild(categoriaBadge);

        /* Local */

        const localCell = document.createElement('td');

        const localNome = document.createElement('div');
        localNome.className = 'small fw-semibold';
        localNome.textContent = obterNomeLocal(dispositivo.local_id);

        const localCaminho = document.createElement('small');
        localCaminho.className = 'd-block text-muted-custom';
        localCaminho.textContent = obterCaminhoLocal(dispositivo.local_id);

        localCell.append(localNome, localCaminho);

        /* Protocolo */

        const protocoloCell = document.createElement('td');
        const protocoloBadge = document.createElement('span');

        protocoloBadge.className = 'protocol-badge';
        protocoloBadge.textContent = dispositivo.protocolo_principal || 'Não definido';

        protocoloCell.appendChild(protocoloBadge);

        /* Status */

        const statusCell = document.createElement('td');

        const statusBadge = document.createElement('span');
        statusBadge.className =
            `device-status ${obterClasseStatus(dispositivo.status_operacional)}`;

        statusBadge.textContent = obterRotuloStatus(dispositivo.status_operacional);

        statusCell.appendChild(statusBadge);

        /* Último contato */

        const contatoCell = document.createElement('td');

        const contatoText = document.createElement('small');
        contatoText.className = 'text-muted-custom';
        contatoText.textContent = dispositivo.ultimo_contato_em || 'Não informado';

        contatoCell.appendChild(contatoText);

        /* Ações */

        const acoesCell = document.createElement('td');
        acoesCell.className = 'text-end text-nowrap';

        acoesCell.appendChild(
            criarBotaoAcao(
                'fa-eye',
                'btn-outline-info',
                'detalhes',
                dispositivo.codigo,
                'Ver detalhes'
            )
        );

        acoesCell.appendChild(
            criarBotaoAcao(
                'fa-pen',
                'btn-outline-warning',
                'editar',
                dispositivo.codigo,
                'Editar dispositivo'
            )
        );

        if (dispositivo.status_operacional === 'inativo') {
            acoesCell.appendChild(
                criarBotaoAcao(
                    'fa-rotate-left',
                    'btn-outline-success',
                    'reativar',
                    dispositivo.codigo,
                    'Reativar dispositivo'
                )
            );
        } else {
            acoesCell.appendChild(
                criarBotaoAcao(
                    'fa-ban',
                    'btn-outline-danger',
                    'desativar',
                    dispositivo.codigo,
                    'Desativar dispositivo'
                )
            );
        }

        tr.append(
            identificacao,
            categoriaCell,
            localCell,
            protocoloCell,
            statusCell,
            contatoCell,
            acoesCell
        );

        return tr;
    }

    function renderizarTabela() {
        tabela.replaceChildren();

        const filtrados = obterDispositivosFiltrados();

        filtrados.forEach(dispositivo => {
            tabela.appendChild(criarLinhaDispositivo(dispositivo));
        });

        countBadge.textContent =
            `${filtrados.length} de ${dispositivos.length} dispositivos`;

        resultadoFiltro.textContent = filtrados.length === 0
            ? 'Nenhum dispositivo corresponde aos filtros.'
            : `Exibindo ${filtrados.length} dispositivo(s) demonstrativo(s).`;

        if (filtrados.length === 0) {
            const linhaVazia = document.createElement('tr');
            const celula = document.createElement('td');

            celula.colSpan = 7;
            celula.className = 'device-empty';

            const icone = document.createElement('i');
            icone.className = 'fa-solid fa-magnifying-glass';

            const titulo = document.createElement('div');
            titulo.className = 'fw-semibold text-light';
            titulo.textContent = 'Nenhum dispositivo encontrado';

            const texto = document.createElement('small');
            texto.textContent = 'Altere os filtros ou cadastre um novo dispositivo.';

            celula.append(icone, titulo, texto);
            linhaVazia.appendChild(celula);
            tabela.appendChild(linhaVazia);
        }

        atualizarEstatisticas();
    }


    /*
     * =========================================
     * FORMULÁRIO
     * =========================================
     */

    function coletarDadosFormulario() {
        const textoConfiguracoes = campoConfiguracoes.value.trim();

        let configuracoes = {};

        if (textoConfiguracoes !== '') {
            try {
                configuracoes = JSON.parse(textoConfiguracoes);
            } catch (erro) {
                campoConfiguracoes.focus();

                throw new Error(
                    'A configuração avançada não é um JSON válido. Verifique as aspas, vírgulas e chaves.'
                );
            }

            if (
                configuracoes === null ||
                Array.isArray(configuracoes) ||
                typeof configuracoes !== 'object'
            ) {
                throw new Error(
                    'A configuração avançada deve ser um objeto JSON, por exemplo: {"intervalo_segundos": 5}.'
                );
            }
        }

        const xTexto = campoX.value.trim();
        const yTexto = campoY.value.trim();
        const zTexto = campoZ.value.trim();

        const temAlgumaCoordenada =
            xTexto !== '' || yTexto !== '' || zTexto !== '';

        let posicao3d = null;

        if (temAlgumaCoordenada) {
            if (xTexto === '' || yTexto === '' || zTexto === '') {
                throw new Error(
                    'Preencha X, Y e Z ou deixe as três coordenadas vazias.'
                );
            }

            posicao3d = {
                x: Number(xTexto),
                y: Number(yTexto),
                z: Number(zTexto)
            };

            if (
                !Number.isFinite(posicao3d.x) ||
                !Number.isFinite(posicao3d.y) ||
                !Number.isFinite(posicao3d.z)
            ) {
                throw new Error('As coordenadas 3D precisam ser números válidos.');
            }
        }

        return {
            codigo: campoCodigo.value.trim(),
            nome: campoNome.value.trim(),
            descricao: campoDescricao.value.trim(),
            categoria: campoCategoria.value,
            modelo: campoModelo.value.trim(),
            fabricante: campoFabricante.value.trim(),
            local_id: campoLocal.value,
            protocolo_principal: campoProtocolo.value,
            topico_mqtt: campoTopico.value.trim(),
            identificador_externo: campoIdentificadorExterno.value.trim(),
            status_operacional: campoStatus.value,

            // Campo de apresentação provisório.
            // A finalidade por componente será tratada separadamente.
            finalidade: campoFinalidade.value,

            // Campo 3D provisório do protótipo.
            posicao3d,

            configuracoes
        };
    }

    function resetarFormulario() {
        form.reset();

        codigoEmEdicao = null;

        campoCodigo.readOnly = false;

        campoStatus.value = 'desconhecido';

        atualizarOpcoesFinalidade();

        formTitle.innerHTML =
            '<i class="fa-solid fa-circle-plus me-2"></i>Cadastrar dispositivo';

        formMode.textContent = 'Novo';

        saveButton.innerHTML =
            '<i class="fa-solid fa-floppy-disk me-2"></i>Salvar dispositivo';

        cancelButton.classList.add('d-none');

        const collapseElement = document.getElementById('configuracaoAvancada');
        bootstrap.Collapse.getOrCreateInstance(collapseElement, {
            toggle: false
        }).hide();

        const notaAnterior = form.querySelector('.form-feedback');

        if (notaAnterior) {
            notaAnterior.remove();
        }
    }

    function exibirFeedbackFormulario(mensagem, tipo = 'danger') {
        const notaAnterior = form.querySelector('.form-feedback');

        if (notaAnterior) {
            notaAnterior.remove();
        }

        const alerta = document.createElement('div');
        alerta.className =
            `alert alert-${tipo} form-feedback small py-2 mt-3 mb-3`;
        alerta.setAttribute('role', 'alert');
        alerta.textContent = mensagem;

        saveButton.parentElement.before(alerta);
    }

    function validarCodigoUnico(codigo) {
        return !dispositivos.some(dispositivo => {
            if (dispositivo.codigo === codigo && codigoEmEdicao === codigo) {
                return false;
            }

            return dispositivo.codigo.toLowerCase() === codigo.toLowerCase();
        });
    }

    function salvarDispositivo(event) {
        event.preventDefault();

        const notaAnterior = form.querySelector('.form-feedback');

        if (notaAnterior) {
            notaAnterior.remove();
        }

        if (!form.reportValidity()) {
            return;
        }

        let dados;

        try {
            dados = coletarDadosFormulario();
        } catch (erro) {
            exibirFeedbackFormulario(erro.message, 'danger');
            return;
        }

        if (!validarCodigoUnico(dados.codigo)) {
            exibirFeedbackFormulario(
                'Já existe um dispositivo com este código. Informe outro código.',
                'warning'
            );

            campoCodigo.focus();
            return;
        }

        const modeloOriginal = codigoEmEdicao;

        if (modeloOriginal) {
            const indice = dispositivos.findIndex(
                dispositivo => dispositivo.codigo === modeloOriginal
            );

            if (indice === -1) {
                exibirFeedbackFormulario(
                    'O dispositivo que estava sendo editado não foi encontrado.',
                    'danger'
                );

                resetarFormulario();
                return;
            }

            /*
             * O código é preservado durante a edição.
             * Último contato é um dado operacional, não alterado pelo formulário.
             * Se a posição 3D estiver vazia, preserva-se a posição anterior
             * apenas quando o campo inteiro estiver sem preenchimento.
             */

            const original = dispositivos[indice];

            dados.codigo = original.codigo;
            dados.ultimo_contato_em = original.ultimo_contato_em;

            dispositivos[indice] = dados;

        } else {
            /*
             * Não inventamos um contato de rede para um novo cadastro.
             * Na aplicação real, o campo será atualizado pela integração.
             */

            dados.ultimo_contato_em = null;

            dispositivos.push(dados);
        }

        resetarFormulario();
        renderizarTabela();

        exibirFeedbackFormulario(
            modeloOriginal
                ? 'Dispositivo atualizado na demonstração.'
                : 'Dispositivo cadastrado na demonstração.',
            'success'
        );

        /*
         * A mensagem acima é transitória no formulário.
         * Nenhuma persistência real foi executada.
         */
    }


    /*
     * =========================================
     * EDITAR
     * =========================================
     */

    function editarDispositivo(codigo) {
        const dispositivo = dispositivos.find(
            item => item.codigo === codigo
        );

        if (!dispositivo) {
            window.alert('O dispositivo não foi encontrado.');
            return;
        }

        codigoEmEdicao = codigo;

        campoCodigo.value = dispositivo.codigo;
        campoCodigo.readOnly = true;

        campoNome.value = dispositivo.nome || '';
        campoCategoria.value = dispositivo.categoria || '';
        campoModelo.value = dispositivo.modelo || '';
        campoFabricante.value = dispositivo.fabricante || '';
        campoDescricao.value = dispositivo.descricao || '';

        atualizarOpcoesFinalidade(dispositivo.finalidade || null);

        campoProtocolo.value = dispositivo.protocolo_principal || '';
        campoTopico.value = dispositivo.topico_mqtt || '';
        campoIdentificadorExterno.value = dispositivo.identificador_externo || '';
        campoLocal.value = dispositivo.local_id || '';
        campoStatus.value = dispositivo.status_operacional || 'desconhecido';

        campoConfiguracoes.value = JSON.stringify(
            dispositivo.configuracoes || {},
            null,
            2
        );

        campoX.value = dispositivo.posicao3d
            ? dispositivo.posicao3d.x
            : '';

        campoY.value = dispositivo.posicao3d
            ? dispositivo.posicao3d.y
            : '';

        campoZ.value = dispositivo.posicao3d
            ? dispositivo.posicao3d.z
            : '';

        formTitle.innerHTML =
            '<i class="fa-solid fa-pen-to-square me-2"></i>Editar dispositivo';

        formMode.textContent = 'Editando';

        saveButton.innerHTML =
            '<i class="fa-solid fa-floppy-disk me-2"></i>Atualizar dispositivo';

        cancelButton.classList.remove('d-none');

        const notaAnterior = form.querySelector('.form-feedback');

        if (notaAnterior) {
            notaAnterior.remove();
        }

        form.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }


    /*
     * =========================================
     * DETALHES
     * =========================================
     */

    function criarBlocoDetalhe(rotulo, valor, coluna = 'col-md-6') {
        const wrapper = document.createElement('div');
        wrapper.className = coluna;

        const box = document.createElement('div');
        box.className = 'detail-box';

        const label = document.createElement('div');
        label.className = 'detail-label';
        label.textContent = rotulo;

        const value = document.createElement('div');
        value.className = 'detail-value';
        value.textContent = valorOuTraco(valor);

        box.append(label, value);
        wrapper.appendChild(box);

        return wrapper;
    }

    function mostrarDetalhes(codigo) {
        const dispositivo = dispositivos.find(
            item => item.codigo === codigo
        );

        if (!dispositivo) {
            window.alert('O dispositivo não foi encontrado.');
            return;
        }

        codigoEmDetalhes = codigo;

        document.getElementById('modalDetalhesTitulo').textContent =
            dispositivo.nome;

        document.getElementById('modalDetalhesSubtitulo').textContent =
            dispositivo.codigo + ' · Dados demonstrativos';

        const conteudo = document.getElementById('conteudoDetalhes');
        conteudo.replaceChildren();

        conteudo.append(
            criarBlocoDetalhe('Código', dispositivo.codigo),
            criarBlocoDetalhe('Categoria', obterRotuloCategoria(dispositivo.categoria)),
            criarBlocoDetalhe('Estado', obterRotuloStatus(dispositivo.status_operacional)),
            criarBlocoDetalhe('Local atual', obterCaminhoLocal(dispositivo.local_id)),
            criarBlocoDetalhe('Modelo', dispositivo.modelo),
            criarBlocoDetalhe('Fabricante', dispositivo.fabricante),
            criarBlocoDetalhe('Protocolo principal', dispositivo.protocolo_principal),
            criarBlocoDetalhe('Último contato', dispositivo.ultimo_contato_em),
            criarBlocoDetalhe('Identificador externo', dispositivo.identificador_externo, 'col-12'),
            criarBlocoDetalhe('Tópico MQTT', dispositivo.topico_mqtt, 'col-12'),
            criarBlocoDetalhe('Descrição', dispositivo.descricao, 'col-12'),
            criarBlocoDetalhe('Finalidade demonstrativa', dispositivo.finalidade, 'col-12'),
            criarBlocoDetalhe(
                'Posição 3D provisória',
                formatarCoordenadas(dispositivo.posicao3d),
                'col-12'
            )
        );

        const tituloConfig = document.createElement('div');
        tituloConfig.className = 'col-12';

        const labelConfig = document.createElement('div');
        labelConfig.className = 'detail-label mb-2';
        labelConfig.textContent = 'Configurações demonstrativas';

        const configBox = document.createElement('pre');
        configBox.className = 'detail-json mb-0';
        configBox.textContent = JSON.stringify(
            dispositivo.configuracoes || {},
            null,
            2
        );

        tituloConfig.append(labelConfig, configBox);
        conteudo.appendChild(tituloConfig);

        modalDetalhes.show();
    }


    /*
     * =========================================
     * DESATIVAR / REATIVAR
     * =========================================
     *
     * A proposta do sistema privilegia a preservação
     * de históricos. Por isso, a ação normal é inativar
     * o dispositivo, em vez de excluir seu cadastro.
     */

    function alternarAtivo(codigo) {
        const dispositivo = dispositivos.find(
            item => item.codigo === codigo
        );

        if (!dispositivo) {
            window.alert('O dispositivo não foi encontrado.');
            return;
        }

        const vaiDesativar = dispositivo.status_operacional !== 'inativo';

        const mensagem = vaiDesativar
            ? `Deseja desativar "${dispositivo.nome}" (${dispositivo.codigo}) na demonstração?`
            : `Deseja reativar "${dispositivo.nome}" (${dispositivo.codigo}) na demonstração?`;

        if (!window.confirm(mensagem)) {
            return;
        }

        if (vaiDesativar) {
            dispositivo.status_operacional = 'inativo';
        } else {
            /*
             * Não presumimos que o equipamento voltou a estar online.
             * O estado passa para desconhecido até haver confirmação.
             */
            dispositivo.status_operacional = 'desconhecido';
        }

        renderizarTabela();

        if (codigoEmDetalhes === codigo) {
            mostrarDetalhes(codigo);
        }
    }


    /*
     * =========================================
     * EVENTOS
     * =========================================
     */

    campoModelo.addEventListener('input', () => {
        atualizarOpcoesFinalidade();
    });

    campoCategoria.addEventListener('change', () => {
        /*
         * A categoria é independente do modelo.
         * Mantemos a seleção do modelo/finalidade para que
         * mudar a categoria não descarte informação sem aviso.
         */
    });

    form.addEventListener('submit', salvarDispositivo);

    cancelButton.addEventListener('click', resetarFormulario);
    document.getElementById('btnLimparFormulario').addEventListener(
        'click',
        resetarFormulario
    );

    filtroTexto.addEventListener('input', renderizarTabela);
    filtroCategoria.addEventListener('change', renderizarTabela);
    filtroStatus.addEventListener('change', renderizarTabela);
    filtroLocal.addEventListener('change', renderizarTabela);

    document.getElementById('btnLimparFiltros').addEventListener(
        'click',
        limparFiltros
    );

    tabela.addEventListener('click', event => {
        const botao = event.target.closest('button[data-action]');

        if (!botao) {
            return;
        }

        const codigo = botao.dataset.codigo;
        const acao = botao.dataset.action;

        if (acao === 'detalhes') {
            mostrarDetalhes(codigo);
        } else if (acao === 'editar') {
            editarDispositivo(codigo);
        } else if (acao === 'desativar' || acao === 'reativar') {
            alternarAtivo(codigo);
        }
    });

    document.getElementById('btnEditarPelosDetalhes').addEventListener(
        'click',
        () => {
            if (!codigoEmDetalhes) {
                return;
            }

            const codigo = codigoEmDetalhes;

            modalDetalhes.hide();

            /*
             * Espera o modal de detalhes fechar antes de usar o formulário.
             */
            modalDetalhesElement.addEventListener(
                'hidden.bs.modal',
                function aoFechar() {
                    modalDetalhesElement.removeEventListener(
                        'hidden.bs.modal',
                        aoFechar
                    );

                    editarDispositivo(codigo);
                },
                { once: true }
            );
        }
    );


    /*
     * =========================================
     * INICIALIZAÇÃO
     * =========================================
     */

    popularSelectLocais();
    atualizarOpcoesFinalidade();
    renderizarTabela();

})();
</script>
<?= $this->endSection() ?>