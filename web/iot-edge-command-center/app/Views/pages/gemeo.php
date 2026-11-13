
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gêmeo Digital Híbrido - IoT-Edge Center
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  
/* Fundo e cores coerentes com o layout principal */
.gemeo-page {
  background-color: #0b0f19;
  color: #f8fafc;
  font-family: 'Segoe UI', system-ui, sans-serif;
  min-height: 100%;
  overflow-x: hidden;
}

.gemeo-page .card-dark {
  background-color: #111827;
  color: #f8fafc;
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 12px;
}

.gemeo-page .text-secondary {
  color: #94a3b8 !important;
}

.gemeo-page .form-select {
  background-color: #0d1117;
  color: #f8fafc;
  border-color: rgba(255,255,255,0.2);
}

.gemeo-page .form-select option {
  background-color: #111827;
  color: #f8fafc;
}

.gemeo-page .demo-notice {
  color: #cbd5e1;
  background-color: rgba(245,158,11,0.08);
  border: 1px solid rgba(245,158,11,0.25);
  border-radius: 8px;
  padding: 8px 10px;
}


  #canvas3d-container {
    width: 100%;
    height: 75vh;
    min-height: 420px;
    background-color: #050811;
    border-radius: 12px;
    position: relative;
    border: 1px solid rgba(59,130,246,0.3);
    overflow: hidden;
  }

  #canvas3d-container canvas {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 12px;
  }

  .overlay-3d-controls {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 10;
    background: rgba(17,24,39,0.85);
    backdrop-filter: blur(8px);
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.1);
    max-width: calc(100% - 30px);
  }

  .cardinal-label {
    position: absolute;
    font-family: monospace;
    font-weight: bold;
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 4px;
    pointer-events: none;
    z-index: 5;
    backdrop-filter: blur(4px);
  }

  .cardinal-norte {
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(239,68,68,0.2);
    color: #fca5a5;
    border: 1px solid #ef4444;
  }

  .cardinal-sul {
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(59,130,246,0.2);
    color: #93c5fd;
    border: 1px solid #3b82f6;
  }

  .cardinal-leste {
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    background: rgba(16,185,129,0.2);
    color: #6ee7b7;
    border: 1px solid #10b981;
  }

  .cardinal-oeste {
    top: 50%;
    left: 20px;
    transform: translateY(-50%);
    background: rgba(245,158,11,0.2);
    color: #fde68a;
    border: 1px solid #f59e0b;
  }

  .floor-selector-list {
    max-height: 140px;
    overflow-y: auto;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    background-color: #0d1117;
  }

  .floor-btn {
    width: 100%;
    text-align: left;
    padding: 6px 12px;
    font-size: 0.82rem;
    border: none;
    background: transparent;
    color: #94a3b8;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    transition: all 0.2s;
  }

  .floor-btn:hover {
    background-color: rgba(59,130,246,0.15);
    color: #fff;
  }

  .floor-btn.active {
    background-color: #2563eb;
    color: #fff;
    font-weight: bold;
  }

  #canvas2d-floorplan {
    display: block;
    width: 100%;
    height: 200px;
    background-color: #0d1117;
    border-radius: 8px;
    border: 1px dashed rgba(255,255,255,0.2);
  }


  @media (max-width: 991.98px) {
    #canvas3d-container {
      height: 60vh;
      min-height: 360px;
    }
  }

  @media (max-width: 575.98px) {
    #canvas3d-container {
      height: 55vh;
      min-height: 320px;
    }

    .overlay-3d-controls {
      top: 8px;
      left: 8px;
      padding: 8px;
    }

    .cardinal-label {
      font-size: 0.62rem;
      padding: 2px 4px;
    }

    .cardinal-oeste {
      left: 8px;
    }

    .cardinal-leste {
      right: 8px;
    }
  }
</style>

