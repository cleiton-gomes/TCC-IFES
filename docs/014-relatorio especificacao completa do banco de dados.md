# ESPECIFICAÇÃO FÍSICA DO BANCO DE DADOS
## IoT-Edge Command Center

**Projeto:** IoT-Edge Command Center  
**Instituição:** Instituto Federal do Espírito Santo — IFES, Campus Serra  
**Tecnologia de aplicação:** CodeIgniter 4.7.5  
**Linguagem:** PHP 8.2  
**Banco de dados:** MySQL 8.0  
**Driver:** MySQLi  
**Sistema de autenticação:** CodeIgniter Shield  
**Documento:** Especificação física e consolidação da modelagem do banco de dados  
**Versão:** 1.0 — Documento de consolidação  
**Data de referência:** Novembro de 2026

---

# 1. IDENTIFICAÇÃO E OBJETIVO DO DOCUMENTO

## 1.1 Objetivo

Este documento consolida a modelagem física do banco de dados do projeto IoT-Edge Command Center, reunindo as decisões arquiteturais, entidades, atributos, tipos de dados, relacionamentos, índices e regras de integridade definidos durante a revisão da estrutura de dados.

O documento será utilizado como referência central para:

- Implementação das migrations do CodeIgniter 4.
- Criação das tabelas de domínio no MySQL.
- Definição das chaves primárias e estrangeiras.
- Implementação dos índices e restrições.
- Desenvolvimento dos Models e Services.
- Implementação das regras transacionais.
- Integração com dispositivos embarcados, MQTT, serviços de IA e distribuição de áudio.
- Validação e testes de integridade do banco.

## 1.2 Escopo

A modelagem consolidada contempla oito grupos funcionais e 45 tabelas de domínio:

1. Estrutura física.
2. Telemetria e gêmeo digital.
3. Eventos e evidências.
4. Inteligência artificial.
5. Integração e comandos.
6. Automação.
7. Áudio e auditoria.
8. Cenas e cenários.

Além das tabelas de domínio, o projeto utiliza as tabelas de infraestrutura gerenciadas pelo CodeIgniter Shield e CodeIgniter Settings.

## 1.3 Classificação do estado das decisões

Para evitar confusão entre planejamento e implementação, este documento utiliza as seguintes classificações:

- **Implementado:** estrutura ou funcionalidade efetivamente existente e verificada.
- **Aprovado conceitualmente:** decisão arquitetural aceita, mas ainda não necessariamente implementada.
- **Especificado:** definição física registrada para orientar a implementação.
- **Pendente:** informação que precisa ser definida ou validada antes da implementação correspondente.

A existência de uma especificação não significa que a tabela já exista no banco.

---

# 2. AMBIENTE TECNOLÓGICO E CONFIGURAÇÃO DO BANCO

## 2.1 Ambiente

| Elemento | Configuração |
|---|---|
| Framework | CodeIgniter 4.7.5 |
| Linguagem | PHP 8.2 |
| Banco | MySQL 8.0 |
| Driver | MySQLi |
| Host | localhost |
| Porta | 3306 |
| Banco de dados | iot_edge_center |
| Usuário de desenvolvimento | root |
| Prefixo de tabelas | Vazio |
| Engine | InnoDB |
| Charset recomendado | utf8mb4 |

Diretório do projeto:

`C:\Users\Cleiton\Documents\TCC-IFES\web\iot-edge-command-center`

## 2.2 Estado real do banco

Na verificação realizada em 20/11/2026, foram identificadas nove tabelas existentes:

- users
- auth_identities
- auth_logins
- auth_token_logins
- auth_remember_tokens
- auth_groups_users
- auth_permissions_users
- settings
- migrations

Nenhuma das 45 tabelas de domínio estava criada naquela verificação.

Portanto, a modelagem deste documento representa a estrutura planejada e consolidada, não uma descrição de tabelas já implantadas.

## 2.3 Identificador de usuários

A tabela `users`, gerenciada pelo Shield, foi inspecionada diretamente no MySQL.

Foi confirmado:

- `users.id`: INT UNSIGNED.
- Chave primária.
- AUTO_INCREMENT.
- `username`: VARCHAR(30), com índice UNIQUE e possibilidade de NULL.
- Engine InnoDB.
- Charset utf8mb4.

Consequentemente, todas as futuras chaves estrangeiras que apontarem para `users.id` deverão utilizar `INT UNSIGNED`.

Não utilizar `BIGINT UNSIGNED` nessas referências.

## 2.4 Configuração de migrations

Arquivo:

`app/Config/Migrations.php`

