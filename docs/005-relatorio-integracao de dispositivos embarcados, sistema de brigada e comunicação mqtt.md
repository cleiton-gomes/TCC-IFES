# 005 — PRÉ-IMPLEMENTAÇÃO: INTEGRAÇÃO DE DISPOSITIVOS EMBARCADOS, SISTEMA DE BRIGADA, VIDEOMONITORAMENTO E COMUNICAÇÃO MQTT

**Projeto:** IoT-Edge Command Center — TCC-IFES  
**Instituição:** IFES — Campus Serra  
**Etapa:** Planejamento e pré-implementação  
**Status:** Em planejamento — implementação ainda não iniciada  
**Data de referência:** 16/11/2026

---

# 1. INTRODUÇÃO

Este documento registra o planejamento inicial de uma nova etapa do projeto IoT-Edge Command Center, voltada à integração de dispositivos embarcados, câmeras, serviços de processamento de vídeo e infraestrutura de comunicação MQTT.

A proposta consiste em iniciar a validação prática da arquitetura utilizando a placa BitDogLab como primeiro dispositivo embarcado de teste, aproveitando seus recursos de entrada, processamento e sinalização para desenvolver um protótipo de sistema local de alarme de brigada.

Paralelamente, pretende-se organizar a identificação dos equipamentos de videomonitoramento, incluindo câmeras IP, câmeras locais conectadas ao computador, câmeras de notebook e câmeras instaladas em drones.

A arquitetura também deverá permitir uma evolução futura para análise de vídeo com detecção e contagem de pessoas, possibilitando obter informações por câmera, setor, andar e conjunto de áreas monitoradas.

A intenção é evoluir gradualmente de uma interface web demonstrativa, inicialmente alimentada por dados fictícios, para uma arquitetura funcional capaz de receber informações de dispositivos físicos, processar imagens, registrar leituras, identificar eventos e apresentar ocorrências no sistema web.

Essa etapa permitirá validar antecipadamente a comunicação entre os diferentes componentes da arquitetura, reduzindo o acúmulo de integrações pendentes nas fases posteriores do desenvolvimento.

# 2. OBJETIVOS

## 2.1. Objetivo geral

Implementar e validar gradualmente a comunicação entre dispositivos embarcados, câmeras, infraestrutura MQTT, serviços de processamento e o IoT-Edge Command Center, iniciando com um protótipo de sistema local de alarme de brigada baseado na BitDogLab.

## 2.2. Objetivos específicos

- Utilizar a BitDogLab como primeiro nó embarcado de teste.
- Desenvolver uma lógica local de acionamento e desacionamento de alarme por botoeiras.
- Avaliar o uso dos sensores e recursos disponíveis na placa.
- Implementar sinalização local por indicadores visuais e sonoros.
- Integrar o dispositivo à rede local.
- Estabelecer comunicação com um broker MQTT.
- Publicar telemetria, estados operacionais e eventos.
- Organizar uma nomenclatura padronizada para embarcados, câmeras e serviços.
- Preparar a integração de câmeras locais, câmeras IP e câmeras de drones.
- Evoluir o serviço de visão computacional para detecção e contagem de pessoas.
- Permitir a consolidação de informações por câmera, setor, andar e área monitorada.
- Desenvolver um serviço responsável por consumir e validar mensagens MQTT.
- Registrar leituras e eventos no banco de dados MySQL.
- Integrar os dados recebidos às páginas já desenvolvidas no sistema web.
- Preparar a arquitetura para incorporar futuramente sensores, câmeras e módulos de áudio.
- Documentar os testes, decisões técnicas, limitações e resultados obtidos durante cada etapa.

# 3. CONTEXTO ATUAL DO PROJETO

## 3.1. Sistema web

O front-end do IoT-Edge Command Center já foi desenvolvido utilizando CodeIgniter 4, PHP, Bootstrap 5 e MySQL.

A estrutura contempla as seguintes áreas:

- Dashboard.
- Dispositivos.
- Locais.
- Videomonitoramento e eventos.
- Automações e operações.
- Diagnóstico.
- Gêmeo digital.
- Histórico e auditoria.

