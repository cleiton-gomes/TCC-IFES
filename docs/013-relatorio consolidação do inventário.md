# RELATÓRIO DE CONTINUIDADE 011
# IoT-Edge Command Center
## Consolidação do Inventário e Início da Revisão Global da Modelagem Física do Banco de Dados

**Instituição:** IFES — Campus Serra  
**Projeto:** IoT-Edge Command Center  
**Banco de dados:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4.7.5  
**Autenticação e autorização:** CodeIgniter Shield  
**Linguagem:** PHP 8.2  
**Etapa:** Consolidação do inventário e início da revisão global  
**Situação:** Modelagem conceitual e física preliminar, sem migrations de domínio executadas.

---

# 1. OBJETIVO DESTE RELATÓRIO

Este relatório registra o encerramento da primeira rodada de definição das tabelas de domínio e o início da etapa de revisão global da modelagem física do banco de dados do projeto IoT-Edge Command Center.

O objetivo é manter uma referência única e organizada sobre:

- O inventário de tabelas identificadas.
- A finalidade de cada tabela.
- Exemplos de utilização dentro do sistema.
- As decisões arquiteturais estabelecidas.
- Os relacionamentos entre os diferentes módulos.
- As tabelas adicionais que ainda precisam ser avaliadas.
- Os pontos de atenção para a revisão global.
- A sequência de trabalho até a implementação das migrations.

Este documento não representa a conclusão definitiva do banco de dados. As estruturas foram discutidas e aprovadas conceitualmente, mas ainda precisam passar por uma revisão integrada de campos, tipos, chaves, índices, restrições e dependências.

**Nenhuma migration de domínio foi criada ou executada nesta etapa.**

# 2. ESTADO ATUAL DO AMBIENTE

## 2.1. Configuração do projeto

O projeto utiliza:

| Item | Configuração |
|---|---|
| Framework | CodeIgniter 4.7.5 |
| PHP | 8.2 |
| Banco de dados | MySQL 8.0 |
| Nome do banco | `iot_edge_center` |
| Host | `localhost` |
| Porta | `3306` |
| Usuário | `root` |
| Driver | MySQLi |
| Autenticação | CodeIgniter Shield |

## 2.2. Tabelas de infraestrutura existentes

O CodeIgniter Shield e o CodeIgniter Settings já possuem suas estruturas instaladas no banco.

As nove tabelas identificadas são:

1. `users`
2. `auth_identities`
3. `auth_logins`
4. `auth_token_logins`
5. `auth_remember_tokens`
6. `auth_groups_users`
7. `auth_permissions_users`
8. `settings`
9. `migrations`

Essas tabelas não fazem parte do inventário das tabelas de domínio IoT.

As migrations de infraestrutura já foram executadas e registradas no batch 1:

- `CodeIgniter\Shield`: `create_auth_tables`.
- `CodeIgniter\Settings`: `CreateSettingsTable`.
- `CodeIgniter\Settings`: `AddContextColumn`.
- `CodeIgniter\Settings`: `ConvertSqlsrvValueColumn`.

## 2.3. Estrutura real da tabela `users`

Foi executado `SHOW CREATE TABLE users` no MySQL Workbench.

A definição real confirmou:

- `id`: `INT UNSIGNED`, chave primária e auto incremento.
- `username`: `VARCHAR(30)`, único e opcional.
- `status`: `VARCHAR(255)`.
- `status_message`: `VARCHAR(255)`.
- `active`: `TINYINT(1)`, padrão 0.
- `last_active`: `DATETIME`.
- `created_at`: `DATETIME`.
- `updated_at`: `DATETIME`.
- `deleted_at`: `DATETIME`.

Consequentemente, todas as futuras chaves estrangeiras que apontarem para `users.id` deverão utilizar `INT UNSIGNED`, e não `BIGINT UNSIGNED`.

As chaves primárias das tabelas de domínio continuam utilizando `BIGINT UNSIGNED` como referência inicial de modelagem.

## 2.4. Estado das migrations

A pasta:

`app/Database/Migrations`

estava vazia, contendo somente `.gitkeep`.

A configuração `app/Config/Migrations.php` já possui:

- Migrations habilitadas.
- Tabela de controle `migrations`.
- Formato de timestamp `Y-m-d-His_`.
- Lock desabilitado.

Não há necessidade identificada de alterar essa configuração neste momento.

# 3. INVENTÁRIO CONSOLIDADO DAS TABELAS DE DOMÍNIO

O inventário atual possui **42 tabelas identificadas**, distribuídas em oito grupos funcionais.

| Grupo | Quantidade |
|---|---:|
| Estrutura física | 4 |
| Telemetria e gêmeo digital | 5 |
| Eventos e evidências | 7 |
| Inteligência artificial | 8 |
| Integração e comandos | 3 |
| Automação | 5 |
| Áudio e auditoria | 3 |
| Cenas e cenários | 7 |
| **Total** | **42** |

A contagem representa o inventário atual de trabalho. Ela ainda poderá ser modificada durante a revisão global, especialmente se forem identificadas redundâncias ou necessidades de novas tabelas.

# 4. ESTRUTURA FÍSICA

Este grupo representa os locais físicos, os equipamentos instalados, seus componentes e o histórico de movimentação.

## 4.1. `locais`

**Finalidade:** cadastrar a estrutura física e hierárquica da instalação.

Permite representar edifícios, andares, zonas, salas, corredores e áreas externas.

Exemplo de hierarquia:

```text
Edifício Industrial
├── Térreo
│   ├── Zona A
│   │   ├── Sala de Controle
│   │   └── Área de Sensores
│   └── Zona B
└── Primeiro Andar
    ├── Sala Técnica
    └── Corredor Principal
```

Características definidas:

- Identificador numérico.
- Código globalmente único.
- Nome e tipo do local.
- Relacionamento opcional com local pai.
- Descrição e coordenadas geográficas opcionais.
- Referência textual.
- Indicador de atividade.
- Timestamps automáticos.

Tipos iniciais:

- `edificio`
- `andar`
- `zona`
- `sala`
- `corredor`
- `area_externa`

A hierarquia será consultada por consultas recursivas `WITH RECURSIVE` do MySQL.

Não serão permitidos ciclos na hierarquia nem exclusão física de locais que possuam relacionamentos históricos.

## 4.2. `dispositivos`

**Finalidade:** cadastrar os equipamentos físicos completos utilizados pelo sistema.

Cada equipamento recebe um código estável e independente de sua localização física.

