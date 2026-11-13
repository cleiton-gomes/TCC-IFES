# Relatório de Continuidade — Migração e Evolução do Serviço de Visão Computacional

**Projeto:** IoT-Edge Command Center — TCC-IFES  
**Data de referência:** 27 de outubro de 2026  
**Finalidade:** servir como contexto de retomada para os próximos testes, ajustes e implementação da arquitetura de visão computacional, interpretação com Llama e síntese de voz.

---

## 1. Resumo executivo

O projeto de visão computacional que já existia no repositório Gasmar foi copiado para o repositório do TCC-IFES, mantendo o projeto original intacto. Foi criado um ambiente virtual Python novo no destino, com Python 3.13.2 e as principais bibliotecas nas mesmas versões de aplicação do ambiente original. A importação de OpenCV, NumPy, PyTorch, TorchVision e Ultralytics foi validada; `pip check` não encontrou dependências quebradas.

Também foi criado e executado com sucesso um teste separado para a webcam local do computador, confirmando que o OpenCV consegue abrir uma câmera local no novo ambiente.

Ainda **não** foi comprovado o funcionamento dos fluxos da câmera IP ou do drone na nova estrutura, pois eles dependem de fontes de vídeo e de infraestrutura de rede que não estão acessíveis no momento. O script `001-teste-camera-ip.py` tentou abrir o endereço RTSP configurado e terminou após um timeout aproximado de 30 segundos. Isso não prova que a instalação Python esteja com defeito; a câmera, a rede, as credenciais e a origem real do fluxo ainda precisam ser verificadas quando o ambiente físico estiver disponível.

O script `006-deteccao-pessoas-ultralitics.py` foi executado, mas o resultado observado só confirmou mensagens de inicialização, o modo de log sem MQTT e a apresentação da URL RTSP configurada. Não houve confirmação de que os quadros foram recebidos nem de que a detecção YOLO estava funcionando sobre vídeo real.

A arquitetura desejada é evoluir os scripts atuais gradualmente para um serviço Python persistente, futuramente controlável por API, mantendo CodeIgniter/PHP, visão computacional, servidor de vídeo, Ollama/Llama, Piper TTS e MQTT como componentes separados, coordenados por uma rotina de inicialização única.

## 2. Organização do repositório definida

A raiz do TCC-IFES fica em:

```text
C:\Users\Cleiton\Documents\TCC-IFES
```

A estrutura conceitual aprovada é:

```text
TCC-IFES/
├── docs/
├── firmware/                       # Código dos microcontroladores
├── hardware/                       # Esquemas, placas e documentação física
├── infrastructure/                 # Configuração/execução dos serviços de suporte
│   ├── mqtt/                        # Configuração do broker, se organizado assim
│   ├── mediamtx/                    # Configuração do servidor de vídeo, se organizado assim
│   ├── network/                     # Configurações de rede, quando necessárias
│   └── scripts/                     # Inicialização e verificação dos serviços
├── services/
│   ├── visao-computacional-server/  # Serviço Python de captura e visão
│   └── audio-tts-server/            # Possível serviço de áudio/Piper no futuro
├── tests/
└── web/
    └── iot-edge-command-center/     # Organização prevista; confirmar nome/caminho real
```

Os subdiretórios ilustrados são uma proposta de organização; não se deve presumir que todos já existam.

### Decisões sobre a organização

- O projeto `visao-computacional-server` fica em `services/`, separado do backend PHP.
- Um serviço futuro de áudio/Piper também poderá ficar em `services/audio-tts-server/`.
- `infrastructure/` é destinada principalmente às configurações e aos mecanismos de suporte, como MediaMTX, broker MQTT, rede e scripts de inicialização. Os binários ou serviços instalados podem continuar fora do repositório quando isso for mais adequado.
- `firmware/` e `hardware/` permanecem separados, pois representam código embarcado e artefatos físicos, não serviços de aplicação.
- Não é necessário mover o projeto original Gasmar nem incorporar os arquivos Python diretamente às views do CodeIgniter.

## 3. Caminhos importantes

### Projeto original — preservar sem alterações

```text
C:\Users\Cleiton\Documents\EMBARCATECH\gasmar-iot-system\visao-computacional-server
```

### Cópia de trabalho no TCC-IFES

```text
C:\Users\Cleiton\Documents\TCC-IFES\services\visao-computacional-server
```

### Ambiente virtual novo

```text
C:\Users\Cleiton\Documents\TCC-IFES\services\visao-computacional-server\venv
```

### Instalação atual do MediaMTX mencionada no README original

```text
C:\mediamtx_v1.17.1_windows_amd64\mediamtx.exe
```