A interface foi inicialmente construída com dados demonstrativos, permitindo avaliar a organização das informações, os relacionamentos entre dispositivos, componentes, locais e eventos, além dos fluxos de operação.

O desenvolvimento da persistência e das integrações reais representa uma etapa posterior. Portanto, a conclusão visual das páginas não significa que a comunicação MQTT, o processamento de vídeo ou o registro automático de dados já estejam implementados.

## 3.2. Arquitetura prevista

A arquitetura geral considera a separação entre:

- Dispositivos embarcados e sensores.
- Câmeras e fontes de vídeo.
- Rede local e infraestrutura de comunicação.
- Broker MQTT.
- Serviços Python de processamento e integração.
- Serviço de visão computacional.
- Serviço de síntese de áudio.
- Backend web em CodeIgniter 4.
- Banco de dados MySQL.
- Interface web para monitoramento e gerenciamento.

O objetivo é manter cada componente com responsabilidades específicas, evitando concentrar toda a lógica no firmware ou no sistema web.

## 3.3. Infraestrutura disponível ou planejada

| Componente | Finalidade | Situação |
|---|---|---|
| BitDogLab | Primeiro embarcado para testes | Planejado para integração |
| Raspberry Pi | Hospedagem de serviços locais, incluindo broker MQTT | Infraestrutura prevista |
| Mosquitto | Broker MQTT | Integração a validar |
| Notebook/PC | Execução dos serviços de processamento e desenvolvimento | Ambiente de desenvolvimento existente |
| Câmera do notebook | Primeira fonte local de vídeo para testes | Teste de captura já realizado |
| Câmera IP HiLook | Fonte de vídeo de rede | Integração a validar |
| Câmera de drone | Fonte de vídeo móvel | Integração futura |
| MediaMTX | Recepção e encaminhamento de fluxos de vídeo, quando necessário | Configuração a validar |
| Serviço Python | Processamento e integração | Em evolução |
| YOLO | Detecção de objetos e pessoas | Testes e evolução pendentes |
| CodeIgniter 4 | Interface e backend web | Front-end desenvolvido |
| MySQL | Persistência dos dados | Estrutura planejada |
| Piper | Síntese de áudio centralizada | Integração futura |
| Flite | Alternativa de síntese local | A avaliar |

# 4. PRIMEIRO DISPOSITIVO: SISTEMA LOCAL DE ALARME DE BRIGADA

## 4.1. Proposta

A primeira integração física será realizada utilizando a BitDogLab como uma unidade local de acionamento e monitoramento de alarme.

A proposta é aproveitar os recursos disponíveis na placa para desenvolver um protótipo inspirado no funcionamento de um sistema de brigada, permitindo que um operador acione e desative o alarme por meio de botoeiras.

O dispositivo também deverá comunicar seus estados e ocorrências ao sistema central por MQTT.

A lógica local deverá funcionar independentemente da disponibilidade da rede. Dessa maneira, uma interrupção na comunicação não deverá impedir o acionamento da sinalização local.

## 4.2. Identificação do dispositivo

Código proposto:

`BRI-BITDOGLAB-001`

Interpretação:

- `BRI`: identificação funcional do sistema de brigada.
- `BITDOGLAB`: plataforma de hardware utilizada.
- `001`: identificação sequencial do dispositivo.

Cadastro demonstrativo previsto:

| Campo | Valor |
|---|---|
| Código | BRI-BITDOGLAB-001 |
| Nome | Unidade local de alarme de brigada |
| Categoria | Sistema de alarme e sinalização |
| Plataforma | BitDogLab |
| Protocolo | MQTT |
| Função | Acionamento e monitoramento local de alarme |
| Local | Laboratório de testes |
| Situação | Em desenvolvimento |

O identificador deverá permanecer estável mesmo que o dispositivo seja transferido para outro local. A localização física será mantida em campo separado no cadastro de dispositivos.

## 4.3. Funcionamento previsto

O protótipo deverá contemplar inicialmente:

