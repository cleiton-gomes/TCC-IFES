# ESPECIFICAÇÃO FÍSICA DO BANCO DE DADOS
## IoT-Edge Command Center

**Projeto:** IoT-Edge Command Center  
**Instituição:** IFES — Campus Serra  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4.7.5  
**Linguagem:** PHP 8.2  
**Autenticação e autorização:** CodeIgniter Shield  
**Banco de dados:** `iot_edge_center`  
**Driver:** MySQLi  
**Host:** `localhost`  
**Porta:** `3306`  
**Engine prevista para as tabelas de domínio:** InnoDB  
**Codificação:** utf8mb4  

**Natureza do documento:** consolidação da modelagem física e das decisões arquiteturais do banco de dados.

---

# 1. OBJETIVO

Este documento especifica a estrutura física do banco de dados do IoT-Edge Command Center, consolidando as decisões de modelagem conceitual, os relacionamentos entre entidades, os tipos de dados, as chaves, os índices, as restrições e as regras de integridade necessárias à implementação.

O banco de dados dará suporte a uma plataforma IoT-Edge voltada ao gerenciamento de dispositivos físicos, telemetria, eventos, evidências, inteligência artificial, integração, comandos, automações, áudio, auditoria, cenas e cenários operacionais.

A arquitetura não está limitada a um único ambiente ou cliente. O caso Gasmar constitui uma aplicação de referência, mas a estrutura deve permitir reutilização em outros ambientes e operações.

O banco armazenará dados estruturados, relacionamentos, históricos, metadados e referências a arquivos externos. Não será utilizado como armazenamento direto de vídeos, imagens, arquivos de áudio ou outros binários de grande porte.

## 1.1 Objetivos específicos

- Registrar a estrutura física dos ambientes e sua hierarquia.
- Cadastrar dispositivos e seus componentes.
- Armazenar telemetria e históricos de estados.
- Manter um histórico do gêmeo digital.
- Registrar eventos e associar evidências.
- Rastrear análises e execuções de inteligência artificial.
- Controlar artefatos, versões e pipelines de IA.
- Registrar mensagens de integração e comandos.
- Controlar regras e execuções de automação.
- Gerenciar geração e distribuição de mensagens de áudio.
- Registrar operações relevantes para auditoria.
- Permitir a definição de cenas e cenários por versões imutáveis.
- Preservar a rastreabilidade das operações realizadas no ambiente.

---

# 2. AMBIENTE TECNOLÓGICO E SITUAÇÃO ATUAL

## 2.1 Configuração do banco

A conexão com o banco foi verificada por meio do próprio CodeIgniter.

| Propriedade | Valor |
|---|---|
| SGBD | MySQL 8.0 |
| Banco | `iot_edge_center` |
| Host | `localhost` |
| Porta | `3306` |
| Usuário de conexão | `root` |
| Driver | MySQLi |
| Framework | CodeIgniter 4.7.5 |
| PHP | 8.2 |
| Engine das tabelas de domínio | InnoDB |
| Charset | utf8mb4 |

## 2.2 Tabelas existentes

Na inspeção realizada em 20/11/2026, foram identificadas nove tabelas:

1. `auth_groups_users`
2. `auth_identities`
3. `auth_logins`
4. `auth_permissions_users`
5. `auth_remember_tokens`
6. `auth_token_logins`
7. `migrations`
8. `settings`
9. `users`

Essas tabelas pertencem à infraestrutura do CodeIgniter Shield, ao módulo Settings e ao controle de migrations.

Nenhuma das tabelas de domínio IoT havia sido criada naquele levantamento.

## 2.3 Estrutura existente de `users`

A coluna `users.id` foi inspecionada diretamente no MySQL.

| Propriedade | Definição |
|---|---|
| Tipo | `INT UNSIGNED` |
| Chave | PRIMARY KEY |
| Incremento | AUTO_INCREMENT |
| Engine | InnoDB |
| Charset | utf8mb4 |
| Collation | utf8mb4_general_ci |

Essa definição é importante porque as tabelas de domínio que referenciam usuários devem utilizar `INT UNSIGNED` nas respectivas chaves estrangeiras.

Não se deve utilizar `BIGINT UNSIGNED` para essas referências, pois os tipos precisam ser compatíveis.

## 2.4 Migrations existentes

O comando `php spark migrate:status` identificou quatro migrations de infraestrutura executadas no primeiro batch:

- Shield: criação das tabelas de autenticação.
- Settings: criação da tabela de configurações.
- Settings: adição da coluna de contexto.
- Settings: conversão da coluna de valor para compatibilidade com SQL Server.

Essas migrations não devem ser recriadas.

O diretório `app/Database/Migrations` continha apenas `.gitkeep` no levantamento realizado.

A configuração de migrations utiliza:

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

---

# 3. ARQUITETURA GERAL DO BANCO

A estrutura de domínio está organizada em oito grupos funcionais.

| Grupo | Área | Tabelas |
|---|---|---:|
| 1 | Estrutura física | 4 |
| 2 | Telemetria e gêmeo digital | 6 |
| 3 | Eventos e evidências | 7 |
| 4 | Inteligência artificial | 8 |
| 5 | Integração e comandos | 3 |
| 6 | Automação | 5 |
| 7 | Áudio e auditoria | 3 |
| 8 | Cenas e cenários | 9 |
| | **Total** | **45** |

Além das 45 tabelas de domínio, existem as tabelas de infraestrutura do Shield, Settings e migrations.

A tabela `migrations` é uma estrutura de controle do framework, não uma entidade de negócio.

## 3.1 Fluxo geral de informações

A arquitetura considera o seguinte fluxo lógico:

1. Dispositivos físicos produzem telemetria e informações de estado.
2. Mensagens chegam por protocolos de integração, especialmente MQTT.
3. O sistema registra as mensagens recebidas e processadas.
4. Leituras podem ser associadas a eventos.
5. Eventos podem reunir dispositivos, locais, componentes e evidências.
6. Serviços de visão computacional e inteligência artificial executam análises.
7. Os resultados podem originar eventos, notificações ou automações.
8. Regras de automação selecionam ações e destinatários.
9. A execução de ações pode produzir comandos físicos.
10. Os dispositivos recebem, executam e confirmam os comandos.
11. Serviços de áudio podem gerar mensagens e distribuí-las aos dispositivos.
12. A auditoria registra operações relevantes para rastreabilidade.

Esse fluxo representa a organização lógica. Não significa que todas as etapas precisem ocorrer em todas as operações.

## 3.2 Separação entre banco e serviços externos

O banco de dados não substitui os serviços especializados.

| Serviço ou tecnologia | Responsabilidade |
|---|---|
| MySQL | Persistência de dados e relacionamentos |
| CodeIgniter | Aplicação web, regras de negócio e acesso ao banco |
| Shield | Autenticação, grupos e permissões |
| MQTT | Comunicação assíncrona com dispositivos |
| WebSocket | Atualização em tempo real da interface |
| Serviço de vídeo | Captura, transmissão e gerenciamento de streams |
| Visão computacional | Processamento de imagens e vídeos |
| Ollama | Execução de modelos LLM |
| Piper | Geração centralizada de áudio |
| Flite | Síntese local de áudio em dispositivos compatíveis |

MQTT e WebSocket possuem finalidades diferentes e não devem ser tratados como substitutos.

---

# 4. CONVENÇÕES FÍSICAS GERAIS

## 4.1 Chaves primárias

As tabelas de domínio utilizarão, como padrão:

```sql
id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
```

A chave será declarada como `PRIMARY KEY`.

A exceção são referências às tabelas do Shield, especialmente `users.id`, que utilizarão `INT UNSIGNED`.

## 4.2 Chaves estrangeiras

As referências entre tabelas de domínio utilizarão `BIGINT UNSIGNED`.

As referências a `users.id` utilizarão `INT UNSIGNED`.

As FKs deverão possuir tipos, sinalização e características compatíveis com as respectivas chaves referenciadas.

## 4.3 Engine e charset

Padrão:

```sql
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
```

O InnoDB será utilizado por oferecer transações, integridade referencial e suporte a chaves estrangeiras.

## 4.4 Datas e horários

Os campos de data e hora deverão utilizar o padrão UTC para persistência.

Quando houver necessidade de precisão em operações, eventos, telemetria ou rastreamento, será utilizado:

```sql
DATETIME(3)
```

A precisão em milissegundos é relevante para ordenar mensagens, leituras, comandos e confirmações.

A aplicação será responsável pela conversão para o fuso horário de apresentação.

## 4.5 Campos de criação e atualização

Quando aplicáveis:

```sql
created_at DATETIME(3) NULL
updated_at DATETIME(3) NULL
```

O campo `created_at` registra a criação do registro.

O campo `updated_at` registra a última atualização.

Tabelas históricas e de execução podem possuir timestamps próprios, como `ocorrido_em`, `registrado_em`, `iniciada_em` e `concluida_em`.

Esses campos não são redundantes quando representam momentos diferentes do ciclo operacional.

## 4.6 Campos JSON

O tipo `JSON` será utilizado para estruturas variáveis, configurações, parâmetros, entradas, resultados, metadados e snapshots.

O uso de JSON não elimina a necessidade de validação no backend.

Informações que precisam de relacionamentos, filtros frequentes, integridade referencial ou índices convencionais deverão permanecer em colunas próprias sempre que isso for necessário.

## 4.7 Estados e categorias

Estados, categorias, origens, papéis e tipos serão armazenados preferencialmente como `VARCHAR`.

Não será adotado `ENUM` como padrão.

A aplicação deverá validar os valores permitidos, evitando a necessidade de alterações estruturais na tabela sempre que uma categoria operacional evoluir.

## 4.8 Exclusão de registros

A política geral será preservar históricos e impedir exclusões que comprometam a rastreabilidade.

O comportamento padrão das FKs será:

```sql
ON DELETE RESTRICT
```

Não será aplicado `ON DELETE CASCADE` indiscriminadamente.

A exclusão lógica poderá ser utilizada quando fizer sentido para a entidade, por exemplo, por meio de `ativo`.

## 4.9 Arquivos externos

Imagens, vídeos, áudios e documentos serão armazenados fora do MySQL.

O banco armazenará:

- Nome original.
- Nome armazenado.
- Tipo MIME.
- Tamanho.
- Hash, quando aplicável.
- URI de armazenamento.
- Metadados.
- Relacionamentos com eventos, análises ou mensagens.

Não serão armazenados arquivos binários diretamente em colunas BLOB.

## 4.10 Integridade e transações

Operações que alterem simultaneamente uma entidade atual e seu histórico deverão ocorrer na mesma transação.

Exemplos:

- Atualizar a localização de um dispositivo e registrar a movimentação.
- Alterar o estado atual e inserir o histórico de estado.
- Atualizar uma configuração e registrar sua versão.
- Atualizar o estado de um comando e registrar sua transição.

---

# 5. GRUPO 1 — ESTRUTURA FÍSICA

Este grupo representa a estrutura física do ambiente e a composição dos dispositivos.

## 5.1 `locais`

**Finalidade:** representar os locais físicos de instalação e sua hierarquia.

### Campos