<script type="importmap">
{
  "imports": {
    "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
    "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
  }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="gemeo-page container-fluid p-3">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h4 class="fw-bold mb-1">
        <i class="fa-solid fa-cubes text-info me-2"></i>
        Gêmeo Digital Híbrido
      </h4>
      <p class="text-secondary small mb-0">
        Modelo 3D, planta simplificada e estados visuais dos dispositivos por local.
      </p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
      <a class="btn btn-sm btn-outline-info" href="<?= site_url('locais') ?>"><i class="fa-solid fa-location-dot me-1"></i>Locais</a>
      <a class="btn btn-sm btn-outline-info" href="<?= site_url('embarcados') ?>"><i class="fa-solid fa-microchip me-1"></i>Dispositivos</a>
      <span class="badge bg-warning bg-opacity-25 text-warning border border-warning">
        <i class="fa-solid fa-flask me-1"></i> Modo demonstrativo
      </span>
    </div>
  </div>

  <div class="demo-notice mb-3">
    <i class="fa-solid fa-circle-info text-warning me-1"></i>
    A arquitetura 3D, os marcadores e a movimentação do drone são demonstrações locais.
    As posições XYZ são ilustrativas e ainda não representam coordenadas persistidas no banco.
    Nenhum comando real é enviado ao hardware.
  </div>

  <div class="row g-3">

    <!-- Visualizador tridimensional -->
    <div class="col-lg-8">
      <div id="canvas3d-container">

        <div class="overlay-3d-controls text-white">
          <span class="fw-bold small d-block">
            <i class="fa-solid fa-compass text-primary me-1"></i>
            Gêmeo Digital 3D (Decolagem Sequencial Segura & Ronda)
          </span>
          <small class="text-secondary" id="droneStatusText">
            DRONE-001 · Base simulada (standby)
          </small>
        </div>

        <div class="cardinal-label cardinal-norte">
          <i class="fa-solid fa-arrow-up me-1"></i>NORTE (+Z)
        </div>
        <div class="cardinal-label cardinal-sul">
          <i class="fa-solid fa-arrow-down me-1"></i>SUL (-Z)
        </div>
        <div class="cardinal-label cardinal-leste">
          <i class="fa-solid fa-arrow-right me-1"></i>LESTE (+X)
        </div>
        <div class="cardinal-label cardinal-oeste">
          <i class="fa-solid fa-arrow-left me-1"></i>OESTE (-X)
        </div>

        <div id="threeError" class="position-absolute top-50 start-50 translate-middle text-center text-secondary p-3 d-none">
          <i class="fa-solid fa-triangle-exclamation text-warning fa-2x mb-2"></i>
          <p class="mb-0">Não foi possível inicializar a visualização 3D.</p>
          <small>Verifique o suporte a WebGL e o carregamento do Three.js.</small>
        </div>
      </div>
    </div>

    <!-- Painel de controle -->
    <div class="col-lg-4">

      <div class="card-dark p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold text-info m-0">
            <i class="fa-solid fa-layer-group me-2"></i>Planta Baixa 2D Tática
          </h6>
          <span class="badge bg-primary" id="selectedFloorBadge">
            Sala de controle
          </span>
        </div>

        <label class="form-label small text-secondary mb-1">
          Selecione um local do cadastro demonstrativo:
        </label>

        <div class="floor-selector-list mb-3">
          <button type="button" class="floor-btn active"
            onclick="selecionarAndar('Sala de controle', 0.0, this)">
            <i class="fa-solid fa-desktop me-2"></i>Sala de controle · LOC-SALA-CTRL
          </button>

          <button type="button" class="floor-btn"
            onclick="selecionarAndar('Corredor norte', 0.0, this)">
            <i class="fa-solid fa-video me-2"></i>Corredor norte · LOC-CORREDOR-N
          </button>

          <button type="button" class="floor-btn"
            onclick="selecionarAndar('Zona técnica', 0.0, this)">
            <i class="fa-solid fa-gears me-2"></i>Zona técnica · LOC-ZONA-TECH
          </button>

          <button type="button" class="floor-btn"
            onclick="selecionarAndar('Andar 2 (inativo no cadastro)', 2.0, this)">
            <i class="fa-solid fa-building me-2"></i>Andar 2 · LOC-ANDAR-02 (inativo)
          </button>

          <button type="button" class="floor-btn"
            onclick="selecionarAndar('Área externa', 0.5, this)">
            <i class="fa-solid fa-tree me-2"></i>Área externa · LOC-EXT-01
          </button>
        </div>

        <canvas id="canvas2d-floorplan" aria-label="Planta baixa tática do pavimento selecionado"></canvas>
      </div>

      <div class="card-dark p-3">
        <h6 class="fw-bold text-warning mb-2">
          <i class="fa-solid fa-sliders me-2"></i>
          Intervenção Humana (Human-in-the-Loop)
        </h6>

        <div class="p-2 mb-3 bg-dark rounded border border-primary border-opacity-25">
          <label class="form-label small fw-semibold text-info mb-2">
            <i class="fa-solid fa-plane me-1"></i>
            Controle de missão demonstrativa (Drone DRONE-001)
          </label>

          <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary btn-sm flex-fill fw-semibold"
              onclick="despacharDroneParaAndarAtual()">
              <i class="fa-solid fa-location-arrow me-1"></i>Simular deslocamento ao local
            </button>

            <button type="button" class="btn btn-outline-warning btn-sm flex-fill fw-semibold"
              onclick="retornarDroneBase()">
              <i class="fa-solid fa-house me-1"></i>Simular retorno à base (RTH)
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label for="selectNodeControl" class="form-label small text-secondary">
            Dispositivo alvo para estado visual
          </label>

          <select id="selectNodeControl" class="form-select form-select-sm bg-dark text-white border-secondary">
            <option value="EMB-GAS-001">EMB-GAS-001 — Sensor MQ-4 · LOC-SALA-CTRL</option>
            <option value="AUD-001">AUD-001 — Áudio / TTS · LOC-SALA-CTRL</option>
            <option value="REL-001">REL-001 — Sinalização / relé · LOC-ZONA-TECH</option>
            <option value="CAM-001">CAM-001 — Câmera IP · LOC-CORREDOR-N</option>
            <option value="DRONE-001">DRONE-001 — Drone de inspeção · LOC-EXT-01</option>
          </select>
        </div>

        <div class="d-grid gap-2 mb-3">
          <button type="button" class="btn btn-danger btn-sm fw-semibold"
            onclick="simularEstadoAlvo('ALERTA')">
            <i class="fa-solid fa-bell me-1"></i>Simular alerta visual do dispositivo (Vermelho)
          </button>

          <button type="button" class="btn btn-warning btn-sm text-dark fw-semibold"
            onclick="simularEstadoAlvo('TTS')">
            <i class="fa-solid fa-bullhorn me-1"></i>Simular aviso / atividade visual (Azul)
          </button>

          <button type="button" class="btn btn-success btn-sm fw-semibold"
            onclick="simularEstadoAlvo('NORMAL')">
            <i class="fa-solid fa-circle-check me-1"></i>Restaurar estado visual normal (Verde)
          </button>
        </div>

        <div class="p-2 bg-dark rounded border border-secondary border-opacity-25 mb-2">
          <div class="small fw-semibold mb-2">Legenda do estado visual</div>
          <div class="d-flex flex-wrap gap-3 small text-secondary">
            <span><i class="fa-solid fa-circle text-success me-1"></i>Normal</span>
            <span><i class="fa-solid fa-circle text-danger me-1"></i>Alerta ilustrativo</span>
            <span><i class="fa-solid fa-circle text-primary me-1"></i>Aviso / atividade simulada</span>
          </div>
        </div>

        <div class="p-2 bg-dark rounded border border-secondary border-opacity-25 d-flex flex-wrap justify-content-between align-items-center gap-2">
          <small class="text-secondary">
            <i class="fa-solid fa-arrows-to-circle me-1 text-success"></i>
            Atualização visual local 3D/2D
          </small>
          <span class="badge bg-warning bg-opacity-25 text-warning border border-warning">
            Sem atuação física
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const container = document.getElementById('canvas3d-container');
const statusText = document.getElementById('droneStatusText');
const errorBox = document.getElementById('threeError');

let scene;
let camera;
let renderer;
let controls;

const nodeObjects = {};
const estadoNo2D = {};

let andarAtual = 'Sala de controle';
let alturaAndarAtual = 0.0; // referência do pavimento, em coordenadas locais ilustrativas

let droneState = 'STANDBY';
let droneAngle = 0;
let droneMissionTarget = null;
let ultimoDesenhoPlanta = 0;

// Destinos locais aproximados da maquete 3D; não são coordenadas geográficas.
// O edifício ocupa aproximadamente X/Z entre -4 e +4. Por isso, os destinos
// dos locais internos ficam FORA da fachada, com margem para o tamanho do drone.
// A altura Y acompanha o nível da ocorrência: andar 1 = 1.2; andar 2 = 3.2.
// Assim, o drone faz a ronda externamente, alinhado ao pavimento da ocorrência,
// sem atravessar paredes ou circular entre as lajes do edifício.
const DESTINOS_DRONE = {
  'Sala de controle': {
    x: 5.8, z: -1.4, y: 1.2, raio: 0.75,
    codigo: 'LOC-SALA-CTRL', pavimento: 'Andar 1', setor: 'fachada leste'
  },
  'Corredor norte': {
    x: -2.7, z: 5.8, y: 1.2, raio: 0.65,
    codigo: 'LOC-CORREDOR-N', pavimento: 'Andar 1', setor: 'fachada norte'
  },
  'Zona técnica': {
    x: -5.8, z: 1.8, y: 1.2, raio: 0.65,
    codigo: 'LOC-ZONA-TECH', pavimento: 'Andar 1', setor: 'fachada oeste'
  },
  'Andar 2 (inativo no cadastro)': {
    x: 5.8, z: 0.4, y: 3.2, raio: 0.7,
    codigo: 'LOC-ANDAR-02', pavimento: 'Andar 2', setor: 'fachada leste'
  },
  'Área externa': {
    x: 8.0, z: 7.0, y: 2.7, raio: 1.6,
    codigo: 'LOC-EXT-01', pavimento: 'Área externa', setor: 'perímetro externo'
  }
};

const ALTITUDE_SEGURA = 6.0;
const ALTITUDE_BASE = 4.3;
const TOLERANCIA_MOVIMENTO = 0.16;

function atualizarStatus(texto) {
  statusText.textContent = texto;
}

function inicializarCena3D() {
  try {
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x050811);

    const initialWidth = Math.max(container.clientWidth, 320);
    const initialHeight = Math.max(container.clientHeight, 360);

    camera = new THREE.PerspectiveCamera(
      50,
      initialWidth / initialHeight,
      0.1,
      1000
    );
    camera.position.set(16, 14, 18);

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.setSize(initialWidth, initialHeight);

    container.appendChild(renderer.domElement);

    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    scene.add(new THREE.AmbientLight(0xffffff, 0.9));

    const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
    dirLight.position.set(15, 25, 15);
    scene.add(dirLight);

    criarEdificio();
    criarDispositivos();

    const gridHelper = new THREE.GridHelper(24, 24, 0x3b82f6, 0x1e293b);
    gridHelper.position.y = -2.1;
    scene.add(gridHelper);

    desenharPlanta2D(andarAtual);
    animate();
  } catch (error) {
    console.error('Erro ao inicializar Three.js:', error);
    errorBox.classList.remove('d-none');
  }
}