O MediaMTX está instalado fora da pasta do projeto. Seu estado e sua disponibilidade atuais ainda não foram comprovados nesta sessão.

## 4. Migração já realizada

A cópia foi feita com `robocopy`, excluindo o ambiente virtual original e arquivos de cache Python. O comando utilizado foi:

```bat
robocopy "C:\Users\Cleiton\Documents\EMBARCATECH\gasmar-iot-system\visao-computacional-server" "C:\Users\Cleiton\Documents\TCC-IFES\services\visao-computacional-server" /E /XD "C:\Users\Cleiton\Documents\EMBARCATECH\gasmar-iot-system\visao-computacional-server\venv" "C:\Users\Cleiton\Documents\EMBARCATECH\gasmar-iot-system\visao-computacional-server\__pycache__" /XF "*.pyc"
```

Resultado informado pelo usuário: **17 arquivos copiados, zero falhas**. O `venv` original não foi copiado. O projeto Gasmar continua intacto.

### Arquivos copiados

- `000-teste-camera-drone.py`
- `001-teste-camera-ip.py`
- `002-teste-cameras-drone-ip-mosaico.py`
- `003-script-interface-monitoramento.py`
- `004-script-agente-llama3.1-8B.py`
- `005-deteccao-pessoas-ultralitics.py`
- `006-deteccao-pessoas-ultralitics-com-mqtt-embarcado-TTS.py`
- `007-benchmarking-LLMs.py`
- `008-llama3.2-1B.py`
- `009-script-agente-llama3.1-8B.py`
- `010-script-agente-llama3.1-8B-com-simulacao-de-sensor-via-ponte-serial.py`
- `LLMs.txt`
- `main.py`
- `read-me.txt`
- `requirements.txt`
- `yolo11n.pt`
- `yolov8n.pt`

O `main.py` foi listado com **0 bytes**. Portanto, o README descreve uma futura forma de iniciar a central com `python main.py`, mas o `main.py` copiado ainda não implementa essa central. Não tentar usá-lo como ponto de entrada até que seu conteúdo seja desenvolvido ou substituído conscientemente.

### Observação sobre a numeração

O usuário decidiu que o teste da webcam local será o primeiro teste, com o nome `000-teste-camera-local.py`. Porém, a cópia também contém o arquivo original `000-teste-camera-drone.py`. Assim, há atualmente dois arquivos iniciados por `000-`. **Não renumerar nem sobrescrever os scripts existentes automaticamente.** A colisão de nomenclatura deve ser resolvida posteriormente, de forma explícita, caso a sequência final dos testes precise ser única.

## 5. Ambiente Python recriado e validado

O ambiente novo foi criado com Python 3.13.2:

```bat
python -m venv venv
venv\Scripts\activate
python --version
```

O prompt mostra `(venv)` quando o ambiente está ativo. O usuário está usando o **CMD do Windows**, não PowerShell. Para ler arquivos no CMD, usar `type arquivo.txt` (não `cat`). Para executar Python, usar sempre `python nome_do_script.py`; digitar apenas o nome de um `.py` pode abrir o aplicativo associado ao arquivo em vez de executar o script.

### Dependências e compatibilidade

O arquivo `requirements.txt` original tem apenas a lista de dependências principais, sem versões fixadas:

```text
opencv-python
numpy
ultralytics
torch
torchvision
paho-mqtt
requests
Pillow
tqdm
matplotlib
pyserial
```

No projeto novo foi criado `requirements-original-freeze.txt`, gerado por `pip freeze` no ambiente original. Esse arquivo registra pacotes instalados e versões exatas, incluindo dependências indiretas.

Foi criado também `requirements-restante.txt`, retirando as linhas `torch==` e `torchvision==` do freeze para que as bibliotecas PyTorch fossem instaladas primeiro pela fonte oficial CPU. Esse arquivo é auxiliar de restauração; sua nomenclatura e a estratégia definitiva de dependências podem ser revisadas depois.

### Resultado do ambiente original

O Python do ambiente original foi confirmado como `3.13.2`. A consulta ao PyTorch original retornou:

```text
PyTorch: 2.11.0+cpu
TorchVision: 0.26.0+cpu
CUDA compilada: None
CUDA disponível: False
```

### Instalação no ambiente novo

O PyTorch e TorchVision CPU foram instalados com:

```bat
python -m pip install torch==2.11.0 torchvision==0.26.0 --index-url https://download.pytorch.org/whl/cpu
```

Depois, as demais dependências foram instaladas com:

```bat
python -m pip install -r requirements-restante.txt
```

A verificação terminou com:

```text
No broken requirements found.
```