| Campo | Tipo | Nulidade / padrão | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `local_pai_id` | BIGINT UNSIGNED | NULL | Local hierarquicamente superior |
| `codigo` | VARCHAR(80) | NOT NULL, UNIQUE | Código estável |
| `nome` | VARCHAR(120) | NOT NULL | Nome do local |
| `tipo` | VARCHAR(30) | NOT NULL | Tipo do local |
| `descricao` | TEXT | NULL | Descrição |
| `latitude` | DECIMAL(10,7) | NULL | Latitude |
| `longitude` | DECIMAL(10,7) | NULL | Longitude |
| `referencia` | VARCHAR(190) | NULL | Referência textual |
| `ativo` | TINYINT(1) | NOT NULL DEFAULT 1 | Situação |
| `created_at` | DATETIME(3) | NULL | Criação |
| `updated_at` | DATETIME(3) | NULL | Atualização |

### Chaves e índices

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `local_pai_id` → `locais.id`.
- Índice: `local_pai_id`.
- Índice: `tipo`.
- Índice: `ativo`.

### Tipos permitidos

- `edificio`
- `andar`
- `zona`
- `sala`
- `corredor`
- `area_externa`

### Regras

Um local pode possuir um local superior, permitindo a construção de uma hierarquia.

Exemplo:

```text
Edifício
 ├── Andar
 │    ├── Zona
 │    │    ├── Sala
 │    │    └── Corredor
 └── Área externa
```

A aplicação deverá impedir:

- Autorreferência.
- Ciclos hierárquicos.
- Associação de um local como descendente de si próprio.
- Exclusão de locais ainda referenciados.

A consulta de hierarquias poderá utilizar `WITH RECURSIVE`.

As coordenadas deverão ser validadas pelo backend e, quando suportado pela versão e configuração do MySQL, por restrições `CHECK`.

## 5.2 `dispositivos`

**Finalidade:** cadastrar equipamentos físicos conectados à plataforma.

### Campos

| Campo | Tipo | Nulidade / padrão | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `local_id` | BIGINT UNSIGNED | NULL | Local atual |
| `codigo` | VARCHAR(80) | NOT NULL, UNIQUE | Identificador estável |
| `nome` | VARCHAR(120) | NOT NULL | Nome |
| `categoria` | VARCHAR(40) | NOT NULL | Categoria |
| `fabricante` | VARCHAR(100) | NULL | Fabricante |
| `modelo` | VARCHAR(100) | NULL | Modelo |
| `numero_serie` | VARCHAR(120) | NULL | Número de série |
| `protocolo` | VARCHAR(30) | NOT NULL | Protocolo principal |
| `topico_base_mqtt` | VARCHAR(255) | NULL | Tópico-base |
| `endereco_rede` | VARCHAR(255) | NULL | Endereço de rede |
| `status` | VARCHAR(30) | NOT NULL | Estado atual |
| `ultimo_contato` | DATETIME(3) | NULL | Último contato |
| `configuracao` | JSON | NULL | Configuração geral |
| `ativo` | TINYINT(1) | NOT NULL | Situação |
| `created_at` | DATETIME(3) | NULL | Criação |
| `updated_at` | DATETIME(3) | NULL | Atualização |

### Chaves e índices

- PK: `id`.
- UNIQUE: `codigo`.
- UNIQUE: `topico_base_mqtt`.
- FK: `local_id` → `locais.id`.
- Índices: `local_id`, `categoria`, `status`, `ativo`.

O MySQL permite múltiplos valores `NULL` em um índice UNIQUE, portanto dispositivos sem tópico-base definido não serão obrigados a possuir um valor artificial.

### Categorias

- `sensor`
- `camera`
- `controlador`
- `gateway`
- `atuador`
- `equipamento`
- `outro`

### Estados

- `desconhecido`
- `online`
- `offline`
- `alerta`
- `manutencao`

### Regras

O código será estável e independente do endereço de rede.

O campo `protocolo` representa o protocolo principal do equipamento. Dispositivos que utilizem protocolos adicionais poderão registrar essas informações em `configuracao`.

O campo `local_id` poderá permanecer `NULL` quando o equipamento ainda não tiver sido instalado ou associado a um local.

O endereço de rede não deverá conter senhas, tokens ou credenciais.

Para dispositivos MQTT, os tópicos poderão seguir a convenção:

```text
iot-edge/devices/{device_id}/telemetry
iot-edge/devices/{device_id}/status
iot-edge/devices/{device_id}/events
iot-edge/devices/{device_id}/commands
iot-edge/devices/{device_id}/command-results
```

O tópico será derivado da identificação estável do dispositivo. A localização física não será inferida a partir do tópico MQTT.

## 5.3 `componentes_dispositivo`

**Finalidade:** representar componentes físicos ou funcionais pertencentes a um dispositivo.

### Campos

| Campo | Tipo | Nulidade / padrão | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo proprietário |
| `codigo` | VARCHAR(80) | NOT NULL | Código local ao dispositivo |
| `nome` | VARCHAR(120) | NOT NULL | Nome |
| `tipo` | VARCHAR(40) | NOT NULL | Tipo do componente |
| `fabricante` | VARCHAR(100) | NULL | Fabricante |
| `modelo` | VARCHAR(100) | NULL | Modelo |
| `numero_serie` | VARCHAR(120) | NULL | Número de série |
| `descricao` | TEXT | NULL | Descrição |
| `configuracao` | JSON | NULL | Configuração individual |
| `status` | VARCHAR(30) | NOT NULL | Estado |
| `ativo` | TINYINT(1) | NOT NULL | Situação |
| `created_at` | DATETIME(3) | NULL | Criação |
| `updated_at` | DATETIME(3) | NULL | Atualização |

### Chaves e índices

- PK: `id`.
- UNIQUE: `(dispositivo_id, codigo)`.
- FK: `dispositivo_id` → `dispositivos.id`.
- Índices: `tipo`, `status`, `ativo`, `dispositivo_id`.

### Tipos

- `sensor`
- `comunicacao`
- `processamento`
- `audio`
- `interface`
- `atuador`
- `alimentacao`
- `armazenamento`
- `outro`

### Regras

Um componente pertence a um único dispositivo.

Exemplos de composição:

- Heltec LoRa32 V3: MCU ESP32-S3, módulo de comunicação SX1262 e display OLED.
- Sistema de áudio: DAC, amplificador e alto-falante.
- Equipamento com W5500: MCU, interface Ethernet e alimentação.

As leituras de sensores serão armazenadas em `leituras`, não nesta tabela.

A configuração individual do componente será armazenada aqui. Snapshots completos da configuração do dispositivo e de seus componentes serão registrados em `versoes_configuracao`.

## 5.4 `historico_localizacao_dispositivo`

**Finalidade:** preservar o histórico de movimentação dos dispositivos entre locais.

### Campos

| Campo | Tipo | Nulidade / padrão | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo |
| `local_id` | BIGINT UNSIGNED | NOT NULL | Local associado |
| `usuario_id` | INT UNSIGNED | NULL | Usuário responsável |
| `inicio_vigencia` | DATETIME(3) | NOT NULL | Início da associação |
| `fim_vigencia` | DATETIME(3) | NULL | Fim da associação |
| `motivo` | VARCHAR(255) | NULL | Motivo |
| `observacoes` | TEXT | NULL | Observações |
| `created_at` | DATETIME(3) | NULL | Criação |
| `dispositivo_vigente_id` | BIGINT UNSIGNED | Gerada | Controle de vigência |

### Coluna gerada

A coluna `dispositivo_vigente_id` deverá representar o dispositivo apenas quando `fim_vigencia IS NULL`.

Expressão conceitual:

```sql
CASE
    WHEN fim_vigencia IS NULL THEN dispositivo_id
    ELSE NULL
END
```

A coluna será armazenada (`STORED`) e terá índice UNIQUE.

Isso permite impedir mais de uma localização vigente para o mesmo dispositivo.

### Chaves e índices

- PK: `id`.
- UNIQUE: `dispositivo_vigente_id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `local_id` → `locais.id`.
- FK: `usuario_id` → `users.id`.
- Índices: `dispositivo_id`, `local_id`, `inicio_vigencia`, `fim_vigencia`.

### Regras

O backend deverá impedir sobreposição de períodos de localização.

Quando um dispositivo for movimentado:

1. A localização anterior será encerrada.
2. Uma nova associação será criada.
3. `dispositivos.local_id` será atualizado.
4. A movimentação será registrada na auditoria.

Essas operações deverão ocorrer na mesma transação.

Se um dispositivo ainda não estiver instalado, `dispositivos.local_id` poderá permanecer `NULL`, sem a necessidade de criar um histórico artificial.

---

# 6. GRUPO 2 — TELEMETRIA E GÊMEO DIGITAL

## 6.1 `leituras`

**Finalidade:** armazenar leituras de telemetria produzidas pelos dispositivos e componentes.

### Campos

| Campo | Tipo | Nulidade / padrão | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo |
| `componente_id` | BIGINT UNSIGNED | NULL | Componente |
| `metrica` | VARCHAR(80) | NOT NULL | Métrica |
| `valor_numerico` | DECIMAL(20,8) | NULL | Valor numérico |
| `valor_textual` | TEXT | NULL | Valor textual |
| `unidade` | VARCHAR(30) | NULL | Unidade |
| `qualidade` | VARCHAR(30) | NOT NULL | Qualidade |
| `payload_original` | JSON | NULL | Payload recebido |
| `medido_em` | DATETIME(3) | NOT NULL | Momento da medição |
| `recebido_em` | DATETIME(3) | NOT NULL | Momento do recebimento |
| `protegida` | TINYINT(1) | NOT NULL | Proteção contra retenção |
| `created_at` | DATETIME(3) | NULL | Criação |

### Chaves e índices

- PK: `id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `componente_id` → `componentes_dispositivo.id`.
- Índices:
  - `(dispositivo_id, medido_em)`.
  - `(componente_id, medido_em)`.
  - `(metrica, medido_em)`.
  - `recebido_em`.

### Regras

O backend deverá verificar se o componente informado pertence ao dispositivo indicado.

Pelo menos um dos campos `valor_numerico` ou `valor_textual` deverá estar preenchido.

Os dois campos poderão coexistir quando uma leitura exigir representação numérica e textual.

`medido_em` e `recebido_em` possuem significados diferentes:

- `medido_em`: momento em que o equipamento produziu a leitura.
- `recebido_em`: momento em que a plataforma recebeu a informação.

O payload original poderá ser preservado para rastreabilidade.

Uma leitura ADC de um sensor MQ-4 não será tratada automaticamente como concentração em ppm. Essa conversão depende de calibração e caracterização do sensor.

## 6.2 `historico_status_dispositivo`

**Finalidade:** registrar as transições de estado dos dispositivos.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo |
| `evento_id` | BIGINT UNSIGNED | NULL | Evento associado |
| `status_anterior` | VARCHAR(30) | NULL | Estado anterior |
| `status_novo` | VARCHAR(30) | NOT NULL | Novo estado |
| `motivo` | VARCHAR(255) | NULL | Motivo |
| `origem` | VARCHAR(30) | NOT NULL | Origem |
| `detalhes` | JSON | NULL | Detalhes |
| `ocorrido_em` | DATETIME(3) | NOT NULL | Ocorrência |
| `registrado_em` | DATETIME(3) | NOT NULL | Registro |

### Chaves e índices

- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `evento_id` → `eventos.id`.
- Índices: `(dispositivo_id, ocorrido_em)`, `evento_id`, `status_novo`, `registrado_em`.

### Estados

`desconhecido`, `online`, `offline`, `alerta`, `manutencao`, `inativo`.

### Origens

`mqtt`, `sistema`, `usuario`, `monitoramento`, `integracao`.

### Regras

O estado atual do dispositivo ficará em `dispositivos.status`.

Esta tabela manterá o histórico das transições.

A alteração do estado atual e a inclusão do histórico deverão ocorrer na mesma transação.