Exemplos:

| Código ilustrativo | Nome | Categoria |
|---|---|---|
| `EMB-GAS-001` | Monitor de gás da Zona A | Sensor |
| `EMB-CAM-001` | Câmera de monitoramento da Zona B | Câmera |
| `EMB-AUDIO-001` | Sistema de áudio da Zona A | Controlador |
| `EMB-GW-001` | Gateway de comunicação industrial | Gateway |

Campos e características previstos:

- Código único.
- Nome.
- Categoria.
- Fabricante e modelo.
- Número de série.
- Protocolo principal.
- Tópico-base MQTT.
- Endereço ou endpoint de rede.
- Status operacional.
- Último contato confirmado.
- Configuração geral em JSON.
- Localização opcional.
- Indicador de atividade.
- Timestamps.

Categorias iniciais:

` sensor`, `camera`, `controlador`, `gateway`, `atuador`, `equipamento` e `outro`.

Estados iniciais:

`desconhecido`, `online`, `offline`, `alerta` e `manutencao`.

A localização será independente do código do dispositivo. Uma movimentação física não modificará sua identificação nem seu tópico MQTT.

Equipamentos que utilizam MQTT terão seu `topico_base_mqtt` cadastrado.

Exemplo:

```text
iot-edge/devices/emb-audio-001
```

Os canais serão derivados desse tópico-base:

```text
/telemetry
/status
/events
/commands
/command-results
```

Um equipamento poderá utilizar mais de um protocolo. O campo `protocolo` identificará o principal, enquanto protocolos complementares poderão ser registrados na configuração JSON.

## 4.3. `componentes_dispositivo`

**Finalidade:** representar componentes físicos e módulos funcionais de hardware que pertencem a um equipamento.

A tabela não será utilizada para cadastrar serviços exclusivamente de software, como Piper, Flite ou Ollama.

Exemplo de componentes:

| Dispositivo | Componente | Tipo |
|---|---|---|
| Heltec LoRa32 V3 | MQ4-001 | Sensor |
| Heltec LoRa32 V3 | LORA-001 / SX1262 | Comunicação |
| Heltec LoRa32 V3 | OLED-001 | Interface |
| Heltec LoRa32 V3 | ESP32-001 | Processamento |
| ESP32 de áudio | DAC-001 | Áudio |
| ESP32 de áudio | AMP-001 | Áudio |
| ESP32 de áudio | SPK-001 | Áudio |

Campos previstos:

- Identificador.
- Dispositivo proprietário.
- Código individual.
- Nome.
- Tipo.
- Fabricante e modelo.
- Número de série.
- Descrição.
- Configuração JSON.
- Status.
- Indicador de atividade.
- Timestamps.

A combinação `dispositivo_id + codigo` será única.

As leituras poderão ser associadas diretamente ao componente que as produziu.

## 4.4. `historico_localizacao_dispositivo`

**Finalidade:** preservar o histórico das localizações físicas dos equipamentos.

Exemplo:

```text
Dispositivo: EMB-GAS-001

Período 1:
Edifício Industrial / Térreo / Zona A
01/10/2026 até 15/11/2026

Período 2:
Edifício Industrial / Térreo / Zona B
A partir de 15/11/2026
```

Campos previstos:

- Dispositivo.
- Local.
- Usuário responsável pela movimentação.
- Início da vigência.
- Fim da vigência.
- Motivo.
- Observações.
- Data de registro.

O usuário responsável aponta para `users.id`, utilizando `INT UNSIGNED`.

Decisão: se um equipamento ainda não estiver instalado, seu `local_id` será NULL e não haverá registro de localização. O histórico começará na primeira instalação.

Apenas uma localização poderá estar vigente por dispositivo.

A movimentação deverá atualizar o dispositivo, fechar o período anterior, abrir o novo período e registrar a auditoria dentro de uma mesma transação.

# 5. TELEMETRIA E GÊMEO DIGITAL

## 5.1. `leituras`

**Finalidade:** armazenar as medições recebidas dos sensores e equipamentos.

Exemplos:

| Dispositivo/componente | Métrica | Valor | Unidade |
|---|---|---:|---|
| MQ4-001 | `gas_level` | 2750 | ADC |
| LORA-001 | `rssi` | -72 | dBm |
| LORA-001 | `snr` | 8.5 | dB |
| ESP32-001 | `connection_status` | online | — |
| Sistema de áudio | `playback_status` | completed | — |

Campos previstos:

- Dispositivo.
- Componente opcional.
- Métrica.
- Valor numérico.
- Valor textual.
- Unidade.
- Qualidade.
- Payload original JSON.
- Horário da medição.
- Horário do recebimento.
- Indicador de proteção contra retenção.
- Data de criação.

Foi decidido permitir que `valor_numerico` e `valor_textual` coexistam. Ambos poderão ser nulos individualmente, mas o backend deverá validar que pelo menos um esteja preenchido.

A associação entre componente e dispositivo será validada pelo backend.

Importante: a leitura ADC do sensor MQ-4 não deve ser apresentada como ppm sem calibração adequada.

RSSI e SNR devem ser relacionados preferencialmente ao componente de comunicação, e não ao sensor de gás.

## 5.2. `historico_status_dispositivo`

**Finalidade:** registrar todas as transições de estado dos equipamentos.

Exemplo:

```text
Dispositivo: EMB-GAS-001

desconhecido → online
online → alerta
alerta → offline
offline → online
```

Campos previstos:

- Dispositivo.
- Evento relacionado, quando houver.
- Status anterior.
- Status novo.
- Motivo.
- Origem.
- Detalhes JSON.
- Horário em que ocorreu.
- Horário em que foi registrado.

Origens iniciais:

`mqtt`, `sistema`, `usuario`, `monitoramento` e `integracao`.

O campo `status_anterior` poderá ser NULL na primeira identificação.

A atualização de `dispositivos.status` e a inserção do histórico deverão ocorrer na mesma transação.

## 5.3. `versoes_configuracao`

**Finalidade:** preservar versões completas da configuração dos equipamentos.

Exemplo:

```text
Dispositivo: EMB-GAS-001

Versão 1:
Configuração inicial do MQ-4,
comunicação LoRa e parâmetros do ESP32.

Versão 2:
Ajuste dos parâmetros do sensor,
intervalo de leitura e comunicação.
```

Campos previstos:

- Dispositivo.
- Evento relacionado.
- Usuário responsável.
- Número da versão.
- Configuração JSON.
- Hash SHA-256.
- Origem.
- Descrição.
- Início da vigência.
- Data de criação.

