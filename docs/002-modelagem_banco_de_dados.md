# Modelagem completa do banco de dados — IoT-Edge Command Center

**Banco de dados:** `iot_edge_center`  
**SGBD:** MySQL 8.0 ou superior  
**Backend:** CodeIgniter 4  
**Mensageria:** MQTT  
**Atualização da interface:** WebSocket ou mecanismo equivalente  
**Síntese de voz:** Piper TTS  
**Arquitetura:** IoT-Edge com hierarquia física, automação espacial, integração de dispositivos e histórico do gêmeo digital

---

# 1. Objetivo do banco de dados

O banco `iot_edge_center` dará suporte ao cadastro e à operação de dispositivos IoT, monitoramento de sensores, gerenciamento de eventos, automação, comandos, mensagens de áudio, auditoria e reconstrução histórica do sistema.

O modelo deverá permitir:

- Cadastrar usuários, perfis e associações de autorização.
- Representar edifícios, andares, zonas, salas, corredores e áreas externas em uma hierarquia física.
- Cadastrar equipamentos e seus componentes embarcados.
- Registrar telemetria, incluindo valores de sensores, RSSI e SNR.
- Registrar eventos, alarmes, testes, falhas e diagnósticos.
- Relacionar um evento a vários dispositivos, componentes, leituras e locais.
- Selecionar equipamentos por localização sem depender do nome do tópico MQTT.
- Executar regras de automação compostas por várias ações.
- Enviar mensagens Piper diferentes para diferentes áreas durante um mesmo evento.
- Rastrear individualmente os destinatários de cada ação.
- Diferenciar solicitação, envio, recebimento, execução e confirmação.
- Manter o histórico das configurações e da localização física dos equipamentos.
- Registrar mensagens de integração e ações de auditoria.
- Preservar leituras associadas a eventos e sessões de coleta.
- Reconstruir o estado histórico do gêmeo digital sem acionar equipamentos físicos.
- Controlar a repetição de comandos e o processamento de mensagens MQTT duplicadas.
- Identificar falhas de comunicação, dispositivos indisponíveis e ações que não foram concluídas.

## 1.1. Princípios fundamentais

1. Um dispositivo representa um equipamento físico completo; seus componentes são registrados separadamente quando necessário.
2. A localização física é mantida no banco de dados, e não codificada como requisito obrigatório nos tópicos MQTT.
3. Um evento pode afetar várias áreas e gerar diversas ações.
4. Uma regra de automação pode conter várias ações, e cada ação pode ter um resultado independente.
5. Uma ação pode ser direcionada a vários dispositivos compatíveis.
6. Uma mensagem de áudio pode ser reutilizada por vários dispositivos quando o conteúdo for idêntico.
7. Um comando enviado não comprova que o dispositivo recebeu ou executou a ação.
8. A confirmação de reprodução não comprova que as pessoas ouviram ou seguiram a orientação.
9. Os arquivos de mídia ficam fora do MySQL; o banco armazena metadados, referências e hashes.
10. Leituras, eventos, comandos, mensagens de integração e auditoria possuem finalidades distintas.
11. A reprodução histórica do gêmeo digital é somente de consulta e nunca deve reenviar comandos físicos.
12. Rotas e orientações de emergência devem ser previamente aprovadas e não inventadas dinamicamente pelo sistema.
13. Mensagens MQTT duplicadas não devem produzir automaticamente ações físicas duplicadas.
14. O histórico deve ser preservado mesmo quando um dispositivo, usuário ou local for desativado.
15. A localização atual de um dispositivo e seu histórico de localização são informações diferentes e devem ser mantidos separadamente.
16. A hierarquia de locais deve ser a fonte oficial para consultas espaciais.
17. A seleção de destinatários deve considerar tanto a localização quanto a compatibilidade do equipamento com a ação solicitada.
18. Estados desconhecidos devem ser diferenciados de estados de sucesso ou falha confirmados.

## 1.2. Convenções utilizadas

- `PK`: chave primária.
- `FK`: chave estrangeira.
- `UNIQUE`: valor ou combinação de valores que não pode se repetir.
- `INDEX`: índice para melhorar o desempenho das consultas.
- `NULL`: campo que pode não possuir valor.
- `JSON`: estrutura de dados flexível, que deverá ser validada pelo backend.
- `DATETIME(3)`: data e hora com precisão de milissegundos.
- `BIGINT UNSIGNED`: identificador inteiro positivo de grande capacidade.
- `created_at`: momento de criação do registro.
- `updated_at`: momento da última atualização, quando aplicável.
- `ocorrido_em`: momento em que o fato ocorreu no sistema ou no dispositivo.
- `recebido_em`: momento em que o backend recebeu o fato.
- `registrado_em`: momento em que o fato foi persistido ou registrado.

Os horários devem ser armazenados segundo uma política consistente, preferencialmente em UTC, e convertidos para o fuso local na apresentação da interface.

---

# 2. Visão geral das 28 tabelas

O modelo mantém as 23 tabelas originais e acrescenta cinco tabelas para formalizar a automação espacial e o histórico físico.

| Nº | Tabela | Finalidade |
|---:|---|---|
| 1 | `usuarios` | Contas de acesso |
| 2 | `perfis` | Perfis de autorização |
| 3 | `usuario_perfis` | Associação entre usuários e perfis |
| 4 | `locais` | Hierarquia física |
| 5 | `dispositivos` | Equipamentos completos |
| 6 | `componentes_dispositivo` | Componentes monitorados |
| 7 | `leituras` | Telemetria |
| 8 | `historico_status_dispositivo` | Histórico de estados operacionais |
| 9 | `versoes_configuracao` | Versões de configuração |
| 10 | `historico_estado_gemeo` | Histórico do gêmeo digital |
| 11 | `eventos` | Ocorrências e alarmes |
| 12 | `evento_dispositivos` | Dispositivos envolvidos em eventos |
| 13 | `evento_componentes` | Componentes envolvidos em eventos |
| 14 | `evento_leituras` | Leituras relacionadas a eventos |
| 15 | `anexos` | Metadados de arquivos |
| 16 | `mensagens_integracao` | Mensagens MQTT e outras integrações |
| 17 | `comandos` | Comandos direcionados a dispositivos |
| 18 | `historico_comandos` | Transições dos comandos |
| 19 | `mensagens_audio` | Textos e arquivos de áudio TTS |
| 20 | `auditoria` | Registro de ações e resultados |
| 21 | `regras_automacao` | Condições das regras automáticas |
| 22 | `execucoes_automacao` | Execuções de regras |
| 23 | `sessoes_coleta` | Sessões de coleta de dados |
| 24 | `evento_locais` | Locais associados a eventos |
| 25 | `acoes_automacao` | Ações configuradas em cada regra |
| 26 | `execucoes_acoes` | Execuções individuais das ações |
| 27 | `execucao_destinatarios` | Destinatários e resultados por ação |
| 28 | `historico_localizacao_dispositivo` | Histórico das mudanças de localização |

**Decisão de modelagem:** `execucoes_acoes` separa a execução de uma regra da execução individual de cada ação. Uma regra pode terminar sua avaliação, mas uma ação pode ter sucesso enquanto outra falha.

Grupos operacionais reutilizáveis de dispositivos não fazem parte da primeira versão. A seleção inicial será realizada pela hierarquia de `locais` e pela localização atual dos dispositivos.

---

# 3. Hierarquia física de locais

## Tabela 4 — `locais`

**Finalidade:** representar a estrutura física do ambiente em uma hierarquia flexível.

Cada local pode possuir um local pai. Por exemplo, um edifício pode conter andares; um andar pode conter zonas; e uma zona pode conter salas e corredores.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `local_pai_id` | BIGINT UNSIGNED, NULL | FK para `locais.id` |
| `codigo` | VARCHAR(80) | Código único do local |
| `nome` | VARCHAR(120) | Nome de exibição |
| `tipo` | VARCHAR(30) | Tipo de local |
| `descricao` | TEXT, NULL | Descrição complementar |
| `latitude` | DECIMAL(10,7), NULL | Latitude, quando aplicável |
| `longitude` | DECIMAL(10,7), NULL | Longitude, quando aplicável |
| `referencia` | VARCHAR(190), NULL | Referência textual |
| `ativo` | BOOLEAN | Indica se o local está ativo |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições e validações

- PK: `id`.
- FK: `local_pai_id` → `locais.id`.
- UNIQUE: `codigo`.
- INDEX: `local_pai_id`.
- `local_pai_id` não pode apontar para o próprio registro.
- O backend deve impedir ciclos na hierarquia.
- Um local com dispositivos ou histórico associado não deve ser apagado indiscriminadamente.
- A desativação de um local não deve apagar dispositivos nem históricos.
- Os tipos de local devem ser padronizados, por exemplo: `edificio`, `andar`, `zona`, `sala`, `corredor` e `area_externa`.

### Três exemplos de registros

| ID | Código | Nome | Tipo | Local pai |
|---:|---|---|---|---:|
| 1 | ED-A | Edifício A | edificio | NULL |
| 2 | ED-A-AND-01 | 1º andar | andar | 1 |
| 3 | ED-A-AND-01-ZN | Zona Norte | zona | 2 |