Os registros históricos não deverão ser alterados ou removidos como parte da operação normal.

## 6.3 `versoes_configuracao`

**Finalidade:** preservar versões completas da configuração dos dispositivos.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo |
| `evento_id` | BIGINT UNSIGNED | NULL | Evento relacionado |
| `usuario_id` | INT UNSIGNED | NULL | Usuário responsável |
| `versao` | INT UNSIGNED | NOT NULL | Número da versão |
| `configuracao` | JSON | NOT NULL | Snapshot |
| `hash_configuracao` | CHAR(64) | NOT NULL | Hash SHA-256 |
| `origem` | VARCHAR(30) | NOT NULL | Origem |
| `descricao` | VARCHAR(255) | NULL | Descrição |
| `vigente_desde` | DATETIME(3) | NOT NULL | Início da vigência |
| `created_at` | DATETIME(3) | NULL | Criação |

### Chaves e índices

- UNIQUE: `(dispositivo_id, versao)`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `usuario_id` → `users.id`.
- Índices: `(dispositivo_id, vigente_desde)`, `evento_id`, `hash_configuracao`.

### Regras

A configuração deverá representar um snapshot completo do dispositivo e de seus componentes.

O hash será calculado a partir de uma representação canônica da configuração.

A atualização da configuração atual e a criação de sua versão deverão ocorrer na mesma transação.

As versões anteriores deverão permanecer preservadas.

## 6.4 `historico_estado_gemeo`

**Finalidade:** manter o histórico das representações do estado do gêmeo digital.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo |
| `evento_id` | BIGINT UNSIGNED | NULL | Evento |
| `leitura_id` | BIGINT UNSIGNED | NULL | Leitura |
| `comando_id` | BIGINT UNSIGNED | NULL | Comando |
| `versao_configuracao_id` | BIGINT UNSIGNED | NULL | Versão da configuração |
| `tipo_estado` | VARCHAR(40) | NOT NULL | Tipo de estado |
| `estado` | JSON | NOT NULL | Snapshot |
| `origem` | VARCHAR(30) | NOT NULL | Origem |
| `ocorrido_em` | DATETIME(3) | NOT NULL | Ocorrência |
| `registrado_em` | DATETIME(3) | NOT NULL | Registro |

### Chaves e índices

- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `leitura_id` → `leituras.id`.
- FK: `comando_id` → `comandos.id`.
- FK: `versao_configuracao_id` → `versoes_configuracao.id`.
- Índices: `(dispositivo_id, ocorrido_em)`, `evento_id`, `tipo_estado`.

### Regras

O histórico poderá representar snapshots completos ou parciais.

As referências de origem poderão coexistir quando uma atualização estiver relacionada a mais de uma entidade.

O backend deverá verificar a coerência entre as referências e o dispositivo.

O gêmeo digital terá caráter consultivo e de representação do estado conhecido. Ele não deverá reenviar automaticamente comandos físicos apenas por atualizar sua representação.

## 6.5 `sessoes_coleta`

**Finalidade:** registrar sessões de coleta, diagnóstico ou monitoramento.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `evento_id` | BIGINT UNSIGNED | NULL | Evento relacionado |
| `dispositivo_id` | BIGINT UNSIGNED | NULL | Dispositivo principal ou originador |
| `usuario_id` | INT UNSIGNED | NULL | Usuário responsável |
| `codigo` | VARCHAR(80) | NOT NULL | Código |
| `tipo` | VARCHAR(40) | NOT NULL | Tipo de sessão |
| `status` | VARCHAR(30) | NOT NULL | Estado |
| `iniciada_em` | DATETIME(3) | NOT NULL | Início |
| `encerrada_em` | DATETIME(3) | NULL | Encerramento |
| `parametros` | JSON | NULL | Parâmetros |
| `resumo` | JSON | NULL | Resultado resumido |
| `created_at` | DATETIME(3) | NULL | Criação |

### Tipos

`continua`, `evento`, `diagnostico`, `manual`, `automatizada`.

### Estados

`aguardando`, `em_execucao`, `concluida`, `cancelada`, `falhou`.

### Relacionamentos

- FK: `evento_id` → `eventos.id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `usuario_id` → `users.id`.

O campo `dispositivo_id` representa o dispositivo principal ou originador, não necessariamente todos os participantes.

## 6.6 `sessao_dispositivos`

**Finalidade:** associar vários dispositivos a uma sessão de coleta.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `sessao_id` | BIGINT UNSIGNED | NOT NULL | Sessão |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL | Dispositivo participante |
| `papel` | VARCHAR(30) | NOT NULL | Papel |
| `status` | VARCHAR(30) | NOT NULL | Estado da participação |
| `inicio_participacao` | DATETIME(3) | NULL | Início |
| `fim_participacao` | DATETIME(3) | NULL | Fim |
| `detalhes` | JSON | NULL | Detalhes |
| `created_at` | DATETIME(3) | NULL | Criação |

### Chaves

- UNIQUE: `(sessao_id, dispositivo_id)`.
- FK: `sessao_id` → `sessoes_coleta.id`.
- FK: `dispositivo_id` → `dispositivos.id`.

O dispositivo principal também poderá ser registrado nesta tabela, com seu papel correspondente.

---

# 7. GRUPO 3 — EVENTOS E EVIDÊNCIAS

## 7.1 `eventos`

**Finalidade:** centralizar eventos operacionais, alertas e ocorrências.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `evento_pai_id` | BIGINT UNSIGNED | NULL | Evento superior |
| `usuario_id` | INT UNSIGNED | NULL | Usuário |
| `codigo` | VARCHAR(80) | NOT NULL | Código |
| `tipo` | VARCHAR(60) | NOT NULL | Tipo |
| `severidade` | VARCHAR(20) | NOT NULL | Severidade |
| `status` | VARCHAR(30) | NOT NULL | Estado |
| `origem` | VARCHAR(40) | NOT NULL | Origem |
| `titulo` | VARCHAR(180) | NOT NULL | Título |
| `descricao` | TEXT | NULL | Descrição |
| `dados` | JSON | NULL | Dados adicionais |
| `ocorrido_em` | DATETIME(3) | NOT NULL | Ocorrência |
| `registrado_em` | DATETIME(3) | NOT NULL | Registro |
| `reconhecido_em` | DATETIME(3) | NULL | Reconhecimento |
| `encerrado_em` | DATETIME(3) | NULL | Encerramento |
| `created_at` | DATETIME(3) | NULL | Criação |
| `updated_at` | DATETIME(3) | NULL | Atualização |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `evento_pai_id` → `eventos.id`.
- FK: `usuario_id` → `users.id`.
- Índices: `(status, severidade)`, `(tipo, ocorrido_em)`, `usuario_id`, `evento_pai_id`, `registrado_em`.

### Severidades

`informativa`, `baixa`, `media`, `alta`, `critica`.

### Estados

`aberto`, `reconhecido`, `em_atendimento`, `resolvido`, `encerrado`, `cancelado`.

### Origens

`dispositivo`, `sistema`, `usuario`, `visao_computacional`, `interpretacao_contextual`, `integracao`, `automacao`.

### Regras

Um evento poderá possuir evento pai, permitindo agrupamento e correlação.

Eventos derivados deverão permanecer identificáveis e independentes quando representarem ocorrências distintas.

O evento poderá reunir locais, dispositivos, componentes, leituras e anexos por meio das tabelas associativas.

A existência de um evento não implica automaticamente que um comando tenha sido enviado ou executado.

## 7.2 `evento_locais`

**Finalidade:** associar eventos aos locais envolvidos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `evento_id` | BIGINT UNSIGNED | NOT NULL |
| `local_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `observacoes` | VARCHAR(255) | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(evento_id, local_id)`.
- FK: `evento_id` → `eventos.id`.
- FK: `local_id` → `locais.id`.

### Papéis

`origem`, `afetado`, `abrangencia`, `referencia`.

## 7.3 `evento_dispositivos`

**Finalidade:** associar eventos aos dispositivos envolvidos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `evento_id` | BIGINT UNSIGNED | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(evento_id, dispositivo_id)`.
- FK: `evento_id` → `eventos.id`.
- FK: `dispositivo_id` → `dispositivos.id`.

### Papéis

`origem`, `afetado`, `participante`, `destinatario`, `referencia`.

O papel `destinatario` representa a seleção do dispositivo como possível destinatário. Não comprova que uma mensagem ou comando tenha sido enviado ou executado.

## 7.4 `evento_componentes`

**Finalidade:** associar eventos aos componentes físicos envolvidos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `evento_id` | BIGINT UNSIGNED | NOT NULL |
| `componente_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(evento_id, componente_id)`.
- FK: `evento_id` → `eventos.id`.
- FK: `componente_id` → `componentes_dispositivo.id`.

### Papéis

`origem`, `afetado`, `participante`, `referencia`.

O backend deverá validar a coerência do componente com seu dispositivo.

## 7.5 `evento_leituras`

**Finalidade:** relacionar eventos com leituras de telemetria.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `evento_id` | BIGINT UNSIGNED | NOT NULL |
| `leitura_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(evento_id, leitura_id)`.
- FK: `evento_id` → `eventos.id`.
- FK: `leitura_id` → `leituras.id`.

### Papéis

`gatilho`, `evidencia`, `contexto`, `posterior`.

O backend deverá validar a coerência entre a leitura e o contexto do evento.

## 7.6 `anexos`