Configuração verificada:

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Migrations extends BaseConfig
{
    public bool $enabled = true;
    public string $table = 'migrations';
    public string $timestampFormat = 'Y-m-d-His_';
    public bool $lock = false;
}
```

As migrations estão habilitadas e utilizam a tabela `migrations`.

A pasta `app/Database/Migrations` continha somente `.gitkeep` na verificação realizada.

## 2.5 Histórico de migrations existentes

Foram identificadas quatro migrations de infraestrutura executadas:

| Componente | Migration |
|---|---|
| CodeIgniter Shield | create_auth_tables |
| CodeIgniter Settings | CreateSettingsTable |
| CodeIgniter Settings | AddContextColumn |
| CodeIgniter Settings | ConvertSqlsrvValueColumn |

Todas estavam registradas no batch 1.

As migrations de infraestrutura não devem ser recriadas como migrations de domínio.

---

# 3. ARQUITETURA GERAL E DECISÕES DE MODELAGEM

## 3.1 Separação entre equipamentos e componentes

Um dispositivo representa um equipamento físico completo.

Exemplos:

- Heltec LoRa32 V3.
- ESP32 com Ethernet W5500.
- Raspberry Pi.
- Câmera IP.
- Controlador de automação.

Os componentes representam partes físicas ou funcionais desses equipamentos.

Exemplos:

- Sensor MQ-4.
- Módulo LoRa SX1262.
- Display OLED.
- ESP32-S3.
- Conversor DAC.
- Amplificador.
- Alto-falante.
- Módulo Ethernet W5500.

Essa separação permite registrar leituras, configurações e históricos em níveis diferentes.

## 3.2 Identificação dos dispositivos

Cada dispositivo terá um código globalmente único, independente de sua localização.

A localização física será resolvida por meio do relacionamento com `locais`.

O código do dispositivo será a referência estável utilizada pela aplicação e pelas integrações.

A localização não deverá ser tratada como dependência obrigatória do tópico MQTT.

## 3.3 Hierarquia de locais

A tabela `locais` utilizará relacionamento autorreferenciado por `local_pai_id`.

Tipos inicialmente previstos:

- edificio
- andar
- zona
- sala
- corredor
- area_externa

A hierarquia permitirá representar estruturas físicas e consultar seus níveis por meio de consultas recursivas.

## 3.4 Histórico e preservação dos dados

O sistema deverá preservar o histórico de:

- Localização dos dispositivos.
- Alterações de status.
- Configurações.
- Comandos.
- Eventos.
- Execuções de automação.
- Análises de IA.
- Distribuição de áudio.
- Auditoria.

A desativação de entidades deverá ser preferida à exclusão física quando houver histórico ou referências operacionais.

A regra geral para chaves estrangeiras será `ON DELETE RESTRICT`.

Não utilizar `ON DELETE CASCADE` indiscriminadamente.

## 3.5 Tipos de dados

Tipos físicos de referência:

| Finalidade | Tipo |
|---|---|
| PK de domínio | BIGINT UNSIGNED |
| FK para PK de domínio | BIGINT UNSIGNED |
| FK para users.id | INT UNSIGNED |
| Código | VARCHAR(80) |
| Nome | VARCHAR(120) |
| Descrição curta | VARCHAR(255) |
| Descrição longa | TEXT |
| Conteúdo extenso | LONGTEXT |
| Dados estruturados | JSON |
| Hash SHA-256 | CHAR(64) |
| Data e hora operacional | DATETIME(3) |
| Valor numérico de telemetria | DECIMAL(20,8) |
| Coordenadas geográficas | DECIMAL(10,7) |
| Indicador booleano | TINYINT(1) |

## 3.6 Datas e horários

Os horários operacionais deverão ser armazenados preferencialmente em UTC.

Quando necessário, utilizar `DATETIME(3)` para preservar milissegundos.

A aplicação deverá diferenciar:

- Momento em que um fato ocorreu.
- Momento em que foi recebido.
- Momento em que foi processado.
- Momento em que foi registrado.

Não utilizar um único timestamp para representar etapas distintas.

## 3.7 JSON

O tipo JSON nativo do MySQL será utilizado para armazenar estruturas variáveis, configurações e resultados.

O JSON não deverá substituir relacionamentos que precisem de integridade referencial, indexação relacional ou consultas frequentes entre entidades.

A validação estrutural dos documentos JSON será responsabilidade do backend.

## 3.8 Estados e categorias

Os estados serão armazenados inicialmente como VARCHAR, e não como ENUM do MySQL.

Os valores permitidos serão validados no backend.

Essa decisão facilita a evolução do sistema sem exigir alterações estruturais frequentes para adicionar novos estados.

---

# 4. INVENTÁRIO COMPLETO DAS TABELAS

| Grupo | Área | Quantidade |
|---|---|---:|
| 1 | Estrutura física | 4 |
| 2 | Telemetria e gêmeo digital | 6 |
| 3 | Eventos e evidências | 7 |
| 4 | Inteligência artificial | 8 |
| 5 | Integração e comandos | 3 |
| 6 | Automação | 5 |
| 7 | Áudio e auditoria | 3 |
| 8 | Cenas e cenários | 9 |
| | **Total de domínio** | **45** |

## 4.1 Relação das tabelas

### Grupo 1 — Estrutura física

1. `locais`
2. `dispositivos`
3. `componentes_dispositivo`
4. `historico_localizacao_dispositivo`

### Grupo 2 — Telemetria e gêmeo digital

5. `leituras`
6. `historico_status_dispositivo`
7. `versoes_configuracao`
8. `historico_estado_gemeo`
9. `sessoes_coleta`
10. `sessao_dispositivos`

### Grupo 3 — Eventos e evidências

11. `eventos`
12. `evento_locais`
13. `evento_dispositivos`
14. `evento_componentes`
15. `evento_leituras`
16. `anexos`
17. `evento_anexos`

### Grupo 4 — Inteligência artificial

18. `analises_ia`
19. `analise_anexos`
20. `artefatos_ia`
21. `versoes_artefatos_ia`
22. `implantacoes_ia`
23. `pipelines_ia`
24. `versoes_pipelines_ia`
25. `pipeline_componentes_ia`

### Grupo 5 — Integração e comandos

26. `mensagens_integracao`
27. `comandos`
28. `historico_comandos`

### Grupo 6 — Automação

29. `regras_automacao`
30. `acoes_automacao`
31. `execucoes_automacao`
32. `execucoes_acoes`
33. `execucao_destinatarios`

### Grupo 7 — Áudio e auditoria

34. `mensagens_audio`
35. `mensagem_audio_destinatarios`
36. `auditoria`

### Grupo 8 — Cenas e cenários

37. `cenas`
38. `versoes_cenas`
39. `cena_dispositivos`
40. `cenarios`
41. `versoes_cenarios`
42. `cenario_cenas`
43. `cenario_dispositivos`
44. `execucoes_cenarios`
45. `execucoes_cenas`

---

# 5. ESPECIFICAÇÃO FÍSICA DAS TABELAS

## 5.1 Grupo 1 — Estrutura física

### 5.1.1 Tabela `locais`

**Responsabilidade:** representar a estrutura física e hierárquica da instalação.

| Campo | Tipo | Nulo | Regra |
|---|---|---|---|
| id | BIGINT UNSIGNED | Não | PK, AUTO_INCREMENT |
| local_pai_id | BIGINT UNSIGNED | Sim | FK para locais.id |
| codigo | VARCHAR(80) | Não | UNIQUE |
| nome | VARCHAR(120) | Não | |
| tipo | VARCHAR(30) | Não | Validado no backend |
| descricao | TEXT | Sim | |
| latitude | DECIMAL(10,7) | Sim | |
| longitude | DECIMAL(10,7) | Sim | |
| referencia | VARCHAR(190) | Sim | |
| ativo | TINYINT(1) | Não | DEFAULT 1 |
| created_at | DATETIME(3) | Sim | DEFAULT CURRENT_TIMESTAMP(3) |
| updated_at | DATETIME(3) | Sim | ON UPDATE CURRENT_TIMESTAMP(3) |

**Chaves e índices:**

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `local_pai_id` → `locais.id`.
- Índices: `local_pai_id`, `tipo`, `ativo`.

**Integridade:**

- Impedir autorreferência direta.
- Impedir ciclos na hierarquia.
- Latitude entre -90 e 90.
- Longitude entre -180 e 180.
- Não excluir fisicamente locais referenciados.
- Utilizar `ON DELETE RESTRICT`.

**Exemplo:** edifício → andar → zona → sala.

### 5.1.2 Tabela `dispositivos`

**Responsabilidade:** cadastrar equipamentos físicos completos.

| Campo | Tipo | Nulo | Regra |
|---|---|---|---|
| id | BIGINT UNSIGNED | Não | PK, AUTO_INCREMENT |
| local_id | BIGINT UNSIGNED | Sim | FK locais.id |
| codigo | VARCHAR(80) | Não | UNIQUE |
| nome | VARCHAR(120) | Não | |
| categoria | VARCHAR(40) | Não | Validada no backend |
| fabricante | VARCHAR(100) | Sim | |
| modelo | VARCHAR(100) | Sim | |
| numero_serie | VARCHAR(120) | Sim | |
| protocolo | VARCHAR(30) | Não | |
| topico_base_mqtt | VARCHAR(255) | Sim | UNIQUE quando preenchido |
| endereco_rede | VARCHAR(255) | Sim | |
| status | VARCHAR(30) | Não | |
| ultimo_contato | DATETIME(3) | Sim | |
| configuracao | JSON | Sim | |
| ativo | TINYINT(1) | Não | |
| created_at | DATETIME(3) | Sim | |
| updated_at | DATETIME(3) | Sim | |

**Índices e chaves:**

- PK: `id`.
- UNIQUE: `codigo`.
- UNIQUE: `topico_base_mqtt`.
- FK: `local_id` → `locais.id`.
- Índices: `local_id`, `categoria`, `status`, `ativo`.

O MySQL permite múltiplos valores NULL em índices UNIQUE, permitindo que dispositivos que não utilizam MQTT não tenham tópico-base.

**Categorias iniciais:**

`sensor`, `camera`, `controlador`, `gateway`, `atuador`, `equipamento`, `outro`.

**Estados iniciais:**

`desconhecido`, `online`, `offline`, `alerta`, `manutencao`.

**Regras:**

- `codigo` permanece estável mesmo quando o equipamento muda de local.
- `local_id` pode ser NULL antes da instalação.
- `status` não substitui `ativo`.
- `ultimo_contato` só deve ser atualizado após contato efetivamente confirmado.
- Segredos e credenciais não devem ser armazenados em texto aberto.
- O JSON representa a configuração geral atual do dispositivo.
- Componentes complementares devem ser cadastrados em `componentes_dispositivo`.

### 5.1.3 Tabela `componentes_dispositivo`

**Responsabilidade:** representar os componentes físicos ou funcionais associados a um equipamento.

| Campo | Tipo | Nulo | Regra |
|---|---|---|---|
| id | BIGINT UNSIGNED | Não | PK |
| dispositivo_id | BIGINT UNSIGNED | Não | FK dispositivos.id |
| codigo | VARCHAR(80) | Não | Único por dispositivo |
| nome | VARCHAR(120) | Não | |
| tipo | VARCHAR(40) | Não | |
| fabricante | VARCHAR(100) | Sim | |
| modelo | VARCHAR(100) | Sim | |
| numero_serie | VARCHAR(120) | Sim | |
| descricao | TEXT | Sim | |
| configuracao | JSON | Sim | |
| status | VARCHAR(30) | Não | |
| ativo | TINYINT(1) | Não | |
| created_at | DATETIME(3) | Sim | |
| updated_at | DATETIME(3) | Sim | |

**Chaves e índices:**

- PK: `id`.
- UNIQUE: `(dispositivo_id, codigo)`.
- FK: `dispositivo_id` → `dispositivos.id`.
- Índices: `tipo`, `status`, `ativo`, `dispositivo_id`.

**Tipos iniciais:**

`sensor`, `comunicacao`, `processamento`, `audio`, `interface`, `atuador`, `alimentacao`, `armazenamento`, `outro`.

**Exemplos:**

- Heltec LoRa32 V3: MQ4-001, LORA-001, OLED-001, ESP32-001.
- ESP32 com áudio: DAC, amplificador e alto-falante.
- ESP32 W5500: MCU, interface Ethernet e alimentação.

A configuração individual de cada componente fica nesta tabela. A configuração integral e histórica do equipamento fica em `versoes_configuracao`.

### 5.1.4 Tabela `historico_localizacao_dispositivo`

**Responsabilidade:** preservar o histórico de instalação e movimentação dos dispositivos.

| Campo | Tipo | Nulo | Regra |
|---|---|---|---|
| id | BIGINT UNSIGNED | Não | PK |
| dispositivo_id | BIGINT UNSIGNED | Não | FK dispositivos.id |
| local_id | BIGINT UNSIGNED | Não | FK locais.id |
| usuario_id | INT UNSIGNED | Sim | FK users.id |
| inicio_vigencia | DATETIME(3) | Não | |
| fim_vigencia | DATETIME(3) | Sim | |
| motivo | VARCHAR(255) | Sim | |
| observacoes | TEXT | Sim | |
| dispositivo_vigente_id | BIGINT UNSIGNED | Gerado | Coluna gerada armazenada |
| created_at | DATETIME(3) | Sim | |

**Coluna gerada:**

```sql
CASE
    WHEN fim_vigencia IS NULL THEN dispositivo_id
    ELSE NULL
END
```

A coluna deverá ser armazenada e possuir índice UNIQUE, garantindo no máximo um registro vigente por dispositivo.

**Chaves e índices:**

- PK: `id`.
- UNIQUE: `dispositivo_vigente_id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `local_id` → `locais.id`.
- FK: `usuario_id` → `users.id`.
- Índices para dispositivo, local e período.

**Restrições:**

- CHECK: `fim_vigencia IS NULL OR fim_vigencia > inicio_vigencia`.
- Uma localização vigente por dispositivo.
- Backend impede sobreposição de períodos.
- Alteração de localização, fechamento do histórico anterior e criação do novo registro devem ocorrer na mesma transação.
- Atualizar `dispositivos.local_id` na mesma operação.

Se um dispositivo ainda não foi instalado, `dispositivos.local_id` permanece NULL e não há registro de histórico.

---

## 5.2 Grupo 2 — Telemetria e gêmeo digital

### 5.2.1 Tabela `leituras`

**Responsabilidade:** armazenar dados de telemetria recebidos dos dispositivos.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| componente_id | BIGINT UNSIGNED | Sim |
| metrica | VARCHAR(80) | Não |
| valor_numerico | DECIMAL(20,8) | Sim |
| valor_textual | TEXT | Sim |
| unidade | VARCHAR(30) | Sim |
| qualidade | VARCHAR(30) | Não |
| payload_original | JSON | Sim |
| medido_em | DATETIME(3) | Não |
| recebido_em | DATETIME(3) | Não |
| protegida | TINYINT(1) | Não |
| created_at | DATETIME(3) | Sim |