function criarEdificio() {
  const buildingGroup = new THREE.Group();

  const slabMaterial = new THREE.MeshStandardMaterial({
    color: 0x1e293b,
    transparent: true,
    opacity: 0.85
  });

  const wallMaterial = new THREE.MeshStandardMaterial({
    color: 0x3b82f6,
    wireframe: true,
    opacity: 0.15,
    transparent: true
  });

  const floorHeights = [
    { name: 'Subsolo', y: -2.0 },
    { name: 'Andar 1', y: 0.0 },
    { name: 'Andar 2', y: 2.0 },
    { name: 'Andar 3', y: 4.0 }
  ];

  floorHeights.forEach(floor => {
    const slab = new THREE.Mesh(
      new THREE.BoxGeometry(8, 0.15, 8),
      slabMaterial
    );
    slab.position.y = floor.y;
    buildingGroup.add(slab);

    const wall = new THREE.Mesh(
      new THREE.BoxGeometry(8, 1.8, 8),
      wallMaterial
    );
    wall.position.y = floor.y + 0.9;
    buildingGroup.add(wall);
  });

  const helipad = new THREE.Mesh(
    new THREE.CylinderGeometry(1.2, 1.2, 0.1, 16),
    new THREE.MeshStandardMaterial({
      color: 0x2563eb,
      emissive: 0x1d4ed8,
      emissiveIntensity: 0.3
    })
  );

  helipad.position.set(0, 4.1, 0);
  buildingGroup.add(helipad);

  scene.add(buildingGroup);
}