A combinação `dispositivo_id + versao` será única.

Decisão importante: `dispositivos.configuracao` representa a configuração geral atual e `componentes_dispositivo.configuracao` representa as configurações atuais individuais.

A tabela `versoes_configuracao` deverá preservar um snapshot completo do equipamento, incluindo as configurações de seus componentes.

A atualização da configuração atual e o registro de uma nova versão deverão ocorrer na mesma transação.

## 5.4. `historico_estado_gemeo`

**Finalidade:** preservar os estados históricos do gêmeo digital.

Exemplos:

- Estado de telemetria.
- Estado operacional.
- Estado de disponibilidade.
- Estado de reprodução de áudio.
- Estado de configuração.
- Estado confirmado após comando.

Campos previstos:

- Dispositivo.
- Evento.
- Leitura.
- Comando.
- Versão de configuração.
- Tipo de estado.
- Estado JSON.
- Origem.
- Horário do acontecimento.
- Horário do registro.

Decisão: o gêmeo digital poderá armazenar snapshots completos ou parciais.

Isso evita gerar um snapshot completo a cada mensagem MQTT, reduzindo redundância.

O gêmeo digital será uma representação histórica e consultiva. Não deverá enviar comandos físicos aos equipamentos.

A semântica dos snapshots e a coerência entre as referências de origem serão revisadas globalmente.

## 5.5. `sessoes_coleta`

**Finalidade:** organizar períodos de coleta de dados associados a eventos, diagnósticos, atividades manuais ou processos automatizados.

Exemplos:

- Sessão contínua de monitoramento de gás.
- Coleta iniciada após um evento de alerta.
- Diagnóstico de um dispositivo.
- Coleta manual solicitada por operador.
- Coleta automatizada executada por regra.

Campos previstos:

- Evento opcional.
- Dispositivo opcional.
- Usuário responsável.
- Código único.
- Tipo.
- Status.
- Início.
- Encerramento.
- Parâmetros JSON.
- Resumo JSON.
- Data de criação.

Tipos:

`continua`, `evento`, `diagnostico`, `manual` e `automatizada`.

Estados:

`aguardando`, `em_execucao`, `concluida`, `cancelada` e `falhou`.

A possibilidade de uma sessão envolver vários equipamentos será avaliada por meio de uma tabela associativa adicional chamada `sessao_dispositivos`.

# 6. EVENTOS E EVIDÊNCIAS

## 6.1. `eventos`

**Finalidade:** registrar ocorrências relevantes do sistema.

Exemplos:

- Detecção de gás elevado.
- Identificação visual de uma ocorrência.
- Interpretação contextual produzida pelo LLM.
- Perda de comunicação.
- Falha de execução de comando.
- Manutenção.
- Alerta de segurança.

Campos previstos:

- Evento pai.
- Usuário responsável.
- Código único.
- Tipo.
- Severidade.
- Status.
- Origem.
- Título.
- Descrição.
- Dados JSON.
- Horário do acontecimento.
- Horário do registro.
- Horário de reconhecimento.
- Horário de encerramento.
- Timestamps.

Severidades iniciais:

`informativa`, `baixa`, `media`, `alta` e `critica`.

Estados:

`aberto`, `reconhecido`, `em_atendimento`, `resolvido`, `encerrado` e `cancelado`.

Origens:

`dispositivo`, `sistema`, `usuario`, `visao_computacional`, `interpretacao_contextual`, `integracao` e `automacao`.

Foi aprovado o uso de eventos pai e filhos quando ocorrências distintas estiverem relacionadas.

Exemplo:

```text
Evento principal:
Alerta de gás elevado na Zona A.

Eventos relacionados:
├── Detecção visual de movimentação na área.
├── Interpretação contextual do LLM.
└── Falha de comunicação de um dispositivo.
```

## 6.2. `evento_locais`

**Finalidade:** relacionar eventos a um ou vários locais.

Exemplo:

Um evento de vazamento pode afetar a Zona A e a Zona B, além de possuir um local de origem identificado.

Papéis:

`origem`, `afetado`, `abrangencia` e `referencia`.

A combinação `evento_id + local_id` será única.

## 6.3. `evento_dispositivos`

**Finalidade:** relacionar eventos a equipamentos físicos.

Exemplo:

Um evento pode ser originado pelo `EMB-GAS-001` e afetar também o `EMB-AUDIO-001`, que deverá emitir um alerta.

Papéis:

`origem`, `afetado`, `participante`, `destinatario` e `referencia`.

A combinação `evento_id + dispositivo_id` será única.

## 6.4. `evento_componentes`

**Finalidade:** relacionar eventos aos componentes específicos dos equipamentos.

Exemplo:

Um evento de leitura anormal poderá identificar o componente MQ-4 responsável pela medição.

Papéis:

`origem`, `afetado`, `participante` e `referencia`.

O backend deverá validar que o componente pertence ao dispositivo relacionado ao evento quando aplicável.

## 6.5. `evento_leituras`

**Finalidade:** associar leituras de telemetria aos eventos que elas originaram ou contextualizaram.

Exemplo:

Um evento de gás elevado poderá estar associado às leituras que ultrapassaram o limite estabelecido.

Papéis:

`gatilho`, `evidencia`, `contexto` e `posterior`.

A combinação `evento_id + leitura_id` será única.

## 6.6. `anexos`

**Finalidade:** armazenar metadados e referências de arquivos externos.

Tipos de arquivos:

- Imagens.
- Vídeos.
- Áudios.
- Documentos.
- Outros arquivos relevantes.

Campos previstos:

- Código.
- Nome original.
- Nome armazenado.
- MIME.
- Categoria.
- Extensão.
- Tamanho.
- Hash SHA-256.
- URI de armazenamento.
- Descrição.
- Metadados JSON.
- Usuário responsável.
- Data de criação.

Decisão: o MySQL não armazenará os arquivos binários.

Imagens, vídeos, áudios e documentos permanecerão em armazenamento externo.

Não serão criados registros de anexo para cada frame de uma transmissão ao vivo.

## 6.7. `evento_anexos`

**Finalidade:** relacionar evidências externas aos eventos.

Exemplo:

Um evento de detecção visual poderá possuir:

- Uma imagem da ocorrência.
- Um vídeo curto.
- Um documento técnico relacionado.

Papéis:

`evidencia`, `entrada_analise`, `resultado` e `contexto`.