| Entrada ou condição | Comportamento previsto |
|---|---|
| Botoeira de acionamento | Solicitar ativação do alarme local |
| Botoeira de desacionamento/rearme | Solicitar desativação ou rearme, conforme a lógica definida |
| Sensor físico | Permitir acionamento automático, quando configurado |
| Indicadores visuais | Sinalizar o estado operacional |
| Indicador sonoro | Emitir sinalização de alarme |
| Comunicação MQTT | Informar estados, telemetria e eventos |

A utilização de sensores físicos dependerá da identificação dos componentes disponíveis e da definição de seus limites de operação.

O acionamento manual deverá ser diferenciado de um acionamento automático provocado por sensor.

## 4.4. Estados operacionais propostos

| Estado | Descrição |
|---|---|
| `NORMAL` | Sistema aguardando acionamento |
| `ALARME_ATIVO` | Alarme local acionado |
| `ALARME_RECONHECIDO` | Ocorrência reconhecida, mas ainda não necessariamente normalizada |
| `FALHA` | Falha identificada no funcionamento do dispositivo |

Também será necessário distinguir o reconhecimento de uma ocorrência do seu desacionamento. O reconhecimento não deverá, por si só, desligar a sinalização.

A máquina de estados definitiva deverá ser definida durante a implementação do firmware.

# 5. PADRONIZAÇÃO DOS IDENTIFICADORES DOS DISPOSITIVOS E SERVIÇOS

## 5.1. Objetivo

Adotar identificadores que representem a função e a plataforma tecnológica dos equipamentos, permitindo diferenciar os dispositivos cadastrados no IoT-Edge Command Center.

Os códigos deverão ser independentes dos locais físicos e dos endereços de rede.

## 5.2. Padrão geral proposto

`FUNCAO-PLATAFORMA-TECNOLOGIA-NUMERO`

A quantidade de segmentos poderá variar conforme o tipo de equipamento. A identificação deverá permanecer legível, estável e sem informações que mudem frequentemente, como endereço IP ou local de instalação.

## 5.3. Exemplos de dispositivos embarcados

| Identificador | Descrição |
|---|---|
| `BRI-BITDOGLAB-001` | Unidade de alarme de brigada baseada em BitDogLab |
| `GAS-ESP32-LORA-001` | Unidade de monitoramento de gás baseada em ESP32 e LoRa |
| `AUD-ESP32-FLITE-001` | Unidade de áudio com síntese local Flite |
| `AUD-ESP32-PIPER-001` | Unidade de reprodução de áudio gerado pelo servidor Piper |
| `AUD-PIPER-SERVER-001` | Serviço centralizado de síntese Piper |

## 5.4. Identificação das câmeras e fontes de vídeo

A identificação das câmeras deverá considerar tanto a função quanto a plataforma ou origem da captura.

Exemplos propostos:

| Identificador | Descrição |
|---|---|
| `CAM-DRONE-001` | Câmera embarcada no primeiro drone cadastrado |
| `CAM-HILOOK-001` | Câmera IP da linha HiLook |
| `CAM-NOTEBOOK-001` | Webcam integrada ao notebook |
| `CAM-USB-001` | Câmera conectada por USB |
| `CAM-ESP32-001` | Fonte de vídeo baseada em dispositivo ESP32 compatível |

Os exemplos representam categorias distintas de fontes de vídeo. A identificação definitiva deverá considerar o equipamento realmente utilizado.

### A. Câmera de drone

Exemplo:

`CAM-DRONE-001`

Representa uma fonte de vídeo móvel associada ao drone.

Informações adicionais recomendadas:

- Identificador da câmera.
- Identificador do drone associado.
- Modelo da câmera, quando conhecido.
- Tipo de fluxo de vídeo.
- Resolução e taxa de quadros.
- Endereço ou caminho do fluxo.
- Estado da conexão.
- Local ou área monitorada durante a operação.
- Período da captura.

Uma câmera de drone não deverá ser tratada automaticamente como câmera fixa de um setor. Sua área de observação muda durante o voo, e cada sessão de inspeção deverá registrar a área e o intervalo de tempo correspondentes.

### B. Câmera IP HiLook

Exemplo:

`CAM-HILOOK-001`