function registrarNo(id, group) {
  group.name = id;
  scene.add(group);
  nodeObjects[id] = group;
}

function criarSintetizadorTTS(id, x, floorY, z) {
  const group = new THREE.Group();

  const boxMesh = new THREE.Mesh(
    new THREE.BoxGeometry(0.5, 0.4, 0.5),
    new THREE.MeshStandardMaterial({
      color: 0x10b981,
      emissive: 0x10b981,
      emissiveIntensity: 0.2
    })
  );
  boxMesh.name = `${id}_BODY`;
  group.add(boxMesh);

  const horn = new THREE.Mesh(
    new THREE.ConeGeometry(0.25, 0.3, 12),
    new THREE.MeshStandardMaterial({ color: 0x334155 })
  );
  horn.rotation.x = Math.PI;
  horn.position.y = -0.25;
  group.add(horn);

  group.position.set(x, floorY + 1.7, z);
  registrarNo(id, group);
}

function criarRefletorCarga(id, x, floorY, z) {
  const group = new THREE.Group();

  const base = new THREE.Mesh(
    new THREE.BoxGeometry(0.6, 0.3, 0.4),
    new THREE.MeshStandardMaterial({ color: 0x475569 })
  );
  group.add(base);

  const lens = new THREE.Mesh(
    new THREE.CylinderGeometry(0.2, 0.2, 0.1, 12),
    new THREE.MeshStandardMaterial({
      color: 0x10b981,
      emissive: 0x10b981,
      emissiveIntensity: 0.3
    })
  );

  lens.position.y = -0.18;
  lens.name = `${id}_BODY`;
  group.add(lens);

  group.position.set(x, floorY + 1.7, z);
  registrarNo(id, group);
}

