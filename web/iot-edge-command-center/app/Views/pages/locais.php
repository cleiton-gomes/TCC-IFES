<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Locais
<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<style>
    /* =========================================
       ESTILO DA PÁGINA DE LOCAIS
    ========================================= */

    .page-container {
        padding: 1.5rem;
    }

    .card-dark {
        background: #111827;
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

    .form-label-custom {
        display: block;
        color: #cbd5e1;
        font-size: .82rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control-dark,
    .form-select-dark {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, .15);
        color: #fff;
        border-radius: 8px;
    }

    .form-control-dark::placeholder {
        color: #94a3b8;
    }

    .form-select-dark option {
        background: #1e293b;
        color: #fff;
    }

    .form-control-dark:focus,
    .form-select-dark:focus {
        background: #1e293b;
        color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, .25);
    }

    .field-help {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: .74rem;
        line-height: 1.45;
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

    .demo-warning {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        color: #fbbf24;
        background: rgba(245, 158, 11, .09);
        border: 1px solid rgba(245, 158, 11, .25);
        border-radius: 8px;
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

    /* =========================================
       INDICADORES
    ========================================= */

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
        color: #4ade80;
        background: rgba(34, 197, 94, .12);
    }

    .stat-icon.warning {
        color: #fbbf24;
        background: rgba(245, 158, 11, .12);
    }

    .stat-icon.secondary {
        color: #cbd5e1;
        background: rgba(148, 163, 184, .12);
    }

    /* =========================================
       TABELA DE LOCAIS
    ========================================= */

    .table-dark-custom {
        color: #f8fafc;
        border-color: rgba(255, 255, 255, .08);
        margin-bottom: 0;
    }

    .table-dark-custom thead th {
        background: #111827 !important;
        color: #cbd5e1 !important;
        border-bottom: 2px solid rgba(255, 255, 255, .13);
        padding: 13px 11px;
        white-space: nowrap;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .035em;
    }

    .table-dark-custom tbody td {
        background: #111827;
        color: #f8fafc;
        border-bottom: 1px solid rgba(255, 255, 255, .06);
        padding: 13px 11px;
        vertical-align: middle;
    }

    .table-dark-custom tbody tr:nth-child(even) td {
        background: rgba(255, 255, 255, .018);
    }

    .table-dark-custom tbody tr:hover td {
        background: rgba(59, 130, 246, .08);
    }

    .location-code {
        color: #f8fafc;
        font-size: .81rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .location-name {
        color: #f8fafc;
        font-size: .85rem;
        font-weight: 650;
    }

    .location-path {
        display: block;
        margin-top: 4px;
        color: #94a3b8;
        font-size: .72rem;
        line-height: 1.4;
        white-space: normal;
        min-width: 150px;
    }

    .location-type {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 6px;
        color: #7dd3fc;
        background: rgba(56, 189, 248, .08);
        border: 1px solid rgba(56, 189, 248, .2);
        font-size: .72rem;
        white-space: nowrap;
    }

    .location-parent {
        color: #cbd5e1;
        font-size: .79rem;
        min-width: 110px;
    }

    .location-count {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 32px;
        min-height: 26px;
        padding: 4px 8px;
        border-radius: 7px;
        color: #bfdbfe;
        background: rgba(59, 130, 246, .12);
        border: 1px solid rgba(59, 130, 246, .18);
        font-size: .78rem;
        font-weight: 700;
    }

    .location-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: .73rem;
        font-weight: 650;
        white-space: nowrap;
    }

    .location-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .location-active {
        color: #4ade80;
        background: rgba(34, 197, 94, .1);
        border: 1px solid rgba(34, 197, 94, .2);
    }

    .location-inactive {
        color: #94a3b8;
        background: rgba(148, 163, 184, .1);
        border: 1px solid rgba(148, 163, 184, .2);
    }

    .location-actions {
        white-space: nowrap;
        text-align: right;
    }

    .location-action {
        width: 31px;
        height: 31px;
        display: inline-grid;
        place-items: center;
        padding: 0;
        border-radius: 7px;
    }

    .location-action + .location-action {
        margin-left: 3px;
    }

    .empty-state {
        padding: 42px 20px !important;
        text-align: center;
        color: #94a3b8 !important;
    }

    .empty-state i {
        display: block;
        color: #64748b;
        font-size: 2rem;
        margin-bottom: 12px;
    }

    /* =========================================
       MODAL DE DETALHES
    ========================================= */

    .modal-dark .modal-content {
        background: #111827;
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
        background: rgba(255, 255, 255, .025);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 9px;
        padding: 13px;
        overflow-wrap: anywhere;
    }

    .detail-label {
        color: #94a3b8;
        font-size: .75rem;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #f8fafc;
        font-size: .86rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .detail-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .detail-list li {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, .07);
    }

    .detail-list li:last-child {
        border-bottom: 0;
    }

    .detail-code {
        display: block;
        padding: 11px;
        border-radius: 8px;
        background: #0b0f19;
        color: #a7f3d0;
        font-size: .78rem;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    /* =========================================
       RESPONSIVIDADE
    ========================================= */

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

<div class="container-fluid page-container">

    <!-- =========================================
         CABEÇALHO
    ========================================== -->

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="page-heading fw-bold mb-1">
                <i class="fa-solid fa-map-location-dot text-primary me-2"></i>
                Locais
            </h2>

            <p class="text-muted-custom mb-0">
                Cadastro e organização hierárquica dos ambientes e áreas de instalação.
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


    <div class="demo-note mb-4">
        <i class="fa-solid fa-circle-info me-2"></i>
        Nesta etapa, os locais são fictícios e as alterações existem apenas na página aberta.
        O banco ainda não está conectado. A hierarquia será usada posteriormente para relacionar
        locais, dispositivos, eventos e histórico de localização.
    </div>


    <!-- =========================================
         INDICADORES
    ========================================== -->

    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Total de locais</div>
                        <div class="stat-value" id="statTotal">0</div>
                        <small class="text-muted-custom">Registros fictícios</small>
                    </div>

                    <div class="stat-icon">
                        <i class="fa-solid fa-map"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Locais ativos</div>
                        <div class="stat-value text-success" id="statAtivos">0</div>
                        <small class="text-muted-custom">Disponíveis no cadastro</small>
                    </div>

                    <div class="stat-icon success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Locais raiz</div>
                        <div class="stat-value" id="statRaiz">0</div>
                        <small class="text-muted-custom">Sem local superior</small>
                    </div>

                    <div class="stat-icon warning">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="stat-label">Dispositivos associados</div>
                        <div class="stat-value" id="statDispositivos">0</div>
                        <small class="text-muted-custom">Associações fictícias</small>
                    </div>

                    <div class="stat-icon secondary">
                        <i class="fa-solid fa-microchip"></i>
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
                        Cadastrar local
                    </h5>

                    <span class="badge bg-secondary bg-opacity-25 text-secondary" id="formMode">
                        Novo
                    </span>

                </div>


                <form id="formLocal" novalidate>

                    <!-- Identificação -->

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
                            title="Use de 3 a 80 caracteres: letras sem acentos, números, hífen e sublinhado."
                            placeholder="Ex.: LOC-SALA-CTRL"
                            required
                        >

                        <small class="field-help">
                            Identificador único do local, independente do nome apresentado.
                        </small>

                    </div>

                    <div class="mb-3">

                        <label for="campoNome" class="form-label-custom">
                            Nome do local *
                        </label>

                        <input
                            type="text"
                            id="campoNome"
                            class="form-control form-control-dark"
                            maxlength="120"
                            placeholder="Ex.: Sala de controle"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label for="campoTipo" class="form-label-custom">
                            Tipo de local *
                        </label>

                        <select
                            id="campoTipo"
                            class="form-select form-select-dark"
                            required
                        >
                            <option value="">Selecione o tipo</option>
                            <option value="edificio">Edifício / prédio</option>
                            <option value="andar">Andar</option>
                            <option value="zona">Zona</option>
                            <option value="sala">Sala</option>
                            <option value="corredor">Corredor</option>
                            <option value="area_externa">Área externa</option>
                        </select>

                    </div>


                    <!-- Hierarquia -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-sitemap"></i>
                        Hierarquia do local
                    </h6>

                    <div class="mb-3">

                        <label for="campoPai" class="form-label-custom">
                            Local superior
                        </label>

                        <select
                            id="campoPai"
                            class="form-select form-select-dark"
                        >
                            <option value="">Nenhum (local raiz)</option>
                        </select>

                        <small class="field-help">
                            Exemplo: Prédio A → Andar 1 → Zona de monitoramento → Sala de controle.
                            Um local raiz não possui local superior.
                        </small>

                    </div>


                    <!-- Referência e descrição -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-location-dot"></i>
                        Referência física
                    </h6>

                    <div class="mb-3">

                        <label for="campoReferencia" class="form-label-custom">
                            Referência
                        </label>

                        <input
                            type="text"
                            id="campoReferencia"
                            class="form-control form-control-dark"
                            maxlength="190"
                            placeholder="Ex.: próximo à entrada principal"
                        >

                    </div>

                    <div class="mb-3">

                        <label for="campoDescricao" class="form-label-custom">
                            Descrição
                        </label>

                        <textarea
                            id="campoDescricao"
                            class="form-control form-control-dark"
                            rows="3"
                            placeholder="Descrição, finalidade e observações do local"
                        ></textarea>

                    </div>


                    <!-- Coordenadas geográficas -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-earth-americas"></i>
                        Coordenadas geográficas
                    </h6>

                    <div class="row g-2 mb-3">

                        <div class="col-6">

                            <label for="campoLatitude" class="form-label-custom">
                                Latitude
                            </label>

                            <input
                                type="number"
                                id="campoLatitude"
                                class="form-control form-control-dark"
                                step="0.0000001"
                                min="-90"
                                max="90"
                                placeholder="-20.1234567"
                            >

                        </div>

                        <div class="col-6">

                            <label for="campoLongitude" class="form-label-custom">
                                Longitude
                            </label>

                            <input
                                type="number"
                                id="campoLongitude"
                                class="form-control form-control-dark"
                                step="0.0000001"
                                min="-180"
                                max="180"
                                placeholder="-40.1234567"
                            >

                        </div>

                    </div>

                    <small class="field-help mb-4">
                        Coordenadas opcionais para localização geográfica.
                        Elas não substituem as coordenadas locais X/Y/Z do gêmeo digital.
                    </small>


                    <!-- Estado -->

                    <h6 class="form-section-title mt-4">
                        <i class="fa-solid fa-toggle-on"></i>
                        Estado do cadastro
                    </h6>

                    <div class="mb-4">

                        <label for="campoAtivo" class="form-label-custom">
                            Disponibilidade
                        </label>

                        <select
                            id="campoAtivo"
                            class="form-select form-select-dark"
                            required
                        >
                            <option value="true">Ativo</option>
                            <option value="false">Inativo</option>
                        </select>

                        <small class="field-help">
                            Desativar um local não apaga seus dados nem o histórico associado.
                        </small>

                    </div>


                    <!-- Botões -->

                    <div class="d-flex flex-wrap gap-2">

                        <button
                            type="submit"
                            id="btnSalvar"
                            class="btn btn-primary fw-semibold flex-grow-1"
                        >
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            Salvar local
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


        <!-- LISTAGEM -->

        <div class="col-12 col-xl-8">

            <div class="card-dark p-3 p-lg-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="fa-solid fa-list-tree me-2 text-primary"></i>
                            Estrutura de locais
                        </h5>

                        <small class="text-muted-custom">
                            Os locais são apresentados com seus níveis hierárquicos.
                        </small>
                    </div>

                    <span class="badge bg-primary" id="countBadge">
                        0 locais
                    </span>

                </div>


                <!-- Pesquisa e filtros -->

                <div class="row g-2 mb-4">

                    <div class="col-12 col-lg-5">

                        <label for="filtroTexto" class="form-label-custom">
                            Pesquisar local
                        </label>

                        <input
                            type="search"
                            id="filtroTexto"
                            class="form-control form-control-dark"
                            placeholder="Código, nome ou referência..."
                        >

                    </div>

                    <div class="col-6 col-lg-4">

                        <label for="filtroTipo" class="form-label-custom">
                            Tipo
                        </label>

                        <select
                            id="filtroTipo"
                            class="form-select form-select-dark"
                        >
                            <option value="">Todos</option>
                            <option value="edificio">Edifício</option>
                            <option value="andar">Andar</option>
                            <option value="zona">Zona</option>
                            <option value="sala">Sala</option>
                            <option value="corredor">Corredor</option>
                            <option value="area_externa">Área externa</option>
                        </select>

                    </div>

                    <div class="col-6 col-lg-3">

                        <label for="filtroAtivo" class="form-label-custom">
                            Estado
                        </label>

                        <select
                            id="filtroAtivo"
                            class="form-select form-select-dark"
                        >
                            <option value="">Todos</option>
                            <option value="true">Ativos</option>
                            <option value="false">Inativos</option>
                        </select>

                    </div>

                    <div class="col-12 d-flex justify-content-end">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                            id="btnLimparFiltros"
                        >
                            <i class="fa-solid fa-filter-circle-xmark me-1"></i>
                            Limpar filtros
                        </button>

                    </div>

                </div>


                <!-- Tabela hierárquica -->

                <div class="table-responsive">

                    <table class="table table-dark-custom align-middle">

                        <thead>
                            <tr>
                                <th>Local</th>
                                <th>Tipo</th>
                                <th>Local superior</th>
                                <th>Dispositivos*</th>
                                <th>Estado</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody id="tabelaLocais">
                            <!-- Preenchido pelo JavaScript -->
                        </tbody>

                    </table>

                </div>

                <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">

                    <small class="text-muted-custom" id="resultadoFiltro">
                        Carregando locais demonstrativos...
                    </small>

                    <small class="text-muted-custom">
                        * Contagem inclui os locais descendentes.
                    </small>

                </div>

            </div>


            <!-- LEGENDA HIERÁRQUICA -->

            <div class="card-dark p-3 mt-4">

                <h6 class="fw-bold mb-3">
                    <i class="fa-solid fa-diagram-project me-2 text-info"></i>
                    Como funciona a hierarquia
                </h6>

                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25 p-2">
                        Edifício
                    </span>

                    <i class="fa-solid fa-chevron-right text-secondary small"></i>

                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 p-2">
                        Andar
                    </span>

                    <i class="fa-solid fa-chevron-right text-secondary small"></i>

                    <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 p-2">
                        Zona
                    </span>

                    <i class="fa-solid fa-chevron-right text-secondary small"></i>

                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 p-2">
                        Sala / corredor
                    </span>

                </div>

                <p class="small text-muted-custom mt-3 mb-0">
                    Essa sequência ilustra uma possível organização. A hierarquia cadastrada
                    representa a estrutura do local e não determina, sozinha, a posição física
                    exata de cada dispositivo.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     MODAL DE DETALHES DO LOCAL
========================================== -->

<div
    class="modal fade modal-dark"
    id="modalDetalhesLocal"
    tabindex="-1"
    aria-labelledby="tituloDetalhesLocal"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-bold" id="tituloDetalhesLocal">
                        Detalhes do local
                    </h5>

                    <small class="text-muted-custom" id="subtituloDetalhesLocal">
                        Dados demonstrativos
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

                <div class="row g-3 mb-4" id="resumoDetalhesLocal">
                    <!-- Preenchido pelo JavaScript -->
                </div>

                <h6 class="fw-bold mb-2">
                    <i class="fa-solid fa-sitemap me-2 text-info"></i>
                    Locais descendentes
                </h6>

                <div class="detail-box mb-4" id="descendentesDetalhesLocal">
                    <!-- Preenchido pelo JavaScript -->
                </div>

                <h6 class="fw-bold mb-2">
                    <i class="fa-solid fa-microchip me-2 text-info"></i>
                    Dispositivos associados
                </h6>

                <div class="detail-box" id="dispositivosDetalhesLocal">
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
                    id="btnEditarDetalhesLocal"
                >
                    <i class="fa-solid fa-pen me-2"></i>
                    Editar local
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
     * LOCAIS FICTÍCIOS
     * =========================================
     *
     * A estrutura reflete a proposta da tabela locais:
     * - id
     * - codigo
     * - nome
     * - descricao
     * - tipo
     * - local_pai_id
     * - latitude
     * - longitude
     * - referencia
     * - ativo
     *
     * Os IDs são identificadores locais para esta demonstração.
     * Não representam IDs já existentes no banco de dados.
     */

    let locais = [
        {
            id: 1,
            codigo: 'LOC-PREDIO-A',
            nome: 'Prédio A',
            descricao: 'Edifício principal do ambiente monitorado.',
            tipo: 'edificio',
            local_pai_id: null,
            latitude: null,
            longitude: null,
            referencia: 'Entrada principal',
            ativo: true
        },
        {
            id: 2,
            codigo: 'LOC-ANDAR-01',
            nome: 'Andar 1',
            descricao: 'Primeiro andar do prédio principal.',
            tipo: 'andar',
            local_pai_id: 1,
            latitude: null,
            longitude: null,
            referencia: 'Térreo / primeiro nível cadastrado',
            ativo: true
        },
        {
            id: 3,
            codigo: 'LOC-SALA-CTRL',
            nome: 'Sala de controle',
            descricao: 'Ambiente demonstrativo para monitoramento e controle.',
            tipo: 'sala',
            local_pai_id: 2,
            latitude: null,
            longitude: null,
            referencia: 'Próximo à entrada do corredor norte',
            ativo: true
        },
        {
            id: 4,
            codigo: 'LOC-CORREDOR-N',
            nome: 'Corredor norte',
            descricao: 'Corredor utilizado na demonstração de monitoramento visual.',
            tipo: 'corredor',
            local_pai_id: 2,
            latitude: null,
            longitude: null,
            referencia: 'Ala norte',
            ativo: true
        },
        {
            id: 5,
            codigo: 'LOC-ZONA-TECH',
            nome: 'Zona técnica',
            descricao: 'Área destinada a equipamentos e infraestrutura.',
            tipo: 'zona',
            local_pai_id: 2,
            latitude: null,
            longitude: null,
            referencia: 'Área técnica do primeiro andar',
            ativo: true
        },
        {
            id: 6,
            codigo: 'LOC-EXT-01',
            nome: 'Área externa leste',
            descricao: 'Área externa usada na demonstração de inspeções móveis.',
            tipo: 'area_externa',
            local_pai_id: null,
            latitude: -20.1234567,
            longitude: -40.1234567,
            referencia: 'Perímetro leste demonstrativo',
            ativo: true
        },
        {
            id: 7,
            codigo: 'LOC-ANDAR-02',
            nome: 'Andar 2',
            descricao: 'Segundo andar demonstrativo.',
            tipo: 'andar',
            local_pai_id: 1,
            latitude: null,
            longitude: null,
            referencia: 'Segundo nível',
            ativo: false
        }
    ];

    /*
     * Relação fictícia para demonstrar a associação entre
     * dispositivos e locais. Na integração real, virá da
     * tabela dispositivos, usando dispositivos.local_id.
     */

    const dispositivosDemo = [
        {
            codigo: 'EMB-GAS-001',
            nome: 'Estação de monitoramento de gás',
            local_id: 3
        },
        {
            codigo: 'AUD-001',
            nome: 'Módulo de áudio de alerta',
            local_id: 3
        },
        {
            codigo: 'CAM-001',
            nome: 'Câmera de inspeção',
            local_id: 4
        },
        {
            codigo: 'REL-001',
            nome: 'Módulo de acionamento',
            local_id: 5
        },
        {
            codigo: 'DRONE-001',
            nome: 'Drone de inspeção',
            local_id: 6
        }
    ];

    let proximoId = 8;
    let codigoEmEdicao = null;
    let codigoEmDetalhes = null;


    /*
     * =========================================
     * ELEMENTOS DA PÁGINA
     * =========================================
     */

    const form = document.getElementById('formLocal');

    const campoCodigo = document.getElementById('campoCodigo');
    const campoNome = document.getElementById('campoNome');
    const campoTipo = document.getElementById('campoTipo');
    const campoPai = document.getElementById('campoPai');
    const campoReferencia = document.getElementById('campoReferencia');
    const campoDescricao = document.getElementById('campoDescricao');
    const campoLatitude = document.getElementById('campoLatitude');
    const campoLongitude = document.getElementById('campoLongitude');
    const campoAtivo = document.getElementById('campoAtivo');

    const filtroTexto = document.getElementById('filtroTexto');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroAtivo = document.getElementById('filtroAtivo');

    const tabela = document.getElementById('tabelaLocais');
    const countBadge = document.getElementById('countBadge');
    const resultadoFiltro = document.getElementById('resultadoFiltro');

    const formTitle = document.getElementById('formTitle');
    const formMode = document.getElementById('formMode');
    const saveButton = document.getElementById('btnSalvar');
    const cancelButton = document.getElementById('btnCancelar');

    const modalDetalhesElement = document.getElementById('modalDetalhesLocal');
    const modalDetalhes = new bootstrap.Modal(modalDetalhesElement);


    /*
     * =========================================
     * FUNÇÕES AUXILIARES
     * =========================================
     */

    function normalizarTexto(valor) {
        return String(valor ?? '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
    }

    function valorOuTraco(valor) {
        return valor === null || valor === undefined || String(valor).trim() === ''
            ? '—'
            : String(valor);
    }

    function obterLocal(id) {
        if (id === null || id === undefined || id === '') {
            return null;
        }

        return locais.find(local => local.id === Number(id)) || null;
    }

    function obterRotuloTipo(tipo) {
        const rotulos = {
            edificio: 'Edifício',
            andar: 'Andar',
            zona: 'Zona',
            sala: 'Sala',
            corredor: 'Corredor',
            area_externa: 'Área externa'
        };

        return rotulos[tipo] || 'Não definido';
    }

    function obterRotuloEstado(ativo) {
        return ativo ? 'Ativo' : 'Inativo';
    }

    function criarElemento(tag, classe, texto) {
        const elemento = document.createElement(tag);

        if (classe) {
            elemento.className = classe;
        }

        if (texto !== undefined && texto !== null) {
            elemento.textContent = texto;
        }

        return elemento;
    }

    function criarBotaoAcao(icone, classe, acao, id, titulo) {
        const botao = document.createElement('button');

        botao.type = 'button';
        botao.className = `btn btn-sm ${classe} location-action`;
        botao.dataset.action = acao;
        botao.dataset.id = String(id);
        botao.title = titulo;
        botao.setAttribute('aria-label', `${titulo}: ${id}`);

        const iconeElemento = document.createElement('i');
        iconeElemento.className = `fa-solid ${icone}`;

        botao.appendChild(iconeElemento);

        return botao;
    }


    /*
     * =========================================
     * HIERARQUIA DE LOCAIS
     * =========================================
     */

    function obterFilhos(idPai) {
        const chavePai = idPai === null ? null : Number(idPai);

        return locais
            .filter(local => local.local_pai_id === chavePai)
            .sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR'));
    }

    function obterDescendentes(idLocal) {
        const resultado = [];
        const visitados = new Set();

        function percorrer(idAtual) {
            obterFilhos(idAtual).forEach(filho => {
                if (visitados.has(filho.id)) {
                    return;
                }

                visitados.add(filho.id);
                resultado.push(filho);

                percorrer(filho.id);
            });
        }

        percorrer(Number(idLocal));

        return resultado;
    }

    function obterCaminhoCompleto(local) {
        if (!local) {
            return 'Sem local superior';
        }

        const partes = [];
        const visitados = new Set();

        let atual = local;

        while (atual && !visitados.has(atual.id)) {
            visitados.add(atual.id);
            partes.unshift(atual.nome);

            atual = obterLocal(atual.local_pai_id);
        }

        return partes.join(' / ');
    }

    function obterNivel(local) {
        let nivel = 0;
        let atual = local;

        const visitados = new Set();

        while (
            atual &&
            atual.local_pai_id !== null &&
            !visitados.has(atual.id)
        ) {
            visitados.add(atual.id);
            nivel++;

            atual = obterLocal(atual.local_pai_id);
        }

        return nivel;
    }

    function ordenarHierarquia() {
        const ordenados = [];
        const visitados = new Set();

        function percorrer(idPai) {
            obterFilhos(idPai).forEach(local => {
                if (visitados.has(local.id)) {
                    return;
                }

                visitados.add(local.id);
                ordenados.push(local);

                percorrer(local.id);
            });
        }

        percorrer(null);

        /*
         * Recuperação defensiva para dados fictícios inconsistentes.
         * Evita deixar um registro invisível caso sua hierarquia seja inválida.
         */

        locais.forEach(local => {
            if (!visitados.has(local.id)) {
                visitados.add(local.id);
                ordenados.push(local);
                percorrer(local.id);
            }
        });

        return ordenados;
    }


    /*
     * =========================================
     * POPULAR SELETOR DO LOCAL SUPERIOR
     * =========================================
     *
     * Ao editar, excluímos da lista:
     * - o próprio local;
     * - seus descendentes.
     *
     * Isso evita criar ciclos na hierarquia.
     */

    function popularSelectPai(valorSelecionado = '') {
        const valorAnterior = valorSelecionado || '';
        const localAtual = codigoEmEdicao
            ? locais.find(local => local.codigo === codigoEmEdicao)
            : null;

        const bloqueados = new Set();

        if (localAtual) {
            bloqueados.add(localAtual.id);

            obterDescendentes(localAtual.id).forEach(descendente => {
                bloqueados.add(descendente.id);
            });
        }

        campoPai.replaceChildren();

        const raiz = document.createElement('option');
        raiz.value = '';
        raiz.textContent = 'Nenhum (local raiz)';
        campoPai.appendChild(raiz);

        ordenarHierarquia().forEach(local => {
            if (bloqueados.has(local.id)) {
                return;
            }

            const option = document.createElement('option');

            option.value = String(local.id);

            const nivel = obterNivel(local);
            const prefixo = nivel > 0 ? `${'— '.repeat(nivel)}` : '';

            option.textContent =
                `${prefixo}${local.nome} (${local.codigo})` +
                (local.ativo ? '' : ' [inativo]');

            option.disabled = !local.ativo;

            campoPai.appendChild(option);
        });

        campoPai.value = valorAnterior;
    }


    /*
     * =========================================
     * CONTAGEM DE DISPOSITIVOS
     * =========================================
     */

    function obterIdsDoSubarvore(idLocal) {
        const ids = new Set([Number(idLocal)]);

        obterDescendentes(idLocal).forEach(descendente => {
            ids.add(descendente.id);
        });

        return ids;
    }

    function contarDispositivosNoLocal(idLocal) {
        const ids = obterIdsDoSubarvore(idLocal);

        return dispositivosDemo.filter(dispositivo => {
            return ids.has(Number(dispositivo.local_id));
        }).length;
    }

    function obterDispositivosDoLocal(idLocal) {
        return dispositivosDemo.filter(dispositivo => {
            return Number(dispositivo.local_id) === Number(idLocal);
        });
    }


    /*
     * =========================================
     * INDICADORES
     * =========================================
     */

    function atualizarEstatisticas() {
        const total = locais.length;

        const ativos = locais.filter(local => local.ativo).length;

        const raizes = locais.filter(
            local => local.local_pai_id === null
        ).length;

        const idsLocais = new Set(locais.map(local => local.id));

        const dispositivosAssociados = dispositivosDemo.filter(
            dispositivo => idsLocais.has(Number(dispositivo.local_id))
        ).length;

        document.getElementById('statTotal').textContent = total;
        document.getElementById('statAtivos').textContent = ativos;
        document.getElementById('statRaiz').textContent = raizes;
        document.getElementById('statDispositivos').textContent =
            dispositivosAssociados;
    }


    /*
     * =========================================
     * FILTROS
     * =========================================
     */

    function obterLocaisFiltrados() {
        const texto = normalizarTexto(filtroTexto.value);
        const tipo = filtroTipo.value;
        const estado = filtroAtivo.value;

        return ordenarHierarquia().filter(local => {
            const caminho = obterCaminhoCompleto(local);

            const textoPesquisa = normalizarTexto([
                local.codigo,
                local.nome,
                local.descricao,
                local.referencia,
                caminho,
                obterRotuloTipo(local.tipo)
            ].join(' '));

            const correspondeTexto =
                texto === '' || textoPesquisa.includes(texto);

            const correspondeTipo =
                tipo === '' || local.tipo === tipo;

            const correspondeEstado =
                estado === '' || String(local.ativo) === estado;

            return (
                correspondeTexto &&
                correspondeTipo &&
                correspondeEstado
            );
        });
    }

    function limparFiltros() {
        filtroTexto.value = '';
        filtroTipo.value = '';
        filtroAtivo.value = '';

        renderizarTabela();
    }


    /*
     * =========================================
     * RENDERIZAÇÃO DA TABELA
     * =========================================
     */

    function renderizarTabela() {
        tabela.replaceChildren();

        const filtrados = obterLocaisFiltrados();

        filtrados.forEach(local => {
            const tr = document.createElement('tr');

            /* Código e nome */

            const celulaNome = document.createElement('td');

            const codigo = criarElemento(
                'div',
                'location-code',
                local.codigo
            );

            const nome = criarElemento(
                'div',
                'location-name'
            );

            nome.style.paddingLeft = `${obterNivel(local) * 13}px`;

            const indicadorNivel = document.createElement('i');
            indicadorNivel.className =
                'fa-solid fa-location-dot text-info me-2';

            nome.append(
                indicadorNivel,
                document.createTextNode(local.nome)
            );

            const caminho = criarElemento(
                'small',
                'location-path',
                obterCaminhoCompleto(local)
            );

            celulaNome.append(codigo, nome, caminho);

            /* Tipo */

            const celulaTipo = document.createElement('td');

            const tipo = criarElemento(
                'span',
                'location-type',
                obterRotuloTipo(local.tipo)
            );

            celulaTipo.appendChild(tipo);

            /* Local superior */

            const celulaPai = document.createElement('td');

            const pai = obterLocal(local.local_pai_id);

            celulaPai.appendChild(
                criarElemento(
                    'span',
                    'location-parent',
                    pai ? pai.nome : '— Local raiz —'
                )
            );

            /* Quantidade de dispositivos no local e descendentes */

            const celulaContagem = document.createElement('td');

            const quantidade = criarElemento(
                'span',
                'location-count',
                String(contarDispositivosNoLocal(local.id))
            );

            quantidade.title =
                'Contagem incluindo dispositivos associados aos locais descendentes';

            celulaContagem.appendChild(quantidade);

            /* Estado */

            const celulaEstado = document.createElement('td');

            const estado = criarElemento(
                'span',
                `location-status ${local.ativo ? 'location-active' : 'location-inactive'}`,
                obterRotuloEstado(local.ativo)
            );

            celulaEstado.appendChild(estado);

            /* Ações */

            const celulaAcoes = document.createElement('td');
            celulaAcoes.className = 'location-actions';

            celulaAcoes.appendChild(
                criarBotaoAcao(
                    'fa-eye',
                    'btn-outline-info',
                    'detalhes',
                    local.id,
                    'Ver detalhes'
                )
            );

            celulaAcoes.appendChild(
                criarBotaoAcao(
                    'fa-pen',
                    'btn-outline-warning',
                    'editar',
                    local.id,
                    'Editar local'
                )
            );

            celulaAcoes.appendChild(
                criarBotaoAcao(
                    local.ativo ? 'fa-ban' : 'fa-rotate-left',
                    local.ativo ? 'btn-outline-danger' : 'btn-outline-success',
                    'alternar',
                    local.id,
                    local.ativo ? 'Desativar local' : 'Reativar local'
                )
            );

            tr.append(
                celulaNome,
                celulaTipo,
                celulaPai,
                celulaContagem,
                celulaEstado,
                celulaAcoes
            );

            tabela.appendChild(tr);
        });

        countBadge.textContent =
            `${filtrados.length} de ${locais.length} locais`;

        resultadoFiltro.textContent = filtrados.length > 0
            ? `Exibindo ${filtrados.length} local(is) demonstrativo(s).`
            : 'Nenhum local corresponde aos filtros.';

        if (filtrados.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 6;
            td.className = 'empty-state';

            const icone = document.createElement('i');
            icone.className = 'fa-solid fa-map-location-dot';

            const titulo = criarElemento(
                'div',
                'fw-semibold text-light',
                'Nenhum local encontrado'
            );

            const texto = criarElemento(
                'small',
                '',
                'Altere os filtros ou cadastre um novo local.'
            );

            td.append(icone, titulo, texto);
            tr.appendChild(td);
            tabela.appendChild(tr);
        }

        atualizarEstatisticas();
    }


    /*
     * =========================================
     * FORMULÁRIO
     * =========================================
     */

    function coletarDadosFormulario() {
        const latitudeTexto = campoLatitude.value.trim();
        const longitudeTexto = campoLongitude.value.trim();

        let latitude = null;
        let longitude = null;

        const informouLatitude = latitudeTexto !== '';
        const informouLongitude = longitudeTexto !== '';

        if (informouLatitude !== informouLongitude) {
            throw new Error(
                'Informe latitude e longitude juntas ou deixe ambas vazias.'
            );
        }

        if (informouLatitude && informouLongitude) {
            latitude = Number(latitudeTexto);
            longitude = Number(longitudeTexto);

            if (
                !Number.isFinite(latitude) ||
                latitude < -90 ||
                latitude > 90
            ) {
                throw new Error(
                    'A latitude deve ser um valor entre -90 e 90.'
                );
            }

            if (
                !Number.isFinite(longitude) ||
                longitude < -180 ||
                longitude > 180
            ) {
                throw new Error(
                    'A longitude deve ser um valor entre -180 e 180.'
                );
            }
        }

        const paiTexto = campoPai.value;

        return {
            codigo: campoCodigo.value.trim(),
            nome: campoNome.value.trim(),
            tipo: campoTipo.value,
            local_pai_id: paiTexto === '' ? null : Number(paiTexto),
            referencia: campoReferencia.value.trim(),
            descricao: campoDescricao.value.trim(),
            latitude,
            longitude,
            ativo: campoAtivo.value === 'true'
        };
    }

    function validarCodigoUnico(codigo) {
        return !locais.some(local => {
            if (
                codigoEmEdicao === local.codigo &&
                local.codigo === codigo
            ) {
                return false;
            }

            return local.codigo.toLowerCase() === codigo.toLowerCase();
        });
    }

    function exibirFeedback(mensagem, tipo = 'danger') {
        const anterior = form.querySelector('.form-feedback');

        if (anterior) {
            anterior.remove();
        }

        const alerta = document.createElement('div');
        alerta.className =
            `alert alert-${tipo} form-feedback small py-2 mt-3 mb-3`;
        alerta.setAttribute('role', 'alert');
        alerta.textContent = mensagem;

        saveButton.parentElement.before(alerta);
    }

    function limparFeedback() {
        const anterior = form.querySelector('.form-feedback');

        if (anterior) {
            anterior.remove();
        }
    }

    function resetarFormulario() {
        form.reset();

        codigoEmEdicao = null;

        campoCodigo.readOnly = false;
        campoAtivo.value = 'true';

        popularSelectPai('');

        formTitle.innerHTML =
            '<i class="fa-solid fa-circle-plus me-2"></i>Cadastrar local';

        formMode.textContent = 'Novo';

        saveButton.innerHTML =
            '<i class="fa-solid fa-floppy-disk me-2"></i>Salvar local';

        cancelButton.classList.add('d-none');

        limparFeedback();
    }

    function salvarLocal(event) {
        event.preventDefault();

        limparFeedback();

        if (!form.reportValidity()) {
            return;
        }

        let dados;

        try {
            dados = coletarDadosFormulario();
        } catch (erro) {
            exibirFeedback(erro.message);
            return;
        }

        if (!validarCodigoUnico(dados.codigo)) {
            exibirFeedback(
                'Já existe um local com este código. Informe outro código.',
                'warning'
            );

            campoCodigo.focus();
            return;
        }

        if (
            dados.local_pai_id !== null &&
            !obterLocal(dados.local_pai_id)
        ) {
            exibirFeedback(
                'O local superior selecionado não foi encontrado.',
                'danger'
            );

            return;
        }

        const codigoOriginal = codigoEmEdicao;

        if (codigoOriginal) {
            const indice = locais.findIndex(
                local => local.codigo === codigoOriginal
            );

            if (indice === -1) {
                exibirFeedback(
                    'O local que estava sendo editado não foi encontrado.',
                    'danger'
                );

                resetarFormulario();
                return;
            }

            dados.id = locais[indice].id;

            // Preserva o código estável durante a edição.
            dados.codigo = locais[indice].codigo;

            locais[indice] = dados;
        } else {
            dados.id = proximoId++;
            locais.push(dados);
        }

        resetarFormulario();
        popularSelectPai('');
        renderizarTabela();

        exibirFeedback(
            codigoOriginal
                ? 'Local atualizado na demonstração.'
                : 'Local cadastrado na demonstração.',
            'success'
        );
    }


    /*
     * =========================================
     * EDITAR LOCAL
     * =========================================
     */

    function editarLocal(id) {
        const local = obterLocal(id);

        if (!local) {
            window.alert('O local não foi encontrado.');
            return;
        }

        codigoEmEdicao = local.codigo;

        campoCodigo.value = local.codigo;
        campoCodigo.readOnly = true;

        campoNome.value = local.nome || '';
        campoTipo.value = local.tipo || '';
        campoReferencia.value = local.referencia || '';
        campoDescricao.value = local.descricao || '';

        campoLatitude.value =
            local.latitude === null ? '' : local.latitude;

        campoLongitude.value =
            local.longitude === null ? '' : local.longitude;

        campoAtivo.value = String(local.ativo);

        popularSelectPai(
            local.local_pai_id === null
                ? ''
                : String(local.local_pai_id)
        );

        formTitle.innerHTML =
            '<i class="fa-solid fa-pen-to-square me-2"></i>Editar local';

        formMode.textContent = 'Editando';

        saveButton.innerHTML =
            '<i class="fa-solid fa-floppy-disk me-2"></i>Atualizar local';

        cancelButton.classList.remove('d-none');

        limparFeedback();

        form.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }


    /*
     * =========================================
     * DESATIVAR / REATIVAR
     * =========================================
     *
     * O protótipo utiliza ativação e desativação,
     * evitando apagar o registro e perder a estrutura.
     */

    function alternarEstadoLocal(id) {
        const local = obterLocal(id);

        if (!local) {
            window.alert('O local não foi encontrado.');
            return;
        }

        const novoEstado = !local.ativo;

        if (!novoEstado) {
            const filhosAtivos = obterDescendentes(local.id).filter(
                descendente => descendente.ativo
            );

            if (filhosAtivos.length > 0) {
                window.alert(
                    'Este local possui locais descendentes ativos. ' +
                    'Desative primeiro os locais filhos se desejar desativar este local.'
                );

                return;
            }
        } else {
            const pai = obterLocal(local.local_pai_id);

            if (pai && !pai.ativo) {
                window.alert(
                    'Não é possível reativar este local enquanto seu local superior estiver inativo.'
                );

                return;
            }
        }

        const mensagem = novoEstado
            ? `Deseja desativar o local "${local.nome}" na demonstração?`
            : `Deseja reativar o local "${local.nome}" na demonstração?`;

        if (!window.confirm(mensagem)) {
            return;
        }

        local.ativo = novoEstado;

        renderizarTabela();
        popularSelectPai(campoPai.value);

        if (codigoEmDetalhes === local.id) {
            mostrarDetalhesLocal(local.id);
        }
    }


    /*
     * =========================================
     * DETALHES DO LOCAL
     * =========================================
     */

    function criarBlocoDetalhe(rotulo, valor, coluna = 'col-md-6') {
        const wrapper = document.createElement('div');
        wrapper.className = coluna;

        const box = document.createElement('div');
        box.className = 'detail-box';

        const label = criarElemento(
            'div',
            'detail-label',
            rotulo
        );

        const value = criarElemento(
            'div',
            'detail-value',
            valorOuTraco(valor)
        );

        box.append(label, value);
        wrapper.appendChild(box);

        return wrapper;
    }

    function mostrarDetalhesLocal(id) {
        const local = obterLocal(id);

        if (!local) {
            window.alert('O local não foi encontrado.');
            return;
        }

        codigoEmDetalhes = local.id;

        document.getElementById('tituloDetalhesLocal').textContent =
            local.nome;

        document.getElementById('subtituloDetalhesLocal').textContent =
            `${local.codigo} · Dados demonstrativos`;

        const resumo = document.getElementById('resumoDetalhesLocal');
        resumo.replaceChildren();

        const pai = obterLocal(local.local_pai_id);

        resumo.append(
            criarBlocoDetalhe('Código', local.codigo),
            criarBlocoDetalhe('Tipo', obterRotuloTipo(local.tipo)),
            criarBlocoDetalhe('Estado', obterRotuloEstado(local.ativo)),
            criarBlocoDetalhe('Local superior', pai ? pai.nome : 'Local raiz'),
            criarBlocoDetalhe('Caminho completo', obterCaminhoCompleto(local), 'col-12'),
            criarBlocoDetalhe('Referência física', local.referencia, 'col-12'),
            criarBlocoDetalhe('Descrição', local.descricao, 'col-12'),
            criarBlocoDetalhe(
                'Latitude',
                local.latitude === null ? 'Não definida' : local.latitude
            ),
            criarBlocoDetalhe(
                'Longitude',
                local.longitude === null ? 'Não definida' : local.longitude
            ),
            criarBlocoDetalhe(
                'Dispositivos no local e descendentes',
                String(contarDispositivosNoLocal(local.id))
            )
        );

        /* Descendentes */

        const descendentesBox =
            document.getElementById('descendentesDetalhesLocal');

        descendentesBox.replaceChildren();

        const descendentes = obterDescendentes(local.id);

        if (descendentes.length === 0) {
            descendentesBox.textContent =
                'Este local não possui locais descendentes cadastrados.';
        } else {
            const lista = document.createElement('ul');
            lista.className = 'detail-list';

            descendentes.forEach(descendente => {
                const li = document.createElement('li');

                const titulo = criarElemento(
                    'div',
                    'fw-semibold',
                    descendente.nome
                );

                const codigo = criarElemento(
                    'small',
                    'text-muted-custom',
                    `${descendente.codigo} · ${obterRotuloTipo(descendente.tipo)}`
                );

                li.append(titulo, codigo);
                lista.appendChild(li);
            });

            descendentesBox.appendChild(lista);
        }

        /* Dispositivos associados diretamente */

        const dispositivosBox =
            document.getElementById('dispositivosDetalhesLocal');

        dispositivosBox.replaceChildren();

        const associados = obterDispositivosDoLocal(local.id);

        if (associados.length === 0) {
            dispositivosBox.textContent =
                'Nenhum dispositivo demonstrativo está associado diretamente a este local.';
        } else {
            const lista = document.createElement('ul');
            lista.className = 'detail-list';

            associados.forEach(dispositivo => {
                const li = document.createElement('li');

                const nome = criarElemento(
                    'div',
                    'fw-semibold',
                    dispositivo.nome
                );

                const codigo = criarElemento(
                    'small',
                    'text-muted-custom',
                    dispositivo.codigo
                );

                li.append(nome, codigo);
                lista.appendChild(li);
            });

            dispositivosBox.appendChild(lista);
        }

        modalDetalhes.show();
    }


    /*
     * =========================================
     * EVENTOS DOS CONTROLES
     * =========================================
     */

    form.addEventListener('submit', salvarLocal);

    cancelButton.addEventListener('click', resetarFormulario);

    document.getElementById('btnLimparFormulario').addEventListener(
        'click',
        resetarFormulario
    );

    filtroTexto.addEventListener('input', renderizarTabela);
    filtroTipo.addEventListener('change', renderizarTabela);
    filtroAtivo.addEventListener('change', renderizarTabela);

    document.getElementById('btnLimparFiltros').addEventListener(
        'click',
        limparFiltros
    );

    tabela.addEventListener('click', event => {
        const botao = event.target.closest('button[data-action]');

        if (!botao) {
            return;
        }

        const id = Number(botao.dataset.id);
        const acao = botao.dataset.action;

        if (acao === 'detalhes') {
            mostrarDetalhesLocal(id);
        } else if (acao === 'editar') {
            editarLocal(id);
        } else if (acao === 'alternar') {
            alternarEstadoLocal(id);
        }
    });

    document.getElementById('btnEditarDetalhesLocal').addEventListener(
        'click',
        () => {
            if (codigoEmDetalhes === null) {
                return;
            }

            const id = codigoEmDetalhes;

            modalDetalhesElement.addEventListener(
                'hidden.bs.modal',
                function aoFechar() {
                    modalDetalhesElement.removeEventListener(
                        'hidden.bs.modal',
                        aoFechar
                    );

                    editarLocal(id);
                },
                { once: true }
            );

            modalDetalhes.hide();
        }
    );


    /*
     * =========================================
     * INICIALIZAÇÃO
     * =========================================
     */

    popularSelectPai('');
    renderizarTabela();

})();
</script>
<?= $this->endSection() ?>