Representa uma câmera IP instalada em uma posição física, com possibilidade de transmissão por rede local.

Informações adicionais:

- Fabricante e modelo.
- Endereço de rede.
- Protocolo de transmissão.
- Identificador do fluxo RTSP.
- Local de instalação.
- Setor monitorado.
- Campo de visão.
- Estado de conectividade.

As credenciais de acesso deverão permanecer em configuração protegida, sem serem armazenadas em texto aberto no código-fonte ou exibidas no front-end.

### C. Câmera do notebook

Exemplo:

`CAM-NOTEBOOK-001`

Representa a webcam integrada ao computador utilizado para testes.

Essa fonte será útil para validar captura, processamento de quadros, detecção de pessoas e integração com o serviço de visão computacional, sem depender inicialmente da câmera IP ou do drone.

### D. Câmera USB

Exemplo:

`CAM-USB-001`

Representa uma câmera conectada fisicamente ao computador por USB, podendo ser utilizada como fonte adicional de vídeo.

## 5.5. Identificação dos dispositivos de áudio

A arquitetura de áudio deverá diferenciar a geração da voz da reprodução do áudio.

Estão previstas duas modalidades principais:

**A. Síntese local com Flite**

Identificador proposto:

`AUD-ESP32-FLITE-001`

Nessa modalidade, a síntese de voz ocorre localmente no equipamento, utilizando Flite ou uma implementação compatível com os recursos disponíveis no ESP32.

Será necessário avaliar memória, processamento, qualidade de voz e compatibilidade da biblioteca com o hardware.

**B. Síntese centralizada com Piper**

Identificadores propostos:

- `AUD-PIPER-SERVER-001`: serviço responsável pela geração de voz.
- `AUD-ESP32-PIPER-001`: equipamento embarcado responsável pela reprodução do áudio recebido.

Nessa modalidade, o servidor Piper recebe o texto, gera o arquivo de áudio e disponibiliza o conteúdo para o dispositivo embarcado.

O ESP32 recebe as informações necessárias e realiza a reprodução por meio de seu circuito de áudio.

Essa separação permite utilizar diferentes dispositivos de reprodução sem duplicar o processamento de síntese de voz.

# 6. COMUNICAÇÃO MQTT

## 6.1. Organização dos tópicos

A comunicação MQTT deverá utilizar identificadores estáveis de dispositivos, evitando associar o tópico diretamente ao local físico.

Como proposta de padronização, considera-se:

```text
iot-edge/devices/{device_id}/telemetry
iot-edge/devices/{device_id}/status
iot-edge/devices/{device_id}/events
iot-edge/devices/{device_id}/commands
iot-edge/devices/{device_id}/command-results
```

Exemplo para o dispositivo de brigada:

```text
iot-edge/devices/BRI-BITDOGLAB-001/telemetry
iot-edge/devices/BRI-BITDOGLAB-001/status
iot-edge/devices/BRI-BITDOGLAB-001/events
iot-edge/devices/BRI-BITDOGLAB-001/commands
iot-edge/devices/BRI-BITDOGLAB-001/command-results
```

**Situação:** proposta de organização a validar antes de ser adotada como padrão definitivo do projeto.

## 6.2. Finalidade dos tópicos

| Tópico | Finalidade |
|---|---|
| `telemetry` | Envio periódico de leituras e informações operacionais |
| `status` | Comunicação de estados do dispositivo |
| `events` | Comunicação de ocorrências relevantes |
| `commands` | Recebimento de solicitações de operação |
| `command-results` | Retorno dos resultados das solicitações |

## 6.3. Separação entre telemetria e eventos

A telemetria representa informações periódicas ou amostras dos sensores.

Os eventos representam ocorrências relevantes, como:

- Acionamento manual do alarme.
- Acionamento automático por sensor.
- Reconhecimento de ocorrência.
- Desacionamento ou rearme.
- Falha de componente.
- Perda ou recuperação de comunicação.

A geração de eventos deverá considerar mudanças de estado, evitando publicar repetidamente a mesma ocorrência enquanto uma condição permanecer ativa.

Também será necessário implementar mecanismos de identificação única, controle de duplicidade e registro temporal.