function criarCameraIP(id, x, floorY, z) {
  const group = new THREE.Group();

  const body = new THREE.Mesh(
    new THREE.BoxGeometry(0.62, 0.28, 0.3),
    new THREE.MeshStandardMaterial({
      color: 0x38bdf8,
      emissive: 0x075985,
      emissiveIntensity: 0.25
    })
  );
  body.name = `${id}_BODY`;
  group.add(body);

  const lens = new THREE.Mesh(
    new THREE.CylinderGeometry(0.09, 0.09, 0.18, 12),
    new THREE.MeshStandardMaterial({ color: 0x0f172a })
  );
  lens.rotation.z = Math.PI / 2;
  lens.position.x = 0.36;
  group.add(lens);

  group.position.set(x, floorY + 1.8, z);
  registrarNo(id, group);
}

function criarSensorGas(id, x, floorY, z) {
  const group = new THREE.Group();

  const body = new THREE.Mesh(
    new THREE.BoxGeometry(0.38, 0.48, 0.38),
    new THREE.MeshStandardMaterial({
      color: 0x34d399,
      emissive: 0x047857,
      emissiveIntensity: 0.25
    })
  );
  body.name = `${id}_BODY`;
  group.add(body);

  const indicator = new THREE.Mesh(
    new THREE.SphereGeometry(0.07, 10, 10),
    new THREE.MeshStandardMaterial({ color: 0xd1fae5, emissive: 0x10b981, emissiveIntensity: 0.4 })
  );
  indicator.position.set(0, 0.28, 0);
  group.add(indicator);

  group.position.set(x, floorY + 1.7, z);
  registrarNo(id, group);
}

function criarDroneDJI(id) {
  const droneGroup = new THREE.Group();

  const body = new THREE.Mesh(
    new THREE.BoxGeometry(0.5, 0.15, 0.6),
    new THREE.MeshStandardMaterial({
      color: 0x3b82f6,
      emissive: 0x3b82f6,
      emissiveIntensity: 0.4
    })
  );
  body.name = `${id}_BODY`;
  droneGroup.add(body);

  const armAngles = [
    Math.PI / 4,
    3 * Math.PI / 4,
    -Math.PI / 4,
    -3 * Math.PI / 4
  ];

  armAngles.forEach(angle => {
    const arm = new THREE.Mesh(
      new THREE.CylinderGeometry(0.03, 0.03, 0.6),
      new THREE.MeshStandardMaterial({ color: 0x0f172a })
    );

    arm.rotation.z = Math.PI / 2;
    arm.rotation.y = angle;
    droneGroup.add(arm);

    const rotor = new THREE.Mesh(
      new THREE.CylinderGeometry(0.2, 0.2, 0.02, 8),
      new THREE.MeshStandardMaterial({ color: 0x94a3b8 })
    );

    rotor.position.set(
      Math.cos(angle) * 0.45,
      0.08,
      Math.sin(angle) * 0.45
    );
    droneGroup.add(rotor);
  });

  droneGroup.position.set(0, 4.3, 0);
  registrarNo(id, droneGroup);
}

function criarDispositivos() {
  // Os códigos e locais correspondem aos registros fictícios das outras telas.
  // As posições são coordenadas locais ilustrativas, ainda não persistidas no banco.
  criarSintetizadorTTS('AUD-001', 1.7, 0.0, -1.5);       // LOC-SALA-CTRL
  criarSensorGas('EMB-GAS-001', 2.6, 0.0, -1.2);         // LOC-SALA-CTRL
  criarCameraIP('CAM-001', -2.4, 0.0, -1.6);             // LOC-CORREDOR-N
  criarRefletorCarga('REL-001', -2.3, 0.0, 1.7);         // LOC-ZONA-TECH
  criarDroneDJI('DRONE-001');                             // LOC-EXT-01 (missão simulada)
}

function obterDestinoSelecionado() {
  const destino = DESTINOS_DRONE[andarAtual];
  if (destino) return destino;

  // Fallback defensivo caso um novo botão/local seja adicionado à página.
  return {
    x: 0,
    z: 0,
    y: Math.max(0.8, alturaAndarAtual + 2.2),
    raio: 1.0,
    codigo: 'LOCAL-NAO-MAPEADO'
  };
}

function distanciaHorizontal(pos, destino) {
  return Math.hypot(pos.x - destino.x, pos.z - destino.z);
}

window.despacharDroneParaAndarAtual = function() {
  const drone = nodeObjects['DRONE-001'];
  if (!drone) {
    atualizarStatus('DRONE-001 · Modelo 3D ainda não foi inicializado.');
    return;
  }

  // Captura o local selecionado no momento do despacho. Se o usuário escolher
  // outro local e despachar novamente, a missão muda para o novo destino.
  droneMissionTarget = { ...obterDestinoSelecionado(), nome: andarAtual };
  droneAngle = 0;
  droneState = 'MISSION_ASCENT';

  atualizarStatus(
    `DRONE-001 · Preparando missão para ${droneMissionTarget.nome} (${droneMissionTarget.codigo}): subida até ${ALTITUDE_SEGURA.toFixed(1)} m.`
  );
};