**Chaves:**

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `componente_id` → `componentes_dispositivo.id`.

**Índices:**

- `(dispositivo_id, medido_em)`.
- `(componente_id, medido_em)`.
- `(metrica, medido_em)`.
- `recebido_em`.

**Regras:**

- Pelo menos um entre `valor_numerico` e `valor_textual` deverá ser preenchido.
- Os dois podem coexistir.
- O componente, quando informado, deverá pertencer ao dispositivo.
- `medido_em` representa o momento da medição.
- `recebido_em` representa o momento em que o sistema recebeu a informação.
- Leituras de ADC não deverão ser automaticamente interpretadas como ppm sem calibração.

### 5.2.2 Tabela `historico_status_dispositivo`

**Responsabilidade:** preservar as mudanças de estado operacional dos dispositivos.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| evento_id | BIGINT UNSIGNED | Sim |
| status_anterior | VARCHAR(30) | Sim |
| status_novo | VARCHAR(30) | Não |
| motivo | VARCHAR(255) | Sim |
| origem | VARCHAR(30) | Não |
| detalhes | JSON | Sim |
| ocorrido_em | DATETIME(3) | Não |
| registrado_em | DATETIME(3) | Não |

**Estados:**

`desconhecido`, `online`, `offline`, `alerta`, `manutencao`, `inativo`.

**Origens:**

`mqtt`, `sistema`, `usuario`, `monitoramento`, `integracao`.

**Chaves e índices:**

- FK dispositivo.
- FK evento.
- Índices `(dispositivo_id, ocorrido_em)`, `evento_id`, `status_novo`, `registrado_em`.

A alteração do estado atual em `dispositivos.status` e a inserção do histórico deverão ocorrer na mesma transação.

O histórico será preservado sem exclusão física.

### 5.2.3 Tabela `versoes_configuracao`

**Responsabilidade:** preservar snapshots completos das configurações dos dispositivos.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| evento_id | BIGINT UNSIGNED | Sim |
| usuario_id | INT UNSIGNED | Sim |
| versao | INT UNSIGNED | Não |
| configuracao | JSON | Não |
| hash_configuracao | CHAR(64) | Não |
| origem | VARCHAR(30) | Não |
| descricao | VARCHAR(255) | Sim |
| vigente_desde | DATETIME(3) | Não |
| created_at | DATETIME(3) | Sim |

**Chaves e índices:**

- UNIQUE `(dispositivo_id, versao)`.
- FK dispositivo.
- FK evento.
- FK users.
- Índices `(dispositivo_id, vigente_desde)`, `evento_id`, `hash_configuracao`.

O snapshot deverá conter a configuração geral do dispositivo e a configuração dos seus componentes.

Cada versão deverá ser imutável.

A atualização da configuração atual e a criação da versão correspondente deverão ocorrer na mesma transação.

O hash será calculado utilizando SHA-256.

### 5.2.4 Tabela `historico_estado_gemeo`

**Responsabilidade:** preservar o histórico dos estados representados pelo gêmeo digital.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| evento_id | BIGINT UNSIGNED | Sim |
| leitura_id | BIGINT UNSIGNED | Sim |
| comando_id | BIGINT UNSIGNED | Sim |
| versao_configuracao_id | BIGINT UNSIGNED | Sim |
| tipo_estado | VARCHAR(40) | Não |
| estado | JSON | Não |
| origem | VARCHAR(30) | Não |
| ocorrido_em | DATETIME(3) | Não |
| registrado_em | DATETIME(3) | Não |

**Chaves estrangeiras:**

- `dispositivo_id` → dispositivos.id.
- `evento_id` → eventos.id.
- `leitura_id` → leituras.id.
- `comando_id` → comandos.id.
- `versao_configuracao_id` → versoes_configuracao.id.

**Índices:**

- `(dispositivo_id, ocorrido_em)`.
- `evento_id`.
- `tipo_estado`.

Os registros poderão representar snapshots completos ou parciais.

As referências a evento, leitura, comando e versão poderão coexistir quando representarem aspectos diferentes da mesma alteração.

O backend deverá validar que todas as referências associadas pertencem ao mesmo dispositivo.

O gêmeo digital será utilizado para consulta e representação do estado. Não deverá reenviar comandos físicos apenas por consultar seu histórico.

### 5.2.5 Tabela `sessoes_coleta`

**Responsabilidade:** representar sessões de coleta de dados e monitoramento.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| evento_id | BIGINT UNSIGNED | Sim |
| dispositivo_id | BIGINT UNSIGNED | Sim |
| usuario_id | INT UNSIGNED | Sim |
| codigo | VARCHAR(80) | Não |
| tipo | VARCHAR(40) | Não |
| status | VARCHAR(30) | Não |
| iniciada_em | DATETIME(3) | Não |
| encerrada_em | DATETIME(3) | Sim |
| parametros | JSON | Sim |
| resumo | JSON | Sim |
| created_at | DATETIME(3) | Sim |

**Tipos:**

`continua`, `evento`, `diagnostico`, `manual`, `automatizada`.

**Estados:**

`aguardando`, `em_execucao`, `concluida`, `cancelada`, `falhou`.

A sessão poderá ser geral, sem dispositivo principal, ou associada a um dispositivo principal/originador.

### 5.2.6 Tabela `sessao_dispositivos`

**Responsabilidade:** registrar todos os dispositivos participantes de uma sessão de coleta.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| sessao_id | BIGINT UNSIGNED | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| papel | VARCHAR(30) | Não |
| status | VARCHAR(30) | Não |
| inicio_participacao | DATETIME(3) | Sim |
| fim_participacao | DATETIME(3) | Sim |
| detalhes | JSON | Sim |
| created_at | DATETIME(3) | Sim |

**Chaves:**

- UNIQUE `(sessao_id, dispositivo_id)`.
- FK `sessao_id` → sessoes_coleta.id.
- FK `dispositivo_id` → dispositivos.id.

A tabela deverá registrar também o dispositivo principal, quando aplicável.

---

## 5.3 Grupo 3 — Eventos e evidências

### 5.3.1 Tabela `eventos`

**Responsabilidade:** registrar ocorrências relevantes no ambiente monitorado.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| evento_pai_id | BIGINT UNSIGNED | Sim |
| usuario_id | INT UNSIGNED | Sim |
| codigo | VARCHAR(80) | Não |
| tipo | VARCHAR(60) | Não |
| severidade | VARCHAR(20) | Não |
| status | VARCHAR(30) | Não |
| origem | VARCHAR(40) | Não |
| titulo | VARCHAR(180) | Não |
| descricao | TEXT | Sim |
| dados | JSON | Sim |
| ocorrido_em | DATETIME(3) | Não |
| registrado_em | DATETIME(3) | Não |
| reconhecido_em | DATETIME(3) | Sim |
| encerrado_em | DATETIME(3) | Sim |
| created_at | DATETIME(3) | Sim |
| updated_at | DATETIME(3) | Sim |

**Chaves:**

- UNIQUE `codigo`.
- FK autorreferenciada `evento_pai_id` → eventos.id.
- FK `usuario_id` → users.id.

**Severidades:**

`informativa`, `baixa`, `media`, `alta`, `critica`.

**Estados:**

`aberto`, `reconhecido`, `em_atendimento`, `resolvido`, `encerrado`, `cancelado`.

**Origens:**

`dispositivo`, `sistema`, `usuario`, `visao_computacional`, `interpretacao_contextual`, `integracao`, `automacao`.

**Índices:**

- `(status, severidade)`.
- `(tipo, ocorrido_em)`.
- `usuario_id`.
- `evento_pai_id`.
- `registrado_em`.

Eventos derivados poderão apontar para um evento pai, mas terão ciclo de vida próprio.

### 5.3.2 Tabela `evento_locais`

**Responsabilidade:** associar eventos a um ou mais locais.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED`.
- `local_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `observacoes VARCHAR(255) NULL`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(evento_id, local_id)`.
- FK evento.
- FK local.

Papéis:

`origem`, `afetado`, `abrangencia`, `referencia`.

### 5.3.3 Tabela `evento_dispositivos`

**Responsabilidade:** associar eventos aos dispositivos relacionados.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED`.
- `dispositivo_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(evento_id, dispositivo_id)`.
- FK evento.
- FK dispositivo.

Papéis:

`origem`, `afetado`, `participante`, `destinatario`, `referencia`.

O papel `destinatario` indica seleção para receber uma ação, não comprova recebimento ou execução.

### 5.3.4 Tabela `evento_componentes`

**Responsabilidade:** associar eventos aos componentes envolvidos.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED`.
- `componente_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(evento_id, componente_id)`.
- FK evento.
- FK componente.

Papéis:

`origem`, `afetado`, `participante`, `referencia`.

O backend deverá validar a coerência do componente com o dispositivo relacionado ao evento, quando aplicável.

### 5.3.5 Tabela `evento_leituras`

**Responsabilidade:** relacionar eventos com leituras que serviram como gatilho, evidência ou contexto.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED`.
- `leitura_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(evento_id, leitura_id)`.
- FK evento.
- FK leitura.

Papéis:

`gatilho`, `evidencia`, `contexto`, `posterior`.

O backend deverá validar a coerência entre a leitura, seu dispositivo e o evento.

### 5.3.6 Tabela `anexos`

**Responsabilidade:** manter o catálogo de arquivos externos utilizados pelo sistema.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| codigo | VARCHAR(80) | Não |
| nome_original | VARCHAR(255) | Não |
| nome_armazenado | VARCHAR(255) | Não |
| tipo_mime | VARCHAR(120) | Não |
| categoria | VARCHAR(30) | Não |
| extensao | VARCHAR(15) | Sim |
| tamanho_bytes | BIGINT UNSIGNED | Não |
| hash_sha256 | CHAR(64) | Sim |
| uri_armazenamento | VARCHAR(500) | Não |
| descricao | TEXT | Sim |
| metadados | JSON | Sim |
| usuario_id | INT UNSIGNED | Sim |
| created_at | DATETIME(3) | Sim |