## 6.4. Exemplo de mensagem de evento

Estrutura ilustrativa, ainda não implementada:

```json
{
  "event_id": "EVT-BRI-000001",
  "device_id": "BRI-BITDOGLAB-001",
  "event_type": "ALARM_ACTIVATED",
  "activation_source": "MANUAL",
  "state": "ALARME_ATIVO",
  "occurred_at": "2026-11-16T15:30:00Z",
  "is_test": true
}
```

O campo `is_test` permitirá identificar mensagens provenientes de testes controlados. Seu uso não deve ser confundido com a identificação permanente do dispositivo.

Os campos definitivos, formatos de data, validações e versões do payload ainda deverão ser especificados.

# 7. INTEGRAÇÃO COM O BACKEND E BANCO DE DADOS

## 7.1. Fluxo previsto

O backend deverá consumir as mensagens publicadas no broker MQTT, validar sua estrutura e identificar o dispositivo de origem.

Após a validação, deverá realizar o processamento correspondente, incluindo:

1. Identificação do dispositivo.
2. Validação da mensagem recebida.
3. Registro da mensagem de integração.
4. Persistência das leituras, quando aplicável.
5. Atualização do estado operacional.
6. Criação ou atualização de eventos.
7. Associação do evento ao dispositivo, componente e local.
8. Registro de histórico e auditoria, conforme a natureza da operação.
9. Disponibilização dos dados para consulta pelo sistema web.

A mensagem recebida não deverá ser considerada automaticamente uma comprovação de execução física. O backend deverá distinguir solicitações, recebimentos, confirmações e resultados efetivamente informados pelo dispositivo.

## 7.2. Tabelas previstas

A integração deverá aproveitar o modelo de dados já planejado para o projeto, evitando a criação desnecessária de novas tabelas.

| Tabela | Utilização prevista |
|---|---|
| `dispositivos` | Cadastro dos embarcados e equipamentos |
| `componentes_dispositivo` | Cadastro de botoeiras, indicadores e componentes |
| `leituras` | Registro das leituras dos sensores |
| `historico_status_dispositivo` | Histórico de estados dos dispositivos |
| `eventos` | Registro das ocorrências |
| `evento_dispositivos` | Associação entre evento e dispositivo |
| `evento_componentes` | Associação com componentes envolvidos |
| `evento_leituras` | Associação com leituras relacionadas |
| `evento_locais` | Associação da ocorrência aos locais afetados |
| `mensagens_integracao` | Registro do processamento das mensagens MQTT |
| `auditoria` | Registro de ações relevantes do sistema |
| `comandos` | Registro de solicitações enviadas aos dispositivos |
| `historico_comandos` | Histórico de processamento e resultados |

As demais tabelas do modelo poderão ser utilizadas conforme a evolução das integrações.

# 8. VISÃO COMPUTACIONAL E CONTAGEM DE PESSOAS

## 8.1. Objetivo da evolução

Além do monitoramento visual e da identificação de eventos, pretende-se evoluir o serviço de visão computacional para realizar a detecção e a contagem de pessoas nas áreas monitoradas.

A funcionalidade poderá fornecer informações quantitativas para diferentes níveis de organização física:

- Pessoas detectadas por câmera.
- Pessoas estimadas por setor.
- Pessoas estimadas por sala ou corredor.
- Pessoas estimadas por andar.
- Total estimado nas áreas monitoradas.
- Histórico de ocupação ao longo do tempo.

A proposta é aproveitar a hierarquia física já planejada no IoT-Edge Command Center, relacionando cada câmera ao local ou setor que efetivamente monitora.

## 8.2. Funcionamento previsto

O serviço de visão computacional deverá receber os fluxos de vídeo das fontes cadastradas, processar os quadros e identificar pessoas utilizando um modelo de detecção, como YOLO.

O fluxo lógico será:

1. Identificar a fonte de vídeo.
2. Capturar os quadros.
3. Executar a detecção de pessoas.
4. Realizar rastreamento de objetos entre quadros.
5. Determinar entradas e saídas de regiões monitoradas, quando configuradas.
6. Produzir informações de contagem.
7. Associar os resultados à câmera e ao local.
8. Enviar os resultados ao backend.
9. Registrar os dados necessários.
10. Apresentar os indicadores no sistema web.