A combinação `evento_id + anexo_id` será única.

# 7. INTELIGÊNCIA ARTIFICIAL

Este grupo representa o catálogo de artefatos, suas versões, pipelines, implantações e execuções.

A arquitetura contempla visão computacional, interpretação contextual com LLM, coordenador, especialistas e validação.

## 7.1. `analises_ia`

**Finalidade:** registrar cada execução de inteligência artificial.

Tipos iniciais:

- `visao_computacional`
- `interpretacao_contextual`
- `classificacao`
- `deteccao`
- `reconhecimento`
- `validacao`

Exemplos:

```text
Execução principal:
Pipeline de interpretação contextual.

Execuções filhas:
├── Especialista de segurança.
├── Especialista de contexto.
├── Especialista de risco.
└── Validador.
```

Campos previstos:

- Código.
- Análise pai.
- Evento.
- Dispositivo.
- Implantação.
- Versão do pipeline.
- Versão do artefato.
- Tipo.
- Serviço.
- Modelo.
- Versão do modelo.
- Status.
- Entrada JSON.
- Resultado JSON.
- Confiança.
- Duração.
- Erro.
- Início.
- Conclusão.
- Data de criação.

Foi aprovada uma estrutura hierárquica:

- A execução principal referencia a versão do pipeline.
- Cada execução especialista referencia sua versão exata do artefato.
- `analise_pai_id` relaciona as execuções filhas à execução principal.

Foi decidido que `pipeline_versao_id` e `artefato_versao_id` serão mutuamente exclusivos.

Também foi aprovada a inclusão do campo:

`metricas JSON`

Esse campo permitirá registrar as métricas produzidas durante cada execução.

Assim, os especialistas terão resultados individuais, enquanto a execução principal poderá guardar os resultados consolidados pelo coordenador.

## 7.2. `analise_anexos`

**Finalidade:** relacionar imagens, vídeos e outros arquivos às execuções de IA.

Exemplo:

Uma análise de visão computacional poderá utilizar uma imagem de entrada e produzir uma imagem de evidência.

Papéis:

`entrada`, `evidencia`, `resultado`, `frame` e `referencia`.

A combinação `analise_id + anexo_id + papel` será única.

Não serão armazenados continuamente todos os frames de uma transmissão.

## 7.3. `artefatos_ia`

**Finalidade:** cadastrar os artefatos lógicos de inteligência artificial.

Exemplos:

- Script de visão computacional.
- Modelo de detecção.
- Coordenador de IA.
- Especialista de segurança.
- Especialista de contexto.
- Especialista de risco.
- Validador.

A tabela representa o cadastro lógico, não o arquivo Python ou o modelo binário.

## 7.4. `versoes_artefatos_ia`

**Finalidade:** preservar versões imutáveis dos artefatos.

Exemplo:

```text
Artefato: Especialista de segurança

Versão 1.0:
Prompt e regras iniciais.

Versão 1.1:
Ajustes nas instruções e critérios de classificação.

Versão 2.0:
Nova lógica de interpretação.
```

Campos conceituais:

- Artefato proprietário.
- Identificador da versão.
- Hash.
- URI do arquivo.
- Dependências.
- Configuração.
- Estado.
- Data de publicação.
- Usuário responsável.

Uma versão publicada não deverá ser sobrescrita.

## 7.5. `implantacoes_ia`

**Finalidade:** registrar onde e como uma versão de artefato ou pipeline foi implantada.

Exemplo:

Um script de visão computacional poderá ser implantado em três câmeras diferentes.

Uma câmera poderá possuir simultaneamente:

- Um script de detecção.
- Um script experimental.
- Um pipeline de interpretação contextual.

Foi aprovada a relação muitos-para-muitos entre equipamentos e artefatos/pipelines por meio das implantações.

Cada implantação deverá apontar para uma versão de artefato ou uma versão de pipeline, nunca para ambas simultaneamente.

## 7.6. `pipelines_ia`

**Finalidade:** cadastrar pipelines lógicos de inteligência artificial.

Exemplo:

```text
Pipeline:
Análise contextual de segurança.

Funções:
Coordenador
├── Especialista de segurança.
├── Especialista de contexto.
├── Especialista de risco.
└── Validador.
```

A tabela representa o cadastro lógico do pipeline.

A definição do coordenador e dos especialistas será preservada nas versões do pipeline.

## 7.7. `versoes_pipelines_ia`

**Finalidade:** preservar versões imutáveis das composições dos pipelines.

Cada versão deverá identificar o coordenador exato utilizado.

O campo `coordenador_versao_id` foi proposto como obrigatório.

Exemplo:

```text
Pipeline: Análise contextual

Versão 1.0:
Coordenador 1.0
Especialistas 1.0

Versão 1.1:
Coordenador 1.1
Especialistas 1.0 e 1.1
```

A composição histórica não deverá ser modificada após publicação.

## 7.8. `pipeline_componentes_ia`

**Finalidade:** relacionar uma versão de pipeline às versões exatas dos artefatos especialistas.

Campos conceituais:

- Versão do pipeline.
- Versão do artefato.
- Código do componente.
- Papel.
- Ordem de execução.
- Grupo de execução.
- Dependências JSON.
- Configuração JSON.
- Parâmetros de execução JSON.
- Indicador de obrigatoriedade.
- Indicador de habilitação.
- Métricas esperadas JSON.
- Data de criação.

Exemplo:

| Componente | Papel | Grupo |
|---|---|---:|
| Especialista de segurança | Segurança | 1 |
| Especialista de contexto | Contexto | 1 |
| Especialista de risco | Risco | 1 |
| Validador | Validação | 2 |

Os especialistas do grupo 1 poderão executar em paralelo, e o validador poderá utilizar os resultados anteriores no grupo 2.

A execução paralela dependerá da configuração do serviço e dos recursos disponíveis.

### Métricas individuais e coletivas

Foi aprovada a utilização de `metricas_esperadas` para definir as métricas que cada componente deverá produzir.

Exemplos:

| Especialista | Métricas possíveis |
|---|---|
| Segurança | Nível de risco, ocorrências críticas, confiança |
| Contexto | Elementos identificados, contexto operacional, confiança |
| Risco | Pontuação, fatores contribuintes, classificação |
| Validador | Consistência, divergências, validação |

As métricas efetivamente produzidas serão registradas em `analises_ia.metricas`.

O coordenador poderá reunir os resultados individuais, comparar avaliações e produzir uma consolidação coletiva.