**Chaves e índices:**

- UNIQUE `codigo`.
- FK `usuario_id` → users.id.
- Índices `categoria`, `tipo_mime`, `hash_sha256`, `created_at`.

Categorias:

`imagem`, `video`, `audio`, `documento`, `outro`.

Os arquivos serão armazenados externamente. O banco guardará seus metadados, URI e hash.

Não armazenar arquivos binários diretamente nessa tabela.

O hash não será UNIQUE, pois arquivos iguais podem ser associados a contextos distintos.

### 5.3.7 Tabela `evento_anexos`

**Responsabilidade:** associar evidências e arquivos aos eventos.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED`.
- `anexo_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `observacoes VARCHAR(255) NULL`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(evento_id, anexo_id)`.
- FK evento.
- FK anexo.

Papéis:

`evidencia`, `entrada_analise`, `resultado`, `contexto`.

Um anexo poderá ser associado a vários eventos, sem duplicidade no mesmo evento.

---

## 5.4 Grupo 4 — Inteligência artificial

### 5.4.1 Tabela `analises_ia`

**Responsabilidade:** registrar as execuções de processamento inteligente.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| codigo | VARCHAR(80) | Não |
| analise_pai_id | BIGINT UNSIGNED | Sim |
| evento_id | BIGINT UNSIGNED | Sim |
| dispositivo_id | BIGINT UNSIGNED | Sim |
| implantacao_id | BIGINT UNSIGNED | Sim |
| pipeline_versao_id | BIGINT UNSIGNED | Sim |
| artefato_versao_id | BIGINT UNSIGNED | Sim |
| pipeline_componente_id | BIGINT UNSIGNED | Sim |
| tipo | VARCHAR(40) | Não |
| servico | VARCHAR(100) | Não |
| modelo | VARCHAR(120) | Sim |
| versao_modelo | VARCHAR(80) | Sim |
| status | VARCHAR(30) | Não |
| entrada | JSON | Sim |
| resultado | JSON | Sim |
| metricas | JSON | Sim |
| confianca | DECIMAL(8,6) | Sim |
| duracao_ms | BIGINT UNSIGNED | Sim |
| erro | TEXT | Sim |
| iniciada_em | DATETIME(3) | Não |
| concluida_em | DATETIME(3) | Sim |
| created_at | DATETIME(3) | Sim |

**Chaves:**

- UNIQUE `codigo`.
- FK autorreferenciada `analise_pai_id`.
- FK evento.
- FK dispositivo.
- FK implantação.
- FK versão pipeline.
- FK versão artefato.
- FK componente do pipeline.

**Tipos:**

`visao_computacional`, `interpretacao_contextual`, `classificacao`, `deteccao`, `reconhecimento`, `validacao`.

**Estados:**

`aguardando`, `em_execucao`, `concluida`, `falhou`, `cancelada`, `expirada`.

**Regras:**

- Uma análise não deverá apontar simultaneamente para uma versão de pipeline e uma versão de artefato.
- Execuções principais de pipeline apontam para `pipeline_versao_id`.
- Execuções filhas podem apontar para a versão exata do artefato executado.
- Execuções independentes podem não possuir pipeline.
- `pipeline_componente_id` identifica o componente lógico que originou a execução filha.
- O backend deverá validar a coerência entre análise pai, pipeline, componente e artefato.
- A implantação, quando informada, deverá ser compatível com dispositivo e versão executada.

As métricas devem preservar sua semântica, unidade e método de agregação.

### 5.4.2 Tabela `analise_anexos`

**Responsabilidade:** relacionar arquivos às análises.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `analise_id BIGINT UNSIGNED`.
- `anexo_id BIGINT UNSIGNED`.
- `papel VARCHAR(30)`.
- `ordem INT UNSIGNED NULL`.
- `observacoes VARCHAR(255) NULL`.
- `created_at DATETIME(3) NULL`.

**Restrições:**

- UNIQUE `(analise_id, anexo_id, papel)`.
- FK análise.
- FK anexo.

Papéis:

`entrada`, `evidencia`, `resultado`, `frame`, `referencia`.

### 5.4.3 Tabela `artefatos_ia`

**Responsabilidade:** representar o cadastro lógico de artefatos de inteligência artificial.

Exemplos:

- Coordenador.
- Especialista de visão computacional.
- Especialista de interpretação contextual.
- Especialista de validação.
- Scripts auxiliares.
- Serviços de processamento.

A tabela representa o artefato lógico, não uma execução ou uma versão específica.

Campos físicos e restrições detalhadas ainda precisam ser formalizados antes da migration.

### 5.4.4 Tabela `versoes_artefatos_ia`

**Responsabilidade:** preservar versões imutáveis dos artefatos de IA.

A estrutura deverá contemplar:

- Identificador.
- Referência ao artefato lógico.
- Número ou identificação da versão.
- Hash SHA-256.
- URI de armazenamento.
- Dependências.
- Configuração.
- Estado.
- Data de publicação.

Cada versão deverá ser identificável e imutável.

Os campos físicos completos, tamanhos, nulabilidade e índices ainda precisam ser formalizados.

### 5.4.5 Tabela `implantacoes_ia`

**Responsabilidade:** representar a implantação de versões de artefatos ou pipelines nos dispositivos.

Relacionamentos definidos:

- Dispositivo.
- Versão de artefato.
- Versão de pipeline.

Uma implantação deverá apontar para exatamente uma das duas opções:

- `artefato_versao_id`.
- `pipeline_versao_id`.

A restrição deverá ser implementada por CHECK no MySQL 8, além da validação no backend.

A estrutura física completa e as regras de unicidade e vigência ainda precisam ser fechadas.

### 5.4.6 Tabela `pipelines_ia`

**Responsabilidade:** representar o cadastro lógico de pipelines de processamento.

A tabela não deverá armazenar novamente a referência ao coordenador, pois essa associação será definida na versão do pipeline.

Campos físicos completos ainda precisam ser formalizados.

### 5.4.7 Tabela `versoes_pipelines_ia`

**Responsabilidade:** preservar versões imutáveis de pipelines.

Cada versão deverá identificar obrigatoriamente a versão exata do artefato coordenador.

O pipeline não deverá depender de uma referência genérica ao artefato lógico quando precisar reproduzir uma execução histórica.

Os campos completos e índices ainda precisam ser formalizados.

### 5.4.8 Tabela `pipeline_componentes_ia`

**Responsabilidade:** registrar os componentes que participam de uma versão de pipeline.

A estrutura deverá contemplar:

- Identificador.
- Versão do pipeline.
- Versão exata do artefato.
- Papel do componente.
- Ordem de execução.
- Grupo lógico.
- Dependências.
- Configuração.
- Parâmetros.
- Indicador de obrigatoriedade.
- Indicador de habilitação.
- `metricas_esperadas JSON`.

Um mesmo artefato poderá participar de diferentes pipelines e assumir papéis diferentes.

O vínculo deverá ser com a versão exata do artefato.

---

## 5.5 Grupo 5 — Integração e comandos

### 5.5.1 Tabela `mensagens_integracao`

**Responsabilidade:** registrar mensagens de integração recebidas e enviadas.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| codigo | VARCHAR(80) | Não |
| dispositivo_id | BIGINT UNSIGNED | Sim |
| comando_id | BIGINT UNSIGNED | Sim |
| evento_id | BIGINT UNSIGNED | Sim |
| analise_id | BIGINT UNSIGNED | Sim |
| protocolo | VARCHAR(30) | Não |
| direcao | VARCHAR(10) | Não |
| topico | VARCHAR(255) | Sim |
| identificador_mensagem | VARCHAR(120) | Sim |
| correlation_id | VARCHAR(120) | Sim |
| chave_idempotencia | VARCHAR(120) | Sim |
| payload | JSON | Sim |
| payload_bruto | LONGTEXT | Sim |
| status | VARCHAR(30) | Não |
| erro | TEXT | Sim |
| recebida_em | DATETIME(3) | Sim |
| processada_em | DATETIME(3) | Sim |
| enviada_em | DATETIME(3) | Sim |
| confirmada_em | DATETIME(3) | Sim |
| created_at | DATETIME(3) | Sim |

**Protocolos:**

`mqtt`, `http`, `https`, `websocket`, `outro`.

**Direções:**

`entrada`, `saida`.

**Estados:**

`recebida`, `validando`, `processada`, `enviada`, `confirmada`, `falhou`, `duplicada`, `descartada`.

**Chaves e índices:**

- UNIQUE `codigo`.
- FKs para dispositivo, comando, evento e análise.
- Índices protocolo/direção/created_at, tópico, dispositivo, comando, evento, correlation_id e status.

Não impor unicidade global para identificadores externos ou chaves de idempotência sem definir o escopo.

Não armazenar segredos ou arquivos binários.

### 5.5.2 Tabela `comandos`

**Responsabilidade:** registrar solicitações de execução destinadas aos dispositivos.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| codigo | VARCHAR(80) | Não |
| dispositivo_id | BIGINT UNSIGNED | Não |
| componente_id | BIGINT UNSIGNED | Sim |
| evento_id | BIGINT UNSIGNED | Sim |
| usuario_id | INT UNSIGNED | Sim |
| tipo | VARCHAR(60) | Não |
| parametros | JSON | Sim |
| status | VARCHAR(30) | Não |
| correlation_id | VARCHAR(120) | Não |
| chave_idempotencia | VARCHAR(120) | Não |
| solicitado_em | DATETIME(3) | Não |
| enviado_em | DATETIME(3) | Sim |
| recebido_em | DATETIME(3) | Sim |
| executado_em | DATETIME(3) | Sim |
| expira_em | DATETIME(3) | Sim |
| execucao_destinatario_id | BIGINT UNSIGNED | Sim |
| execucao_cena_id | BIGINT UNSIGNED | Sim |
| created_at | DATETIME(3) | Sim |
| updated_at | DATETIME(3) | Sim |

**Chaves e índices:**

- UNIQUE `codigo`.
- UNIQUE `(dispositivo_id, chave_idempotencia)`.
- FK dispositivo.
- FK componente.
- FK evento.
- FK usuário.
- FK `execucao_destinatario_id` → execucao_destinatarios.id.
- FK `execucao_cena_id` → execucoes_cenas.id.
- Índices `(dispositivo_id, solicitado_em)`, `(status, solicitado_em)`, evento e correlation_id.

**Estados:**

`solicitado`, `enviado`, `recebido`, `executado`, `falhou`, `expirado`, `cancelado`, `desconhecido`.

Um comando é destinado a um dispositivo.

Quando houver componente, o backend deverá validar que ele pertence ao dispositivo informado.

Os vínculos com execução de destinatário e execução de cena são opcionais, pois comandos também podem ser originados por outros fluxos.

### 5.5.3 Tabela `historico_comandos`

**Responsabilidade:** preservar as transições e ocorrências relacionadas aos comandos.

| Campo | Tipo | Nulo |
|---|---|---|
| id | BIGINT UNSIGNED | Não |
| comando_id | BIGINT UNSIGNED | Não |
| status_anterior | VARCHAR(30) | Sim |
| status_novo | VARCHAR(30) | Não |
| origem | VARCHAR(30) | Não |
| mensagem_integracao_id | BIGINT UNSIGNED | Sim |
| detalhes | JSON | Sim |
| erro | TEXT | Sim |
| ocorrido_em | DATETIME(3) | Não |
| registrado_em | DATETIME(3) | Não |

**Origens:**

`sistema`, `mqtt`, `dispositivo`, `usuario`, `monitoramento`, `integracao`.

**Chaves e índices:**

- FK comando.
- FK mensagem de integração.
- Índices `(comando_id, ocorrido_em)`, `status_novo`, `registrado_em`.

O histórico deverá ser imutável.

A transição do comando e a inserção do histórico deverão ocorrer na mesma transação.

---

## 5.6 Grupo 6 — Automação

### 5.6.1 Tabela `regras_automacao`

**Responsabilidade:** armazenar regras que determinam quando uma automação deverá ser executada.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `nome VARCHAR(120)`.
- `descricao TEXT NULL`.
- `tipo_gatilho VARCHAR(40)`.
- `condicoes JSON NULL`.
- `prioridade INT UNSIGNED`.
- `ativa TINYINT(1)`.
- `cooldown_segundos INT UNSIGNED`.
- `vigente_desde DATETIME(3) NULL`.
- `vigente_ate DATETIME(3) NULL`.
- `usuario_id INT UNSIGNED NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Gatilhos:**