O teste dos imports retornou:

```text
OpenCV: 4.13.0
NumPy: 2.4.4
PyTorch: 2.11.0+cpu
TorchVision: 0.26.0+cpu
Ultralytics: 8.4.37
CUDA disponível: False
```

**Status:** o ambiente consegue importar as bibliotecas principais e não apresenta dependências quebradas segundo `pip check`. Isso valida a instalação básica, mas não comprova todos os scripts, todas as integrações ou o funcionamento dos modelos com fontes de vídeo indisponíveis.

## 6. Testes executados e resultados

### 6.1 Webcam local — sucesso

Foi criado o script:

```text
000-teste-camera-local.py
```

Ele usa OpenCV com `cv2.VideoCapture(0, cv2.CAP_DSHOW)`, captura quadros e exibe uma janela com um texto identificando o teste. O usuário confirmou que a webcam abriu.

**O que esse resultado comprova:** o ambiente novo consegue carregar o OpenCV, abrir a webcam local e apresentar vídeo em uma janela. Não comprova ainda a inferência YOLO nem RTSP/MediaMTX.

Para executar novamente, na pasta do serviço e com `(venv)` ativo:

```bat
python 000-teste-camera-local.py
```

### 6.2 `001-teste-camera-ip.py` — não conectado; pendente de infraestrutura

Esse script usa `cv2.VideoCapture(RTSP_URL, cv2.CAP_FFMPEG)` para acessar a câmera IP diretamente.

A tentativa executada com `python 001-teste-camera-ip.py` esperou aproximadamente 30 segundos e retornou uma mensagem de timeout do FFmpeg/OpenCV, seguida de uma mensagem de falha de conexão.

A causa ainda não foi determinada. O usuário explicou que no momento não está no ambiente físico nem tem acesso às câmeras e ao equipamento necessário. Assim, **não insistir na tentativa até que a rede/fonte esteja acessível**.

Nota técnica: apesar da mensagem do script citar MediaMTX, a URL que ele usa aponta diretamente para uma câmera IP. MediaMTX não é necessário para essa conexão direta, a menos que o fluxo real tenha sido configurado para passar pelo servidor. Quando retomar, corrigir também a mensagem de erro para refletir a fonte verdadeira.

A URL possui credenciais embutidas no código e o caractere `@` na senha. Uma URL com credenciais deve codificar caracteres especiais, como `@` para `%40`, no trecho de usuário/senha. A credencial foi exposta durante a conversa; se for uma senha real, recomenda-se alterá-la e não mantê-la hardcoded. Usar configuração externa/variáveis de ambiente quando for feita a adaptação.

### 6.3 `006-deteccao-pessoas-ultralitics.py` — inicialização observada, inferência não validada

Comando executado:

```bat
python 006-deteccao-pessoas-ultralitics.py
```

Saída observada, resumida:

- `[INFO] Carregando modelo YOLOv11...`
- um `DeprecationWarning` da API de callback do cliente MQTT;
- aviso de MQTT local não detectado e modo apenas log;
- mensagem de sistema online;
- indicação de que o script monitoraria o endereço RTSP da câmera IP.

Não foi apresentada uma janela de vídeo e o comando retornou ao prompt. Isso não comprova que o fluxo de vídeo foi capturado nem que o YOLO detectou pessoas. É coerente com o fato de a câmera IP não estar acessível naquele momento, mas o comportamento exato do script deve ser confirmado pela leitura completa do arquivo, porque a saída sozinha não determina por que ele retornou ao prompt.

Próximo procedimento recomendado: abrir e ler o conteúdo integral do `006-deteccao-pessoas-ultralitics.py`, preservá-lo e criar uma cópia de teste que use a webcam local como fonte. O teste local permitirá conferir carregamento de pesos, inferência e desenho de caixas sem depender de RTSP, MediaMTX, câmera IP, drone ou broker MQTT.

### 6.4 Comandos digitados incorretamente ou que não servem para Python

- `cat main.py` falhou porque `cat` não é comando padrão do CMD; usar `type main.py`.
- `node main.py` não executa Python. O arquivo também está vazio.
- Digitar apenas `001-teste-camera-ip.py` abriu o VS Code por associação de arquivo, em vez de rodar o código. Usar `python 001-teste-camera-ip.py`.

## 7. Infraestrutura mencionada pelo projeto original

O `read-me.txt` do serviço original descreve esta ordem pretendida:

1. Ativar o `venv`.
2. Iniciar MediaMTX em `C:\mediamtx_v1.17.1_windows_amd64\mediamtx.exe`.
3. Iniciar/usar o Ollama e o modelo `llama3.1:8b`.
4. Rodar `python main.py` (mas o `main.py` copiado está vazio e não é utilizável como central neste momento).
5. Opcionalmente, abrir VLC ou um viewer RTSP.