Não se deve presumir que todas as métricas possam ser somadas ou comparadas diretamente. O significado, a unidade e o método de agregação precisarão ser definidos.

### Arquitetura de execução

O projeto utiliza Ollama com Llama 3.1 8B.

Os especialistas são funções lógicas independentes e não necessariamente modelos carregados simultaneamente.

Vários prompts especialistas poderão utilizar o mesmo modelo carregado, cada qual com suas instruções e contexto.

A execução poderá ser sequencial ou concorrente, dependendo da configuração do Ollama e dos recursos de CPU, GPU e memória.

# 8. INTEGRAÇÃO E COMANDOS

## 8.1. `mensagens_integracao`

**Finalidade:** registrar mensagens de comunicação recebidas e enviadas pelo sistema.

Exemplos:

- Mensagem MQTT recebida de um sensor.
- Comando MQTT publicado para um dispositivo.
- Confirmação recebida.
- Comunicação HTTP com o servidor de áudio.
- Mensagem duplicada ou inválida.

Campos previstos:

- Código.
- Dispositivo.
- Comando.
- Evento.
- Análise de IA.
- Protocolo.
- Direção.
- Tópico.
- Identificador da mensagem.
- Correlation ID.
- Chave de idempotência.
- Payload JSON.
- Payload bruto.
- Status.
- Erro.
- Horários de recebimento e processamento.
- Data de criação.

Protocolos:

`mqtt`, `http`, `https`, `websocket` e `outro`.

Direções:

`entrada` e `saida`.

Estados:

`recebida`, `validando`, `processada`, `enviada`, `confirmada`, `falhou`, `duplicada` e `descartada`.

A tabela não deverá armazenar segredos nem arquivos binários.

## 8.2. `comandos`

**Finalidade:** registrar comandos individuais enviados aos equipamentos.

Exemplos:

- Acionar um alarme.
- Ativar um atuador.
- Alterar configuração.
- Reproduzir áudio.
- Solicitar diagnóstico.

Cada comando será direcionado a um dispositivo individual, mesmo quando uma ação atingir vários equipamentos.

Campos previstos:

- Código.
- Dispositivo.
- Componente opcional.
- Evento.
- Usuário.
- Tipo.
- Parâmetros JSON.
- Status.
- Correlation ID.
- Chave de idempotência.
- Horários de solicitação, envio, recebimento, execução e expiração.
- Timestamps.

Estados:

`solicitado`, `enviado`, `recebido`, `executado`, `falhou`, `expirado`, `cancelado` e `desconhecido`.

O envio não será considerado confirmação de execução.

## 8.3. `historico_comandos`

**Finalidade:** preservar todas as transições de estado dos comandos.

Exemplo:

```text
Comando CMD-001

solicitado
    ↓
enviado
    ↓
recebido
    ↓
executado
```

Caso ocorra falha, a transição será registrada com detalhes e erro.

Campos previstos:

- Comando.
- Status anterior.
- Status novo.
- Origem.
- Mensagem de integração relacionada.
- Detalhes JSON.
- Erro.
- Horário do acontecimento.
- Horário do registro.

A atualização do comando e a inserção de seu histórico deverão ocorrer na mesma transação.

# 9. AUTOMAÇÃO

## 9.1. `regras_automacao`

**Finalidade:** cadastrar as regras que determinam quando uma automação deverá ser executada.

Exemplos:

- Se houver evento de gás elevado, executar ações de segurança.
- Se uma câmera identificar uma ocorrência, iniciar análise contextual.
- Se um equipamento perder comunicação, registrar alerta.
- Executar uma ação em horário programado.

Campos previstos:

- Código.
- Nome.
- Descrição.
- Tipo de gatilho.
- Condições JSON.
- Prioridade.
- Indicador de atividade.
- Cooldown.
- Vigência inicial e final.
- Usuário responsável.
- Timestamps.

Gatilhos:

`evento`, `telemetria`, `status_dispositivo`, `analise_ia`, `interpretacao_ia`, `agendamento`, `manual` e `outro`.

## 9.2. `acoes_automacao`

**Finalidade:** definir as ações individuais pertencentes a cada regra.

Exemplos:

- Ativar uma cena.
- Ativar um cenário.
- Acionar um dispositivo.
- Acionar um componente.
- Reproduzir áudio.
- Gerar áudio.
- Registrar evento.
- Executar análise de IA.
- Notificar operador.
- Executar integração externa.

Cada ação possuirá configuração, ordem de execução, obrigatoriedade e indicador de atividade.

Uma regra poderá conter várias ações.

## 9.3. `execucoes_automacao`

**Finalidade:** registrar cada ocorrência de execução de uma regra.

Exemplo:

```text
Regra:
Alerta de gás elevado.

Execução:
Evento EVT-001.

Resultado:
Concluída parcialmente.
```

Campos previstos:

- Regra.
- Evento.
- Código.
- Chave de idempotência.
- Status.
- Entrada JSON.
- Resultado JSON.
- Erro.
- Início.
- Conclusão.
- Timestamps.

Estados:

`aguardando`, `em_execucao`, `concluida`, `concluida_parcialmente`, `falhou`, `cancelada` e `expirada`.

## 9.4. `execucoes_acoes`

**Finalidade:** acompanhar a execução individual de cada ação de uma automação.

Exemplo:

```text
Execução da regra:
Alerta de gás elevado.

Ações:
1. Ativar cena de segurança.
2. Gerar mensagem de áudio.
3. Enviar comandos aos dispositivos.
4. Registrar resultado.
```

Campos previstos:

- Execução da automação.
- Ação.
- Status.
- Tentativa.
- Entrada JSON.
- Resultado JSON.
- Erro.
- Início.
- Conclusão.
- Timestamps.

Estados:

`aguardando`, `em_execucao`, `concluida`, `falhou`, `ignorada`, `cancelada` e `expirada`.

## 9.5. `execucao_destinatarios`

**Finalidade:** registrar os equipamentos selecionados para receber uma ação de automação.

Exemplo:

```text
Ação:
Reproduzir alerta na Zona A.

Destinatários:
├── EMB-AUDIO-001
├── EMB-ALARME-002
└── EMB-GATEWAY-001
```

Cada destinatário possuirá seu próprio status, tentativas, detalhes, erro e horários.

Campos previstos:

- Execução da ação.
- Dispositivo.
- Local considerado.
- Comando.
- Status.
- Tentativas.
- Detalhes JSON.
- Erro.
- Horários de seleção, envio, confirmação e conclusão.
- Timestamps.