window.retornarDroneBase = function() {
  if (droneState === 'STANDBY') {
    atualizarStatus('DRONE-001 · Já está na base simulada (heliponto / standby).');
    return;
  }

  droneState = 'RTH_ASCENT';
  atualizarStatus('DRONE-001 · RTH Passo 1/3: subida vertical até a altitude segura.');
};

function animate() {
  requestAnimationFrame(animate);

  const drone = nodeObjects['DRONE-001'];

  if (drone) {
    if (droneState === 'MISSION_ASCENT') {
      // Passo 1: subir verticalmente no ponto atual antes do deslocamento lateral.
      drone.position.y = THREE.MathUtils.lerp(
        drone.position.y, ALTITUDE_SEGURA, 0.035
      );

      if (Math.abs(drone.position.y - ALTITUDE_SEGURA) < TOLERANCIA_MOVIMENTO) {
        drone.position.y = ALTITUDE_SEGURA;
        droneState = 'TRAVEL_TO_TARGET';
        atualizarStatus(
          `DRONE-001 · Passo 2/4: deslocamento horizontal para ${droneMissionTarget.nome} (${droneMissionTarget.codigo}).`
        );
      }
    } else if (droneState === 'TRAVEL_TO_TARGET' && droneMissionTarget) {
      // Passo 2: voar até as coordenadas X/Z do local selecionado em altura segura.
      const dx = droneMissionTarget.x - drone.position.x;
      const dz = droneMissionTarget.z - drone.position.z;
      drone.position.x = THREE.MathUtils.lerp(drone.position.x, droneMissionTarget.x, 0.035);
      drone.position.z = THREE.MathUtils.lerp(drone.position.z, droneMissionTarget.z, 0.035);
      drone.position.y = THREE.MathUtils.lerp(drone.position.y, ALTITUDE_SEGURA, 0.035);
      if (Math.hypot(dx, dz) > 0.01) {
        drone.rotation.y = Math.atan2(dx, dz);
      }

      if (distanciaHorizontal(drone.position, droneMissionTarget) < TOLERANCIA_MOVIMENTO) {
        drone.position.x = droneMissionTarget.x;
        drone.position.z = droneMissionTarget.z;
        droneState = 'MISSION_DESCENT';
        atualizarStatus(
          `DRONE-001 · Passo 3/4: chegou à região de ${droneMissionTarget.nome}; iniciando descida controlada.`
        );
      }
    } else if (droneState === 'MISSION_DESCENT' && droneMissionTarget) {
      // Passo 3: descer somente depois de alcançar horizontalmente o destino.
      drone.position.x = THREE.MathUtils.lerp(drone.position.x, droneMissionTarget.x, 0.08);
      drone.position.z = THREE.MathUtils.lerp(drone.position.z, droneMissionTarget.z, 0.08);
      drone.position.y = THREE.MathUtils.lerp(drone.position.y, droneMissionTarget.y, 0.035);

      if (
        Math.abs(drone.position.x - droneMissionTarget.x) < TOLERANCIA_MOVIMENTO &&
        Math.abs(drone.position.z - droneMissionTarget.z) < TOLERANCIA_MOVIMENTO &&
        Math.abs(drone.position.y - droneMissionTarget.y) < TOLERANCIA_MOVIMENTO
      ) {
        drone.position.x = droneMissionTarget.x;
        drone.position.y = droneMissionTarget.y;
        drone.position.z = droneMissionTarget.z;
        droneState = 'PATROL';
        droneAngle = 0;
        atualizarStatus(
          `DRONE-001 · Passo 4/4: ronda externa na altura de ${droneMissionTarget.pavimento || 'pavimento selecionado'}, junto à ${droneMissionTarget.setor || 'região selecionada'} — ${droneMissionTarget.nome} (${droneMissionTarget.codigo}).`
        );
      }
    } else if (droneState === 'PATROL' && droneMissionTarget) {
      // Ronda local pelo lado externo da fachada correspondente à ocorrência.
      // Os centros foram posicionados além do limite do edifício (X/Z ±4),
      // e o Y permanece no nível vertical do pavimento selecionado.
      droneAngle += 0.012;
      const targetX = droneMissionTarget.x + Math.cos(droneAngle) * droneMissionTarget.raio;
      const targetZ = droneMissionTarget.z + Math.sin(droneAngle) * droneMissionTarget.raio;
      drone.position.x = THREE.MathUtils.lerp(drone.position.x, targetX, 0.055);
      drone.position.z = THREE.MathUtils.lerp(drone.position.z, targetZ, 0.055);
      drone.position.y = THREE.MathUtils.lerp(drone.position.y, droneMissionTarget.y, 0.04);
      drone.rotation.y = -droneAngle + Math.PI / 2;
    } else if (droneState === 'RTH_ASCENT') {
      // RTH Passo 1: alcançar altura segura antes de cruzar horizontalmente o cenário.
      drone.position.y = THREE.MathUtils.lerp(
        drone.position.y, ALTITUDE_SEGURA, 0.04
      );

      if (Math.abs(drone.position.y - ALTITUDE_SEGURA) < TOLERANCIA_MOVIMENTO) {
        drone.position.y = ALTITUDE_SEGURA;
        droneState = 'RTH_ALIGN';
        atualizarStatus('DRONE-001 · RTH Passo 2/3: retorno horizontal à base (0, 0).');
      }
    } else if (droneState === 'RTH_ALIGN') {
      // RTH Passo 2: deslocamento horizontal até o heliponto da maquete.
      const dx = drone.position.x;
      const dz = drone.position.z;
      drone.position.x = THREE.MathUtils.lerp(drone.position.x, 0, 0.04);
      drone.position.z = THREE.MathUtils.lerp(drone.position.z, 0, 0.04);
      if (Math.hypot(dx, dz) > 0.01) {
        drone.rotation.y = Math.atan2(-dx, -dz);
      }

      if (Math.abs(drone.position.x) < TOLERANCIA_MOVIMENTO && Math.abs(drone.position.z) < TOLERANCIA_MOVIMENTO) {
        drone.position.x = 0;
        drone.position.z = 0;
        droneState = 'RTH_DESCENT';
        atualizarStatus('DRONE-001 · RTH Passo 3/3: descida vertical para pouso no heliponto.');
      }
    } else if (droneState === 'RTH_DESCENT') {
      drone.position.y = THREE.MathUtils.lerp(drone.position.y, ALTITUDE_BASE, 0.04);

      if (Math.abs(drone.position.y - ALTITUDE_BASE) < 0.05) {
        drone.position.set(0, ALTITUDE_BASE, 0);
        drone.rotation.set(0, 0, 0);
        droneState = 'STANDBY';
        droneMissionTarget = null;
        atualizarStatus('DRONE-001 · Na base simulada (heliponto / standby).');
      }
    }
  }

  controls.update();
  renderer.render(scene, camera);

  // Mantém os elementos 2D da planta responsivos sem redesenhar a cada frame.
  const agora = performance.now();
  if (agora - ultimoDesenhoPlanta > 150) {
    ultimoDesenhoPlanta = agora;
    desenharPlanta2D(andarAtual);
  }
}