A contagem não deverá ser baseada simplesmente no número de detecções de cada quadro. Uma pessoa presente durante vários quadros deve ser contabilizada como uma única presença no intervalo ou na região correspondente.

## 8.3. Contagem por câmera

Cada fonte de vídeo poderá apresentar informações próprias:

| Indicador | Descrição |
|---|---|
| Pessoas detectadas | Quantidade de pessoas identificadas no quadro ou janela de processamento |
| Pessoas rastreadas | Quantidade de identificadores de rastreamento ativos |
| Entradas | Quantidade de entradas detectadas em uma região configurada |
| Saídas | Quantidade de saídas detectadas em uma região configurada |
| Ocupação estimada | Quantidade estimada de pessoas presentes na região monitorada |

É necessário diferenciar uma contagem instantânea de detecções de uma estimativa de ocupação. A segunda depende de rastreamento, definição de regiões e controle das entradas e saídas.

## 8.4. Contagem por setor

As câmeras poderão ser associadas a setores físicos, como:

- Sala de controle.
- Corredor norte.
- Área de produção.
- Área externa.
- Outros ambientes cadastrados.

O sistema poderá consolidar a ocupação estimada de cada setor, desde que existam informações suficientes para evitar a contagem duplicada de pessoas observadas por mais de uma câmera.

A associação da câmera ao setor deverá ser configurada no cadastro de dispositivos ou em uma estrutura específica de cobertura de monitoramento.

## 8.5. Contagem por andar

A partir da hierarquia de locais, será possível consolidar os indicadores dos setores vinculados a um determinado andar.

Exemplo ilustrativo:

```text
Edifício A
└── Andar 1
    ├── Sala de controle
    │   └── CAM-HILOOK-001
    ├── Corredor norte
    │   ├── CAM-HILOOK-002
    │   └── CAM-NOTEBOOK-001
    └── Área de produção
        └── CAM-HILOOK-003
```

O sistema poderá apresentar:

- Ocupação estimada por sala.
- Ocupação estimada por corredor.
- Ocupação estimada por setor.
- Total estimado do andar.

Os números deverão ser derivados das regiões monitoradas e dos resultados de contagem, e não da simples soma indiscriminada das detecções das câmeras.

## 8.6. Total de pessoas nas áreas monitoradas

A arquitetura poderá futuramente apresentar um indicador consolidado de pessoas nas áreas monitoradas.

Esse indicador deverá deixar claro:

- Quais câmeras estão incluídas.
- Quais locais estão cobertos.
- Qual o horário de referência.
- Quais fontes estão indisponíveis.
- Se o valor representa ocupação estimada ou contagem de passagem.
- Se existem áreas sem cobertura.
- Se há possibilidade de duplicidade entre câmeras.

O total deverá ser apresentado como estimativa de ocupação das áreas efetivamente monitoradas, não como quantidade absoluta de pessoas em todo o edifício ou instalação.

## 8.7. Problema de duplicidade entre câmeras

Um dos principais desafios será evitar que uma mesma pessoa seja contada em duas ou mais câmeras.

Exemplo:

Uma pessoa sai de uma sala monitorada por `CAM-HILOOK-001` e entra em um corredor monitorado por `CAM-HILOOK-002`.

Se os resultados forem simplesmente somados, a pessoa poderá aparecer duas vezes no total consolidado.

Para reduzir esse problema, poderão ser avaliadas as seguintes técnicas:

- Rastreamento de pessoas dentro de cada câmera.
- Definição de linhas virtuais para entradas e saídas.
- Definição de regiões de interesse.
- Associação entre câmeras com áreas de cobertura complementares.
- Reidentificação entre câmeras, quando tecnicamente e legalmente adequada.
- Controle temporal dos registros de entrada e saída.
- Regras para áreas com câmeras sobrepostas.