**Finalidade:** manter metadados e referências de arquivos armazenados externamente.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL |
| `nome_original` | VARCHAR(255) | NOT NULL |
| `nome_armazenado` | VARCHAR(255) | NOT NULL |
| `tipo_mime` | VARCHAR(120) | NOT NULL |
| `categoria` | VARCHAR(30) | NOT NULL |
| `extensao` | VARCHAR(15) | NULL |
| `tamanho_bytes` | BIGINT UNSIGNED | NOT NULL |
| `hash_sha256` | CHAR(64) | NULL |
| `uri_armazenamento` | VARCHAR(500) | NOT NULL |
| `descricao` | TEXT | NULL |
| `metadados` | JSON | NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`.
- Índices: `categoria`, `tipo_mime`, `hash_sha256`, `created_at`.

### Categorias

`imagem`, `video`, `audio`, `documento`, `outro`.

### Regras

Os arquivos permanecerão em armazenamento externo.

O backend deverá validar:

- Tipo MIME.
- Extensão.
- Tamanho.
- Conteúdo real do arquivo.
- URI de armazenamento.
- Integridade por hash, quando aplicável.

O hash não será UNIQUE, pois arquivos iguais poderão aparecer em contextos distintos.

## 7.7 `evento_anexos`

**Finalidade:** associar anexos aos eventos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `evento_id` | BIGINT UNSIGNED | NOT NULL |
| `anexo_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `observacoes` | VARCHAR(255) | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(evento_id, anexo_id)`.
- FK: `evento_id` → `eventos.id`.
- FK: `anexo_id` → `anexos.id`.

### Papéis

`evidencia`, `entrada_analise`, `resultado`, `contexto`.

---

# 8. GRUPO 4 — INTELIGÊNCIA ARTIFICIAL

Este grupo registra análises, artefatos, versões, implantações e pipelines de inteligência artificial.

## 8.1 Arquitetura lógica

A arquitetura separa:

- Cadastro lógico do artefato.
- Versões imutáveis do artefato.
- Implantação de versões em dispositivos.
- Cadastro lógico do pipeline.
- Versões imutáveis do pipeline.
- Componentes de cada versão de pipeline.
- Execuções individuais e consolidadas de análise.

Um artefato pode participar de vários pipelines e assumir papéis diferentes.

Os especialistas de interpretação contextual podem compartilhar o mesmo modelo LLM carregado pelo Ollama, utilizando prompts e responsabilidades diferentes. Não é obrigatório manter três cópias do mesmo modelo carregadas simultaneamente.

## 8.2 `analises_ia`

**Finalidade:** registrar execuções de análises realizadas por serviços de inteligência artificial.

### Campos

| Campo | Tipo | Nulidade | Descrição |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK | Identificador |
| `codigo` | VARCHAR(80) | NOT NULL | Código |
| `analise_pai_id` | BIGINT UNSIGNED | NULL | Análise principal |
| `evento_id` | BIGINT UNSIGNED | NULL | Evento |
| `dispositivo_id` | BIGINT UNSIGNED | NULL | Dispositivo |
| `implantacao_id` | BIGINT UNSIGNED | NULL | Implantação |
| `pipeline_versao_id` | BIGINT UNSIGNED | NULL | Versão do pipeline |
| `artefato_versao_id` | BIGINT UNSIGNED | NULL | Versão do artefato |
| `pipeline_componente_id` | BIGINT UNSIGNED | NULL | Componente do pipeline |
| `tipo` | VARCHAR(40) | NOT NULL | Tipo de análise |
| `servico` | VARCHAR(100) | NOT NULL | Serviço |
| `modelo` | VARCHAR(120) | NULL | Modelo |
| `versao_modelo` | VARCHAR(80) | NULL | Versão do modelo |
| `status` | VARCHAR(30) | NOT NULL | Estado |
| `entrada` | JSON | NULL | Entrada |
| `resultado` | JSON | NULL | Resultado |
| `metricas` | JSON | NULL | Métricas efetivas |
| `confianca` | DECIMAL(8,6) | NULL | Confiança |
| `duracao_ms` | BIGINT UNSIGNED | NULL | Duração |
| `erro` | TEXT | NULL | Erro |
| `iniciada_em` | DATETIME(3) | NOT NULL | Início |
| `concluida_em` | DATETIME(3) | NULL | Conclusão |
| `created_at` | DATETIME(3) | NULL | Criação |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `analise_pai_id` → `analises_ia.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `implantacao_id` → `implantacoes_ia.id`.
- FK: `pipeline_versao_id` → `versoes_pipelines_ia.id`.
- FK: `artefato_versao_id` → `versoes_artefatos_ia.id`.
- FK: `pipeline_componente_id` → `pipeline_componentes_ia.id`.
- Índices: `(tipo, status)`, horários, `evento_id`, `dispositivo_id`, `implantacao_id`.

### Tipos

`visao_computacional`, `interpretacao_contextual`, `classificacao`, `deteccao`, `reconhecimento`, `validacao`.

### Estados

`aguardando`, `em_execucao`, `concluida`, `falhou`, `cancelada`, `expirada`.

### Regras

Uma análise principal poderá possuir análises filhas.

A análise principal representa a execução coordenadora ou consolidada. As análises filhas representam execuções individuais.

`pipeline_versao_id` e `artefato_versao_id` são mutuamente exclusivos.

Quando a análise for filha de um pipeline e representar um componente, `pipeline_componente_id` deverá identificar o componente correspondente.

O backend deverá validar que:

- O componente pertence à versão de pipeline informada pela análise principal.
- O artefato executado corresponde à versão definida para aquele componente.
- A implantação, quando informada, é compatível com o dispositivo e com a versão executada.

O campo `metricas` representa métricas efetivamente produzidas pela execução.

As métricas esperadas pelo pipeline pertencem à configuração do componente, não à execução.

## 8.3 `analise_anexos`

**Finalidade:** associar anexos às análises de IA.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `analise_id` | BIGINT UNSIGNED | NOT NULL |
| `anexo_id` | BIGINT UNSIGNED | NOT NULL |
| `papel` | VARCHAR(30) | NOT NULL |
| `ordem` | INT UNSIGNED | NULL |
| `observacoes` | VARCHAR(255) | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(analise_id, anexo_id, papel)`.
- FK: `analise_id` → `analises_ia.id`.
- FK: `anexo_id` → `anexos.id`.

### Papéis

`entrada`, `evidencia`, `resultado`, `frame`, `referencia`.

## 8.4 `artefatos_ia`

**Finalidade:** cadastrar artefatos lógicos de inteligência artificial, sem misturar o cadastro com suas versões executáveis.

### Campos aprovados para a especificação

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL, UNIQUE |
| `nome` | VARCHAR(150) | NOT NULL |
| `descricao` | TEXT | NULL |
| `tipo` | VARCHAR(40) | NOT NULL |
| `finalidade` | VARCHAR(50) | NOT NULL |
| `linguagem` | VARCHAR(40) | NULL |
| `framework` | VARCHAR(80) | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `ativo` | TINYINT(1) | NOT NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`, com `ON DELETE RESTRICT`.

### Regras

A tabela representa o cadastro lógico do artefato.

Ela não deve armazenar diretamente o conteúdo binário ou o código completo do artefato.

As versões, hashes, URIs e configurações específicas de cada versão deverão pertencer a `versoes_artefatos_ia`.

O cadastro poderá representar scripts, modelos, serviços, coordenadores e especialistas.

## 8.5 `versoes_artefatos_ia`

**Finalidade:** manter versões imutáveis dos artefatos de IA.

A separação entre cadastro lógico e versão permite preservar a identidade do artefato enquanto sua implementação evolui.

### Estrutura física

A especificação detalhada de todos os campos, tipos, nulabilidade, defaults e índices ainda não foi fechada.

### Requisitos conceituais aprovados

A tabela deverá permitir identificar:

- Artefato de origem.
- Número ou identificador da versão.
- Hash de integridade.
- URI do conteúdo armazenado.
- Dependências.
- Configuração específica.
- Estado da versão.
- Informações de publicação.

### Relacionamento

- FK obrigatória para `artefatos_ia`.

### Regras

Uma versão publicada deverá ser tratada como imutável.

Alterações deverão produzir uma nova versão, preservando a anterior.

A definição final de campos e índices deverá ocorrer antes da migration.

## 8.6 `implantacoes_ia`

**Finalidade:** registrar a implantação de versões de artefatos ou pipelines em dispositivos.

### Estrutura conceitual aprovada

Uma implantação deverá apontar para:

- Uma versão de artefato; ou
- Uma versão de pipeline.

Não deverá apontar simultaneamente para as duas versões.

### Relacionamentos previstos

- Dispositivo de destino.
- Versão de artefato, quando implantação individual.
- Versão de pipeline, quando implantação de pipeline.

### Restrição lógica

Deverá existir uma restrição que garanta exatamente uma das referências:

```text
artefato_versao_id preenchido
OU
pipeline_versao_id preenchido
```

Não será permitido que ambas estejam preenchidas nem que ambas estejam vazias.

### Situação física

Os campos complementares de estado, vigência, datas, índices e política de unicidade ainda precisam ser formalizados.

## 8.7 `pipelines_ia`

**Finalidade:** cadastrar pipelines lógicos de processamento de inteligência artificial.

O pipeline representa uma composição lógica de componentes e não uma execução individual.

### Estrutura conceitual aprovada

O cadastro deverá permitir identificar o pipeline e suas características gerais.

O coordenador não deverá ser duplicado como se fosse uma entidade independente dentro do cadastro lógico do pipeline.

A versão específica do coordenador deverá ser registrada em `versoes_pipelines_ia`.

### Situação física

A definição completa de campos, tipos, nulabilidade, índices e restrições ainda está pendente.

## 8.8 `versoes_pipelines_ia`

**Finalidade:** manter versões imutáveis de pipelines.

### Requisitos aprovados

Cada versão deverá representar uma composição específica e reproduzível.

A versão deverá possuir uma referência obrigatória para a versão exata do artefato responsável pela coordenação do pipeline.

Não deverá depender de uma referência genérica ao cadastro lógico do coordenador.

### Situação física

Os campos complementares, tipos, índices, estados, hash e demais restrições ainda precisam ser formalizados.

## 8.9 `pipeline_componentes_ia`

**Finalidade:** definir os componentes que fazem parte de uma versão específica de pipeline.

### Requisitos aprovados

Cada componente deverá estar associado a:

- Uma versão de pipeline.
- Uma versão exata de artefato.

A composição deverá permitir registrar:

- Papel do componente.
- Ordem.
- Código identificador dentro do pipeline.
- Grupo lógico.
- Dependências.
- Configuração.
- Parâmetros.
- Obrigatoriedade.
- Habilitação.
- Métricas esperadas.

O campo conceitual `metricas_esperadas` será do tipo JSON.

### Regras

Um mesmo artefato poderá aparecer em mais de um pipeline e exercer papéis diferentes.

A versão do artefato deverá ser exata, garantindo reprodutibilidade.

O campo de métricas esperadas não deverá ser confundido com `analises_ia.metricas`, que representa os resultados efetivamente produzidos.

### Situação física

A estrutura completa de colunas, tipos, índices, nulabilidade e restrições ainda precisa ser fechada.

---

# 9. GRUPO 5 — INTEGRAÇÃO E COMANDOS

## 9.1 `mensagens_integracao`

**Finalidade:** registrar mensagens recebidas e enviadas pelos serviços de integração.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NULL |
| `comando_id` | BIGINT UNSIGNED | NULL |
| `evento_id` | BIGINT UNSIGNED | NULL |
| `analise_id` | BIGINT UNSIGNED | NULL |
| `protocolo` | VARCHAR(30) | NOT NULL |
| `direcao` | VARCHAR(10) | NOT NULL |
| `topico` | VARCHAR(255) | NULL |
| `identificador_mensagem` | VARCHAR(120) | NULL |
| `correlation_id` | VARCHAR(120) | NULL |
| `chave_idempotencia` | VARCHAR(120) | NULL |
| `payload` | JSON | NULL |
| `payload_bruto` | LONGTEXT | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `erro` | TEXT | NULL |
| `recebida_em` | DATETIME(3) | NULL |
| `processada_em` | DATETIME(3) | NULL |
| `enviada_em` | DATETIME(3) | NULL |
| `confirmada_em` | DATETIME(3) | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `comando_id` → `comandos.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `analise_id` → `analises_ia.id`.
- Índices: protocolo/direção/criação, tópico, dispositivo, comando, evento, correlation ID e status.

### Protocolos

`mqtt`, `http`, `https`, `websocket`, `outro`.

### Direções

`entrada`, `saida`.

### Estados

`recebida`, `validando`, `processada`, `enviada`, `confirmada`, `falhou`, `duplicada`, `descartada`.

### Regras

Os momentos de recebimento, processamento, envio e confirmação são diferentes.

O campo `identificador_mensagem` não terá UNIQUE global.

A chave de idempotência não deverá possuir restrição UNIQUE global, pois seu escopo depende do protocolo, dispositivo e operação.

O backend deverá implementar a política de deduplicação.

Segredos e credenciais não deverão ser armazenados no payload.

## 9.2 `comandos`

**Finalidade:** registrar solicitações de comandos destinados a dispositivos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL |
| `componente_id` | BIGINT UNSIGNED | NULL |
| `evento_id` | BIGINT UNSIGNED | NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `tipo` | VARCHAR(60) | NOT NULL |
| `parametros` | JSON | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `correlation_id` | VARCHAR(120) | NOT NULL |
| `chave_idempotencia` | VARCHAR(120) | NOT NULL |
| `solicitado_em` | DATETIME(3) | NOT NULL |
| `enviado_em` | DATETIME(3) | NULL |
| `recebido_em` | DATETIME(3) | NULL |
| `executado_em` | DATETIME(3) | NULL |
| `expira_em` | DATETIME(3) | NULL |
| `execucao_destinatario_id` | BIGINT UNSIGNED | NULL |
| `execucao_cena_id` | BIGINT UNSIGNED | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- UNIQUE: `(dispositivo_id, chave_idempotencia)`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `componente_id` → `componentes_dispositivo.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `usuario_id` → `users.id`.
- FK: `execucao_destinatario_id` → `execucao_destinatarios.id`.
- FK: `execucao_cena_id` → `execucoes_cenas.id`.
- Índices: `(dispositivo_id, solicitado_em)`, `(status, solicitado_em)`, `evento_id`, `correlation_id`.