Outros locais podem ser adicionados à mesma hierarquia, como Sala 101, Sala 102, Corredor Norte, Zona Sul e Corredor Sul.

### Exemplo de hierarquia

```text
Edifício A
└── 1º andar
    ├── Zona Norte
    │   ├── Sala 101
    │   ├── Sala 102
    │   └── Corredor Norte
    └── Zona Sul
        ├── Sala 103
        └── Corredor Sul
```

### Exemplos de consultas por área

O backend deve resolver a seleção de dispositivos da seguinte forma:

- **Sala 101:** equipamentos instalados diretamente na Sala 101.
- **Corredor Norte:** equipamentos instalados diretamente no Corredor Norte.
- **Zona Norte:** equipamentos nos locais descendentes da Zona Norte, incluindo salas e corredores.
- **1º andar:** equipamentos de todas as zonas, salas e corredores daquele andar.
- **Edifício A:** equipamentos de toda a hierarquia do edifício.

Para consultar todos os descendentes, o backend pode utilizar uma consulta recursiva com `WITH RECURSIVE`, disponível nas versões atuais do MySQL.

A consulta deve selecionar somente equipamentos compatíveis com a ação. Uma solicitação de reprodução de áudio, por exemplo, não deve enviar um comando de áudio a um sensor de gás.

---

# 4. Dispositivos e componentes

## Tabela 5 — `dispositivos`

**Finalidade:** representar cada equipamento físico completo, como uma estação embarcada, sintetizador de áudio, refletor, câmera ou gateway.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(80) | Código estável e único |
| `nome` | VARCHAR(150) | Nome de exibição |
| `descricao` | TEXT, NULL | Descrição |
| `categoria` | VARCHAR(40) | Categoria funcional |
| `modelo` | VARCHAR(100), NULL | Modelo |
| `fabricante` | VARCHAR(100), NULL | Fabricante |
| `local_id` | BIGINT UNSIGNED, NULL | FK para `locais.id` |
| `protocolo_principal` | VARCHAR(30), NULL | Protocolo de comunicação |
| `topico_mqtt` | VARCHAR(255), NULL | Tópico principal, se necessário |
| `identificador_externo` | VARCHAR(150), NULL | Identificador em sistema externo |
| `status_operacional` | VARCHAR(30) | Estado operacional atual |
| `ultimo_contato_em` | DATETIME(3), NULL | Último contato conhecido |
| `configuracoes` | JSON, NULL | Configurações gerais |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `local_id` → `locais.id`.
- INDEX: `local_id`, `categoria` e `status_operacional`.
- O código deve permanecer estável mesmo quando o dispositivo mudar de local.
- A localização atual deve refletir a posição física vigente.
- Alterações de localização devem atualizar também `historico_localizacao_dispositivo`.

### Três exemplos de registros

| ID | Código | Categoria | Local |
|---:|---|---|---|
| 1 | EMB-GAS-001 | estacao_embarcada | Laboratório IoT |
| 2 | AUD-001 | sintetizador_audio | Sala 101 |
| 3 | REF-001 | refletor | Sala 101 |

Os nomes de locais são ilustrativos; no banco, `local_id` deve conter o ID real de `locais`.

### Política MQTT

Os tópicos de comando serão centrados no identificador estável do dispositivo, por exemplo:

```text
iot-edge/devices/AUD-001/commands
```

A localização será consultada no banco. Não será necessário renomear o tópico MQTT quando um dispositivo mudar de sala.

---

## Tabela 6 — `componentes_dispositivo`

**Finalidade:** representar sensores, controladores, módulos de comunicação, interfaces e atuadores que integram um dispositivo.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `codigo` | VARCHAR(80) | Código do componente dentro do dispositivo |
| `nome` | VARCHAR(150) | Nome de exibição |
| `tipo` | VARCHAR(40) | Tipo funcional |
| `modelo` | VARCHAR(100), NULL | Modelo |
| `funcao` | TEXT, NULL | Função |
| `status_operacional` | VARCHAR(30) | Estado operacional |
| `configuracoes` | JSON, NULL | Configurações específicas |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- UNIQUE: (`dispositivo_id`, `codigo`).
- INDEX: `dispositivo_id`, `tipo` e `status_operacional`.
- O backend deve validar que um componente pertence ao dispositivo informado nas leituras, comandos e eventos.

### Três exemplos de registros

| ID | Dispositivo | Código | Tipo |
|---:|---:|---|---|
| 1 | 1 | CTRL-ESP32 | controlador |
| 2 | 1 | SENSOR-MQ4 | sensor |
| 3 | 1 | LORA-RA01S | comunicacao |

---

# 5. Usuários, perfis e acesso

## Tabela 1 — `usuarios`

**Finalidade:** armazenar as contas utilizadas para acessar o sistema.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `nome` | VARCHAR(120) | Nome de exibição |
| `email` | VARCHAR(190) | E-mail de acesso |
| `senha_hash` | VARCHAR(255), NULL | Hash da senha, se houver autenticação própria |
| `status` | VARCHAR(20) | Estado da conta |
| `ultimo_acesso_em` | DATETIME(3), NULL | Último acesso |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições e segurança

- PK: `id`.
- UNIQUE: `email`.
- O e-mail deve ser normalizado antes da comparação.
- Nunca armazenar senhas em texto puro.
- Utilizar o mecanismo seguro de hash de senhas do sistema de autenticação adotado.
- Validar a autorização no backend, e não somente na interface.

**Decisão de implementação:** escolher entre CodeIgniter Shield e autenticação própria. Se Shield for utilizado, adaptar a modelagem à estrutura de autenticação do Shield em vez de manter duas fontes independentes de contas e senhas.

### Três exemplos de registros

| ID | Nome | E-mail | Status |
|---:|---|---|---|
| 1 | Administrador do sistema | admin@example.test | ativo |
| 2 | Operadora de monitoramento | operadora@example.test | ativo |
| 3 | Visualizador de relatórios | visualizador@example.test | ativo |

Os domínios `.test` são utilizados somente para exemplos.

---

## Tabela 2 — `perfis`

**Finalidade:** definir os perfis gerais de autorização.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `nome` | VARCHAR(60) | Nome único do perfil |
| `descricao` | VARCHAR(255), NULL | Descrição |
| `created_at` | DATETIME(3) | Criação |

### Restrições

- PK: `id`.
- UNIQUE: `nome`.
- Os perfis devem ser utilizados pelo backend para controlar as operações autorizadas.

### Três exemplos de registros

| ID | Nome | Descrição |
|---:|---|---|
| 1 | administrador | Gerenciamento integral do sistema |
| 2 | operador | Monitoramento e operações autorizadas |
| 3 | visualizador | Consulta de dados e relatórios |

---

## Tabela 3 — `usuario_perfis`

**Finalidade:** associar usuários a perfis, permitindo que um usuário tenha mais de um perfil.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `usuario_id` | BIGINT UNSIGNED | FK para `usuarios.id` |
| `perfil_id` | BIGINT UNSIGNED | FK para `perfis.id` |
| `created_at` | DATETIME(3) | Data da associação |

### Restrições

- PK composta: (`usuario_id`, `perfil_id`).
- FK: `usuario_id` → `usuarios.id`.
- FK: `perfil_id` → `perfis.id`.
- INDEX: `perfil_id`.

### Três exemplos de registros

| Usuário | Perfil | Interpretação |
|---:|---:|---|
| 1 | 1 | Administrador possui perfil administrador |
| 2 | 2 | Operadora possui perfil operador |
| 3 | 3 | Visualizador possui perfil de consulta |

---

# 6. Telemetria e histórico operacional

## Tabela 7 — `leituras`

**Finalidade:** armazenar as leituras produzidas por dispositivos e componentes.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `componente_id` | BIGINT UNSIGNED, NULL | Componente de origem |
| `metrica` | VARCHAR(100) | Nome da métrica |
| `valor_numerico` | DECIMAL(20,6), NULL | Valor numérico |
| `valor_texto` | TEXT, NULL | Valor textual |
| `unidade` | VARCHAR(30), NULL | Unidade de medida |
| `qualidade` | VARCHAR(30), NULL | Qualidade da leitura |
| `dados_originais` | JSON, NULL | Payload ou dados complementares |
| `medido_em` | DATETIME(3) | Momento da medição |
| `recebido_em` | DATETIME(3) | Momento do recebimento |
| `protegida` | BOOLEAN | Indicador auxiliar de retenção |

### Restrições e índices

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `componente_id` → `componentes_dispositivo.id`, quando informado.
- INDEX: (`dispositivo_id`, `medido_em`).
- INDEX: (`componente_id`, `medido_em`).
- INDEX: (`metrica`, `medido_em`).
- O backend deve validar que `componente_id` pertence ao `dispositivo_id` informado.
- Uma leitura pode estar relacionada a mais de um evento por meio de `evento_leituras`.

### Três exemplos de registros