window.selecionarAndar = function(nomeAndar, alturaY, btnEl) {
  andarAtual = nomeAndar;
  alturaAndarAtual = alturaY;

  document.getElementById('selectedFloorBadge').textContent = nomeAndar;

  document.querySelectorAll('.gemeo-page .floor-btn').forEach(button => {
    button.classList.remove('active');
  });

  if (btnEl) btnEl.classList.add('active');
  desenharPlanta2D(nomeAndar);

  // Se o drone estiver voando para outro local, a seleção visual não redireciona
  // a missão sozinha. Clique em "Simular deslocamento ao local" para despachar
  // ou redirecionar explicitamente para o destino recém-selecionado.
};

function desenharPlanta2D(andar) {
  const canvas2d = document.getElementById('canvas2d-floorplan');
  const ctx = canvas2d.getContext('2d');
  const width = canvas2d.clientWidth;
  const height = canvas2d.clientHeight;

  if (!width || !height) return;

  canvas2d.width = width;
  canvas2d.height = height;
  ctx.clearRect(0, 0, width, height);

  ctx.strokeStyle = 'rgba(255,255,255,0.05)';
  ctx.lineWidth = 1;
  for (let x = 0; x < width; x += 30) {
    ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, height); ctx.stroke();
  }
  for (let y = 0; y < height; y += 30) {
    ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(width, y); ctx.stroke();
  }

  ctx.strokeStyle = '#3b82f6';
  ctx.lineWidth = 2;
  ctx.strokeRect(15, 15, width - 30, height - 30);
  ctx.fillStyle = 'rgba(255,255,255,0.65)';
  ctx.font = '10px monospace';
  ctx.fillText('NORTE (+Z)', Math.max(18, width / 2 - 30), 28);
  ctx.fillText('SUL (-Z)', Math.max(18, width / 2 - 22), height - 20);
  ctx.fillText('OESTE (-X)', 22, height / 2);
  ctx.fillText('LESTE (+X)', Math.max(18, width - 72), height / 2);

  if (andar === 'Sala de controle') {
    desenharMarcadorNo2D(ctx, width * 0.64, height * 0.36, 'EMB-GAS-001', 'EMB-GAS-001 · MQ-4');
    desenharMarcadorNo2D(ctx, width * 0.72, height * 0.66, 'AUD-001', 'AUD-001 · Áudio');
  } else if (andar === 'Corredor norte') {
    desenharMarcadorNo2D(ctx, width * 0.35, height * 0.5, 'CAM-001', 'CAM-001 · Câmera');
  } else if (andar === 'Zona técnica') {
    desenharMarcadorNo2D(ctx, width * 0.68, height * 0.56, 'REL-001', 'REL-001 · Sinalização');
  } else if (andar === 'Área externa') {
    desenharMarcadorNo2D(ctx, width * 0.5, height * 0.22, 'DRONE-001', 'DRONE-001 · Drone');
  } else {
    ctx.fillStyle = '#fbbf24';
    ctx.font = '12px Segoe UI, sans-serif';
    ctx.fillText('Local inativo no cadastro demonstrativo.', 24, height / 2);
    ctx.fillStyle = '#94a3b8';
    ctx.font = '10px Segoe UI, sans-serif';
    ctx.fillText('Nenhum dispositivo ativo associado neste exemplo.', 24, height / 2 + 20);
  }

  desenharPosicaoDroneNaPlanta(ctx, width, height, andar);
}