O rastreamento dentro de uma câmera não resolve automaticamente a identificação da mesma pessoa em câmeras diferentes. A consolidação entre fontes exigirá uma solução adicional, cuja precisão deverá ser avaliada experimentalmente.

## 8.8. Câmera de drone e contagem de pessoas

A câmera do drone poderá ser utilizada para detectar e estimar a quantidade de pessoas em uma área durante uma inspeção.

Entretanto, sua característica móvel exige tratamento diferente das câmeras fixas.

Será necessário registrar:

- Identificador do drone.
- Identificador da câmera.
- Sessão de voo ou inspeção.
- Horário da captura.
- Área ou região observada.
- Posição do equipamento, quando disponível.
- Resultados da detecção.
- Condições de visibilidade e qualidade da imagem.

A contagem de pessoas observadas pelo drone deverá ser tratada como resultado de uma inspeção em determinado intervalo e área.

Não deverá ser somada diretamente à ocupação atual de câmeras fixas, pois pode representar pessoas já contabilizadas em outras fontes ou em horários diferentes.

# 9. INTEGRAÇÃO COM O FRONT-END E APRESENTAÇÃO DOS INDICADORES

A integração deverá substituir gradualmente os dados fictícios pelas informações efetivamente recebidas e persistidas.

As páginas existentes serão aproveitadas, evitando reconstruir a interface.

| Página | Integração prevista |
|---|---|
| Dashboard | Indicadores de dispositivos, eventos e ocupação estimada |
| Dispositivos | Cadastro e estado de embarcados e câmeras |
| Locais | Associação dos dispositivos e câmeras aos setores físicos |
| Videomonitoramento e eventos | Visualização dos fluxos, detecções e ocorrências |
| Automações e operações | Consulta e gerenciamento de operações autorizadas |
| Diagnóstico | Conectividade, disponibilidade das fontes e processamento |
| Gêmeo digital | Representação dos locais e informações históricas |
| Histórico e auditoria | Consulta das ocorrências e ações registradas |

Na evolução da contagem de pessoas, poderão ser apresentados:

- Total estimado de pessoas por andar.
- Total estimado por setor.
- Ocupação por câmera.
- Entradas e saídas registradas.
- Evolução da ocupação ao longo do tempo.
- Câmeras indisponíveis ou com dados desatualizados.

A atualização dos dados poderá ser realizada por consultas periódicas ou por mecanismos de atualização em tempo real, conforme a arquitetura do backend.

O MQTT permanecerá como protocolo de comunicação entre dispositivos e infraestrutura. Para resultados produzidos por serviços Python, poderá ser utilizado MQTT ou uma API interna, conforme a finalidade e o volume de dados.

Vídeos e arquivos de mídia deverão ser transportados por mecanismos apropriados, como RTSP ou HTTP, e não enviados como grandes mensagens MQTT.

# 10. ARQUITETURA DE ÁUDIO E INTEGRAÇÃO FUTURA

A arquitetura deverá permitir a geração de mensagens de áudio a partir de eventos relevantes.

O fluxo previsto é:

1. Um evento é identificado pelo sistema.
2. O backend avalia se existe uma ação de áudio aplicável.
3. O texto da mensagem é definido e validado.
4. O mecanismo de síntese selecionado gera o áudio.
5. O arquivo é disponibilizado ao dispositivo de reprodução.
6. O equipamento recebe a solicitação.
7. O equipamento informa o resultado da reprodução, quando houver mecanismo de confirmação.
8. O resultado é registrado no sistema.

O Piper será considerado para síntese centralizada, enquanto o Flite será avaliado como alternativa de síntese local.

Os arquivos de áudio deverão permanecer fora do banco MySQL, com metadados, URI, hash, duração e informações de processamento registrados nas tabelas apropriadas.

A publicação de uma mensagem MQTT ou o recebimento de um arquivo não deverá ser interpretado como comprovação de que o áudio foi reproduzido.

# 11. ETAPAS DE IMPLEMENTAÇÃO

A implementação será realizada de maneira incremental, mantendo documentação dos testes e resultados.

## Etapa 1 — Levantamento e teste da BitDogLab

- Identificar o modelo exato da placa e seu microcontrolador