O README informa que o Ollama roda como serviço local separado, com API HTTP em `localhost:11434`, e que o modelo precisa estar instalado. Esses serviços não foram verificados neste ciclo de testes. Não iniciar ou instalar todos eles só para repetir o teste local da webcam: são desnecessários para esse teste.

### Diferentes caminhos de vídeo nos scripts

- `000-teste-camera-drone.py` usa um fluxo RTSP com caminho `/drone`, apontando a um endereço de rede (`10.0.0.2:8554` no script copiado).
- `002-teste-cameras-drone-ip-mosaico.py` usa uma URL da câmera IP e outro fluxo de drone (`localhost:8554/drone`).
- `001-teste-camera-ip.py` e o script `006` usam a URL direta da câmera IP.

É necessário revisar, quando a infraestrutura estiver disponível, qual endereço é o correto em cada computador e se o MediaMTX está recebendo/publicando o caminho esperado. Não supor que `localhost`, `10.0.0.2` e o endereço da câmera IP apontam para o mesmo serviço.

## 8. Arquitetura desejada para a evolução

### 8.1 Separação de componentes

A decisão é **não incorporar os scripts de visão ao PHP**. O código Python, os pesos de modelos e suas bibliotecas permanecerão em `services/visao-computacional-server/`; o backend CodeIgniter 4 controlará e consumirá o serviço por meio de uma interface definida.

Componentes previstos:

1. **Fonte de vídeo:** webcam em testes locais, câmera IP e/ou fonte de vídeo do drone quando acessíveis.
2. **MediaMTX:** serviço de mídia quando necessário para receber/reencaminhar os fluxos e disponibilizar protocolos que clientes diferentes possam consumir.
3. **Serviço Python de visão:** captura, OpenCV, YOLO, gerenciamento de eventos de visão e API futura.
4. **Ollama + Llama 3.1 8B:** componente local de linguagem para contextualização e geração de textos.
5. **Piper TTS:** componente de síntese de voz, com possibilidade futura de um serviço separado em `services/audio-tts-server/`.
6. **MQTT:** comunicação de telemetria, comandos e resultados com os componentes que adotarem esse protocolo.
7. **CodeIgniter 4/PHP:** interface, autenticação/autorização, persistência, consulta de resultados e controle das operações.
8. **MySQL `iot_edge_center`:** persistência de cadastros, eventos, mensagens, comandos e históricos, conforme a modelagem de 28 tabelas.
9. **Rotina de inicialização:** script geral (por exemplo, PowerShell ou `.bat`) que inicie/verifique os serviços locais na ordem correta e abra o navegador. Serviços em outras máquinas devem ser verificados por conectividade, não iniciados como se estivessem no computador local.

### 8.2 API Python

Uma API (FastAPI é a opção considerada) deverá ser adicionada depois que o script de vídeo for entendido e validado. A API não é por si só uma otimização de inferência; seu propósito é oferecer uma interface previsível para controlar e consultar um processo Python persistente.

Endpoints de exemplo para uma implementação futura:

- `GET /health`: serviço está respondendo.
- `GET /status`: fonte de vídeo, estado da análise, FPS/latência e erros.
- `POST /analysis/start`: iniciar análise.
- `POST /analysis/stop`: parar análise de forma controlada.
- `GET /detections/latest`: detecções mais recentes.
- Possivelmente, endpoints para configurar intervalos de análise, fonte de vídeo e modo de execução.

A autenticação e autorização da API deverão ser consideradas; o browser não deve acessar operações privilegiadas diretamente nem receber credenciais MQTT privilegiadas.

### 8.3 Processamento em threads/processos

Começar simples e medir:

- uma thread de captura por fonte ativa, conforme necessário;
- um worker de inferência YOLO no início;
- filas limitadas para desacoplar captura e inferência;
- política `latest_only` ou equivalente para evitar acumular quadros antigos quando a prioridade for baixa latência;
- uma fila controlada para solicitações ao Llama/Ollama;
- fila de síntese Piper;
- envio MQTT e persistência desacoplados das tarefas pesadas.

Não criar vários processos de YOLO/Llama só por criar concorrência. O computador usa o ambiente PyTorch CPU (`torch.cuda.is_available() == False`), e múltiplos workers podem disputar CPU e memória. Medir FPS de captura, FPS de inferência, duração da inferência, tempo de resposta do Llama, memória e quadros descartados antes de aumentar a concorrência.