Estados:

`selecionado`, `aguardando`, `enviado`, `recebido`, `executando`, `concluido`, `falhou`, `expirado`, `cancelado`, `desconhecido` e `ignorado`.

O campo `local_id` preservará a localização considerada no momento da seleção.

# 10. ÁUDIO E AUDITORIA

## 10.1. `mensagens_audio`

**Finalidade:** registrar solicitações e resultados de geração de áudio.

A arquitetura deverá contemplar dois mecanismos principais:

- Piper centralizado em um Raspberry Pi.
- Flite executado localmente nos equipamentos embarcados.

Exemplo:

```text
Interpretação contextual do LLM
          ↓
Frase gerada
          ↓
Solicitação de síntese
          ↓
Piper ou Flite
          ↓
Arquivo de áudio ou reprodução local
```

Campos previstos:

- Código.
- Análise de IA.
- Evento.
- Execução de ação.
- Usuário.
- Texto.
- Mecanismo TTS.
- Provedor.
- Modelo de voz.
- Idioma.
- Formato de áudio.
- Anexo.
- Status.
- Duração.
- Erro.
- Horários de solicitação, geração e disponibilização.
- Timestamps.

Mecanismos:

`piper`, `flite` e `outro`.

Estados:

`solicitada`, `processando`, `gerada`, `disponibilizada`, `falhou` e `cancelada`.

Os arquivos de áudio permanecerão em armazenamento externo.

A geração do áudio não significa que ele tenha sido recebido ou reproduzido.

## 10.2. `mensagem_audio_destinatarios`

**Finalidade:** acompanhar a distribuição de uma mesma mensagem de áudio para vários dispositivos.

Foi aprovada a abordagem de gerar uma única mensagem e relacioná-la a vários destinatários.

Exemplo:

```text
Mensagem de áudio:
AUD-2026-0001

Destinatários:
├── EMB-AUDIO-001 — Zona A
├── EMB-ALARME-002 — Zona B
└── EMB-AUDIO-003 — Corredor Principal
```

Campos previstos:

- Mensagem de áudio.
- Dispositivo.
- Local considerado.
- Comando.
- Tópico de destino.
- Status.
- Tentativas.
- Detalhes JSON.
- Erro.
- Horários de solicitação, envio, recebimento, início e reprodução.
- Timestamps.

Estados:

`aguardando`, `enviado`, `recebido`, `reproduzindo`, `reproduzido`, `falhou`, `expirado`, `cancelado` e `desconhecido`.

A combinação `mensagem_audio_id + dispositivo_id` será única.

### Identificação dos destinatários

A distribuição deverá identificar o equipamento real cadastrado, e não somente uma categoria genérica como ESP32.

A identificação deverá utilizar:

1. O cadastro do dispositivo.
2. Seu código e nome.
3. O local físico associado.
4. O tópico MQTT utilizado no envio.

Exemplo de tópico:

```text
iot-edge/devices/emb-audio-001/commands
```

Foram aprovados os campos `local_id` e `topico_destino` para preservar a localização considerada e o tópico efetivamente utilizado.

Isso evita que alterações futuras de local ou tópico prejudiquem a interpretação histórica das distribuições anteriores.

## 10.3. `auditoria`

**Finalidade:** registrar operações relevantes realizadas por usuários, dispositivos, serviços, automações e integrações.

Exemplos:

- Administrador alterou a configuração de um equipamento.
- Operador reconheceu um evento.
- Automação acionou um dispositivo.
- Coordenador de IA executou um pipeline.
- Equipamento confirmou o recebimento de um comando.
- Integração recebeu uma mensagem MQTT inválida.

Campos previstos:

- Código.
- Usuário.
- Dispositivo.
- Evento.
- Ação.
- Entidade.
- Identificador da entidade.
- Origem.
- Canal.
- Resultado.
- Descrição.
- Dados anteriores JSON.
- Dados posteriores JSON.
- Correlation ID.
- Endereço IP.
- User Agent.
- Horário do acontecimento.
- Horário do registro.

A auditoria deverá ser preferencialmente append-only, sem edição ou exclusão pela interface comum.

Acesso restrito e filtragem de informações sensíveis deverão ser considerados.

A relação `entidade` e `entidade_id` será polimórfica, sem tentar criar uma FK genérica para todas as tabelas do sistema.

# 11. CENAS E CENÁRIOS

Este grupo representa configurações predefinidas para equipamentos e contextos operacionais.

Foi estabelecida a distinção:

- **Automação:** determina quando e como executar ações.
- **Cena:** representa um conjunto reutilizável de configurações para dispositivos.
- **Cenário:** representa um contexto operacional que pode combinar várias cenas.

Uma cena poderá participar de vários cenários, e um cenário poderá combinar várias cenas.

As versões publicadas deverão ser imutáveis.

## 11.1. `cenas`

**Finalidade:** cadastrar cenas reutilizáveis.

Exemplos:

- Operação normal.
- Alerta de segurança.
- Evacuação.
- Manutenção.
- Monitoramento noturno.

Campos conceituais:

- Código.
- Nome.
- Descrição.
- Categoria.
- Status.
- Indicador de atividade.
- Usuário responsável.
- Timestamps.

## 11.2. `versoes_cenas`

**Finalidade:** preservar versões imutáveis de uma cena.

Exemplo:

```text
Cena: Alerta de segurança

Versão 1.0:
Preset inicial de segurança.

Versão 1.1:
Ajuste de parâmetros e dispositivos.
```

Campos:

- Cena.
- Número ou identificador da versão.
- Descrição da versão.
- Status.
- Hash de configuração.
- Usuário responsável.
- Data de publicação.
- Data de criação.

Estados:

`rascunho`, `publicada` e `descontinuada`.

## 11.3. `cena_dispositivos`

**Finalidade:** associar uma versão de cena aos equipamentos participantes e armazenar o preset de cada equipamento.

Exemplo:

```text
Cena: Alerta de segurança — versão 1.0

EMB-AUDIO-001:
Configuração de áudio de alerta.

EMB-ALARME-002:
Configuração de alarme luminoso.

EMB-GATEWAY-001:
Configuração de comunicação.
```

Campos:

- Versão da cena.
- Dispositivo.
- Configuração JSON.
- Ordem de aplicação.
- Indicador de obrigatoriedade.
- Data de criação.

A ordem de aplicação será lógica, não necessariamente sequencial.

Dispositivos independentes poderão receber comandos paralelamente.