| ID | Dispositivo | Componente | Métrica | Valor | Unidade |
|---:|---:|---:|---|---:|---|
| 1 | 1 | 2 | gas_level | 2780 | contagens ADC |
| 2 | 1 | 3 | rssi | -92 | dBm |
| 3 | 1 | 3 | snr | 7.5 | dB |

O valor ADC de um MQ-4 não representa automaticamente uma concentração em ppm. A conversão exige calibração e implementação apropriada.

O campo `protegida` é apenas um indicador auxiliar. A política de retenção deve considerar também os vínculos com eventos e sessões de coleta.

---

## Tabela 8 — `historico_status_dispositivo`

**Finalidade:** registrar as transições de estado operacional de cada dispositivo.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `status_anterior` | VARCHAR(30), NULL | Estado anterior |
| `status_novo` | VARCHAR(30) | Novo estado |
| `origem` | VARCHAR(30) | Origem da informação |
| `detalhes` | JSON, NULL | Detalhes complementares |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `ocorrido_em` | DATETIME(3) | Momento da transição |
| `registrado_em` | DATETIME(3) | Momento do registro |

### Restrições e índices

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `evento_id` → `eventos.id`, quando informado.
- INDEX: (`dispositivo_id`, `ocorrido_em`).
- A FK de `evento_id` pode ser adicionada depois da criação de `eventos`, para resolver a dependência de ordem das migrations.

### Três exemplos de registros

| ID | Dispositivo | Estado anterior | Estado novo | Origem |
|---:|---:|---|---|---|
| 1 | 1 | offline | online | mqtt |
| 2 | 2 | online | offline | monitoramento |
| 3 | 3 | online | manutencao | operador |

---

## Tabela 9 — `versoes_configuracao`

**Finalidade:** preservar snapshots de configuração de dispositivos e componentes.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `componente_id` | BIGINT UNSIGNED, NULL | Componente configurado |
| `versao` | INT UNSIGNED | Número da versão |
| `configuracao` | JSON | Snapshot da configuração |
| `hash_sha256` | CHAR(64), NULL | Hash de integridade |
| `origem` | VARCHAR(30) | Origem da alteração |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário responsável |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `vigente_desde` | DATETIME(3) | Início da vigência |
| `vigente_ate` | DATETIME(3), NULL | Fim da vigência |
| `created_at` | DATETIME(3) | Criação do registro |

### Restrições e índices

- PK: `id`.
- FK para dispositivo, componente, usuário e evento.
- INDEX: (`dispositivo_id`, `vigente_desde`).
- A versão deve ser única para o dispositivo ou componente correspondente.
- A combinação de `dispositivo_id`, `componente_id` e `versao` exige atenção quando `componente_id` for `NULL`, pois o MySQL permite múltiplos valores `NULL` em índices `UNIQUE`.
- A implementação pode utilizar uma estratégia separada para versões do equipamento completo e versões de componentes.

### Três exemplos de registros

| ID | Dispositivo | Componente | Versão | Configuração resumida |
|---:|---:|---:|---:|---|
| 1 | 1 | NULL | 1 | `{"limite_gas":2500}` |
| 2 | 1 | 2 | 1 | `{"amostragem_ms":1000}` |
| 3 | 1 | 2 | 2 | `{"amostragem_ms":500,"filtro":"media_movel"}` |

Ao registrar uma nova configuração, o backend deve encerrar a vigência da configuração anterior e iniciar a vigência da nova versão.

---

## Tabela 10 — `historico_estado_gemeo`

**Finalidade:** registrar snapshots e mudanças para reconstruir o estado conhecido do gêmeo digital em um momento passado.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `componente_id` | BIGINT UNSIGNED, NULL | Componente relacionado |
| `tipo_registro` | VARCHAR(30) | Tipo de registro histórico |
| `estado` | JSON | Estado conhecido no momento |
| `versao_configuracao_id` | BIGINT UNSIGNED, NULL | Configuração vigente |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `comando_id` | BIGINT UNSIGNED, NULL | Comando relacionado |
| `origem` | VARCHAR(30) | Origem do registro |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `ocorrido_em` | DATETIME(3) | Momento do fato |
| `registrado_em` | DATETIME(3) | Momento do registro |

### Restrições e índices

- PK: `id`.
- FK para dispositivo, componente, configuração, evento e comando.
- INDEX: (`dispositivo_id`, `ocorrido_em`).
- INDEX: (`evento_id`, `ocorrido_em`).
- O estado deve indicar se representa uma observação, snapshot, comando solicitado ou estado confirmado.

### Três exemplos de registros

| ID | Dispositivo | Tipo | Estado resumido |
|---:|---:|---|---|
| 1 | 1 | snapshot | `{"conectividade":"online","gas_level":2780}` |
| 2 | 1 | telemetria | `{"rssi":-92,"snr":7.5}` |
| 3 | 3 | estado_confirmado | `{"rele":"ativado","confirmado":true}` |

Um comando enviado não deve ser registrado como estado físico confirmado sem evidência correspondente. A reprodução histórica é de consulta e não deve acionar equipamentos.

---

# 7. Eventos e suas associações

## Tabela 11 — `eventos`

**Finalidade:** registrar alarmes, testes, falhas, diagnósticos e outras ocorrências relevantes.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(80) | Código único do evento |
| `tipo_evento` | VARCHAR(60) | Tipo da ocorrência |
| `titulo` | VARCHAR(180) | Título |
| `descricao` | TEXT, NULL | Descrição detalhada |
| `origem_tipo` | VARCHAR(30) | Tipo de origem |
| `origem_canal` | VARCHAR(30) | Canal de origem |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário associado |
| `severidade` | VARCHAR(20) | Nível de severidade |
| `status` | VARCHAR(30) | Estado atual do evento |
| `is_teste` | BOOLEAN | Indica se é teste |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `evento_pai_id` | BIGINT UNSIGNED, NULL | Evento pai |
| `dados_extras` | JSON, NULL | Dados complementares |
| `ocorrido_em` | DATETIME(3) | Momento da ocorrência |
| `recebido_em` | DATETIME(3) | Momento do recebimento |
| `normalizado_em` | DATETIME(3), NULL | Momento da normalização |
| `encerrado_em` | DATETIME(3), NULL | Momento do encerramento |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições e índices

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `usuario_id` → `usuarios.id`.
- FK: `evento_pai_id` → `eventos.id`.
- INDEX: (`status`, `ocorrido_em`).
- INDEX: (`tipo_evento`, `ocorrido_em`).
- INDEX: `correlation_id`.
- A associação espacial será realizada por `evento_locais`, sem exigir que um evento tenha somente um local.
- Um evento pode ser relacionado a diversos dispositivos, componentes e leituras.

### Três exemplos de registros

| ID | Código | Tipo | Severidade | Status |
|---:|---|---|---|---|
| 1 | GAS-2026-001 | alerta_gas | alta | aberto |
| 2 | NET-2026-002 | dispositivo_offline | media | em_analise |
| 3 | TTS-2026-003 | teste_audio | baixa | encerrado |

Um evento de invasão ou emergência pode estar associado a vários locais e gerar diversas ações sem criar um evento independente para cada área.

---

## Tabela 12 — `evento_dispositivos`

**Finalidade:** associar eventos a dispositivos envolvidos.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `evento_id` | BIGINT UNSIGNED | FK para `eventos.id` |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `papel` | VARCHAR(40) | Papel do dispositivo no evento |
| `observacao` | TEXT, NULL | Observação |
| `created_at` | DATETIME(3) | Criação da associação |

### Restrições

- PK composta: (`evento_id`, `dispositivo_id`).
- FK para `eventos` e `dispositivos`.
- INDEX: (`dispositivo_id`, `evento_id`).

### Três exemplos de registros

| Evento | Dispositivo | Papel |
|---:|---:|---|
| 1 | 1 | origem |
| 1 | 2 | afetado |
| 2 | 3 | alvo_teste |

O campo `papel` permite distinguir o dispositivo que originou a ocorrência daquele que foi afetado ou selecionado para um teste.

---

## Tabela 13 — `evento_componentes`

**Finalidade:** associar eventos a componentes específicos.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `evento_id` | BIGINT UNSIGNED | FK para `eventos.id` |
| `componente_id` | BIGINT UNSIGNED | FK para `componentes_dispositivo.id` |
| `papel` | VARCHAR(40) | Papel do componente |
| `observacao` | TEXT, NULL | Observação |
| `created_at` | DATETIME(3) | Criação da associação |

### Restrições

- PK composta: (`evento_id`, `componente_id`).
- FK para `eventos` e `componentes_dispositivo`.
- INDEX: (`componente_id`, `evento_id`).

### Três exemplos de registros

| Evento | Componente | Papel |
|---:|---:|---|
| 1 | 2 | origem |
| 1 | 1 | controlador |
| 3 | 3 | alvo_teste |

---

## Tabela 14 — `evento_leituras`