### Estados

`solicitado`, `enviado`, `recebido`, `executado`, `falhou`, `expirado`, `cancelado`, `desconhecido`.

### Regras

Cada registro representa um comando destinado a um dispositivo.

O componente, quando informado, deverá pertencer ao dispositivo.

Os campos `execucao_destinatario_id` e `execucao_cena_id` são opcionais.

A solicitação, o envio, o recebimento e a execução são etapas diferentes e não devem ser confundidas.

Os vínculos opcionais com evento, destinatário e execução de cena não devem ser considerados mutuamente exclusivos sem uma regra de negócio específica.

## 9.3 `historico_comandos`

**Finalidade:** registrar todas as transições relevantes de estado dos comandos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `comando_id` | BIGINT UNSIGNED | NOT NULL |
| `status_anterior` | VARCHAR(30) | NULL |
| `status_novo` | VARCHAR(30) | NOT NULL |
| `origem` | VARCHAR(30) | NOT NULL |
| `mensagem_integracao_id` | BIGINT UNSIGNED | NULL |
| `detalhes` | JSON | NULL |
| `erro` | TEXT | NULL |
| `ocorrido_em` | DATETIME(3) | NOT NULL |
| `registrado_em` | DATETIME(3) | NOT NULL |

### Chaves e índices

- FK: `comando_id` → `comandos.id`.
- FK: `mensagem_integracao_id` → `mensagens_integracao.id`.
- Índices: `(comando_id, ocorrido_em)`, `status_novo`, `registrado_em`.

### Origens

`sistema`, `mqtt`, `dispositivo`, `usuario`, `monitoramento`, `integracao`.

### Regras

A atualização do estado do comando e a inserção do histórico deverão ocorrer na mesma transação.

O histórico deverá ser preservado.

A referência de `mensagens_integracao.comando_id` representa o vínculo da mensagem com o comando.

A referência de `historico_comandos.mensagem_integracao_id` representa a mensagem que originou ou documentou uma transição específica.

As duas referências possuem semânticas distintas. A necessidade de manter ambas deverá ser verificada na revisão final das dependências e dos fluxos de integração.

---

# 10. GRUPO 6 — AUTOMAÇÃO

## 10.1 `regras_automacao`

**Finalidade:** cadastrar regras que determinam quando uma automação poderá ser executada.

### Campos

| Campo | Tipo | Nulidade / padrão |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL, UNIQUE |
| `nome` | VARCHAR(120) | NOT NULL |
| `descricao` | TEXT | NULL |
| `tipo_gatilho` | VARCHAR(40) | NOT NULL |
| `condicoes` | JSON | NULL |
| `prioridade` | INT UNSIGNED | NOT NULL |
| `ativa` | TINYINT(1) | NOT NULL |
| `cooldown_segundos` | INT UNSIGNED | NOT NULL DEFAULT 0 |
| `vigente_desde` | DATETIME(3) | NULL |
| `vigente_ate` | DATETIME(3) | NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`.
- Índices: `(ativa, prioridade)`, `tipo_gatilho`, vigência.

### Gatilhos

`evento`, `telemetria`, `status_dispositivo`, `analise_ia`, `interpretacao_ia`, `agendamento`, `manual`, `outro`.

### Regras

As condições serão armazenadas em JSON e validadas pela aplicação.

O cooldown será utilizado para impedir execuções repetidas dentro de um intervalo configurado.

## 10.2 `acoes_automacao`

**Finalidade:** definir as ações executadas por uma regra.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `regra_id` | BIGINT UNSIGNED | NOT NULL |
| `codigo` | VARCHAR(80) | NOT NULL |
| `nome` | VARCHAR(120) | NOT NULL |
| `tipo` | VARCHAR(40) | NOT NULL |
| `configuracao` | JSON | NULL |
| `ordem_execucao` | INT UNSIGNED | NOT NULL |
| `obrigatoria` | TINYINT(1) | NOT NULL |
| `ativa` | TINYINT(1) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `(regra_id, codigo)`.
- FK: `regra_id` → `regras_automacao.id`.
- Índices: `(regra_id, ordem_execucao)`, `(tipo, ativa)`.

### Tipos

`ativar_cena`, `ativar_cenario`, `acionar_dispositivo`, `acionar_componente`, `reproduzir_audio`, `gerar_audio`, `registrar_evento`, `executar_analise_ia`, `notificar_operador`, `integracao_externa`.

### Regras

A configuração da ação deverá permitir identificar a versão exata de cena ou cenário quando aplicável.

A execução deverá preservar um snapshot da configuração utilizada.

## 10.3 `execucoes_automacao`

**Finalidade:** registrar a execução de uma regra de automação.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `regra_id` | BIGINT UNSIGNED | NOT NULL |
| `evento_id` | BIGINT UNSIGNED | NULL |
| `codigo` | VARCHAR(80) | NOT NULL |
| `chave_idempotencia` | VARCHAR(120) | NOT NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `entrada` | JSON | NULL |
| `resultado` | JSON | NULL |
| `erro` | TEXT | NULL |
| `iniciada_em` | DATETIME(3) | NOT NULL |
| `concluida_em` | DATETIME(3) | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- UNIQUE: `(regra_id, chave_idempotencia)`.
- FK: `regra_id` → `regras_automacao.id`.
- FK: `evento_id` → `eventos.id`.
- Índices: `(regra_id, iniciada_em)`, `(status, iniciada_em)`, `evento_id`.

### Estados

`aguardando`, `em_execucao`, `concluida`, `concluida_parcialmente`, `falhou`, `cancelada`, `expirada`.

## 10.4 `execucoes_acoes`

**Finalidade:** registrar cada execução de ação dentro de uma execução de automação.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `execucao_automacao_id` | BIGINT UNSIGNED | NOT NULL |
| `acao_id` | BIGINT UNSIGNED | NOT NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `tentativa` | INT UNSIGNED | NOT NULL |
| `entrada` | JSON | NULL |
| `resultado` | JSON | NULL |
| `erro` | TEXT | NULL |
| `iniciada_em` | DATETIME(3) | NULL |
| `concluida_em` | DATETIME(3) | NULL |
| `configuracao_snapshot` | JSON | NOT NULL |
| `hash_configuracao` | CHAR(64) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `(execucao_automacao_id, acao_id, tentativa)`.
- FK: `execucao_automacao_id` → `execucoes_automacao.id`.
- FK: `acao_id` → `acoes_automacao.id`.
- Índices: `(execucao_automacao_id, status)`, `acao_id`.

### Estados

`aguardando`, `em_execucao`, `concluida`, `falhou`, `ignorada`, `cancelada`, `expirada`.

### Regras

O snapshot e o hash são obrigatórios.

O snapshot deverá preservar tipo, parâmetros, ordem, obrigatoriedade, configuração e referências a versões exatas de cenas ou cenários, quando aplicável.

Isso garante que a execução possa ser interpretada mesmo que a configuração lógica da ação seja alterada posteriormente.

## 10.5 `execucao_destinatarios`

**Finalidade:** registrar os dispositivos selecionados como destinatários de uma ação de automação.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `execucao_acao_id` | BIGINT UNSIGNED | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL |
| `local_id` | BIGINT UNSIGNED | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `tentativas` | INT UNSIGNED | NOT NULL |
| `detalhes` | JSON | NULL |
| `erro` | TEXT | NULL |
| `selecionado_em` | DATETIME(3) | NOT NULL |
| `enviado_em` | DATETIME(3) | NULL |
| `confirmado_em` | DATETIME(3) | NULL |
| `concluido_em` | DATETIME(3) | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `(execucao_acao_id, dispositivo_id)`.
- FK: `execucao_acao_id` → `execucoes_acoes.id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `local_id` → `locais.id`.
- Índices: `(dispositivo_id, status)`, `local_id`, `(execucao_acao_id, status)`.

### Estados

`selecionado`, `aguardando`, `enviado`, `recebido`, `executando`, `concluido`, `falhou`, `expirado`, `cancelado`, `desconhecido`, `ignorado`.

### Regras

O registro representa a seleção do dispositivo e o acompanhamento de sua participação na ação.

O campo `local_id` preserva o local considerado no momento da seleção.

A tabela não substitui `mensagem_audio_destinatarios`, pois as duas possuem finalidades distintas.

O vínculo com o comando será realizado por `comandos.execucao_destinatario_id`.

---

# 11. GRUPO 7 — ÁUDIO E AUDITORIA

## 11.1 `mensagens_audio`

**Finalidade:** registrar mensagens de áudio geradas e disponibilizadas pela plataforma.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL |
| `analise_id` | BIGINT UNSIGNED | NULL |
| `evento_id` | BIGINT UNSIGNED | NULL |
| `execucao_acao_id` | BIGINT UNSIGNED | NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `texto` | LONGTEXT | NOT NULL |
| `mecanismo_tts` | VARCHAR(30) | NOT NULL |
| `provedor` | VARCHAR(80) | NULL |
| `modelo_voz` | VARCHAR(120) | NULL |
| `idioma` | VARCHAR(20) | NULL |
| `formato_audio` | VARCHAR(15) | NULL |
| `anexo_id` | BIGINT UNSIGNED | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `duracao_ms` | BIGINT UNSIGNED | NULL |
| `erro` | TEXT | NULL |
| `solicitada_em` | DATETIME(3) | NOT NULL |
| `gerada_em` | DATETIME(3) | NULL |
| `disponibilizada_em` | DATETIME(3) | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `codigo`.
- FK: `analise_id` → `analises_ia.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `execucao_acao_id` → `execucoes_acoes.id`.
- FK: `usuario_id` → `users.id`.
- FK: `anexo_id` → `anexos.id`.

### Mecanismos

`piper`, `flite`, `outro`.

### Estados

`solicitada`, `processando`, `gerada`, `disponibilizada`, `falhou`, `cancelada`.

### Regras

A mensagem representa a solicitação e o processo de geração de áudio.

A geração não significa que o áudio foi reproduzido.

O arquivo será armazenado externamente, com referência em `anexo_id`.

Não será adicionado o campo `hash_texto`.

Piper e Flite representam mecanismos de síntese, não dispositivos físicos.

A arquitetura de áudio poderá utilizar:

- Piper centralizado, executado em serviço de rede, para gerar arquivos WAV.
- Flite executado localmente em dispositivos embarcados compatíveis.
- Operação híbrida, redundante ou de fallback, conforme a configuração do ambiente.

## 11.2 `mensagem_audio_destinatarios`

**Finalidade:** controlar a distribuição e a reprodução de mensagens de áudio nos dispositivos.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `mensagem_audio_id` | BIGINT UNSIGNED | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL |
| `local_id` | BIGINT UNSIGNED | NULL |
| `comando_id` | BIGINT UNSIGNED | NULL |
| `execucao_destinatario_id` | BIGINT UNSIGNED | NULL |
| `topico_destino` | VARCHAR(255) | NULL |
| `status` | VARCHAR(30) | NOT NULL |
| `tentativas` | INT UNSIGNED | NOT NULL |
| `detalhes` | JSON | NULL |
| `erro` | TEXT | NULL |
| `solicitado_em` | DATETIME(3) | NOT NULL |
| `enviado_em` | DATETIME(3) | NULL |
| `recebido_em` | DATETIME(3) | NULL |
| `iniciado_em` | DATETIME(3) | NULL |
| `reproduzido_em` | DATETIME(3) | NULL |
| `concluido_em` | DATETIME(3) | NULL |
| `created_at` | DATETIME(3) | NULL |
| `updated_at` | DATETIME(3) | NULL |

### Chaves e índices

- UNIQUE: `(mensagem_audio_id, dispositivo_id)`.
- FK: `mensagem_audio_id` → `mensagens_audio.id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `local_id` → `locais.id`.
- FK: `comando_id` → `comandos.id`.
- FK: `execucao_destinatario_id` → `execucao_destinatarios.id`.
- Índices: `dispositivo_id`, `comando_id`, `(mensagem_audio_id, status)`.