`evento`, `telemetria`, `status_dispositivo`, `analise_ia`, `interpretacao_ia`, `agendamento`, `manual`, `outro`.

**Chaves e índices:**

- UNIQUE `codigo`.
- FK usuário.
- Índices ativa/prioridade, tipo_gatilho e vigência.

### 5.6.2 Tabela `acoes_automacao`

**Responsabilidade:** definir as ações executadas por uma regra.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `regra_id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `nome VARCHAR(120)`.
- `tipo VARCHAR(40)`.
- `configuracao JSON NULL`.
- `ordem_execucao INT UNSIGNED`.
- `obrigatoria TINYINT(1)`.
- `ativa TINYINT(1)`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Tipos:**

`ativar_cena`, `ativar_cenario`, `acionar_dispositivo`, `acionar_componente`, `reproduzir_audio`, `gerar_audio`, `registrar_evento`, `executar_analise_ia`, `notificar_operador`, `integracao_externa`.

**Chaves e índices:**

- FK regra.
- UNIQUE `(regra_id, codigo)`.
- Índices `(regra_id, ordem_execucao)` e tipo/ativa.

### 5.6.3 Tabela `execucoes_automacao`

**Responsabilidade:** registrar cada execução de uma regra.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `regra_id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED NULL`.
- `codigo VARCHAR(80)`.
- `chave_idempotencia VARCHAR(120)`.
- `status VARCHAR(30)`.
- `entrada JSON NULL`.
- `resultado JSON NULL`.
- `erro TEXT NULL`.
- `iniciada_em DATETIME(3)`.
- `concluida_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Estados:**

`aguardando`, `em_execucao`, `concluida`, `concluida_parcialmente`, `falhou`, `cancelada`, `expirada`.

**Chaves e índices:**

- UNIQUE `codigo`.
- UNIQUE `(regra_id, chave_idempotencia)`.
- FK regra.
- FK evento.
- Índices regra/início, status/início e evento.

### 5.6.4 Tabela `execucoes_acoes`

**Responsabilidade:** registrar a execução individual de cada ação de uma automação.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `execucao_automacao_id BIGINT UNSIGNED`.
- `acao_id BIGINT UNSIGNED`.
- `status VARCHAR(30)`.
- `tentativa INT UNSIGNED`.
- `entrada JSON NULL`.
- `resultado JSON NULL`.
- `erro TEXT NULL`.
- `iniciada_em DATETIME(3) NULL`.
- `concluida_em DATETIME(3) NULL`.
- `configuracao_snapshot JSON NOT NULL`.
- `hash_configuracao CHAR(64) NOT NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Chaves e índices:**

- FK execução de automação.
- FK ação.
- UNIQUE `(execucao_automacao_id, acao_id, tentativa)`.
- Índices execução/status e ação.

**Estados:**

`aguardando`, `em_execucao`, `concluida`, `falhou`, `ignorada`, `cancelada`, `expirada`.

O snapshot deverá preservar tipo, parâmetros, ordem, obrigatoriedade, configuração e referências exatas às versões de cenas ou cenários utilizadas.

### 5.6.5 Tabela `execucao_destinatarios`

**Responsabilidade:** acompanhar individualmente os dispositivos destinatários de uma ação.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `execucao_acao_id BIGINT UNSIGNED`.
- `dispositivo_id BIGINT UNSIGNED`.
- `local_id BIGINT UNSIGNED NULL`.
- `status VARCHAR(30)`.
- `tentativas INT UNSIGNED`.
- `detalhes JSON NULL`.
- `erro TEXT NULL`.
- `selecionado_em DATETIME(3)`.
- `enviado_em DATETIME(3) NULL`.
- `confirmado_em DATETIME(3) NULL`.
- `concluido_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Chaves e índices:**

- FK execução de ação.
- FK dispositivo.
- FK local.
- UNIQUE `(execucao_acao_id, dispositivo_id)`.
- Índices dispositivo/status, local e execução/status.

**Estados:**

`selecionado`, `aguardando`, `enviado`, `recebido`, `executando`, `concluido`, `falhou`, `expirado`, `cancelado`, `desconhecido`, `ignorado`.

A tabela preservará o local considerado no momento da seleção do destinatário.

O comando deverá apontar para `execucao_destinatario_id`, quando originado dessa execução.

---

## 5.7 Grupo 7 — Áudio e auditoria

### 5.7.1 Tabela `mensagens_audio`

**Responsabilidade:** registrar solicitações, geração e disponibilização de mensagens de áudio.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `analise_id BIGINT UNSIGNED NULL`.
- `evento_id BIGINT UNSIGNED NULL`.
- `execucao_acao_id BIGINT UNSIGNED NULL`.
- `usuario_id INT UNSIGNED NULL`.
- `texto LONGTEXT`.
- `mecanismo_tts VARCHAR(30)`.
- `provedor VARCHAR(80) NULL`.
- `modelo_voz VARCHAR(120) NULL`.
- `idioma VARCHAR(20) NULL`.
- `formato_audio VARCHAR(15) NULL`.
- `anexo_id BIGINT UNSIGNED NULL`.
- `status VARCHAR(30)`.
- `duracao_ms BIGINT UNSIGNED NULL`.
- `erro TEXT NULL`.
- `solicitada_em DATETIME(3)`.
- `gerada_em DATETIME(3) NULL`.
- `disponibilizada_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Mecanismos:**

`piper`, `flite`, `outro`.

**Estados:**

`solicitada`, `processando`, `gerada`, `disponibilizada`, `falhou`, `cancelada`.

**Relacionamentos:**

- Análise.
- Evento.
- Execução de ação.
- Usuário.
- Anexo.

Piper poderá ser executado centralmente, por exemplo, em um Raspberry Pi.

Flite poderá ser executado localmente em dispositivos embarcados.

A geração e a disponibilização do áudio não significam que houve reprodução.

Não foi aprovada a criação de um campo `hash_texto`.

### 5.7.2 Tabela `mensagem_audio_destinatarios`

**Responsabilidade:** acompanhar a distribuição e reprodução de áudio por dispositivo.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `mensagem_audio_id BIGINT UNSIGNED`.
- `dispositivo_id BIGINT UNSIGNED`.
- `local_id BIGINT UNSIGNED NULL`.
- `comando_id BIGINT UNSIGNED NULL`.
- `execucao_destinatario_id BIGINT UNSIGNED NULL`.
- `topico_destino VARCHAR(255) NULL`.
- `status VARCHAR(30)`.
- `tentativas INT UNSIGNED`.
- `detalhes JSON NULL`.
- `erro TEXT NULL`.
- `solicitado_em DATETIME(3)`.
- `enviado_em DATETIME(3) NULL`.
- `recebido_em DATETIME(3) NULL`.
- `iniciado_em DATETIME(3) NULL`.
- `reproduzido_em DATETIME(3) NULL`.
- `concluido_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.
- `updated_at DATETIME(3)`.

**Estados:**