function desenharPosicaoDroneNaPlanta(ctx, width, height, andar) {
  const drone = nodeObjects['DRONE-001'];
  const destino = droneMissionTarget;
  if (!drone || !destino || destino.nome !== andar || droneState === 'STANDBY') return;

  // Planta esquemática centrada no local selecionado: a posição acompanha a
  // projeção X/Z real da maquete. Durante o trajeto, o marcador pode tocar a borda.
  const escala = 16;
  const margem = 18;
  const cx = width / 2;
  const cy = height / 2;
  const px = Math.max(margem, Math.min(width - margem, cx + (drone.position.x - destino.x) * escala));
  const py = Math.max(margem, Math.min(height - margem, cy + (drone.position.z - destino.z) * escala));

  ctx.beginPath();
  ctx.arc(cx, cy, Math.max(9, destino.raio * escala), 0, Math.PI * 2);
  ctx.strokeStyle = 'rgba(56,189,248,0.65)';
  ctx.lineWidth = 1.5;
  ctx.setLineDash([4, 4]);
  ctx.stroke();
  ctx.setLineDash([]);

  ctx.beginPath();
  ctx.moveTo(px, py - 7);
  ctx.lineTo(px + 6, py + 5);
  ctx.lineTo(px - 6, py + 5);
  ctx.closePath();
  ctx.fillStyle = '#38bdf8';
  ctx.fill();
  ctx.strokeStyle = '#f8fafc';
  ctx.lineWidth = 1.2;
  ctx.stroke();

  ctx.fillStyle = '#7dd3fc';
  ctx.font = 'bold 10px Segoe UI, sans-serif';
  ctx.fillText('DRONE-001', Math.min(width - 76, px + 8), Math.max(12, py - 8));
}

function desenharMarcadorNo2D(ctx, x, y, id, label) {
  const estado = estadoNo2D[id] || 'NORMAL';

  ctx.beginPath();
  ctx.arc(x, y, 9, 0, Math.PI * 2);

  if (estado === 'ALERTA') {
    ctx.fillStyle = '#ef4444';
  } else if (estado === 'TTS') {
    ctx.fillStyle = '#3b82f6';
  } else {
    ctx.fillStyle = '#10b981';
  }

  ctx.fill();
  ctx.strokeStyle = '#ffffff';
  ctx.lineWidth = 1.5;
  ctx.stroke();

  ctx.fillStyle = '#f8fafc';
  ctx.font = '11px Segoe UI, sans-serif';
  ctx.fillText(label, x - 40, y + 22);
}

window.simularEstadoAlvo = function(estado) {
  const id = document.getElementById('selectNodeControl').value;
  estadoNo2D[id] = estado;

  const group = nodeObjects[id];

  if (group) {
    let corHex = 0x10b981;

    if (estado === 'ALERTA') {
      corHex = 0xef4444;
    } else if (estado === 'TTS') {
      corHex = 0x3b82f6;
    }

    group.traverse(child => {
      if (
        child.name &&
        child.name.includes('_BODY') &&
        child.material
      ) {
        child.material.color.setHex(corHex);

        if (child.material.emissive) {
          child.material.emissive.setHex(corHex);
          child.material.emissiveIntensity =
            estado === 'NORMAL' ? 0.2 : 0.8;
        }
      }
    });
  }

  desenharPlanta2D(andarAtual);
};

window.addEventListener('resize', () => {
  if (camera && renderer) {
    const width = container.clientWidth;
    const height = container.clientHeight;

    if (width && height) {
      camera.aspect = width / height;
      camera.updateProjectionMatrix();
      renderer.setSize(width, height);
    }
  }

  desenharPlanta2D(andarAtual);
});

inicializarCena3D();
</script>
<?= $this->endSection() ?>