### Estados

`aguardando`, `enviado`, `recebido`, `reproduzindo`, `reproduzido`, `falhou`, `expirado`, `cancelado`, `desconhecido`.

### Regras

O campo `execucao_destinatario_id` é opcional e poderá permanecer `NULL`.

O campo `comando_id` será mantido.

A tabela representa a distribuição de áudio e o acompanhamento de sua reprodução.

O campo `local_id` preserva o contexto de localização considerado.

`execucao_destinatarios` e `mensagem_audio_destinatarios` não são duplicações: o primeiro registra destinatários de ações de automação; o segundo registra a distribuição de mensagens de áudio.

## 11.3 `auditoria`

**Finalidade:** manter o registro de operações relevantes realizadas na plataforma.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `codigo` | VARCHAR(80) | NOT NULL |
| `usuario_id` | INT UNSIGNED | NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NULL |
| `evento_id` | BIGINT UNSIGNED | NULL |
| `acao` | VARCHAR(80) | NOT NULL |
| `entidade` | VARCHAR(100) | NOT NULL |
| `entidade_id` | BIGINT UNSIGNED | NULL |
| `origem` | VARCHAR(40) | NOT NULL |
| `canal` | VARCHAR(30) | NULL |
| `resultado` | VARCHAR(30) | NOT NULL |
| `descricao` | TEXT | NULL |
| `dados_anteriores` | JSON | NULL |
| `dados_posteriores` | JSON | NULL |
| `correlation_id` | VARCHAR(120) | NULL |
| `endereco_ip` | VARCHAR(45) | NULL |
| `user_agent` | VARCHAR(500) | NULL |
| `ocorrido_em` | DATETIME(3) | NOT NULL |
| `registrado_em` | DATETIME(3) | NOT NULL |

### Chaves e índices

- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`.
- FK: `dispositivo_id` → `dispositivos.id`.
- FK: `evento_id` → `eventos.id`.
- Índices:
  - `(entidade, entidade_id)`.
  - `(usuario_id, ocorrido_em)`.
  - `(dispositivo_id, ocorrido_em)`.
  - `(acao, ocorrido_em)`.
  - `(resultado, ocorrido_em)`.
  - `correlation_id`.

### Regras

A auditoria será append-only no fluxo normal da aplicação.

O campo `entidade_id` será polimórfico, acompanhado por `entidade`, pois poderá referenciar diferentes tabelas de domínio.

Não será criada uma FK convencional para `entidade_id`.

O backend deverá validar a existência e a coerência da entidade referenciada.

Não deverão ser armazenados segredos, senhas, tokens ou credenciais nos campos de auditoria.

Os dados anteriores e posteriores deverão conter somente informações necessárias à rastreabilidade, respeitando a proteção de dados sensíveis.

---

# 12. GRUPO 8 — CENAS E CENÁRIOS

Este grupo representa configurações operacionais reutilizáveis e suas execuções.

Uma cena representa uma configuração operacional específica.

Um cenário representa uma composição de uma ou mais cenas, podendo incluir dispositivos adicionais e regras de precedência.

As versões publicadas deverão ser imutáveis para permitir reprodução e rastreabilidade.

## 12.1 `cenas`

**Finalidade:** cadastrar cenas operacionais.

### Campos conceituais definidos

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `codigo` | VARCHAR(80) | Definido |
| `nome` | VARCHAR(120) | Definido |
| `descricao` | TEXT | Definido |
| `categoria` | VARCHAR(40) | Definido conceitualmente |
| `status` | VARCHAR(30) | Definido conceitualmente |
| `ativo` | TINYINT(1) | Definido |
| `usuario_id` | INT UNSIGNED | Definido |
| `created_at` | DATETIME(3) | Definido |
| `updated_at` | DATETIME(3) | Definido |

### Chaves

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`.

### Exemplos de cenas

- Operação normal.
- Alerta de segurança.
- Evacuação.
- Manutenção.
- Monitoramento noturno.

### Situação física

A nulabilidade e os defaults definitivos de todos os campos ainda deverão ser formalizados antes da migration.

## 12.2 `versoes_cenas`

**Finalidade:** preservar versões das configurações de cenas.

### Campos definidos conceitualmente

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `cena_id` | BIGINT UNSIGNED | Definido |
| `versao` | VARCHAR(40) | Definido |
| `descricao_versao` | VARCHAR(255) | Definido |
| `status` | VARCHAR(30) | Definido |
| `hash_configuracao` | CHAR(64) | Definido |
| `usuario_id` | INT UNSIGNED | Definido |
| `publicada_em` | DATETIME(3) | Definido |
| `created_at` | DATETIME(3) | Definido |

### Chaves

- UNIQUE: `(cena_id, versao)`.
- FK: `cena_id` → `cenas.id`.
- FK: `usuario_id` → `users.id`.

### Estados

`rascunho`, `publicada`, `descontinuada`.

### Regras

Uma versão publicada será imutável.

Alterações deverão gerar uma nova versão.

O hash será obrigatório para versões publicadas.

## 12.3 `cena_dispositivos`

**Finalidade:** definir os dispositivos e suas configurações dentro de uma versão de cena.

### Campos definidos

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `cena_versao_id` | BIGINT UNSIGNED | Definido |
| `dispositivo_id` | BIGINT UNSIGNED | Definido |
| `configuracao` | JSON | Definido |
| `ordem_aplicacao` | INT UNSIGNED | Definido |
| `obrigatorio` | TINYINT(1) | Definido |
| `created_at` | DATETIME(3) | Definido |

### Chaves

- UNIQUE: `(cena_versao_id, dispositivo_id)`.
- FK: `cena_versao_id` → `versoes_cenas.id`.
- FK: `dispositivo_id` → `dispositivos.id`.

### Regras

A configuração representa o estado desejado do dispositivo na cena.

A presença do dispositivo na configuração não comprova que o estado foi aplicado.

A ordem representa a ordem lógica de aplicação. Não obriga que os comandos sejam executados sequencialmente quando a execução paralela for permitida.

## 12.4 `cenarios`

**Finalidade:** cadastrar cenários operacionais que podem combinar várias cenas.

### Campos conceituais definidos

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `codigo` | VARCHAR(80) | Definido |
| `nome` | VARCHAR(120) | Definido |
| `descricao` | TEXT | Definido |
| `categoria` | VARCHAR(40) | Definido conceitualmente |
| `status` | VARCHAR(30) | Definido conceitualmente |
| `ativo` | TINYINT(1) | Definido |
| `usuario_id` | INT UNSIGNED | Definido |
| `created_at` | DATETIME(3) | Definido |
| `updated_at` | DATETIME(3) | Definido |

### Chaves

- PK: `id`.
- UNIQUE: `codigo`.
- FK: `usuario_id` → `users.id`.

### Exemplos

- Operação normal.
- Emergência por vazamento de gás.
- Incêndio.
- Invasão de área restrita.
- Manutenção programada.

### Situação física

A nulabilidade e os defaults definitivos ainda precisam ser formalizados.

## 12.5 `versoes_cenarios`

**Finalidade:** preservar versões imutáveis dos cenários.

### Campos definidos conceitualmente

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `cenario_id` | BIGINT UNSIGNED | Definido |
| `versao` | VARCHAR(40) | Definido |
| `descricao_versao` | VARCHAR(255) | Definido |
| `status` | VARCHAR(30) | Definido |
| `hash_configuracao` | CHAR(64) | Definido |
| `usuario_id` | INT UNSIGNED | Definido |
| `publicada_em` | DATETIME(3) | Definido |
| `created_at` | DATETIME(3) | Definido |

### Chaves

- UNIQUE: `(cenario_id, versao)`.
- FK: `cenario_id` → `cenarios.id`.
- FK: `usuario_id` → `users.id`.

### Estados

`rascunho`, `publicada`, `descontinuada`.

### Regras

Uma versão publicada será imutável.

O hash deverá identificar a configuração consolidada da versão.

## 12.6 `cenario_cenas`

**Finalidade:** relacionar versões de cenários a versões exatas de cenas.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `cenario_versao_id` | BIGINT UNSIGNED | NOT NULL |
| `cena_versao_id` | BIGINT UNSIGNED | NOT NULL |
| `ordem_execucao` | INT UNSIGNED | NOT NULL |
| `obrigatoria` | TINYINT(1) | NOT NULL |
| `condicao_ativacao` | JSON | NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(cenario_versao_id, cena_versao_id)`.
- FK: `cenario_versao_id` → `versoes_cenarios.id`.
- FK: `cena_versao_id` → `versoes_cenas.id`.
- Índices: `(cenario_versao_id, ordem_execucao)`, `cena_versao_id`.

### Regras

Cada associação aponta para uma versão exata da cena.

A ordem representa a sequência lógica de composição, não necessariamente execução estritamente sequencial.

A execução paralela poderá ser utilizada quando as dependências e condições permitirem.

## 12.7 `cenario_dispositivos`

**Finalidade:** definir configurações adicionais ou sobrescritas de dispositivos em um cenário.

### Campos

| Campo | Tipo | Nulidade |
|---|---|---|
| `id` | BIGINT UNSIGNED | NOT NULL, PK |
| `cenario_versao_id` | BIGINT UNSIGNED | NOT NULL |
| `dispositivo_id` | BIGINT UNSIGNED | NOT NULL |
| `configuracao` | JSON | NOT NULL |
| `estrategia_aplicacao` | VARCHAR(20) | NOT NULL |
| `obrigatorio` | TINYINT(1) | NOT NULL |
| `created_at` | DATETIME(3) | NULL |

### Chaves

- UNIQUE: `(cenario_versao_id, dispositivo_id)`.
- FK: `cenario_versao_id` → `versoes_cenarios.id`.
- FK: `dispositivo_id` → `dispositivos.id`.

### Estratégias

`complementar`, `sobrescrever`.

### Regras de composição

A composição obedecerá à seguinte precedência:

1. A cena fornece a configuração-base.
2. A estratégia `complementar` adiciona parâmetros que não conflitam com a configuração existente.
3. A estratégia `sobrescrever` substitui parâmetros conflitantes.
4. Conflitos deverão ser tratados explicitamente pelo backend.
5. O sistema não deverá aplicar conflitos silenciosamente.

A composição final deverá ser validada antes da execução.

## 12.8 `execucoes_cenarios`

**Finalidade:** registrar a execução de uma versão específica de cenário.

### Campos definidos conceitualmente

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `codigo` | VARCHAR(80) | Definido |
| `cenario_versao_id` | BIGINT UNSIGNED | Definido |
| `evento_id` | BIGINT UNSIGNED | NULL permitido |
| `usuario_id` | INT UNSIGNED | NULL permitido |
| `status` | VARCHAR(30) | Definido |
| `entrada` | JSON | NULL permitido |
| `resultado` | JSON | NULL permitido |
| `erro` | TEXT | NULL permitido |
| `iniciada_em` | DATETIME(3) | Definido |
| `concluida_em` | DATETIME(3) | NULL permitido |
| `created_at` | DATETIME(3) | Definido |

### Relacionamentos

- FK: `cenario_versao_id` → `versoes_cenarios.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `usuario_id` → `users.id`.