A aplicação do preset não significa que a configuração foi efetivamente aplicada. A confirmação deverá ser acompanhada por comandos, histórico de comandos e versões de configuração.

## 11.4. `cenarios`

**Finalidade:** cadastrar contextos operacionais.

Exemplos:

- Operação industrial normal.
- Emergência por vazamento de gás.
- Incêndio.
- Invasão de área restrita.
- Manutenção programada.

Campos:

- Código.
- Nome.
- Descrição.
- Categoria.
- Status.
- Indicador de atividade.
- Usuário responsável.
- Timestamps.

## 11.5. `versoes_cenarios`

**Finalidade:** preservar versões imutáveis da composição de cada cenário.

Exemplo:

```text
Cenário: Emergência por vazamento de gás

Versão 1.0:
Composição inicial de cenas.

Versão 1.1:
Inclusão de nova cena e ajuste das condições.
```

Campos:

- Cenário.
- Versão.
- Descrição da versão.
- Status.
- Hash.
- Usuário responsável.
- Publicação.
- Data de criação.

## 11.6. `cenario_cenas`

**Finalidade:** associar versões específicas de cenas a uma versão de cenário.

Exemplo:

```text
Cenário: Emergência por vazamento de gás

1. Cena de alerta de segurança.
2. Cena de isolamento da área.
3. Cena de comunicação de emergência.
```

Campos:

- Versão do cenário.
- Versão da cena.
- Ordem de execução.
- Indicador de obrigatoriedade.
- Condição de ativação JSON.
- Data de criação.

Uma cena poderá participar de vários cenários.

## 11.7. `cenario_dispositivos`

**Finalidade:** permitir que um cenário também possua presets próprios para equipamentos.

Campos:

- Versão do cenário.
- Dispositivo.
- Configuração JSON.
- Estratégia de aplicação.
- Indicador de obrigatoriedade.
- Data de criação.

Estratégias iniciais:

- `complementar`
- `sobrescrever`

A precedência entre os presets do cenário e os presets das cenas ainda precisará ser definida formalmente na revisão global.

# 12. TABELAS ADICIONAIS EM AVALIAÇÃO

As tabelas abaixo foram identificadas como possíveis necessidades, mas ainda não fazem parte das 42 tabelas aprovadas conceitualmente.

## 12.1. `sessao_dispositivos`

**Motivo:** permitir que uma sessão de coleta envolva vários dispositivos.

Exemplo:

```text
Sessão de diagnóstico:
COLETA-001

Equipamentos:
├── EMB-GAS-001
├── EMB-GAS-002
└── EMB-GATEWAY-001
```

Poderá registrar a participação individual de cada equipamento, seu estado e os horários correspondentes.

A necessidade deverá ser comparada com a estrutura atual de `sessoes_coleta`.

## 12.2. `execucoes_cenas`

**Motivo:** registrar cada ativação de uma cena.

Exemplo:

```text
Cena:
Alerta de segurança — versão 1.0

Execução:
EVT-001

Resultado:
Aplicação concluída parcialmente.
```

Deverá preservar a versão da cena utilizada, o evento de origem, o status e os resultados.

## 12.3. `execucoes_cenarios`

**Motivo:** registrar cada ativação de um cenário.

Exemplo:

```text
Cenário:
Emergência por vazamento de gás.

Execução:
EVT-002

Cenas:
├── Alerta de segurança.
├── Isolamento da área.
└── Comunicação de emergência.
```

Deverá preservar a versão do cenário utilizada e o resultado da execução.

## 12.4. `execucao_cena_dispositivos`

**Motivo:** acompanhar a aplicação individual de uma cena em cada equipamento.

Essa tabela somente deverá ser criada se a análise demonstrar que o acompanhamento não pode ser representado adequadamente pelas estruturas existentes de execução de ações, destinatários e comandos.

# 13. DECISÕES ARQUITETURAIS CONSOLIDADAS

As seguintes decisões foram estabelecidas ao longo da modelagem:

## 13.1. Identificação dos equipamentos

- Cada equipamento físico possui código estável e único.
- A localização não faz parte da identidade do equipamento.
- Mudanças de local não modificam o código.
- Tópicos MQTT são associados ao cadastro do equipamento.
- Equipamentos podem utilizar mais de um protocolo.

## 13.2. Localização

- A localização é resolvida pelo banco de dados.
- Os tópicos MQTT não dependem da estrutura física.
- O histórico de localização preserva movimentações.
- Uma localização vigente por dispositivo.
- Histórico e estado atual devem ser atualizados transacionalmente.

## 13.3. Componentes

- Um dispositivo pode possuir vários componentes.
- Cada componente possui código próprio dentro do equipamento.
- Componentes físicos e módulos de hardware serão cadastrados.
- Serviços de software não serão tratados como componentes físicos.
- Leituras poderão apontar para componentes específicos.

## 13.4. Telemetria

- Leituras são distintas de eventos.
- Horário da medição é diferente do horário de recebimento.
- Dados numéricos e textuais são permitidos.
- Payload original poderá ser preservado em JSON.
- MQ-4 ADC não equivale automaticamente a ppm.

## 13.5. Gêmeo digital

- Representa estados históricos e snapshots.
- Poderá utilizar snapshots completos e parciais.
- Não executa comandos físicos.
- Deve distinguir estado solicitado, observado e confirmado.

## 13.6. Eventos

- Um evento pode estar associado a vários locais.
- Um evento pode estar associado a vários dispositivos.
- Um evento pode estar associado a componentes e leituras.
- Eventos relacionados poderão utilizar hierarquia pai-filho.
- Arquivos relevantes poderão ser associados como evidências.

## 13.7. Inteligência artificial

- Artefatos e versões serão entidades distintas.
- Versões publicadas serão imutáveis.
- Uma versão de pipeline preservará a composição exata.
- Especialistas poderão executar em paralelo ou sequencialmente.
- Cada especialista terá execução própria.
- O coordenador consolidará os resultados.
- Métricas individuais e coletivas serão preservadas.
- Arquivos Python, modelos e vídeos não serão armazenados como binários no MySQL.

## 13.8. MQTT e comandos

- MQTT é utilizado para comunicação com os equipamentos.
- Cada comando será individual por dispositivo.
- Envio não significa recebimento.
- Recebimento não significa execução.
- Execução deverá possuir confirmação confiável.
- Idempotência deverá evitar duplicações.
- Histórico deverá preservar transições.

## 13.9. Áudio