`aguardando`, `enviado`, `recebido`, `reproduzindo`, `reproduzido`, `falhou`, `expirado`, `cancelado`, `desconhecido`.

**Chaves e índices:**

- UNIQUE `(mensagem_audio_id, dispositivo_id)`.
- FK mensagem.
- FK dispositivo.
- FK local.
- FK comando.
- FK execução destinatário.
- Índices dispositivo, comando e mensagem/status.

O registro deverá preservar o local e o tópico de destino considerados no momento da distribuição.

### 5.7.3 Tabela `auditoria`

**Responsabilidade:** preservar o registro das operações relevantes realizadas no sistema.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `usuario_id INT UNSIGNED NULL`.
- `dispositivo_id BIGINT UNSIGNED NULL`.
- `evento_id BIGINT UNSIGNED NULL`.
- `acao VARCHAR(80)`.
- `entidade VARCHAR(100)`.
- `entidade_id BIGINT UNSIGNED NULL`.
- `origem VARCHAR(40)`.
- `canal VARCHAR(30) NULL`.
- `resultado VARCHAR(30)`.
- `descricao TEXT NULL`.
- `dados_anteriores JSON NULL`.
- `dados_posteriores JSON NULL`.
- `correlation_id VARCHAR(120) NULL`.
- `endereco_ip VARCHAR(45) NULL`.
- `user_agent VARCHAR(500) NULL`.
- `ocorrido_em DATETIME(3)`.
- `registrado_em DATETIME(3)`.

**Chaves e índices:**

- UNIQUE `codigo`.
- FK usuário.
- FK dispositivo.
- FK evento.
- Índices entidade/ID, usuário/ocorrido, dispositivo/ocorrido, ação/ocorrido, resultado/ocorrido e correlation_id.

A tabela será append-only.

Não armazenar senhas, tokens, segredos ou credenciais nos dados de auditoria.

`entidade_id` é uma referência polimórfica sem FK direta, devendo ser validada pela aplicação.

---

## 5.8 Grupo 8 — Cenas e cenários

### 5.8.1 Tabela `cenas`

**Responsabilidade:** armazenar o cadastro lógico das cenas operacionais.

Exemplos:

- Operação normal.
- Alerta de segurança.
- Evacuação.
- Manutenção.
- Monitoramento noturno.

A tabela deverá contemplar identificador, código único, nome, descrição, categoria, status, indicador de atividade, usuário responsável e timestamps.

FK para `users.id`.

Os tipos físicos completos e os índices adicionais deverão ser formalizados antes da migration.

### 5.8.2 Tabela `versoes_cenas`

**Responsabilidade:** preservar versões de configuração das cenas.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `cena_id BIGINT UNSIGNED`.
- `versao VARCHAR(40)`.
- `descricao_versao VARCHAR(255) NULL`.
- `status VARCHAR(30)`.
- `hash_configuracao CHAR(64)`.
- `usuario_id INT UNSIGNED NULL`.
- `publicada_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.

**Restrições:**

- UNIQUE `(cena_id, versao)`.
- FK cena.
- FK usuário.

Estados:

`rascunho`, `publicada`, `descontinuada`.

Versões publicadas serão imutáveis.

O hash deverá representar a configuração integral da cena, incluindo seus dispositivos e parâmetros.

### 5.8.3 Tabela `cena_dispositivos`

**Responsabilidade:** definir quais dispositivos participam de uma versão de cena e qual configuração desejada será aplicada.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `cena_versao_id BIGINT UNSIGNED`.
- `dispositivo_id BIGINT UNSIGNED`.
- `configuracao JSON NULL`.
- `ordem_aplicacao INT UNSIGNED`.
- `obrigatorio TINYINT(1)`.
- `created_at DATETIME(3)`.

**Restrições:**

- UNIQUE `(cena_versao_id, dispositivo_id)`.
- FK versão da cena.
- FK dispositivo.

A configuração representa o estado desejado, não a confirmação de execução física.

A ordem representa sequência lógica. Dispositivos independentes poderão ser processados em paralelo.

### 5.8.4 Tabela `cenarios`

**Responsabilidade:** armazenar o cadastro lógico de cenários operacionais compostos.

Exemplos:

- Operação normal.
- Emergência por vazamento de gás.
- Incêndio.
- Invasão de área restrita.
- Manutenção programada.

A tabela deverá contemplar identificador, código único, nome, descrição, categoria, status, indicador de atividade, usuário responsável e timestamps.

Os campos físicos completos deverão ser formalizados antes da migration.

### 5.8.5 Tabela `versoes_cenarios`

**Responsabilidade:** preservar versões imutáveis dos cenários.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `cenario_id BIGINT UNSIGNED`.
- `versao VARCHAR(40)`.
- `descricao_versao VARCHAR(255) NULL`.
- `status VARCHAR(30)`.
- `hash_configuracao CHAR(64)`.
- `usuario_id INT UNSIGNED NULL`.
- `publicada_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.

**Restrições:**

- UNIQUE `(cenario_id, versao)`.
- FK cenário.
- FK usuário.

Estados:

`rascunho`, `publicada`, `descontinuada`.

O hash deverá considerar as versões exatas das cenas, ordem, condições e configurações adicionais do cenário.

### 5.8.6 Tabela `cenario_cenas`

**Responsabilidade:** compor um cenário com uma ou mais versões específicas de cenas.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `cenario_versao_id BIGINT UNSIGNED`.
- `cena_versao_id BIGINT UNSIGNED`.
- `ordem_execucao INT UNSIGNED`.
- `obrigatoria TINYINT(1)`.
- `condicao_ativacao JSON NULL`.
- `created_at DATETIME(3)`.

**Restrições:**

- UNIQUE `(cenario_versao_id, cena_versao_id)`.
- FK versão do cenário.
- FK versão da cena.
- Índices cenário/ordem e cena versão.

A composição deverá apontar para versões exatas das cenas, nunca apenas para o cadastro lógico.

### 5.8.7 Tabela `cenario_dispositivos`

**Responsabilidade:** permitir configurações adicionais de dispositivos diretamente no cenário.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `cenario_versao_id BIGINT UNSIGNED`.
- `dispositivo_id BIGINT UNSIGNED`.
- `configuracao JSON NULL`.
- `estrategia_aplicacao VARCHAR(20)`.
- `obrigatorio TINYINT(1)`.
- `created_at DATETIME(3)`.

**Restrições:**

- UNIQUE `(cenario_versao_id, dispositivo_id)`.
- FK versão do cenário.
- FK dispositivo.

Estratégias:

`complementar`, `sobrescrever`.

A configuração da versão da cena fornece a base.

A estratégia complementar adiciona parâmetros não conflitantes.

A estratégia sobrescrever substitui parâmetros conflitantes.

O backend deverá validar a composição antes de enviar comandos.

Conflitos não resolvidos não poderão ser aplicados silenciosamente.

### 5.8.8 Tabela `execucoes_cenarios`