### Estados

`aguardando`, `em_execucao`, `concluida`, `concluida_parcialmente`, `falhou`, `cancelada`, `expirada`.

### Regras

A execução deverá preservar a versão exata do cenário.

A entrada, o resultado e os erros deverão permitir rastrear a execução.

## 12.9 `execucoes_cenas`

**Finalidade:** registrar a execução individual de uma versão de cena, seja isoladamente ou como parte de um cenário.

### Campos definidos conceitualmente

| Campo | Tipo previsto | Situação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Definido |
| `codigo` | VARCHAR(80) | Definido |
| `execucao_cenario_id` | BIGINT UNSIGNED | NULL permitido |
| `cena_versao_id` | BIGINT UNSIGNED | Definido |
| `evento_id` | BIGINT UNSIGNED | NULL permitido |
| `usuario_id` | INT UNSIGNED | NULL permitido |
| `status` | VARCHAR(30) | Definido |
| `entrada` | JSON | NULL permitido |
| `resultado` | JSON | NULL permitido |
| `erro` | TEXT | NULL permitido |
| `iniciada_em` | DATETIME(3) | Definido |
| `concluida_em` | DATETIME(3) | NULL permitido |
| `created_at` | DATETIME(3) | Definido |

### Relacionamentos

- FK: `execucao_cenario_id` → `execucoes_cenarios.id`.
- FK: `cena_versao_id` → `versoes_cenas.id`.
- FK: `evento_id` → `eventos.id`.
- FK: `usuario_id` → `users.id`.

### Regras

A cena poderá ser executada isoladamente, sem cenário pai.

Quando fizer parte de um cenário, deverá apontar para a execução correspondente.

O campo `comandos.execucao_cena_id` permitirá rastrear comandos originados pela execução de uma cena.

---

# 13. RELACIONAMENTOS E DEPENDÊNCIAS PRINCIPAIS

A tabela abaixo resume as relações mais importantes entre os grupos.

| Origem | Destino | Relação |
|---|---|---|
| `locais.local_pai_id` | `locais.id` | Hierarquia |
| `dispositivos.local_id` | `locais.id` | Local atual |
| `componentes_dispositivo.dispositivo_id` | `dispositivos.id` | Composição |
| `historico_localizacao_dispositivo.dispositivo_id` | `dispositivos.id` | Histórico |
| `historico_localizacao_dispositivo.local_id` | `locais.id` | Histórico |
| `leituras.dispositivo_id` | `dispositivos.id` | Telemetria |
| `leituras.componente_id` | `componentes_dispositivo.id` | Origem da leitura |
| `historico_status_dispositivo.dispositivo_id` | `dispositivos.id` | Estado |
| `versoes_configuracao.dispositivo_id` | `dispositivos.id` | Configuração |
| `historico_estado_gemeo.dispositivo_id` | `dispositivos.id` | Gêmeo digital |
| `eventos.evento_pai_id` | `eventos.id` | Hierarquia |
| `evento_locais.evento_id` | `eventos.id` | Associação |
| `evento_dispositivos.evento_id` | `eventos.id` | Associação |
| `evento_componentes.evento_id` | `eventos.id` | Associação |
| `evento_leituras.evento_id` | `eventos.id` | Associação |
| `evento_anexos.evento_id` | `eventos.id` | Evidências |
| `analises_ia.evento_id` | `eventos.id` | Contexto |
| `analise_anexos.analise_id` | `analises_ia.id` | Evidências |
| `versoes_artefatos_ia.artefato_id` | `artefatos_ia.id` | Versionamento |
| `implantacoes_ia.dispositivo_id` | `dispositivos.id` | Implantação |
| `implantacoes_ia.artefato_versao_id` | `versoes_artefatos_ia.id` | Implantação individual |
| `implantacoes_ia.pipeline_versao_id` | `versoes_pipelines_ia.id` | Implantação de pipeline |
| `versoes_pipelines_ia.pipeline_id` | `pipelines_ia.id` | Versionamento |
| `pipeline_componentes_ia.pipeline_versao_id` | `versoes_pipelines_ia.id` | Composição |
| `pipeline_componentes_ia.artefato_versao_id` | `versoes_artefatos_ia.id` | Composição |
| `mensagens_integracao.comando_id` | `comandos.id` | Mensagem associada |
| `historico_comandos.comando_id` | `comandos.id` | Histórico |
| `acoes_automacao.regra_id` | `regras_automacao.id` | Regra |
| `execucoes_automacao.regra_id` | `regras_automacao.id` | Execução |
| `execucoes_acoes.execucao_automacao_id` | `execucoes_automacao.id` | Execução de ação |
| `execucoes_acoes.acao_id` | `acoes_automacao.id` | Ação executada |
| `execucao_destinatarios.execucao_acao_id` | `execucoes_acoes.id` | Destinatários |
| `mensagens_audio.analise_id` | `analises_ia.id` | Origem |
| `mensagens_audio.anexo_id` | `anexos.id` | Arquivo |
| `mensagem_audio_destinatarios.mensagem_audio_id` | `mensagens_audio.id` | Distribuição |
| `mensagem_audio_destinatarios.comando_id` | `comandos.id` | Comando |
| `mensagem_audio_destinatarios.execucao_destinatario_id` | `execucao_destinatarios.id` | Automação |
| `versoes_cenas.cena_id` | `cenas.id` | Versionamento |
| `cena_dispositivos.cena_versao_id` | `versoes_cenas.id` | Configuração |
| `versoes_cenarios.cenario_id` | `cenarios.id` | Versionamento |
| `cenario_cenas.cenario_versao_id` | `versoes_cenarios.id` | Composição |
| `cenario_cenas.cena_versao_id` | `versoes_cenas.id` | Composição |
| `cenario_dispositivos.cenario_versao_id` | `versoes_cenarios.id` | Configuração |
| `execucoes_cenarios.cenario_versao_id` | `versoes_cenarios.id` | Execução |
| `execucoes_cenas.cena_versao_id` | `versoes_cenas.id` | Execução |
| `execucoes_cenas.execucao_cenario_id` | `execucoes_cenarios.id` | Execução composta |
| `comandos.execucao_cena_id` | `execucoes_cenas.id` | Origem do comando |

As referências de usuário deverão utilizar `INT UNSIGNED` e apontar para `users.id`.

---

# 14. REGRAS DE INTEGRIDADE E CONSISTÊNCIA

## 14.1 Dispositivos e componentes

- Um componente pertence a um único dispositivo.
- Uma leitura associada a um componente deverá pertencer ao mesmo dispositivo informado na leitura.
- A movimentação de um dispositivo deverá preservar seu histórico.
- Um dispositivo poderá permanecer sem local associado.
- O código do dispositivo deverá ser estável.

## 14.2 Telemetria

- `medido_em` e `recebido_em` representam momentos diferentes.
- A qualidade da leitura deverá ser validada.
- A unidade não deverá ser presumida quando não estiver informada.
- A conversão de valores brutos para unidades físicas deverá depender de calibração.
- A retenção de leituras deverá respeitar política específica, ainda não definida.

## 14.3 Eventos

- Eventos poderão ser relacionados a vários locais, dispositivos, componentes, leituras e anexos.
- Os papéis das associações deverão ser validados.
- Eventos derivados deverão manter sua própria identificação.
- Um evento não comprova a execução de comandos.

## 14.4 Inteligência artificial

- Versões de artefatos e pipelines deverão ser imutáveis quando publicadas.
- Uma análise deverá identificar a versão efetivamente utilizada.
- A análise principal poderá reunir resultados de componentes.
- Métricas esperadas e métricas produzidas deverão permanecer separadas.
- A implantação deverá ser coerente com o dispositivo e a versão executada.
- O pipeline deverá apontar para versões exatas de seus componentes.

## 14.5 Comandos

O ciclo de um comando deverá distinguir:

1. Solicitação.
2. Envio.
3. Recebimento.
4. Execução.
5. Confirmação ou resultado.
6. Falha, expiração ou cancelamento, quando aplicável.

A mudança de estado deverá ser registrada no histórico.

A confirmação de recebimento não deverá ser confundida com a confirmação de execução.

## 14.6 Automação

- A execução de uma regra deverá possuir chave de idempotência.
- Cada ação deverá possuir registro de execução.
- Cada destinatário deverá ser rastreável.
- Snapshots de configuração deverão preservar a definição utilizada.
- Falhas parciais deverão ser representáveis.

## 14.7 Áudio

- Geração não significa disponibilização.
- Disponibilização não significa recebimento.
- Recebimento não significa reprodução.
- Reprodução deverá possuir registro próprio por destinatário.
- A distribuição deverá preservar o dispositivo e o contexto de localização.
- O arquivo deverá ser armazenado externamente.

## 14.8 Cenas e cenários

- Versões publicadas deverão ser imutáveis.
- Cenários deverão referenciar versões exatas de cenas.
- A configuração final deverá respeitar a precedência definida.
- Conflitos de configuração deverão ser detectados pelo backend.
- Execuções deverão preservar a versão utilizada.
- Uma cena poderá ser executada isoladamente ou dentro de um cenário.

---

# 15. ÍNDICES E DESEMPENHO

A criação de índices deverá ser orientada pelos relacionamentos e pelas consultas reais da aplicação.

Os índices definidos nas seções de cada tabela representam a base da implementação.

A revisão final deverá verificar:

- Índices de chaves estrangeiras.
- Índices compostos para consultas temporais.
- Índices de estados e datas.
- Índices para filtros por dispositivo.
- Índices para filtros por evento.
- Índices para consultas de histórico.
- Índices para correlação e idempotência.

Não deverão ser criados índices indiscriminadamente.

Índices adicionais aumentam o custo de escrita, armazenamento e manutenção.

## 15.1 Telemetria

As consultas mais importantes deverão considerar:

```text
dispositivo_id + medido_em
componente_id + medido_em
metrica + medido_em
```

Não será adotada particionamento prematuro da tabela `leituras`.

A necessidade de particionamento dependerá do volume real, da retenção e dos resultados de monitoramento.

## 15.2 Histórico

As tabelas históricas deverão priorizar consultas por entidade e data.

Exemplos:

```text
dispositivo_id + ocorrido_em
comando_id + ocorrido_em
usuario_id + ocorrido_em
evento_id
```

## 15.3 Idempotência

A deduplicação de mensagens deverá ser implementada considerando o escopo da operação.

Não será adotada uma restrição UNIQUE global para todos os identificadores de mensagens.

---

# 16. SEGURANÇA E AUDITORIA

## 16.1 Autenticação

O CodeIgniter Shield será responsável pela autenticação e pela estrutura de grupos e permissões.

Não será criada uma estrutura paralela de usuários, grupos e permissões sem necessidade comprovada.