- Piper poderá gerar áudio centralizado.
- Flite poderá executar síntese local.
- A geração poderá ser distribuída a vários dispositivos.
- Uma geração poderá possuir vários destinatários.
- Cada destinatário terá acompanhamento individual.
- Os destinatários serão identificados pelo cadastro real, local e tópico utilizado.
- A geração não significa reprodução confirmada.

## 13.10. Automação

- Regras possuem várias ações.
- Execuções são registradas individualmente.
- Cada ação poderá possuir várias tentativas.
- Destinatários são acompanhados individualmente.
- Cooldown e idempotência deverão impedir execuções indevidas.

## 13.11. Cenas e cenários

- Cenas representam presets reutilizáveis.
- Cenários representam contextos compostos por cenas.
- Relação entre cenas e cenários é N:N.
- Versões publicadas são imutáveis.
- Aplicação de preset gera comandos e exige confirmação.
- A configuração efetiva será preservada em `versoes_configuracao`.

## 13.12. Auditoria

- Operações relevantes deverão ser registradas.
- Auditoria deverá ser preferencialmente append-only.
- Dados sensíveis deverão ser filtrados.
- O histórico deverá preservar ator, ação, entidade, resultado e correlação.

# 14. PONTOS CRÍTICOS DA REVISÃO GLOBAL

A próxima etapa deverá analisar as relações entre todas as tabelas, e não apenas suas definições isoladas.

Os principais pontos de atenção são:

| Área | O que precisa ser verificado |
|---|---|
| Estrutura física | Integridade entre locais, dispositivos e componentes |
| Telemetria | Relação entre leitura, componente e dispositivo |
| Configuração | Consistência entre configuração atual e versões históricas |
| Gêmeo digital | Semântica de snapshots completos e parciais |
| Eventos | Coerência entre eventos e suas associações |
| Anexos | Associação correta de arquivos externos |
| IA | Referência às versões exatas dos artefatos e pipelines |
| Implantações | Coerência entre dispositivo, versão e tipo de implantação |
| Integração | Relações entre mensagens, comandos, eventos e análises |
| Comandos | Integridade entre comando, histórico e confirmação |
| Automação | Relação entre regra, ação, execução e destinatário |
| Áudio | Evitar duplicação com `execucao_destinatarios` |
| Cenas | Registro de execução e aplicação de presets |
| Cenários | Composição, precedência e conflitos entre cenas |
| Sessões | Participação de vários dispositivos |
| Auditoria | Registro de operações sem dependências inconsistentes |

# 15. PLANO DE CONTINUIDADE

O trabalho deverá prosseguir na seguinte sequência:

## Etapa 1 — Inventário consolidado

**Situação:** concluída nesta etapa.

Foram organizadas 42 tabelas de domínio em oito grupos funcionais.

O inventário será utilizado como base de referência, podendo sofrer alterações após a revisão.

## Etapa 2 — Revisão dos campos e tipos

**Situação:** pendente.

Conferir cada tabela individualmente, validando:

- Nome do campo.
- Tipo MySQL.
- Tamanho.
- Precisão.
- Nulabilidade.
- Valor padrão.
- Regras de validação.
- Timestamps.

## Etapa 3 — Revisão de PKs, FKs e índices

**Situação:** pendente.

Verificar:

- Chaves primárias.
- Chaves estrangeiras.
- Tipos compatíveis.
- Restrições UNIQUE.
- Índices simples e compostos.
- Regras `ON DELETE`.
- Regras de integridade referencial.

## Etapa 4 — Avaliação de tabelas adicionais

**Situação:** pendente.

Analisar especialmente:

- `sessao_dispositivos`.
- `execucoes_cenas`.
- `execucoes_cenarios`.
- `execucao_cena_dispositivos`.

Também deverão ser identificadas eventuais redundâncias entre as estruturas existentes.

## Etapa 5 — Fechamento do modelo físico

**Situação:** pendente.

Produzir uma especificação definitiva, com todas as tabelas e seus relacionamentos validados.

## Etapa 6 — Planejamento das migrations

**Situação:** pendente.

Definir a ordem correta de criação, respeitando as dependências entre as tabelas e a infraestrutura já instalada.

## Etapa 7 — Implementação e validação

**Situação:** pendente.

Somente depois da aprovação global:

1. Criar todas as migrations.
2. Revisar o conjunto completo.
3. Executar no banco `iot_edge_center`.
4. Conferir a estrutura física criada.
5. Validar chaves estrangeiras e índices.
6. Registrar os resultados reais dos testes.

# 16. REGRAS PARA A CONTINUIDADE DO DESENVOLVIMENTO

As seguintes regras deverão ser mantidas:

1. Não criar migrations de domínio antes da aprovação global.
2. Não alterar estruturas aprovadas sem apresentar justificativa técnica.
3. Não confundir aprovação conceitual com implementação.
4. Não inventar testes, resultados ou estruturas já executadas.
5. Não duplicar informações sem necessidade.
6. Preservar o histórico dos equipamentos, eventos, comandos e configurações.
7. Utilizar os tipos reais das chaves referenciadas, especialmente `users.id`.
8. Manter os arquivos binários fora do MySQL.
9. Preservar as versões exatas dos artefatos e pipelines de IA.
10. Registrar as decisões e mudanças nos próximos relatórios de continuidade.

# 17. CONCLUSÃO

A primeira rodada de definição das tabelas de domínio foi encerrada conceitualmente.

O inventário atual possui 42 tabelas, organizadas em oito grupos funcionais, além das nove tabelas de infraestrutura já existentes no banco.

A modelagem contempla:

- Estrutura física e hierarquia de locais.
- Cadastro de equipamentos e componentes.
- Telemetria e histórico operacional.
- Gêmeo digital.
- Eventos e evidências.
- Visão computacional e interpretação contextual.
- Artefatos, versões e pipelines de IA.
- Integração MQTT e comandos.
- Automações e execução individualizada.
- Geração e distribuição de áudio.
- Auditoria.
- Cenas, cenários e presets versionados.

Também foram identificadas tabelas adicionais que poderão ser necessárias para completar o controle de sessões de coleta e a execução de cenas e cenários.

**O próximo trabalho será a revisão integrada da estrutura física, começando pelo grupo de estrutura física: `locais`, `dispositivos`, `componentes_dispositivo` e `historico_localizacao_dispositivo`.**

A implementação das migrations de domínio continuará bloqueada até a conclusão e aprovação dessa revisão.

---