**Responsabilidade:** registrar a execução operacional de um cenário.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `cenario_versao_id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED NULL`.
- `usuario_id INT UNSIGNED NULL`.
- `status VARCHAR(30)`.
- `entrada JSON NULL`.
- `resultado JSON NULL`.
- `erro TEXT NULL`.
- `iniciada_em DATETIME(3)`.
- `concluida_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.

**Estados:**

`aguardando`, `em_execucao`, `concluida`, `concluida_parcialmente`, `falhou`, `cancelada`, `expirada`.

A execução deverá apontar para a versão exata do cenário.

### 5.8.9 Tabela `execucoes_cenas`

**Responsabilidade:** registrar a execução operacional de uma cena, individualmente ou como parte de um cenário.

Campos definidos:

- `id BIGINT UNSIGNED`.
- `codigo VARCHAR(80)`.
- `execucao_cenario_id BIGINT UNSIGNED NULL`.
- `cena_versao_id BIGINT UNSIGNED`.
- `evento_id BIGINT UNSIGNED NULL`.
- `usuario_id INT UNSIGNED NULL`.
- `status VARCHAR(30)`.
- `entrada JSON NULL`.
- `resultado JSON NULL`.
- `erro TEXT NULL`.
- `iniciada_em DATETIME(3)`.
- `concluida_em DATETIME(3) NULL`.
- `created_at DATETIME(3)`.

A FK `execucao_cenario_id` será opcional.

Uma cena poderá ser executada individualmente ou como parte de um cenário.

O vínculo com a versão exata da cena deverá ser obrigatório.

**Relacionamento operacional:**

`comandos.execucao_cena_id` aponta para `execucoes_cenas.id`.

A criação de uma tabela adicional para participantes de execução de cena não foi aprovada até o momento.

---

# 6. RELACIONAMENTOS ENTRE OS MÓDULOS

## 6.1 Estrutura física

- Um local pode conter outros locais.
- Um local pode possuir vários dispositivos.
- Um dispositivo pode possuir vários componentes.
- Um dispositivo pode ter vários registros históricos de localização.
- Um dispositivo pode ter no máximo uma localização vigente.

## 6.2 Telemetria

- Um dispositivo possui várias leituras.
- Uma leitura pode estar associada a um componente.
- Um dispositivo possui histórico de status.
- Um dispositivo possui versões de configuração.
- O gêmeo digital registra estados relacionados a leituras, comandos, eventos e configurações.
- Uma sessão pode reunir vários dispositivos por meio de `sessao_dispositivos`.

## 6.3 Eventos

- Um evento pode afetar vários locais.
- Um evento pode afetar vários dispositivos.
- Um evento pode envolver vários componentes.
- Um evento pode possuir várias leituras associadas.
- Um evento pode possuir vários anexos.
- Eventos podem possuir hierarquia por evento pai.

## 6.4 Inteligência artificial

- Um artefato lógico possui versões.
- Um pipeline lógico possui versões.
- Uma versão de pipeline possui um coordenador em versão exata.
- Uma versão de pipeline possui vários componentes.
- Cada componente aponta para uma versão exata de artefato.
- Uma implantação aponta para um dispositivo e exatamente uma versão de pipeline ou artefato.
- Uma análise pode possuir análises filhas.
- Uma análise pode estar associada a evento, dispositivo, implantação, pipeline ou artefato.
- Uma análise pode possuir anexos.

## 6.5 Integração e comandos

- Um dispositivo pode receber vários comandos.
- Um comando pode gerar várias mensagens de integração relacionadas ao seu ciclo de vida.
- Um comando possui histórico de transições.
- Uma mensagem de integração pode estar relacionada a um comando, evento, dispositivo ou análise.
- O histórico de comandos pode referenciar a mensagem de integração que originou determinada transição.

## 6.6 Automação

- Uma regra possui várias ações.
- Uma regra possui várias execuções.
- Uma execução possui várias execuções de ações.
- Uma execução de ação pode possuir vários destinatários.
- Cada destinatário corresponde a um dispositivo.
- Comandos podem apontar para o destinatário que os originou.

## 6.7 Áudio

- Uma mensagem de áudio pode estar associada a evento, análise, execução de ação, usuário e anexo.
- Uma mensagem de áudio pode possuir vários destinatários.
- Cada destinatário representa um dispositivo.
- O destinatário pode apontar para um comando e para um destinatário de automação.

## 6.8 Cenas e cenários

- Uma cena possui várias versões.
- Uma versão de cena possui vários dispositivos.
- Um cenário possui várias versões.
- Uma versão de cenário possui várias versões de cenas.
- Uma versão de cenário pode possuir configurações adicionais por dispositivo.
- Uma execução de cenário pode possuir várias execuções de cenas.
- Uma cena também pode ser executada individualmente.
- Comandos podem apontar para uma execução de cena.

---

# 7. REGRAS DE NEGÓCIO E INTEGRIDADE TRANSACIONAL

## 7.1 Histórico de localização

A movimentação de um dispositivo deverá:

1. Bloquear ou proteger a operação concorrente.
2. Encerrar o período de localização anterior.
3. Criar o novo registro de localização.
4. Atualizar `dispositivos.local_id`.
5. Registrar a operação na auditoria.
6. Confirmar a transação.

## 7.2 Telemetria

A recepção de mensagens MQTT deverá considerar:

- Identificação do dispositivo.
- Validação do tópico.
- Validação do payload.
- Idempotência.
- Horário de medição.
- Horário de recebimento.
- Componente de origem, quando aplicável.

Mensagens duplicadas não poderão provocar processamento duplicado.

## 7.3 Atualização de status

A atualização do status atual e o registro em `historico_status_dispositivo` deverão ser atômicos.

Estados desconhecidos não deverão ser interpretados automaticamente como sucesso ou falha.

## 7.4 Versionamento de configurações

Toda alteração relevante de configuração deverá gerar uma nova versão.

As versões anteriores serão preservadas.

A configuração atual poderá ser consultada em `dispositivos` e `componentes_dispositivo`.

A configuração histórica completa será consultada em `versoes_configuracao`.

## 7.5 Eventos

A criação e atualização de eventos deverão preservar:

- Origem.
- Severidade.
- Ciclo de vida.
- Dispositivos relacionados.
- Locais afetados.
- Componentes envolvidos.
- Leituras de evidência.
- Anexos.

O encerramento do evento não deverá eliminar suas evidências.

## 7.6 Inteligência artificial

As execuções principais e filhas deverão manter rastreabilidade.

Os especialistas podem compartilhar um mesmo modelo LLM no Ollama, utilizando prompts e funções diferentes.

Não é necessário carregar uma cópia do modelo para cada especialista.

As métricas deverão preservar sua unidade, semântica e método de agregação.

## 7.7 Comandos

O ciclo de vida de um comando deverá distinguir:

1. Solicitação.
2. Envio.
3. Recebimento pelo dispositivo.
4. Execução.
5. Confirmação ou resultado.
6. Falha, expiração ou cancelamento.

A mensagem enviada não representa confirmação de execução física.

## 7.8 Idempotência

Para comandos, a chave de idempotência será única por dispositivo:

`UNIQUE (dispositivo_id, chave_idempotencia)`

Para execuções de automação:

`UNIQUE (regra_id, chave_idempotencia)`

A deduplicação de mensagens de integração deverá considerar o protocolo e o contexto de origem, sem impor unicidade global não justificada.

## 7.9 Automação

O cooldown deverá ser protegido por transação e bloqueio adequado da regra, utilizando mecanismo equivalente a `SELECT ... FOR UPDATE`.

As tentativas individuais deverão ser preservadas.

O resultado geral deverá considerar as ações obrigatórias e opcionais.

Uma ação obrigatória com falha não deverá produzir resultado geral de sucesso integral.

## 7.10 Distribuição de áudio

O sistema deverá diferenciar:

- Solicitação de áudio.
- Processamento TTS.
- Geração do arquivo.
- Disponibilização.
- Envio ao dispositivo.
- Recebimento.
- Início da reprodução.
- Reprodução concluída.

A geração do arquivo não comprova que o dispositivo reproduziu o áudio.

## 7.11 Cenas e cenários

A ativação deverá utilizar versões exatas.

A composição de configurações de cenas e cenários deverá ser validada antes do envio dos comandos.

Resultados individuais deverão ser preservados para permitir identificar dispositivos que falharam, foram ignorados ou não precisaram de comando.

---

# 8. VERSIONAMENTO, HISTÓRICOS E RETENÇÃO

## 8.1 Entidades históricas

As seguintes entidades terão preservação histórica:

- `historico_localizacao_dispositivo`.
- `leituras`.
- `historico_status_dispositivo`.
- `versoes_configuracao`.
- `historico_estado_gemeo`.
- `eventos`.
- `versoes_artefatos_ia`.
- `versoes_pipelines_ia`.
- `historico_comandos`.
- `execucoes_automacao`.
- `execucoes_acoes`.
- `execucao_destinatarios`.
- `mensagens_audio`.
- `mensagem_audio_destinatarios`.
- `auditoria`.
- Versões de cenas e cenários.
- Execuções de cenas e cenários.

## 8.2 Imutabilidade

Versões publicadas de artefatos, pipelines, cenas e cenários deverão ser imutáveis.

Alterações deverão gerar novas versões.

Históricos não deverão ser sobrescritos para simular o estado anterior.

## 8.3 Retenção

A política de retenção ainda deverá ser definida.

Não foi aprovada uma duração fixa para retenção de leituras, eventos, mensagens ou auditoria.

A referência a 100 amostras anteriores a um evento representa uma quantidade de amostras, não uma duração fixa.

## 8.4 Armazenamento externo

Arquivos de áudio, imagens, vídeos e documentos deverão permanecer fora do MySQL.

O banco armazenará:

- URI.
- Nome.
- MIME.
- Extensão.
- Tamanho.
- Hash.
- Metadados.
- Relacionamentos.

---

# 9. SEGURANÇA E INTEGRAÇÃO COM CODEIGNITER SHIELD

## 9.1 Autenticação

O projeto utiliza CodeIgniter Shield.

Não deverá ser implementado um sistema paralelo de autenticação.

As tabelas de usuários e autenticação serão gerenciadas pelo Shield.

## 9.2 Grupos

Grupos definidos:

- administrador.
- operador.
- visualizador.

Grupo padrão: visualizador.

O cadastro público está desabilitado.

## 9.3 Permissões

Permissões configuradas:

- dashboard.view
- telemetry.view
- history.view
- digital_twin.view
- video.view
- devices.view
- devices.manage
- locations.view
- locations.manage
- events.view
- events.acknowledge
- events.manage
- automations.view
- automations.execute
- automations.manage
- commands.view
- commands.execute
- users.view
- users.manage
- audit.view
- system.settings

Administrador possui todas as permissões.

Operador possui permissões de consulta e execução operacional autorizada, sem gerenciamento administrativo.

Visualizador possui permissões de consulta.

## 9.4 Segurança das informações

O sistema deverá:

- Validar todas as entradas.
- Restringir operações conforme permissões.
- Não armazenar senhas em tabelas de domínio.
- Não armazenar tokens ou segredos em payloads de auditoria.
- Validar arquivos enviados.
- Validar MIME, extensão, tamanho e conteúdo.
- Controlar o acesso aos arquivos externos.
- Utilizar CSRF nas operações web aplicáveis.
- Registrar operações relevantes na auditoria.

## 9.5 Integridade de usuários

Toda FK de domínio que referenciar usuários deverá utilizar:

`INT UNSIGNED`

Referência:

`users.id`

---

# 10. DEPENDÊNCIAS E ORDEM DE CRIAÇÃO DAS MIGRATIONS

## 10.1 Princípio

As migrations deverão respeitar a ordem de dependência das chaves estrangeiras.

As tabelas de domínio serão criadas somente após a conclusão da especificação física.

## 10.2 Ordem lógica sugerida

A ordem abaixo é uma proposta de organização, não uma sequência de migrations já implementada.

1. `locais`
2. `dispositivos`
3. `componentes_dispositivo`
4. `historico_localizacao_dispositivo`
5. `leituras`
6. `eventos`
7. `evento_locais`
8. `evento_dispositivos`
9. `evento_componentes`
10. `evento_leituras`
11. `anexos`
12. `evento_anexos`
13. `historico_status_dispositivo`
14. `versoes_configuracao`
15. `historico_estado_gemeo`
16. `sessoes_coleta`
17. `sessao_dispositivos`
18. `artefatos_ia`
19. `versoes_artefatos_ia`
20. `pipelines_ia`
21. `versoes_pipelines_ia`
22. `pipeline_componentes_ia`
23. `implantacoes_ia`
24. `analises_ia`
25. `analise_anexos`
26. `mensagens_integracao`
27. `comandos`
28. `historico_comandos`
29. `regras_automacao`
30. `acoes_automacao`
31. `execucoes_automacao`
32. `execucoes_acoes`
33. `execucao_destinatarios`
34. `mensagens_audio`
35. `mensagem_audio_destinatarios`
36. `auditoria`
37. `cenas`
38. `versoes_cenas`
39. `cena_dispositivos`
40. `cenarios`
41. `versoes_cenarios`
42. `cenario_cenas`
43. `cenario_dispositivos`
44. `execucoes_cenarios`
45. `execucoes_cenas`

A sequência final deverá ser ajustada para resolver ciclos de dependência.

## 10.3 Ciclos de relacionamento

Foi identificado um ciclo potencial entre:

- `mensagens_integracao.comando_id`.
- `comandos`.
- `historico_comandos.mensagem_integracao_id`.

A migration poderá criar primeiro as tabelas sem uma das FKs e adicionar a restrição posteriormente.

Entretanto, antes disso, deverá ser confirmada a necessidade de manter todas as referências.

A referência `mensagens_integracao.comando_id` representa o comando relacionado à mensagem.

A referência `historico_comandos.mensagem_integracao_id` representa a mensagem que provocou uma transição específica.

Essas relações têm finalidades distintas, mas sua necessidade deverá ser confirmada na auditoria final.

## 10.4 Infraestrutura existente

As migrations do Shield e Settings já estão executadas.

Não deverão ser recriadas.

---

# 11. PONTOS PENDENTES DA AUDITORIA GLOBAL

Esta seção reúne os pontos que ainda exigem definição física ou validação antes da criação das migrations.

## 11.1 Especificações físicas incompletas

As seguintes tabelas possuem responsabilidade e relacionamentos definidos, mas precisam de fechamento dos campos, nulabilidade, defaults e índices:

- `artefatos_ia`.
- `versoes_artefatos_ia`.
- `implantacoes_ia`.
- `pipelines_ia`.
- `versoes_pipelines_ia`.
- `pipeline_componentes_ia`.
- `cenas`.
- `cenarios`.

A especificação dessas tabelas não deverá ser preenchida por suposição.

## 11.2 Relacionamentos de execução de cenas e cenários

Deverá ser avaliada a inclusão de `execucao_acao_id` em:

- `execucoes_cenas`.
- `execucoes_cenarios`.

A finalidade é permitir rastrear se a execução foi iniciada por uma ação de automação.

A inclusão foi recomendada, mas ainda precisa ser formalmente aprovada.

## 11.3 Participantes de execução de cenas

A tabela `execucao_cena_dispositivos` não foi aprovada.

Deverá ser avaliado se os comandos, históricos e resultados estruturados existentes são suficientes para registrar:

- Dispositivos acionados.
- Dispositivos ignorados.
- Dispositivos indisponíveis.
- Dispositivos considerados, mas sem comando necessário.

Não criar uma tabela adicional sem justificativa funcional.

## 11.4 Coerência entre análises e pipelines

Formalizar as regras de integridade entre:

- Análise principal.
- Análises filhas.
- Versão do pipeline.
- Componente do pipeline.
- Versão do artefato.
- Implantação.
- Dispositivo.

## 11.5 Implantação de IA

Formalizar:

- CHECK de exclusividade entre versão de artefato e versão de pipeline.
- Unicidade.
- Estados.
- Vigência.
- Histórico de implantação e desativação.

## 11.6 Comandos e origens

Revisar a coexistência dos campos:

- `evento_id`.
- `execucao_destinatario_id`.
- `execucao_cena_id`.

Esses campos podem representar contextos diferentes, mas a semântica de coexistência deverá ser documentada.

## 11.7 Mensagens de integração

Confirmar a necessidade das duas referências:

- `mensagens_integracao.comando_id`.
- `historico_comandos.mensagem_integracao_id`.

Definir também o escopo de deduplicação das mensagens.

## 11.8 Timestamps

Padronizar:

- DEFAULT CURRENT_TIMESTAMP(3).
- ON UPDATE CURRENT_TIMESTAMP(3), quando aplicável.
- Datas de ocorrência.
- Datas de registro.
- Datas de envio.
- Datas de confirmação.
- Datas de execução.

Não adicionar `updated_at` a tabelas históricas imutáveis sem justificativa.

## 11.9 Índices

Revisar os índices de todas as FKs e os índices compostos utilizados nas consultas operacionais.

A existência de índice automático de FK não substitui a análise dos índices de negócio.

## 11.10 Integridade das tabelas associativas

Confirmar a manutenção de PK substituta `BIGINT UNSIGNED` e restrições UNIQUE compostas.

O modelo atual utiliza identificadores próprios e UNIQUE nas combinações das associações.

## 11.11 Retenção

Definir a política de retenção de:

- Leituras.
- Mensagens de integração.
- Eventos.
- Logs.
- Auditoria.
- Arquivos externos.

## 11.12 Execuções de automação e cenas

Conferir se os snapshots são suficientes para reproduzir e auditar a configuração utilizada em cada execução.

## 11.13 Configuração de cenários

Formalizar a precedência e a validação de conflitos entre configurações de cena e configurações diretas do cenário.

---

# 12. PLANO DE IMPLEMENTAÇÃO E VALIDAÇÃO

## 12.1 Etapa 1 — Fechamento da especificação

- Consolidar as 45 tabelas.
- Resolver os pontos pendentes.
- Conferir tipos e nulabilidade.
- Definir defaults.
- Definir PKs e FKs.
- Definir índices.
- Definir CHECKs.
- Confirmar dependências.
- Revisar os ciclos de relacionamento.

## 12.2 Etapa 2 — Implementação das migrations

Criar as migrations do domínio respeitando as dependências.

As migrations existentes de infraestrutura serão preservadas.

## 12.3 Etapa 3 — Revisão das migrations

Antes da execução:

- Conferir todos os nomes.
- Conferir tipos das colunas.
- Conferir nulabilidade.
- Conferir valores padrão.
- Conferir chaves estrangeiras.
- Conferir índices.
- Conferir restrições CHECK.
- Conferir ordem de criação.
- Conferir possibilidade de rollback.

## 12.4 Etapa 4 — Execução

Executar as migrations no banco `iot_edge_center`.

Verificar:

- Status das migrations.
- Estrutura real das tabelas.
- Tipos de dados.
- Chaves primárias.
- Chaves estrangeiras.
- Índices.
- Restrições.
- Integridade referencial.

## 12.5 Etapa 5 — Validação funcional

Realizar testes progressivos:

1. Cadastro de locais.
2. Cadastro de dispositivos.
3. Cadastro de componentes.
4. Histórico de localização.
5. Inserção de telemetria.
6. Histórico de status.
7. Versionamento de configurações.
8. Criação e associação de eventos.
9. Cadastro de anexos.
10. Registro de análises de IA.
11. Registro de mensagens de integração.
12. Solicitação e histórico de comandos.
13. Execução de automações.
14. Distribuição de áudio.
15. Registro de auditoria.
16. Cadastro e execução de cenas e cenários.

Os testes deverão utilizar dados de desenvolvimento e resultados reais, sem apresentar operações não executadas como concluídas.

---

# 13. ESTADO CONSOLIDADO DO PROJETO

## 13.1 Infraestrutura implementada

- CodeIgniter 4.7.5 instalado.
- PHP 8.2.
- MySQL 8.0.
- Banco `iot_edge_center` criado e acessível.
- CodeIgniter Shield instalado e configurado.
- Grupos e permissões definidos.
- Contas demonstrativas criadas.
- Rotas protegidas e verificadas.
- Migrations de infraestrutura executadas.
- Configuração de migrations conferida.

## 13.2 Modelagem conceitual

- Oito grupos funcionais revisados.
- Inventário de 45 tabelas consolidado.
- Estrutura física revisada conceitualmente.
- Principais relacionamentos definidos.
- Decisões de histórico e versionamento registradas.
- Integração com Shield definida.

## 13.3 Implementação de domínio

Na verificação registrada em 20/11/2026:

- Nenhuma das 45 tabelas de domínio estava criada.
- Nenhuma migration de domínio havia sido executada.

Portanto, a modelagem não deverá ser descrita como implementada no banco.

## 13.4 Situação das próximas etapas

| Atividade | Situação |
|---|---|
| Infraestrutura do banco | Implementada e verificada |
| CodeIgniter Shield | Implementado e testado |
| Revisão conceitual dos oito grupos | Concluída |
| Inventário de 45 tabelas | Consolidado |
| Especificação física completa | Em consolidação |
| Auditoria global de dependências | Pendente de fechamento |
| Relatório técnico | Documento em consolidação |
| Migrations de domínio | Não iniciadas |
| Criação das tabelas de domínio | Não iniciada |
| Testes de integridade do domínio | Pendentes |

---

# 14. CONSIDERAÇÕES FINAIS

A modelagem do banco de dados do IoT-Edge Command Center foi organizada para suportar uma plataforma IoT-Edge modular, com integração entre dispositivos físicos, telemetria, eventos, gêmeo digital, inteligência artificial, comandos, automações, áudio, auditoria e execução de cenas e cenários.

A arquitetura diferencia os registros de configuração, as solicitações operacionais, as mensagens de comunicação e as confirmações de execução.

O modelo também preserva o histórico necessário para rastrear mudanças de localização, configuração, estado, execução e distribuição de informações.

O uso do CodeIgniter Shield evita a implementação de um sistema paralelo de autenticação e mantém a gestão de usuários e permissões sob responsabilidade do framework.

As 45 tabelas de domínio representam a estrutura consolidada até esta etapa. A implementação física deverá ocorrer somente após o fechamento dos pontos pendentes registrados neste documento.

**Regra de continuidade:** nenhuma migration de domínio deverá ser criada ou executada antes da conclusão da especificação física, da conferência das dependências e da aprovação final do conjunto de tabelas.

---