Multiprocessing só será testado se as medições indicarem gargalo que possa beneficiar de processos independentes. Aumentar o número de threads não garante ganho de velocidade.

## 9. Estratégia de eventos e uso de Llama

O objetivo do Llama 3.1 8B não é interpretar cada quadro individual. Ele será orientado a eventos e também poderá receber gatilhos temporais para produzir descrição periódica.

### Tipos de gatilho previstos

- **Evento visual:** detecção de YOLO que satisfaça uma regra definida.
- **Evento temporal:** por exemplo, gerar descrição da cena a cada X segundos, usando os dados visuais mais recentes.
- **Evento de telemetria/estado:** dado recebido por MQTT ou mudança de estado, se relevante para uma regra.
- **Solicitação manual:** operador solicita interpretação/descrição conforme suas permissões.

A frequência de captura, frequência de inferência YOLO e frequência de geração do Llama serão configuráveis separadamente. O intervalo configurado é um alvo: se uma inferência demorar mais que o período, a implementação deve decidir se pula uma execução, coalesce solicitações ou mantém uma fila limitada, em vez de acumular trabalho indefinidamente.

### Função do Llama

O Llama local será usado principalmente para:

1. gerar uma descrição de cena contextualizada para o sistema, eventos, relatórios ou histórico;
2. gerar uma string de mensagem curta, gramaticalmente correta, semanticamente coerente e adequada para sintetização Piper;
3. usar contexto conhecido (local, tipo de evento, detecções, momento e instruções aprovadas) sem inventar fatos que os sensores ou a visão não observaram.

O resultado pode separar campos, por exemplo `descricao_cena` e `mensagem_audio`, para que o histórico receba mais detalhe e o sintetizador receba uma mensagem curta e fácil de compreender auditivamente.

O Llama deve receber resultados estruturados do YOLO e o contexto relevante. O modelo `llama3.1:8b` usado via Ollama é um modelo de linguagem; não presumir que interpreta pixels diretamente. Se for necessária análise visual direta pelo LLM, isso exigirá modelo com capacidade visual e uma decisão separada.

### Eventos por tempo e deduplicação

Uma descrição periódica a cada X segundos pode ser tratada como gatilho temporal, mas não deve automaticamente virar um alarme. Eventos críticos e atualizações rotineiras devem ser distinguidos.

Para não gerar mensagens repetidas:

- coalescer detecções repetidas relacionadas ao mesmo evento;
- considerar persistência em mais de um quadro, quando apropriado;
- estabelecer cooldown/regras de repetição;
- priorizar eventos urgentes sobre descrições periódicas;
- limitar filas e concorrência;
- registrar identificadores de evento/correlação e idempotência.

Orientações de segurança/emergência devem ser previamente aprovadas. O Llama pode contextualizar e formular a linguagem, mas não deve inventar rotas de evacuação nem ser o único responsável por uma decisão crítica ou uma atuação física. As condições determinísticas e as permissões de operação devem ficar no backend/motor de regras.

## 10. Mensagens diferentes para áreas diferentes

Um único evento pode envolver várias áreas do mesmo andar e gerar mensagens específicas. Exemplo ilustrativo: evento associado à Sala 101, Corredor Norte e Sala 102.

A proposta é preservar o evento de origem e criar ações lógicas distintas por destino quando o conteúdo de áudio variar. Cada mensagem terá o seu próprio texto e metadados de síntese. O backend resolverá os dispositivos a partir da hierarquia de `locais` e da compatibilidade com a ação; tópicos MQTT não serão usados como substitutos da hierarquia espacial.

Duas estratégias de geração ainda deverão ser comparadas:

- **Estratégia A:** uma chamada ao Llama por setor/destino, dando contexto específico em cada chamada.
- **Estratégia B:** uma chamada ao Llama solicita uma saída estruturada com mensagens para vários setores.

A recomendação inicial é testar a estratégia B para um evento que afeta vários locais e avaliar qualidade semântica, latência, consistência e robustez do JSON. Se a qualidade for insuficiente, testar A. As duas estratégias poderão coexistir conforme a necessidade.

Piper sintetiza cada texto específico (ou reutiliza áudio idêntico quando apropriado). Cada destinatário deve ter resultado próprio; publicar um comando não significa que foi recebido nem executado.

## 11. Relação com a modelagem de 28 tabelas

A modelagem do banco `iot_edge_center` já prevê o rastreamento necessário para esse fluxo. Não é necessário criar uma tabela por serviço Python.