**Finalidade:** associar eventos às leituras que contribuíram para a ocorrência ou para seu diagnóstico.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `evento_id` | BIGINT UNSIGNED | FK para `eventos.id` |
| `leitura_id` | BIGINT UNSIGNED | FK para `leituras.id` |
| `papel` | VARCHAR(30) | Papel da leitura |
| `observacao` | TEXT, NULL | Observação |
| `created_at` | DATETIME(3) | Criação da associação |

### Restrições

- PK composta: (`evento_id`, `leitura_id`).
- FK para `eventos` e `leituras`.
- INDEX: (`leitura_id`, `evento_id`).

### Três exemplos de registros

| Evento | Leitura | Papel |
|---:|---:|---|
| 1 | 1 | trigger |
| 1 | 2 | diagnostic |
| 2 | 3 | before |

Os papéis podem representar a leitura que disparou o evento, uma leitura de diagnóstico ou uma amostra anterior à ocorrência.

---

## Tabela 24 — `evento_locais` — NOVA

**Finalidade:** associar um evento a vários locais, preservando o contexto espacial da ocorrência.

Essa tabela permite que um único evento afete uma sala, um corredor e uma zona sem precisar criar cópias do evento.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `evento_id` | BIGINT UNSIGNED | FK para `eventos.id` |
| `local_id` | BIGINT UNSIGNED | FK para `locais.id` |
| `papel_local` | VARCHAR(40) | Papel do local no evento |
| `observacao` | TEXT, NULL | Observação |
| `created_at` | DATETIME(3) | Criação da associação |

### Restrições

- PK: `id`.
- FK: `evento_id` → `eventos.id`.
- FK: `local_id` → `locais.id`.
- UNIQUE: (`evento_id`, `local_id`, `papel_local`).
- INDEX: (`local_id`, `evento_id`).
- Um evento pode ter vários locais associados.
- Um local pode participar de vários eventos.
- A associação deve registrar o papel espacial de cada local.

### Três exemplos de registros

| ID | Evento | Local | Papel |
|---:|---|---|---|
| 1 | INV-2026-001 | Sala 101 | origem |
| 2 | INV-2026-001 | Corredor Norte | area_afetada |
| 3 | INV-2026-001 | Sala 102 | area_apoio |

Os códigos de evento e os nomes dos locais são referências ilustrativas. Na implementação, as FKs devem armazenar os IDs numéricos correspondentes.

### Observação sobre localização histórica

`evento_locais` registra a relação espacial do evento. `historico_localizacao_dispositivo` registra onde um dispositivo estava instalado ao longo do tempo. As duas tabelas têm finalidades diferentes e complementares.

---

# 8. Anexos e integração

## Tabela 15 — `anexos`

**Finalidade:** armazenar os metadados e as referências a imagens, vídeos, áudios e documentos relacionados a eventos.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `evento_id` | BIGINT UNSIGNED | FK para `eventos.id` |
| `tipo_arquivo` | VARCHAR(30) | Tipo do arquivo |
| `nome_original` | VARCHAR(255) | Nome original |
| `caminho_armazenamento` | VARCHAR(500) | Referência de armazenamento |
| `mime_type` | VARCHAR(120) | Tipo MIME |
| `tamanho_bytes` | BIGINT UNSIGNED | Tamanho |
| `hash_sha256` | CHAR(64), NULL | Hash de integridade |
| `metadados` | JSON, NULL | Metadados complementares |
| `created_at` | DATETIME(3) | Criação |

### Restrições

- PK: `id`.
- FK: `evento_id` → `eventos.id`.
- INDEX: (`evento_id`, `created_at`).
- Os arquivos devem permanecer em armazenamento apropriado, não em uma coluna de texto ou JSON.
- O backend deve controlar o acesso aos arquivos e validar o tipo e o tamanho.
- Se futuramente houver anexos sem vínculo obrigatório com eventos, será necessário definir uma associação genérica ou permitir `evento_id` nulo.

### Três exemplos de registros

| ID | Evento | Tipo | Nome |
|---:|---:|---|---|
| 1 | 1 | imagem | `camera_corredor.jpg` |
| 2 | 1 | video | `trecho_evento.mp4` |
| 3 | 3 | audio | `teste_tts.wav` |

---

## Tabela 16 — `mensagens_integracao`

**Finalidade:** registrar mensagens recebidas e enviadas por MQTT ou outros canais de integração.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `canal` | VARCHAR(30) | MQTT ou outro canal |
| `direcao` | VARCHAR(20) | Entrada ou saída |
| `dispositivo_id` | BIGINT UNSIGNED, NULL | Dispositivo relacionado |
| `topico` | VARCHAR(255), NULL | Tópico de integração |
| `message_id_externo` | VARCHAR(150), NULL | Identificador externo |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `tipo_mensagem` | VARCHAR(60) | Tipo de mensagem |
| `payload` | JSON, NULL | Payload interpretado |
| `payload_bruto` | LONGTEXT, NULL | Payload original, se necessário |
| `status_processamento` | VARCHAR(30) | Estado do processamento |
| `erro` | TEXT, NULL | Erro de processamento |
| `recebido_em` | DATETIME(3) | Momento do recebimento ou registro |
| `processado_em` | DATETIME(3), NULL | Momento do processamento |