As tabelas de domínio utilizarão referências ao `users.id` do Shield.

## 16.2 Autorização

A autorização deverá ser aplicada no backend.

A interface poderá ocultar funcionalidades de acordo com o perfil, mas a proteção real deverá ocorrer nas rotas, filtros, serviços e regras de negócio.

## 16.3 Segredos

Não deverão ser armazenados em texto aberto:

- Senhas.
- Tokens de acesso.
- Credenciais MQTT.
- Chaves privadas.
- Segredos de serviços externos.

Payloads de integração e dados de auditoria deverão ser avaliados antes de serem persistidos.

## 16.4 Auditoria

As operações relevantes deverão registrar:

- Quem realizou a operação.
- Qual entidade foi afetada.
- Qual ação ocorreu.
- Qual foi a origem.
- Quando ocorreu.
- Qual foi o resultado.
- Qual era o estado anterior e posterior, quando necessário.
- Qual correlation ID permitiu correlacionar a operação.

---

# 17. POLÍTICA DE RETENÇÃO E VOLUME

A política de retenção ainda não está definida.

Não será inventado um prazo de retenção para telemetria, eventos, mensagens ou históricos.

A definição deverá considerar:

- Volume de leituras por dispositivo.
- Frequência de coleta.
- Quantidade de dispositivos.
- Requisitos de auditoria.
- Necessidade de investigação de eventos.
- Capacidade de armazenamento.
- Custos de manutenção.
- Requisitos operacionais e contratuais.

O campo `leituras.protegida` poderá permitir que determinados registros sejam excluídos de uma rotina de retenção, mas sua semântica operacional deverá ser implementada e documentada no backend.

O particionamento de `leituras` não será adotado antes de existir evidência de necessidade.

---

# 18. PENDÊNCIAS TÉCNICAS PARA FECHAMENTO

As pendências abaixo não representam ausência de modelagem conceitual. São pontos que ainda exigem formalização física ou validação final.

## 18.1 Especificação física de tabelas

As tabelas abaixo possuem decisões conceituais, mas precisam de fechamento de todos os campos, tipos, nulabilidade, defaults, índices e restrições:

1. `versoes_artefatos_ia`
2. `implantacoes_ia`
3. `pipelines_ia`
4. `versoes_pipelines_ia`
5. `pipeline_componentes_ia`
6. `cenas`
7. `cenarios`

`artefatos_ia` possui uma proposta de campos já definida nesta especificação, mas deverá passar pela conferência física final junto às demais tabelas do grupo.

## 18.2 Relacionamento de execução com automação

Deverá ser avaliada a inclusão de `execucao_acao_id` em:

- `execucoes_cenas`
- `execucoes_cenarios`

A finalidade seria rastrear se a execução foi iniciada por uma ação de automação.

Essa inclusão ainda não deve ser tratada como decisão aprovada.

## 18.3 Tabela `execucao_cena_dispositivos`

Ainda deverá ser avaliada a necessidade de uma tabela específica para registrar a execução individual de cada dispositivo em uma cena.

A alternativa é utilizar:

- `comandos`.
- `historico_comandos`.
- `execucoes_cenas.resultado`.
- `execucoes_cenas.erro`.

A criação de uma nova tabela somente deverá ocorrer se os dados existentes não forem suficientes para rastrear o resultado individual por dispositivo.

## 18.4 Coerência das implantações de IA

Deverão ser formalizados:

- Restrição de exatamente uma referência entre artefato e pipeline.
- Estados de implantação.
- Datas de início e fim.
- Política de implantação vigente.
- Unicidade de implantação.
- Histórico de mudanças.

## 18.5 Coerência das análises

O backend deverá validar:

- Relação entre análise pai e filha.
- Relação entre componente e versão de pipeline.
- Relação entre componente e versão de artefato.
- Relação entre implantação e dispositivo.
- Exclusividade entre pipeline e artefato na análise.
- Compatibilidade da versão efetivamente executada.

## 18.6 Referências circulares de comandos e integração

Existe uma dependência entre:

- `mensagens_integracao.comando_id`.
- `comandos`.
- `historico_comandos.mensagem_integracao_id`.

As referências possuem semânticas diferentes, mas a ordem de criação das tabelas e das FKs deverá ser definida para evitar problemas na criação das migrations.

## 18.7 Timestamps

A revisão final deverá verificar a coerência entre:

- `created_at`.
- `updated_at`.
- `ocorrido_em`.
- `registrado_em`.
- `medido_em`.
- `recebido_em`.
- `solicitado_em`.
- `enviado_em`.
- `executado_em`.
- `iniciada_em`.
- `concluida_em`.

Esses campos não deverão ser removidos apenas por parecerem semelhantes. Cada um deverá representar um momento operacional específico.

## 18.8 Retenção

A política de retenção de telemetria, eventos, mensagens e históricos permanece pendente.

## 18.9 Idempotência

O escopo de deduplicação das mensagens deverá ser formalizado na aplicação, sem presumir unicidade global.

## 18.10 Índices

A revisão final deverá conferir os índices de todas as FKs, índices compostos e consultas previstas.

---

# 19. ORDEM PREVISTA DE IMPLEMENTAÇÃO DAS MIGRATIONS

A ordem deverá respeitar as dependências entre as tabelas.

Uma sequência inicial de implementação é:

## 19.1 Infraestrutura

As tabelas do Shield e Settings já existentes não deverão ser recriadas.

A tabela `migrations` continuará sendo gerenciada pelo CodeIgniter.

## 19.2 Estrutura física

1. `locais`
2. `dispositivos`
3. `componentes_dispositivo`
4. `historico_localizacao_dispositivo`

## 19.3 Telemetria e gêmeo digital

5. `leituras`
6. `historico_status_dispositivo`
7. `versoes_configuracao`
8. `historico_estado_gemeo`
9. `sessoes_coleta`
10. `sessao_dispositivos`

## 19.4 Eventos e evidências

11. `eventos`
12. `evento_locais`
13. `evento_dispositivos`
14. `evento_componentes`
15. `evento_leituras`
16. `anexos`
17. `evento_anexos`

## 19.5 Inteligência artificial

18. `artefatos_ia`
19. `versoes_artefatos_ia`
20. `pipelines_ia`
21. `versoes_pipelines_ia`
22. `pipeline_componentes_ia`
23. `implantacoes_ia`
24. `analises_ia`
25. `analise_anexos`

A ordem definitiva deverá ser ajustada às FKs reais, especialmente às dependências entre análises, eventos, implantações e pipelines.

## 19.6 Integração e comandos

26. `comandos`
27. `mensagens_integracao`
28. `historico_comandos`

As referências circulares deverão ser resolvidas por estratégia de migrations em etapas, caso necessário.

## 19.7 Automação

29. `regras_automacao`
30. `acoes_automacao`
31. `execucoes_automacao`
32. `execucoes_acoes`
33. `execucao_destinatarios`

## 19.8 Áudio e auditoria

34. `mensagens_audio`
35. `mensagem_audio_destinatarios`
36. `auditoria`

## 19.9 Cenas e cenários

37. `cenas`
38. `versoes_cenas`
39. `cena_dispositivos`
40. `cenarios`
41. `versoes_cenarios`
42. `cenario_cenas`
43. `cenario_dispositivos`
44. `execucoes_cenarios`
45. `execucoes_cenas`

A ordem acima é uma organização funcional preliminar, não uma sequência final de migrations já validada. As dependências reais deverão ser verificadas antes da implementação, inclusive as referências cruzadas entre comandos, execuções, eventos e áudio.

---

# 20. PLANO DE VALIDAÇÃO DO BANCO

Após o fechamento da especificação física, a implementação deverá ser validada por etapas.

## 20.1 Estrutura

- Verificar criação das 45 tabelas de domínio.
- Verificar engine InnoDB.
- Verificar charset utf8mb4.
- Verificar PKs e FKs.
- Verificar índices UNIQUE e compostos.
- Verificar nulabilidade e defaults.
- Verificar compatibilidade dos tipos de FKs.

## 20.2 Integridade referencial

- Impedir referências inexistentes.
- Impedir exclusões indevidas.
- Validar coerência entre dispositivos e componentes.
- Validar associações entre eventos e evidências.
- Validar versões de pipelines e artefatos.
- Validar execução de cenas e cenários.

## 20.3 Transações

Testar operações que exigem atualização conjunta de estado e histórico.

## 20.4 Idempotência

Testar recebimento repetido de mensagens e comandos.

## 20.5 Versionamento

Testar criação, publicação e preservação de versões.

## 20.6 Auditoria

Verificar rastreabilidade das operações relevantes.

## 20.7 Consultas

Validar consultas temporais, históricos, painéis e filtros por dispositivo, local, evento e estado.

---

# 21. CONCLUSÃO E SITUAÇÃO DA ESPECIFICAÇÃO

A modelagem consolidada prevê 45 tabelas de domínio distribuídas em oito grupos funcionais.

A arquitetura separa adequadamente:

- Estrutura física.
- Dispositivos e componentes.
- Telemetria.
- Gêmeo digital.
- Eventos e evidências.
- Inteligência artificial.
- Integração e comandos.
- Automação.
- Áudio.
- Auditoria.
- Cenas e cenários.

A estrutura também mantém a separação entre os serviços externos e a persistência relacional, preservando o papel do MySQL como repositório de dados estruturados e referências.

As decisões de maior impacto arquitetural já estabelecidas incluem:

- Uso do CodeIgniter Shield para autenticação e autorização.
- PKs de domínio `BIGINT UNSIGNED`.
- Referências a `users.id` como `INT UNSIGNED`.
- Uso de InnoDB e utf8mb4.
- Persistência de horários em UTC.
- Uso de JSON para estruturas variáveis.
- Histórico preservado.
- Arquivos armazenados externamente.
- Versionamento imutável de artefatos, pipelines, cenas e cenários.
- Separação entre solicitação, envio, recebimento e execução de comandos.
- Separação entre geração, disponibilização e reprodução de áudio.
- Idempotência tratada no backend.
- Gêmeo digital consultivo, sem reenvio automático de comandos físicos.
- Separação entre destinatários de automação e destinatários de áudio.

**Situação da implementação:** as tabelas de domínio ainda não foram criadas no banco no levantamento registrado em 20/11/2026. A infraestrutura do Shield e Settings já existe.

**Situação da especificação:** a modelagem conceitual dos oito grupos foi consolidada, mas a especificação física ainda exige o fechamento dos campos e restrições de algumas tabelas e a revisão global das dependências.

**Critério para iniciar as migrations:** concluir a especificação física das tabelas pendentes, validar todas as FKs, conferir os índices e fechar a ordem de criação. Até lá, não considerar esta documentação uma autorização para implementar estruturas ainda não aprovadas.

---

# 22. REGISTRO DE DECISÕES E CONTROLE DE ALTERAÇÕES

Este documento consolida as decisões técnicas registradas durante a modelagem do IoT-Edge Command Center.

As decisões deverão ser preservadas durante a implementação, e qualquer alteração estrutural deverá possuir justificativa.

Uma alteração em uma tabela deverá levar em consideração seus efeitos sobre:

- Relacionamentos.
- Integridade referencial.
- Consultas.
- Históricos.
- Versionamento.
- Auditoria.
- Migrations.
- Serviços externos.
- Compatibilidade com dados existentes.

Não deverão ser removidos campos ou relacionamentos apenas para reduzir a quantidade de tabelas ou simplificar artificialmente o modelo.

O inventário de 45 tabelas é a referência consolidada utilizada nesta versão.