| Tabela | Papel previsto no fluxo |
|---|---|
| `dispositivos` | Cadastro de câmera, gateway, sintetizador, estação embarcada ou outro equipamento, com identidade estável e local atual. |
| `componentes_dispositivo` | Sensores, controladores, módulos de comunicação e atuadores vinculados a um dispositivo. |
| `locais` | Hierarquia física e seleção dos destinos por sala, corredor, zona, andar ou edifício. |
| `leituras` | Telemetria produzida por dispositivos/componentes. |
| `eventos` | Registro de uma ocorrência/atualização contextual. |
| `evento_dispositivos` | Dispositivos envolvidos e seus papéis. |
| `evento_componentes` | Componentes envolvidos. |
| `evento_leituras` | Leituras que dispararam ou ajudam a explicar o evento. |
| `evento_locais` | Associação N:N do mesmo evento a múltiplas áreas. |
| `anexos` | Referências e metadados de imagens, vídeos e outros arquivos; binários ficam fora do MySQL. |
| `mensagens_integracao` | Mensagens MQTT e outros registros de transporte/processamento, quando aplicável. |
| `regras_automacao` | Gatilhos visuais, temporais ou de estado, com condições validadas. |
| `acoes_automacao` | Ações normalizadas/ordenadas configuradas em uma regra. |
| `execucoes_automacao` | Execução geral de uma regra. |
| `execucoes_acoes` | Estado e tentativa de cada ação numa execução. |
| `execucao_destinatarios` | Resultado individual por dispositivo selecionado para cada ação. |
| `comandos` | Comandos individuais encaminhados a dispositivos compatíveis. |
| `historico_comandos` | Transições de estado dos comandos. |
| `mensagens_audio` | Texto pedido/gerado, provedor TTS, modelo de voz, URI/hash do áudio e estado da síntese. |
| `auditoria` | Ações sensíveis, resultados, ator e correlação. |
| `historico_status_dispositivo` | Mudanças operacionais/conectividade. |
| `versoes_configuracao` | Snapshots de configurações. |
| `historico_estado_gemeo` | Estados/snapshots e reconstrução histórica. |
| `historico_localizacao_dispositivo` | Localização física vigente em períodos anteriores. |
| `sessoes_coleta` | Sessões de coleta ligadas a eventos/diagnóstico. |
| `usuarios`, `perfis`, `usuario_perfis` | Identidade e autorização para operações do backend. |

As tabelas restantes de apoio e histórico mantêm suas responsabilidades próprias, conforme a especificação de banco já elaborada. A reconstrução histórica do gêmeo digital será somente leitura e nunca deverá republicar comandos físicos.

### Mensagens por setor e associações

A decisão inicial é representar mensagens diferentes para setores por meio de ações lógicas distintas dentro da mesma execução de regra, com seus respectivos destinatários e mensagens de áudio. O vínculo exato entre `mensagens_audio`, `execucoes_acoes` e os destinatários deverá ser consolidado antes das migrations definitivas, de modo que não haja ambiguidade sobre qual áudio foi destinado a qual equipamento.

### Fluxo lógico esperado

1. Receber uma detecção, telemetria ou gatilho temporal.
2. Validar e registrar a mensagem de entrada quando aplicável.
3. Criar/atualizar o evento e relacioná-lo a dispositivos, leituras e locais.
4. Avaliar regras de automação.
5. Registrar a execução geral e suas ações.
6. Resolver destinatários por hierarquia física e compatibilidade funcional.
7. Solicitar descrição contextual e/ou mensagem de áudio ao Llama conforme a regra.
8. Validar formato, tamanho, segurança e conteúdo da resposta.
9. Solicitar geração do áudio ao Piper quando necessária.
10. Registrar metadados de áudio e referências aos arquivos fora do banco.
11. Criar registros de destinatários e comandos individuais.
12. Enviar por MQTT quando a integração estiver ativa.
13. Processar ACK/resultados, atualizar estados, registrar histórico e auditoria.
14. Apresentar os resultados no frontend.

## 12. Desempenho e operação

### O que já está confirmado

- PyTorch está instalado para CPU; `torch.cuda.is_available()` retorna `False`.
- A webcam local abre com OpenCV.
- O tempo de inferência YOLO ainda não foi medido de forma controlada na webcam local.
- O desempenho/latência do Llama local e do Piper ainda não foram medidos neste novo layout.

### O que vamos medir antes de otimizar

- FPS de captura;
- FPS efetivo de inferência YOLO;
- tempo médio e percentis de latência por inferência;
- tempo de geração do Llama por solicitação;
- tempo de síntese Piper;
- CPU, memória e, quando possível, uso do acelerador;
- número de quadros descartados;
- espera nas filas, solicitações coalescidas e eventos atrasados.

### Medidas de controle previstas