### Restrições e índices

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`, quando informado.
- INDEX: (`canal`, `recebido_em`).
- INDEX: (`status_processamento`, `recebido_em`).
- INDEX: `correlation_id`.
- Se `message_id_externo` for único por dispositivo ou canal, a restrição UNIQUE deverá refletir esse escopo.
- Não registrar tokens, senhas ou outros segredos no payload bruto.
- Definir limites de tamanho, retenção e política de anonimização quando aplicável.

### Três exemplos de registros

| ID | Canal | Direção | Tipo | Estado |
|---:|---|---|---|---|
| 1 | mqtt | entrada | telemetry | processado |
| 2 | mqtt | saida | command | enviado |
| 3 | mqtt | entrada | command_result | rejeitado |

Essa tabela é um registro de integração. Ela não substitui as tabelas especializadas de leituras, eventos e comandos.

---

# 9. Comandos e rastreamento de resultados

## Tabela 17 — `comandos`

**Finalidade:** registrar os comandos direcionados aos dispositivos e acompanhar sua evolução.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(80) | Código único do comando |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `componente_id` | BIGINT UNSIGNED, NULL | Componente de destino |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário solicitante |
| `execucao_destinatario_id` | BIGINT UNSIGNED, NULL | Destinatário que originou o comando |
| `origem_tipo` | VARCHAR(30) | Origem da solicitação |
| `tipo_comando` | VARCHAR(60) | Tipo do comando |
| `parametros` | JSON, NULL | Parâmetros do comando |
| `status` | VARCHAR(30) | Estado atual |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `idempotency_key` | VARCHAR(150), NULL | Chave de idempotência |
| `solicitado_em` | DATETIME(3) | Solicitação |
| `enviado_em` | DATETIME(3), NULL | Envio |
| `confirmado_em` | DATETIME(3), NULL | Confirmação de recebimento |
| `concluido_em` | DATETIME(3), NULL | Conclusão |
| `mensagem_erro` | TEXT, NULL | Erro |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições e índices

- PK: `id`.
- UNIQUE: `codigo`.
- FK para dispositivo, componente, evento, usuário e destinatário.
- UNIQUE para `idempotency_key`, quando informada, respeitando a estratégia adotada.
- INDEX: (`dispositivo_id`, `status`, `solicitado_em`).
- INDEX: `correlation_id`.
- O backend deve validar que o componente informado pertence ao dispositivo de destino.
- A FK para `execucao_destinatarios` deverá ser adicionada depois da criação dessa tabela, caso a ordem das migrations exija.

### Estados possíveis

- `solicitado`
- `enviado`
- `recebido`
- `executado`
- `falhou`
- `expirado`
- `cancelado`
- `desconhecido`

O contrato do sistema deverá definir quais estados são suportados por cada tipo de dispositivo. Não se deve considerar o recebimento como prova de execução física.

### Três exemplos de registros

| ID | Código | Dispositivo | Tipo | Status |
|---:|---|---|---|---|
| 1 | CMD-2026-0001 | 2 | play_audio | executado |
| 2 | CMD-2026-0002 | 3 | activate_relay | falhou |
| 3 | CMD-2026-0003 | 2 | play_audio | expirado |

### Idempotência

A chave de idempotência representa uma solicitação lógica. Se uma mensagem for retransmitida, o backend deverá identificar a solicitação já processada e evitar a criação de uma segunda ação física indevida.

A chave deve ser construída e validada de forma consistente entre a execução da automação, o comando e o processamento de resultados.

---

## Tabela 18 — `historico_comandos`

**Finalidade:** preservar as transições de estado de cada comando.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `comando_id` | BIGINT UNSIGNED | FK para `comandos.id` |
| `status_anterior` | VARCHAR(30), NULL | Estado anterior |
| `status_novo` | VARCHAR(30) | Novo estado |
| `origem` | VARCHAR(30) | Origem da transição |
| `detalhes` | JSON, NULL | Informações complementares |
| `ocorrido_em` | DATETIME(3) | Momento do fato |
| `registrado_em` | DATETIME(3) | Momento do registro |

### Restrições

- PK: `id`.
- FK: `comando_id` → `comandos.id`.
- INDEX: (`comando_id`, `registrado_em`).
- A alteração do estado atual e a inserção no histórico devem ocorrer na mesma transação de banco de dados.

### Três exemplos de registros

| ID | Comando | Estado anterior | Estado novo | Origem |
|---:|---:|---|---|---|
| 1 | 1 | solicitado | enviado | backend |
| 2 | 1 | enviado | recebido | dispositivo |
| 3 | 1 | recebido | executado | dispositivo |

---

# 10. Mensagens Piper TTS

## Tabela 19 — `mensagens_audio`

**Finalidade:** armazenar os metadados do texto e do áudio gerado. A tabela não representa, por si só, o envio individual a cada dispositivo.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(80) | Código único do áudio |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `execucao_acao_id` | BIGINT UNSIGNED, NULL | Execução da ação que solicitou o áudio |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário solicitante |
| `origem_tipo` | VARCHAR(30) | Origem da solicitação |
| `texto_solicitado` | TEXT | Texto original solicitado |
| `texto_enviado` | TEXT, NULL | Texto efetivamente enviado ao TTS |
| `provedor_llm` | VARCHAR(100), NULL | Provedor de modelo de linguagem, se utilizado |
| `modelo_llm` | VARCHAR(100), NULL | Modelo de linguagem, se utilizado |
| `provedor_tts` | VARCHAR(100), NULL | Provedor TTS |
| `modelo_voz` | VARCHAR(150), NULL | Voz utilizada |
| `idioma` | VARCHAR(20), NULL | Idioma |
| `status_sintese` | VARCHAR(30) | Estado da síntese |
| `audio_uri` | VARCHAR(500), NULL | URI ou caminho do arquivo |
| `audio_hash_sha256` | CHAR(64), NULL | Hash do arquivo |
| `formato_audio` | VARCHAR(20), NULL | Formato, como WAV |
| `duracao_ms` | INT UNSIGNED, NULL | Duração em milissegundos |
| `erro` | TEXT, NULL | Erro de geração |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `solicitada_em` | DATETIME(3) | Solicitação |
| `sintetizada_em` | DATETIME(3), NULL | Conclusão da síntese |
| `created_at` | DATETIME(3) | Criação |

### Restrições

- PK: `id`.
- UNIQUE: `codigo`.
- FK para `eventos`, `execucoes_acoes` e `usuarios`.
- INDEX: (`evento_id`, `created_at`).
- INDEX: `correlation_id`.
- O vínculo com `execucoes_acoes` permite identificar qual ação solicitou a síntese.
- O resultado de reprodução por dispositivo será registrado no destinatário e/ou comando correspondente.
- Se a mesma ação precisar de mensagens distintas para áreas diferentes, devem ser criadas execuções de ação distintas, uma por destino lógico, ou deve ser adicionada uma associação explícita entre ações e mensagens.

### Estados possíveis da síntese

- `pendente`
- `processando`
- `generated`
- `failed`

Esses estados representam a geração do áudio, não o seu envio nem a reprodução.

### Três exemplos de registros

| ID | Código | Evento | Texto resumido | Estado |
|---:|---|---|---|---|
| 1 | MSG-001 | INV-2026-001 | Orientação para a Sala 101 | generated |
| 2 | MSG-002 | INV-2026-001 | Orientação para o Corredor Norte | generated |
| 3 | MSG-003 | INV-2026-001 | Orientação para a Sala 102 | failed |

### Exemplo de metadados de áudio

```json
{
  "audio_id": "MSG-001",
  "event_id": "INV-2026-001",
  "idioma": "pt-BR",
  "modelo_voz": "voz-aprovada",
  "formato": "wav",
  "audio_uri": "/audio/INV-2026-001/sala101.wav",
  "status_sintese": "generated"
}
```

Os valores são ilustrativos. O hash deve ser calculado sobre o arquivo real.

### Política de armazenamento

- Os arquivos ficam fora do MySQL.
- O banco armazena caminho ou URI, formato, duração, hash e metadados.
- O acesso ao arquivo deve ser autorizado.
- Um áudio idêntico pode ser reutilizado por vários destinatários.
- Áudios diferentes devem possuir identificadores próprios.
- O arquivo deve permanecer disponível enquanto houver operações ou registros que dependam dele.
- O dispositivo deve validar se o arquivo está acessível e se o formato é compatível antes da reprodução.
- O sistema deve evitar que comandos expirados provoquem reprodução tardia.

O Piper TTS pode ser executado em um serviço central, como um servidor Raspberry Pi. O backend solicita a geração, armazena os metadados e distribui a referência ao arquivo por MQTT.

---

# 11. Auditoria

## Tabela 20 — `auditoria`

**Finalidade:** registrar ações de usuários, dispositivos, integrações e processos automáticos.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `ator_tipo` | VARCHAR(30) | Tipo de ator |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário relacionado |
| `dispositivo_id` | BIGINT UNSIGNED, NULL | Dispositivo relacionado |
| `acao` | VARCHAR(80) | Ação executada |
| `entidade` | VARCHAR(80) | Nome da entidade afetada |
| `entidade_id` | BIGINT UNSIGNED | ID da entidade afetada |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `resultado` | VARCHAR(30) | Resultado |
| `descricao` | TEXT, NULL | Descrição |
| `dados_anteriores` | JSON, NULL | Estado anterior |
| `dados_posteriores` | JSON, NULL | Estado posterior |
| `canal_origem` | VARCHAR(30) | Canal de origem |
| `correlation_id` | VARCHAR(100), NULL | Correlação |
| `ip_origem` | VARCHAR(45), NULL | Endereço IP, quando aplicável |
| `ocorrido_em` | DATETIME(3) | Momento da ação |
| `registrado_em` | DATETIME(3) | Momento do registro |

### Restrições e índices

- PK: `id`.
- FK para usuário, dispositivo e evento, quando informados.
- INDEX: (`entidade`, `entidade_id`, `registrado_em`).
- INDEX: (`usuario_id`, `registrado_em`).
- INDEX: `correlation_id`.
- `entidade` e `entidade_id` formam uma referência polimórfica. O backend deve validar a entidade, pois uma FK convencional não consegue apontar para várias tabelas diferentes.
- Restringir alterações e exclusões da auditoria conforme a política de segurança.
- Proteger backups e limitar o acesso aos registros.

### Três exemplos de registros

| ID | Ator | Ação | Entidade | Resultado |
|---:|---|---|---|---|
| 1 | usuario | criar_evento | eventos | sucesso |
| 2 | sistema | publicar_comando | comandos | sucesso |
| 3 | dispositivo | reportar_falha | execucao_destinatarios | falha |

---

# 12. Regras e automações

## Tabela 21 — `regras_automacao`

**Finalidade:** definir as condições que disparam automações.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `nome` | VARCHAR(150) | Nome da regra |
| `descricao` | TEXT, NULL | Descrição |
| `tipo_gatilho` | VARCHAR(40) | Tipo de gatilho |
| `condicoes` | JSON | Condições de ativação |
| `prioridade` | INT | Prioridade |
| `ativa` | BOOLEAN | Indica se a regra está ativa |
| `criado_por` | BIGINT UNSIGNED, NULL | FK para `usuarios.id` |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições

- PK: `id`.
- FK: `criado_por` → `usuarios.id`.
- INDEX: (`ativa`, `tipo_gatilho`, `prioridade`).
- A coluna JSON `acoes` foi removida da versão anterior.
- As ações são cadastradas em `acoes_automacao`, permitindo validar, ordenar, executar e auditar cada ação individualmente.
- As condições JSON devem ser validadas pelo backend.
- Somente tipos de gatilho e operadores permitidos devem ser aceitos.

### Três exemplos de registros

| ID | Nome | Gatilho | Condição |
|---:|---|---|---|
| 1 | Alerta gás alto | leitura | `gas_level > 2500` |
| 2 | Câmera offline | status_dispositivo | offline por 60 segundos |
| 3 | Comando falhou | status_comando | status igual a `falhou` |

As condições são ilustrativas. O formato JSON definitivo deverá ser definido pelo motor de regras.

---

## Tabela 25 — `acoes_automacao` — NOVA

**Finalidade:** definir cada ação configurada dentro de uma regra.

Essa tabela separa a condição que dispara a regra da ação que deve ser realizada.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `regra_automacao_id` | BIGINT UNSIGNED | FK para `regras_automacao.id` |
| `codigo` | VARCHAR(80) | Código da ação dentro da regra |
| `nome` | VARCHAR(150) | Nome de exibição |
| `tipo_acao` | VARCHAR(60) | Tipo de ação |
| `configuracao` | JSON, NULL | Parâmetros padrão da ação |
| `ordem_execucao` | INT UNSIGNED | Ordem de execução |
| `ativa` | BOOLEAN | Indica se a ação está ativa |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições

- PK: `id`.
- FK: `regra_automacao_id` → `regras_automacao.id`.
- UNIQUE: (`regra_automacao_id`, `codigo`).
- INDEX: (`regra_automacao_id`, `ordem_execucao`).
- A ordem define a sequência lógica, mas a política de execução deve determinar se as ações são sequenciais ou paralelas.
- A configuração JSON deve ser validada pelo backend.

### Tipos de ação exemplificativos

- `criar_evento`
- `reproduzir_audio`
- `acionar_rele`
- `notificar_operador`
- `iniciar_coleta`
- `solicitar_intervencao`

### Três exemplos de registros

| ID | Regra | Nome | Tipo |
|---:|---:|---|---|
| 1 | 1 | Gerar alerta de áudio | reproduzir_audio |
| 2 | 1 | Acionar sinalizador | acionar_rele |
| 3 | 1 | Notificar operador | notificar_operador |

Uma configuração pode definir prioridade, idioma, perfil de voz, tipo de destino e exigência de confirmação. A automação não deve poder executar operações não autorizadas.

---

## Tabela 22 — `execucoes_automacao`

**Finalidade:** registrar cada avaliação e execução de uma regra.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `regra_automacao_id` | BIGINT UNSIGNED | FK para `regras_automacao.id` |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento que motivou a execução |
| `idempotency_key` | VARCHAR(150), NULL | Chave de idempotência |
| `status` | VARCHAR(30) | Estado da execução |
| `entrada` | JSON, NULL | Dados de entrada |
| `resultado` | JSON, NULL | Resultado consolidado |
| `erro` | TEXT, NULL | Erro |
| `iniciada_em` | DATETIME(3) | Início |
| `concluida_em` | DATETIME(3), NULL | Conclusão |

### Restrições

- PK: `id`.
- FK para `regras_automacao` e `eventos`.
- UNIQUE para `idempotency_key`, quando informada.
- INDEX: (`regra_automacao_id`, `iniciada_em`).
- INDEX: (`evento_id`, `status`).
- O estado da execução não substitui os estados individuais das ações.

### Estados possíveis

- `pendente`
- `em_execucao`
- `concluida`
- `concluida_parcialmente`
- `falhou`
- `cancelada`

### Três exemplos de registros

| ID | Regra | Evento | Status |
|---:|---:|---|---|
| 900 | 1 | GAS-2026-001 | concluida |
| 901 | 2 | NET-2026-002 | concluida_parcialmente |
| 902 | 3 | TTS-2026-003 | falhou |

A execução de uma regra não comprova que todas as ações foram realizadas fisicamente.

---

## Tabela 26 — `execucoes_acoes` — NOVA

**Finalidade:** registrar cada tentativa de executar uma ação específica dentro de uma execução de regra.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `execucao_automacao_id` | BIGINT UNSIGNED | FK para `execucoes_automacao.id` |
| `acao_automacao_id` | BIGINT UNSIGNED | FK para `acoes_automacao.id` |
| `numero_tentativa` | INT UNSIGNED | Número da tentativa |
| `status` | VARCHAR(30) | Estado da execução da ação |
| `entrada` | JSON, NULL | Dados usados na ação |
| `resultado` | JSON, NULL | Resultado da ação |
| `erro` | TEXT, NULL | Erro |
| `iniciada_em` | DATETIME(3) | Início |
| `concluida_em` | DATETIME(3), NULL | Conclusão |

### Restrições

- PK: `id`.
- FK para `execucoes_automacao` e `acoes_automacao`.
- UNIQUE: (`execucao_automacao_id`, `acao_automacao_id`, `numero_tentativa`).
- INDEX: (`execucao_automacao_id`, `status`).
- INDEX: (`acao_automacao_id`, `iniciada_em`).
- A tentativa deve ser registrada conforme a política de repetição.
- O resultado deve distinguir falha técnica, sucesso lógico e execução física confirmada.

### Três exemplos de registros

| ID | Execução da regra | Ação | Tentativa | Status |
|---:|---:|---:|---:|---|
| 1001 | 900 | 1 | 1 | concluida |
| 1002 | 900 | 2 | 1 | falhou |
| 1003 | 900 | 3 | 1 | concluida |

Uma regra pode ter várias ações, e cada ação pode apresentar um resultado independente.

**Observação:** se uma nova tentativa representar uma nova execução física, a idempotência deverá impedir que uma repetição de transporte provoque uma duplicação indevida.

---

# 13. Destinatários das ações

## Tabela 27 — `execucao_destinatarios` — NOVA

**Finalidade:** registrar os dispositivos selecionados para cada execução de ação e o resultado individual de cada destinatário.

A tabela representa a seleção e o resultado por dispositivo, não a localização hierárquica em si.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `execucao_acao_id` | BIGINT UNSIGNED | FK para `execucoes_acoes.id` |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `local_id` | BIGINT UNSIGNED, NULL | Local considerado na seleção |
| `status_envio` | VARCHAR(30) | Estado do envio |
| `status_resultado` | VARCHAR(30) | Resultado da execução |
| `tentativas` | INT UNSIGNED | Quantidade de tentativas |
| `selecionado_em` | DATETIME(3) | Momento da seleção |
| `enviado_em` | DATETIME(3), NULL | Momento do envio |
| `resultado_em` | DATETIME(3), NULL | Momento do resultado |
| `detalhes_resultado` | JSON, NULL | Resultado complementar |
| `mensagem_erro` | TEXT, NULL | Erro |

### Restrições

- PK: `id`.
- FK para `execucoes_acoes`, `dispositivos` e `locais`.
- INDEX: (`dispositivo_id`, `status_envio`).
- INDEX: (`execucao_acao_id`, `status_resultado`).
- UNIQUE: (`execucao_acao_id`, `dispositivo_id`) se houver somente um destinatário lógico por dispositivo e execução.
- Se o mesmo dispositivo puder receber diversas mensagens distintas dentro da mesma execução, a restrição deverá incluir a identidade lógica da mensagem ou deverá ser adotado um registro separado por entrega.
- `local_id` registra o local considerado no momento da seleção. O histórico de localização permite verificar onde o dispositivo estava instalado em determinado instante.

### Três exemplos de registros

| ID | Execução da ação | Dispositivo | Envio | Resultado |
|---:|---:|---|---|---|
| 1 | 1001 | AUD-001 | enviado | playback_completed |
| 2 | 1001 | AUD-002 | enviado | aguardando_confirmacao |
| 3 | 1001 | AUD-003 | falhou | dispositivo_indisponivel |

### Interpretação dos estados

- `pendente`: destinatário selecionado, ainda sem envio.
- `enviado`: comando publicado pelo backend.
- `recebido`: dispositivo confirmou o recebimento.
- `executado`: dispositivo confirmou a execução segundo o contrato do comando.
- `falhou`: ocorreu uma falha conhecida.
- `expirado`: o prazo terminou sem resultado válido.
- `desconhecido`: não existe evidência suficiente para determinar o resultado.

Os valores devem ser padronizados no backend. A ausência de resposta não pode ser tratada como confirmação de sucesso.

---

# 14. Histórico de localização física

## Tabela 28 — `historico_localizacao_dispositivo` — NOVA

**Finalidade:** registrar onde um dispositivo esteve instalado ao longo do tempo.

A tabela permite reconstruir a localização histórica de um dispositivo mesmo que ele tenha sido transferido para outra sala, corredor ou andar.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `local_id` | BIGINT UNSIGNED | FK para `locais.id` |
| `inicio_vigencia` | DATETIME(3) | Início da permanência |
| `fim_vigencia` | DATETIME(3), NULL | Fim da permanência |
| `motivo` | VARCHAR(150), NULL | Motivo da mudança |
| `registrado_por` | BIGINT UNSIGNED, NULL | Usuário responsável |
| `created_at` | DATETIME(3) | Criação |

### Restrições

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `local_id` → `locais.id`.
- FK: `registrado_por` → `usuarios.id`.
- INDEX: (`dispositivo_id`, `inicio_vigencia`, `fim_vigencia`).
- A linha sem `fim_vigencia` representa a localização vigente.
- Não podem existir períodos conflitantes para o mesmo dispositivo.
- A validação de sobreposição de intervalos deve ser implementada no backend ou por estratégia de banco que garanta a integridade temporal.

### Três exemplos de registros

| ID | Dispositivo | Local | Início | Fim |
|---:|---|---|---|---|
| 1 | AUD-001 | Sala 101 | 2026-01-10 | 2026-05-20 |
| 2 | AUD-001 | Sala 102 | 2026-05-20 | 2026-08-01 |
| 3 | AUD-001 | Sala 101 | 2026-08-01 | NULL |

### Regra de atualização

Ao transferir um equipamento, o backend deve:

1. Encerrar o período anterior.
2. Criar o novo período de localização.
3. Atualizar `dispositivos.local_id`.
4. Registrar a alteração na auditoria.
5. Realizar essas operações na mesma transação de banco de dados.

A mudança de localização física não deve exigir a alteração do identificador estável do dispositivo nem dos tópicos MQTT.

---

# 15. Sessões de coleta

## Tabela 23 — `sessoes_coleta`

**Finalidade:** controlar coletas de dados associadas a eventos, diagnósticos ou estudos manuais.

### Campos

| Campo | Tipo sugerido | Finalidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK |
| `codigo` | VARCHAR(80) | Código único da sessão |
| `dispositivo_id` | BIGINT UNSIGNED | FK para `dispositivos.id` |
| `componente_id` | BIGINT UNSIGNED, NULL | Componente monitorado |
| `evento_id` | BIGINT UNSIGNED, NULL | Evento relacionado |
| `tipo` | VARCHAR(30) | Tipo de sessão |
| `status` | VARCHAR(30) | Estado da sessão |
| `iniciada_por_tipo` | VARCHAR(30) | Origem da solicitação |
| `usuario_id` | BIGINT UNSIGNED, NULL | Usuário solicitante |
| `politica_pos_normalizacao` | VARCHAR(30) | Política após normalização |
| `amostras_pre_evento` | INT UNSIGNED | Quantidade de amostras anteriores |
| `iniciada_em` | DATETIME(3) | Início |
| `encerrada_em` | DATETIME(3), NULL | Encerramento |
| `motivo_encerramento` | VARCHAR(100), NULL | Motivo |
| `observacoes` | TEXT, NULL | Observações |
| `created_at` | DATETIME(3) | Criação |
| `updated_at` | DATETIME(3), NULL | Atualização |

### Restrições e índices

- PK: `id`.
- UNIQUE: `codigo`.
- FK para dispositivo, componente, evento e usuário.
- INDEX: (`dispositivo_id`, `iniciada_em`).
- INDEX: (`evento_id`, `status`).
- O backend deve validar a compatibilidade entre componente e dispositivo.
- A retenção deve preservar leituras referenciadas por eventos ou sessões.
- Cem amostras representam uma quantidade de registros, não uma duração fixa.

### Três exemplos de registros

| ID | Código | Tipo | Status |
|---:|---|---|---|
| 1 | COL-2026-001 | evento | concluida |
| 2 | COL-2026-002 | manual | em_execucao |
| 3 | COL-2026-003 | diagnostico | concluida |

---

# 16. Fluxo espacial de automação e Piper

Considere o evento `INV-2026-001`, relacionado à Sala 101, ao Corredor Norte e à Sala 102.

O sistema pode gerar três mensagens distintas:

| Evento | Ação | Destino | Áudio |
|---|---|---|---|
| INV-2026-001 | Ação para Sala 101 | Sala 101 / AUD-001 | MSG-001 |
| INV-2026-001 | Ação para Corredor Norte | Corredor Norte / AUD-002 | MSG-002 |
| INV-2026-001 | Ação para Sala 102 | Sala 102 / AUD-003 | MSG-003 |

O fluxo lógico é:

1. O backend recebe uma mensagem MQTT.
2. A mensagem é validada e registrada em `mensagens_integracao`.
3. Se for relevante, o sistema cria ou atualiza um evento em `eventos`.
4. Os locais relacionados são registrados em `evento_locais`.
5. O motor de automação avalia `regras_automacao`.
6. A execução é registrada em `execucoes_automacao`.
7. Cada ação configurada é registrada em `execucoes_acoes`.
8. O backend consulta `locais` e `dispositivos` para resolver os destinatários.
9. O serviço Piper gera os áudios necessários.
10. Os metadados são registrados em `mensagens_audio`.
11. Cada destinatário é registrado em `execucao_destinatarios`.
12. O backend cria os comandos individuais em `comandos`.
13. O envio e os resultados são registrados em `historico_comandos` e nos destinatários.
14. A interface apresenta o resultado de cada ação e dispositivo.

Uma mensagem idêntica pode ser reutilizada por vários dispositivos. Mensagens diferentes devem possuir registros de áudio distintos, embora pertençam ao mesmo evento.

**Segurança operacional:** orientações de emergência devem ser definidas por planos previamente aprovados. O sistema não deve inventar rotas de evacuação com base apenas na localização de um equipamento. Se a rota estiver bloqueada, for desconhecida ou não estiver validada, a automação deverá seguir uma política segura previamente definida e, quando necessário, solicitar intervenção humana.

---

# 17. Modelo de tópicos MQTT

A estratégia adotada é a **Opção B: tópicos centrados no identificador estável do dispositivo**.

A localização física será resolvida pelo backend utilizando `dispositivos.local_id` e a hierarquia da tabela `locais`.

## 17.1. Padrão de tópicos

```text
iot-edge/devices/{device_id}/telemetry
iot-edge/devices/{device_id}/status
iot-edge/devices/{device_id}/events
iot-edge/devices/{device_id}/commands
iot-edge/devices/{device_id}/command-results
```

Exemplos:

```text
iot-edge/devices/SEN-001/telemetry
iot-edge/devices/AUD-001/status
iot-edge/devices/AUD-001/commands
iot-edge/devices/AUD-001/command-results
iot-edge/devices/REF-001/commands
```

Os nomes são exemplos de uma convenção de tópicos. O identificador do dispositivo deve corresponder ao código cadastrado no banco.

A localização não precisa estar no tópico. Assim, transferir `AUD-001` da Sala 101 para a Sala 102 não exige mudar seu identificador nem sua estrutura de tópicos.

## 17.2. Exemplo de telemetria

Tópico:

```text
iot-edge/devices/SEN-001/telemetry
```

Payload ilustrativo:

```json
{
  "message_id": "TEL-2026-0001",
  "device_id": "SEN-001",
  "measured_at": "2026-10-21T18:20:00Z",
  "metrics": {
    "gas_level": 2780,
    "rssi": -92,
    "snr": 7.5
  }
}
```

O backend deve validar o formato, identificar o dispositivo e registrar as métricas em `leituras`. A mensagem original pode ser preservada em `mensagens_integracao` conforme a política de retenção.

O valor `gas_level` é ilustrativo. A interpretação do valor ADC de um MQ-4 exige calibração adequada.

## 17.3. Exemplo de comando de áudio

Tópico:

```text
iot-edge/devices/AUD-001/commands
```

Payload ilustrativo:

```json
{
  "command_id": "CMD-2026-0001",
  "event_id": "INV-2026-001",
  "action_id": "ACT-001",
  "audio_id": "MSG-001",
  "operation": "play_audio",
  "audio_uri": "https://servidor-autorizado/audio/INV-2026-001/sala101.wav",
  "priority": "emergency",
  "expires_at": "2026-10-21T18:30:00Z"
}
```

O endereço é ilustrativo e deverá ser substituído por um endereço real autorizado. O arquivo não deve ser transmitido como um payload MQTT grande; MQTT transporta o comando e os metadados, enquanto HTTP ou outro mecanismo apropriado entrega o arquivo.

O backend e o dispositivo devem verificar a validade temporal do comando. Um anúncio emergencial expirado não deve ser reproduzido tardiamente.

## 17.4. Exemplo de resultado

Tópico:

```text
iot-edge/devices/AUD-001/command-results
```

Payload ilustrativo:

```json
{
  "command_id": "CMD-2026-0001",
  "event_id": "INV-2026-001",
  "device_id": "AUD-001",
  "status": "playback_completed",
  "completed_at": "2026-10-21T18:29:42Z"
}
```

O dispositivo só deve emitir `playback_completed` quando a reprodução tiver terminado conforme o contrato do protocolo. Esse resultado não comprova que as pessoas ouviram ou seguiram a orientação.

## 17.5. Seleção de vários dispositivos

Para acionar todos os sintetizadores de uma zona, o backend deverá:

1. Identificar a zona selecionada.
2. Consultar os locais descendentes dessa zona.
3. Selecionar os dispositivos cadastrados nesses locais.
4. Filtrar os dispositivos compatíveis com a ação.
5. Registrar os destinatários em `execucao_destinatarios`.
6. Criar os comandos individuais em `comandos`.
7. Publicar um comando no tópico de cada dispositivo.
8. Processar os resultados e atualizar o estado de cada destinatário.

Os curingas `+` e `#` são utilizados em assinaturas MQTT. Eles não publicam automaticamente uma mensagem para todos os tópicos correspondentes.

## 17.6. Segurança e confiabilidade

Definir antes da implementação:

- Autenticação dos clientes MQTT.
- ACLs de publicação e assinatura.
- QoS adequado por tipo de mensagem.
- Política de mensagens retidas.
- Prazo de validade de comandos.
- Chave de idempotência.
- Correlação entre evento, ação, comando e resultado.
- Timeout e política de repetição.
- Tratamento de mensagens duplicadas.
- Validação dos payloads.
- Limites de tamanho.
- Confirmação de recebimento e execução.
- Política de auditoria e retenção.
- Proteção das credenciais e do transporte.

A política de QoS não substitui a idempotência. Mesmo com entrega confiável, o backend e os dispositivos devem tolerar mensagens duplicadas sem repetir indevidamente ações físicas.

---

# 18. Relacionamentos principais

| Origem | Destino | Cardinalidade |
|---|---|---|
| `locais` | `locais` | Relação hierárquica pai-filho |
| `locais` | `dispositivos` | Um local pode possuir vários dispositivos |
| `dispositivos` | `componentes_dispositivo` | Um dispositivo possui vários componentes |
| `dispositivos` | `leituras` | Um dispositivo possui várias leituras |
| `dispositivos` | `historico_status_dispositivo` | Um dispositivo possui vários registros de estado |
| `dispositivos` | `versoes_configuracao` | Um dispositivo possui várias versões |
| `dispositivos` | `historico_estado_gemeo` | Um dispositivo possui vários registros históricos |
| `dispositivos` | `historico_localizacao_dispositivo` | Um dispositivo possui vários períodos de localização |
| `usuarios` | `perfis` | N:N por `usuario_perfis` |
| `eventos` | `dispositivos` | N:N por `evento_dispositivos` |
| `eventos` | `componentes_dispositivo` | N:N por `evento_componentes` |
| `eventos` | `leituras` | N:N por `evento_leituras` |
| `eventos` | `locais` | N:N por `evento_locais` |
| `eventos` | `anexos` | Um evento pode possuir vários anexos |
| `regras_automacao` | `acoes_automacao` | Uma regra pode possuir várias ações |
| `regras_automacao` | `execucoes_automacao` | Uma regra pode ser executada várias vezes |
| `execucoes_automacao` | `execucoes_acoes` | Uma execução contém várias execuções de ações |
| `acoes_automacao` | `execucoes_acoes` | Uma ação configurada pode ser executada várias vezes |
| `execucoes_acoes` | `execucao_destinatarios` | Uma execução de ação pode possuir vários destinatários |
| `execucao_destinatarios` | `comandos` | Um destinatário pode originar um ou mais comandos conforme a política de tentativas |
| `comandos` | `historico_comandos` | Um comando possui várias transições históricas |
| `execucoes_acoes` | `mensagens_audio` | Uma execução de ação pode solicitar uma ou várias mensagens |
| `eventos` | `mensagens_audio` | Um evento pode originar várias mensagens de áudio |
| `eventos` | `sessoes_coleta` | Um evento pode possuir várias sessões de coleta |