- carregar YOLO uma vez no serviço persistente, em vez de recarregá-lo por requisição;
- manter Ollama/modelo aquecido quando a memória disponível permitir, usando configuração de permanência apropriada;
- separar captura e inferência;
- filas limitadas e priorizadas;
- limitar tarefas simultâneas do Llama e do YOLO no início;
- não disparar Llama em cada quadro;
- configurar independentemente o intervalo do YOLO e dos gatilhos temporais do Llama;
- descartar quadros antigos quando baixa latência for mais importante do que processar cada quadro;
- considerar processos adicionais somente após evidência de gargalo e benchmarking.

Uma API melhora o controle e a integração, mas não acelera automaticamente a inferência. Os ganhos devem ser demonstrados com medições antes/depois.

## 13. Segurança e cuidados

- Não colocar senhas de câmera, tokens ou segredos diretamente nos scripts ou em logs.
- A credencial RTSP já apareceu em texto durante esta conversa. Se for real, trocar a senha e usar variável de ambiente/arquivo de configuração protegido na implementação futura.
- Codificar corretamente caracteres reservados dentro da parte de credenciais da URL RTSP.
- Proteger a API Python contra chamadas não autorizadas; o PHP valida sessão/permissões e o serviço só expõe ações necessárias.
- Não expor credenciais MQTT privilegiadas ao navegador.
- Definir ACLs MQTT, prazos de validade de comandos, idempotência, correlação, timeouts e política de repetição.
- Validar toda saída estruturada do Llama antes de usar, especialmente saída JSON; tratar modelo como componente probabilístico e não como fonte de verdade operacional.
- Mensagens de emergência e instruções operacionais precisam de planos aprovados.
- Um comando enviado não prova execução física; estado desconhecido deve ser exibido quando não houver evidência.
- Vídeos/áudios grandes ficam fora do MySQL; armazenar URI/caminho, hash, tamanho, MIME e metadados.

## 14. Próximas etapas, na ordem recomendada

### Etapa 1 — Preparar um teste YOLO com webcam local

1. Ler integralmente `006-deteccao-pessoas-ultralitics.py` usando, por exemplo:

   ```bat
   type 006-deteccao-pessoas-ultralitics.py
   ```

2. Preservar o arquivo original.
3. Criar uma cópia de teste com nome não conflitante, por exemplo `teste-yolo-camera-local.py`, ou acordar primeiro a renumeração de scripts.
4. Alterar somente a fonte de vídeo para a webcam local (`cv2.VideoCapture(0, cv2.CAP_DSHOW)`) e retirar/desativar integrações externas no teste isolado quando isso for necessário para evitar dependências de MQTT/RTSP.
5. Executar com `python teste-yolo-camera-local.py`.
6. Confirmar que o peso YOLO carrega, que aparecem quadros e deteções, e que a janela fecha corretamente.
7. Medir o desempenho básico antes de introduzir threads ou alterar a arquitetura.

### Etapa 2 — Validar os scripts existentes em isolamento

- Inspecionar os scripts de um em um antes de executá-los.
- Não executar scripts de agente/Llama ou MQTT sem entender as dependências e possíveis efeitos externos.
- Verificar caminhos de modelos (`yolo11n.pt`, `yolov8n.pt`) relativos ao diretório de trabalho e preferir caminhos robustos quando adaptados.

### Etapa 3 — Preparar a conexão RTSP quando a infraestrutura estiver acessível

- Confirmar conectividade de rede com a câmera IP.
- Testar a URL sem expor segredo no histórico/relatório.
- Confirmar se o endereço é direto ou se depende do MediaMTX.
- Iniciar MediaMTX somente quando o fluxo de teste realmente o exigir.
- Conferir os endereços de drone/MediaMTX (`localhost` versus endereço de rede) e os paths publicados.

### Etapa 4 — Organizar o script em serviço persistente

- Extrair captura, inferência, configuração e controle de estado em módulos internos sem reescrever modelos que já funcionem.
- Carregar modelo uma vez.
- Implementar fila limitada/worker e coleta de métricas.
- Criar testes para iniciar/parar análise e recuperação de falha de captura.

### Etapa 5 — Adicionar a API

- FastAPI é a opção considerada, ainda não instalada nem implementada.
- Expor health/status, iniciar/parar análise, parâmetros de amostragem e últimas deteções.
- Garantir controle de concorrência para não abrir a mesma câmera múltiplas vezes nem carregar o mesmo modelo em vários workers por engano.

### Etapa 6 — Acrescentar gerenciador de eventos e Llama