As associações opcionais precisam ter FKs explícitas e regras de exclusão adequadas. O histórico não deve ser apagado em cascata quando um usuário, dispositivo ou local for desativado.

---

# 19. Coleta de dados e preservação de amostras

Para preservar as 100 amostras anteriores a um evento:

1. Manter uma janela móvel de leituras recentes por dispositivo ou componente.
2. Quando um evento ocorrer, identificar as leituras anteriores relevantes.
3. Criar ou atualizar a sessão de coleta.
4. Preservar as leituras referenciadas pelo evento ou pela sessão.
5. Continuar a coleta conforme a política definida.
6. Encerrar a sessão quando o período terminar ou quando a política permitir.
7. Aplicar retenção somente às leituras que não sejam necessárias para eventos, sessões ou obrigações de auditoria.

Cem amostras representam uma quantidade de registros, não uma duração fixa. A duração depende da frequência de coleta.

A implementação deve considerar sessões concorrentes, limites de armazenamento e a possibilidade de uma leitura estar associada a vários eventos.

O campo `protegida` em `leituras` pode auxiliar a política de retenção, mas não substitui as referências explícitas e as regras de preservação.

---

# 20. Reconstrução histórica do gêmeo digital

Para reconstruir um estado anterior:

1. Selecionar o evento ou intervalo de tempo.
2. Localizar o snapshot anterior mais próximo.
3. Determinar a versão de configuração vigente.
4. Consultar leituras, eventos, comandos e estados associados.
5. Ordenar os fatos por horário de ocorrência, preservando também o horário de registro.
6. Reconstruir o estado conhecido até cada instante.
7. Indicar lacunas, atrasos e estados desconhecidos.
8. Apresentar a linha do tempo no dashboard.
9. Consultar o histórico de localização quando for necessário identificar onde o equipamento estava instalado naquele momento.
10. Diferenciar fatos observados de comandos apenas solicitados.