- Definir eventos visuais e temporais.
- Configurar separadamente frequência de YOLO e frequência de descrição pelo Llama.
- Adicionar deduplicação, cooldown, prioridades, correlação e limite de fila.
- Gerar saídas separadas para descrição de cena e mensagem curta de áudio.
- Comparar uma chamada Llama por setor com uma chamada estruturada para vários setores.

### Etapa 7 — Integrar Piper, MQTT e CodeIgniter

- Piper gera áudio a partir do texto validado.
- MQTT transporta comandos/metadados/resultados, não o arquivo grande de áudio indiscriminadamente.
- CodeIgniter valida permissões, persiste eventos/operações e chama a API Python quando autorizada.
- Usar as tabelas da modelagem para registrar cada ação, destinatário, comando e resultado.

### Etapa 8 — Inicialização com um clique

Depois que os serviços estiverem estáveis, criar um script de inicialização na raiz ou em `infrastructure/scripts/` que:

1. verifique/inicie os serviços locais necessários;
2. use os caminhos/ambientes corretos;
3. espere readiness/health, em vez de apenas aguardar um tempo fixo;
4. não tente iniciar como processo local um serviço que esteja em outra máquina;
5. inicie o servidor de desenvolvimento CodeIgniter;
6. abra o navegador na URL local;
7. facilite visualizar logs e encerrar os serviços de maneira controlada.

O comando atual do CodeIgniter em desenvolvimento (`php spark serve`) pode continuar sendo iniciado separadamente até essa rotina estar implementada. Ollama/MediaMTX podem já estar ativos como serviços e devem ser verificados para evitar instâncias duplicadas.

## 15. O que não fazer ainda

- Não copiar ou substituir o `venv` original.
- Não apagar o projeto Gasmar.
- Não considerar `main.py` funcional; ele está vazio.
- Não insistir em câmera IP ou drone enquanto estiverem inacessíveis.
- Não instalar/ativar toda a infraestrutura só para testar a webcam local.
- Não criar já uma quantidade grande de threads/processos sem benchmark.
- Não acionar hardware ou enviar comandos MQTT físicos durante testes locais de visão.
- Não tratar mensagens impressas de inicialização como prova de inferência real.
- Não renumerar os scripts originais de modo automático; há colisão atual entre o teste local `000-teste-camera-local.py` e o arquivo original `000-teste-camera-drone.py`.

## 16. Estado de conclusão desta sessão

| Item | Situação |
|---|---|
| Cópia dos arquivos para `services/visao-computacional-server` | Concluída |
| Ambiente virtual novo Python 3.13.2 | Concluído |
| PyTorch CPU/TorchVision CPU | Instalados e importados |
| Instalação das demais dependências a partir do freeze | Concluída |
| `pip check` | Sem dependências quebradas |
| Imports OpenCV/NumPy/PyTorch/TorchVision/Ultralytics | Confirmados |
| Webcam local com OpenCV | Funcionou |
| RTSP da câmera IP | Não conectado; aguardar infraestrutura acessível |
| Fluxo de drone via MediaMTX | Ainda não validado no novo local |
| Inferência YOLO com webcam local | Próximo teste recomendado |
| Ollama/Llama no destino | Não validado nesta sessão |
| Piper TTS no destino | Não validado nesta sessão |
| MQTT conectado ao serviço | Não validado; o script 006 relatou modo de log sem broker local |
| API Python | Ainda não implementada |
| Multithreading/multiprocessing do pipeline | Decisão técnica pendente de benchmark |
| Integração com CodeIgniter/MySQL | Etapa futura |
| Inicialização de tudo com um clique | Etapa futura |

## 17. Instrução para retomar a conversa

Ao retomar, usar este relatório como contexto e **começar pelo próximo passo pendente, sem repetir a cópia, a criação do `venv`, a instalação das dependências ou o teste de webcam já concluídos**.

O próximo passo recomendado é pedir/ler o conteúdo integral de `006-deteccao-pessoas-ultralitics.py` no diretório do TCC-IFES e criar uma cópia segura de teste para YOLO + webcam local. Preservar a lógica original e alterar somente o necessário para trocar a fonte RTSP por `VideoCapture(0, cv2.CAP_DSHOW)` e isolar MQTT/serviços externos para o primeiro teste. Após comprovar a inferência local, medir desempenho antes de criar a API e introduzir concorrência.

Quando a infraestrutura física/rede voltar a estar disponível, retomar os testes RTSP/MediaMTX; quando o pipeline estiver compreendido e estável, seguir para serviço persistente, API, gerenciador de eventos, geração contextual com Llama, Piper, MQTT, persistência CodeIgniter/MySQL e inicialização coordenada com um clique.