A reprodução histórica deve ser somente de consulta. Ela nunca deve publicar comandos MQTT nem acionar equipamentos físicos.

---

# 21. Integridade e decisões técnicas antes das migrations

Antes de criar as tabelas no MySQL, validar:

1. A escolha entre CodeIgniter Shield e autenticação própria.
2. A ordem das migrations e as FKs que dependem de tabelas criadas posteriormente.
3. A integridade entre `dispositivo_id` e `componente_id`.
4. A consistência entre `execucao_destinatarios`, `comandos` e `historico_comandos`.
5. A validade dos estados permitidos por tabela.
6. A política de idempotência e repetição dos comandos.
7. A forma de evitar ciclos em `locais`.
8. A consistência temporal de `historico_localizacao_dispositivo`.
9. A política de retenção de leituras e mensagens de integração.
10. Os índices de busca por horário, dispositivo, evento, local e correlação.
11. A proteção dos arquivos de áudio e dos demais anexos.
12. A forma de validar as configurações JSON das automações.
13. A política de auditoria e de proteção contra alterações indevidas.
14. O contrato MQTT para telemetria, eventos, comandos e resultados.
15. O comportamento seguro em falhas de comunicação e em mensagens expiradas.
16. A política de compatibilidade de ações com categorias de dispositivos.
17. A política de confirmação para cada classe de comando.
18. A forma de preservar o contexto espacial e a localização histórica.
19. A estratégia de deduplicação de mensagens recebidas.
20. A política de expiração e disponibilidade dos arquivos de áudio.

## 21.1. Índices gerais recomendados

Além das PKs, FKs e restrições UNIQUE, avaliar:

- `dispositivos(local_id, categoria, status_operacional)`;
- `leituras(dispositivo_id, medido_em)`;
- `leituras(metrica, medido_em)`;
- `eventos(status, ocorrido_em)`;
- `evento_locais(local_id, evento_id)`;
- `mensagens_integracao(canal, recebido_em)`;
- `comandos(dispositivo_id, status, solicitado_em)`;
- `historico_comandos(comando_id, registrado_em)`;
- `execucoes_automacao(regra_automacao_id, iniciada_em)`;
- `execucoes_acoes(execucao_automacao_id, status)`;
- `execucao_destinatarios(dispositivo_id, status_envio)`;
- `historico_localizacao_dispositivo(dispositivo_id, inicio_vigencia, fim_vigencia)`.

Os índices definitivos devem ser validados contra as consultas reais e o volume esperado de dados. Índices adicionais aumentam o custo de escrita, especialmente nas tabelas de telemetria e integração.

## 21.2. Ordem sugerida de criação das migrations

Como existem FKs entre tabelas, a ordem de criação precisa considerar as dependências.

Uma sequência possível é:

1. `usuarios`
2. `perfis`
3. `locais`
4. `dispositivos`
5. `usuario_perfis`
6. `componentes_dispositivo`
7. `eventos`
8. `leituras`
9. `historico_status_dispositivo`
10. `versoes_configuracao`
11. `historico_estado_gemeo`
12. `evento_dispositivos`
13. `evento_componentes`
14. `evento_leituras`
15. `evento_locais`
16. `anexos`
17. `mensagens_integracao`
18. `regras_automacao`
19. `acoes_automacao`
20. `execucoes_automacao`
21. `execucoes_acoes`
22. `execucao_destinatarios`
23. `comandos`
24. `historico_comandos`
25. `mensagens_audio`
26. `auditoria`
27. `sessoes_coleta`
28. `historico_localizacao_dispositivo`

Essa ordem é uma referência, não uma exigência fixa. Como `comandos` e `execucao_destinatarios` se referenciam mutuamente, pode ser necessário criar primeiro as tabelas sem uma das FKs e adicioná-la em uma migration posterior. O mesmo cuidado vale para qualquer outra dependência circular.

---

# 22. Conclusão

O modelo proposto possui **28 tabelas**: as 23 originais e cinco extensões para locais de eventos, ações de automação, execução individual de ações, destinatários e histórico de localização.

A hierarquia física permite consultar equipamentos por sala, corredor, zona, andar ou edifício sem depender dos tópicos MQTT. O motor de automação pode selecionar os dispositivos adequados para cada ação, respeitando sua localização atual e sua compatibilidade funcional.

Um único evento pode produzir várias ações e mensagens Piper diferentes. Cada mensagem pode ser associada à execução correspondente, e cada destinatário possui seu próprio registro de envio e resultado. Essa estrutura permite distinguir geração de áudio, envio de comando, recebimento, execução e confirmação.

O banco também preserva o histórico de configurações, estados, comandos, eventos e localização física. Isso permite reconstruir o estado conhecido do gêmeo digital sem confundir uma solicitação com uma execução física confirmada.

A modelagem está consolidada no nível lógico. O próximo passo é validar as cardinalidades, os estados permitidos, as políticas de integridade e as dependências entre FKs, elaborar o diagrama relacional e então gerar as migrations e os seeders do CodeIgniter 4.

Os exemplos de registros são ilustrativos e não representam dados reais do ambiente. As estruturas JSON, os estados e os contratos MQTT deverão ser formalizados antes da implementação definitiva.
