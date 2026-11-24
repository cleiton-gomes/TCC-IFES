# RELATÓRIO DE CHECKPOINT — CONSOLIDAÇÃO DA MODELAGEM DO BANCO DE DADOS

**Projeto:** IoT-Edge Command Center  
**Etapa:** Consolidação da modelagem do banco de dados e integração com CodeIgniter Shield  
**Data:** 20/11/2026  
**Banco de dados:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4.7.5  
**Autenticação:** CodeIgniter Shield

---

# 1. OBJETIVO DESTA ETAPA

Esta etapa teve como objetivo recuperar, revisar e consolidar as decisões registradas nos relatórios anteriores de desenvolvimento do banco de dados do projeto IoT-Edge Command Center.

O trabalho considera os relatórios sequenciais:

- **Relatório 002:** modelagem lógica original do banco de dados;
- **Relatório 007:** planejamento de implementação por migrations no CodeIgniter;
- **Relatório 008:** decisão de adoção do CodeIgniter Shield;
- **Relatório 009:** implementação e validação inicial da autenticação e autorização com Shield.

A finalidade não é simplesmente reunir os documentos, mas estabelecer uma especificação técnica coerente, respeitando a evolução do projeto.

Quando uma decisão posterior modifica uma definição anterior, deve prevalecer a decisão mais recente, desde que esteja documentada e validada.

Outro objetivo importante é preservar o detalhamento técnico dos relatórios originais. A documentação final deverá explicar as tabelas, os relacionamentos, as responsabilidades, os campos, as restrições, os índices, os fluxos operacionais e as decisões arquiteturais.

**Nesta etapa, nenhuma migration de domínio foi criada.** O trabalho foi concentrado na verificação da estrutura existente e na consolidação das decisões que orientarão a implementação futura.

# 2. AMBIENTE ATUAL DO BANCO DE DADOS

O banco utilizado pelo projeto é:

`iot_edge_center`

A conexão foi conferida por meio do comando:

```bash
php spark db:table
```

O CodeIgniter apresentou os seguintes dados:

| Propriedade | Valor |
|---|---|
| Hostname | `localhost` |
| Database | `iot_edge_center` |
| Username | `root` |
| DBDriver | `MySQLi` |
| DBPrefix | vazio |
| Port | `3306` |

A execução também apresentou a relação das tabelas existentes no banco.

## 2.1. Tabelas atualmente existentes

Foram identificadas nove tabelas:

| Tabela | Origem/finalidade |
|---|---|
| `auth_groups_users` | Associação entre usuários e grupos do Shield |
| `auth_identities` | Identidades de autenticação |
| `auth_logins` | Histórico de autenticações |
| `auth_permissions_users` | Permissões associadas diretamente aos usuários |
| `auth_remember_tokens` | Tokens de autenticação persistente |
| `auth_token_logins` | Autenticação por tokens |
| `migrations` | Controle das migrations executadas |
| `settings` | Configurações utilizadas pelo Shield |
| `users` | Contas de usuários |

Essas tabelas correspondem à estrutura de autenticação e suporte instalada pelo CodeIgniter Shield.

**Situação confirmada:** até esta etapa, nenhuma das tabelas de domínio IoT previstas na modelagem original foi criada.

Portanto, o banco já possui a estrutura necessária para a autenticação inicial, mas ainda não possui a estrutura de persistência operacional dos dispositivos, sensores, leituras, eventos, comandos, automações e demais entidades do domínio IoT.

# 3. INTEGRAÇÃO DA MODELAGEM COM O CODEIGNITER SHIELD

Uma das principais mudanças arquiteturais ocorridas após a modelagem original foi a decisão de utilizar o CodeIgniter Shield para gerenciar a autenticação e a autorização.

Essa decisão substitui a proposta inicial de desenvolver tabelas próprias para usuários e perfis.

## 3.1. Tabelas removidas da modelagem original

O modelo original previa três tabelas:

- `usuarios`;
- `perfis`;
- `usuario_perfis`.

Essas tabelas não deverão ser criadas na nova implementação, pois suas responsabilidades passaram a ser atendidas pelo Shield.

A estrutura oficial de autenticação será mantida pelo próprio framework.

| Responsabilidade | Estrutura utilizada |
|---|---|
| Contas de usuários | `users` |
| Identidades de autenticação | `auth_identities` |
| Associação entre usuários e grupos | `auth_groups_users` |
| Permissões individuais | `auth_permissions_users` |
| Histórico de autenticação | `auth_logins` |
| Tokens de autenticação | Estruturas do Shield |

Os grupos funcionais serão:

- `administrador`;
- `operador`;
- `visualizador`.

O grupo padrão será `visualizador`, e o cadastro público permanecerá desabilitado.

## 3.2. Consequência para a modelagem de domínio

As tabelas de domínio que precisarem registrar um usuário deverão estabelecer relacionamentos com `users.id`.

Exemplos de campos que poderão utilizar essa referência:

- `usuario_id`;
- `criado_por`;
- `registrado_por`;
- `executado_por`;
- `reconhecido_por`.

A utilização de cada campo deverá respeitar a responsabilidade específica da tabela.

Também deverá ser considerada a possibilidade de uma operação ser originada por um dispositivo, pelo próprio sistema ou por um processo automático, sem necessariamente existir um usuário humano associado.

Nesses casos, a ausência de usuário deverá ser representada adequadamente, sem atribuir artificialmente a operação a uma conta.

# 4. VERIFICAÇÃO DA ESTRUTURA REAL DA TABELA USERS

Nesta etapa foi realizada uma conferência direta da definição SQL da tabela `users`.

Primeiramente, no MySQL Workbench, foi selecionado o banco:

```sql
USE iot_edge_center;
```

Em seguida, foi executado:

```sql
SHOW CREATE TABLE users;
```

O resultado confirmou a seguinte estrutura:

```sql
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB
  AUTO_INCREMENT=4
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;
```

## 4.1. Estrutura confirmada

| Campo | Tipo | Características |
|---|---|---|
| `id` | `INT UNSIGNED` | Chave primária, incremento automático |
| `username` | `VARCHAR(30)` | Índice UNIQUE, permite NULL |
| `status` | `VARCHAR(255)` | Permite NULL |
| `status_message` | `VARCHAR(255)` | Permite NULL |
| `active` | `TINYINT(1)` | NOT NULL, padrão 0 |
| `last_active` | `DATETIME` | Permite NULL |
| `created_at` | `DATETIME` | Permite NULL |
| `updated_at` | `DATETIME` | Permite NULL |
| `deleted_at` | `DATETIME` | Permite NULL |

Características adicionais:

- Engine: InnoDB;
- Charset: `utf8mb4`;
- Collation: `utf8mb4_general_ci`;
- Chave primária: `id`;
- Índice UNIQUE: `username`;
- Incremento automático atual: `AUTO_INCREMENT=4`.

## 4.2. Decisão sobre os tipos das chaves estrangeiras

A confirmação da estrutura real de `users` permitiu estabelecer uma regra importante para a implementação das futuras migrations.

A coluna `users.id` utiliza:

```sql
INT UNSIGNED
```

Consequentemente, qualquer chave estrangeira que referencie diretamente essa coluna deverá utilizar o mesmo tipo.

Exemplo:

```sql
criado_por INT UNSIGNED NULL,
registrado_por INT UNSIGNED NULL,
usuario_id INT UNSIGNED NULL
```

Esses campos são apenas exemplos de compatibilidade de tipos. A criação efetiva de cada coluna dependerá da necessidade identificada na tabela correspondente.

Para as tabelas de domínio, permanece como referência inicial a utilização de `BIGINT UNSIGNED` nas chaves primárias, conforme a modelagem original.

Assim, ficam estabelecidas as seguintes convenções:

| Tipo de relacionamento | Tipo de referência |
|---|---|
| PK de tabela de domínio | `BIGINT UNSIGNED` |
| FK para tabela de domínio com PK BIGINT | `BIGINT UNSIGNED` |
| FK para `users.id` | `INT UNSIGNED` |

Essa distinção deverá ser respeitada em todas as migrations futuras.

**Observação:** a definição SQL de `users` foi efetivamente conferida. Os tipos das demais tabelas do Shield não foram individualmente auditados nesta etapa. Caso seja necessário criar relacionamentos adicionais com elas, suas estruturas deverão ser verificadas antes da implementação.

# 5. RECUPERAÇÃO E CONSOLIDAÇÃO DA MODELAGEM ORIGINAL

A modelagem original do Relatório 002 permanece como referência estrutural para o domínio IoT.

A proposta contempla a persistência e o gerenciamento de:

- locais e hierarquia física;
- dispositivos;
- componentes dos dispositivos;
- leituras de sensores;
- histórico de estados;
- versões de configuração;
- histórico do gêmeo digital;
- eventos;
- associações de eventos;
- anexos;
- mensagens de integração;
- comandos;
- histórico de comandos;
- mensagens de áudio;
- auditoria;
- regras de automação;
- execuções de automação;
- ações de automação;
- destinatários;
- sessões de coleta;
- histórico de localização dos dispositivos.

## 5.1. Quantidade de tabelas

A modelagem original possuía 28 tabelas lógicas.

Com a adoção do Shield, três tabelas próprias de autenticação e perfil deixam de fazer parte do domínio da aplicação:

1. `usuarios`;
2. `perfis`;
3. `usuario_perfis`.

A referência inicial passa, portanto, a ser de **25 tabelas de domínio IoT**, além das tabelas de infraestrutura e autenticação mantidas pelo Shield.

Essa quantidade representa a estrutura lógica consolidada como ponto de partida. A especificação definitiva ainda deverá revisar cada tabela e seus relacionamentos antes da implementação.

# 6. DECISÕES ARQUITETURAIS PRESERVADAS

As seguintes decisões da modelagem original continuam relevantes e deverão ser mantidas na documentação definitiva.

## 6.1. Dispositivo e componente são entidades distintas

Um dispositivo representa o equipamento físico completo.

Os componentes representam sensores, módulos, controladores, atuadores ou outros elementos internos associados ao equipamento.

Essa separação permite que um dispositivo possua múltiplos componentes sem precisar criar um registro de dispositivo independente para cada sensor.

## 6.2. Localização pertence ao banco de dados

A localização física de um dispositivo será resolvida por meio do relacionamento com a tabela `locais`.

O tópico MQTT não deverá depender do nome do prédio, andar, sala ou zona.

O código do dispositivo deverá permanecer estável mesmo quando houver mudança de localização.

## 6.3. Hierarquia física dos locais

A tabela `locais` deverá representar a organização física por meio de uma relação hierárquica entre registros.

Os tipos considerados são:

- edifício;
- andar;
- zona;
- sala;
- corredor;
- área externa.

A modelagem deverá permitir consultas hierárquicas e impedir ciclos inválidos na estrutura pai-filho.

## 6.4. Eventos podem envolver múltiplas entidades

Um evento poderá estar relacionado a:

- vários locais;
- vários dispositivos;
- vários componentes;
- várias leituras.

Essas associações serão representadas por tabelas próprias, evitando limitar um evento a apenas um dispositivo ou local.

## 6.5. Regras de automação e ações individuais

Uma regra de automação poderá conter várias ações.

As ações serão representadas individualmente pela tabela `acoes_automacao`, em vez de permanecerem agrupadas em um único campo JSON da regra.

Essa separação permite controlar ordem, configuração, ativação e execução de cada ação.

## 6.6. Execução e destinatários

A execução de uma automação deverá ser separada da execução individual de suas ações.

Também deverá existir controle dos destinatários selecionados para cada ação.

Isso permite acompanhar individualmente os resultados de cada dispositivo envolvido, sem considerar que o sucesso de uma ação significa automaticamente o sucesso de todos os destinatários.

## 6.7. Estados de comandos

A solicitação de um comando não deverá ser confundida com seu envio, recebimento ou execução física.

Os estados considerados são:

- `solicitado`;
- `enviado`;
- `recebido`;
- `executado`;
- `falhou`;
- `expirado`;
- `cancelado`;
- `desconhecido`.

A confirmação de envio MQTT, por exemplo, não comprova que o dispositivo executou fisicamente a operação.

## 6.8. Histórico e rastreabilidade

O projeto deverá preservar o histórico de:

- alterações de configuração;
- mudanças de estado;
- comandos;
- eventos;
- localização dos dispositivos;
- execuções de automação;
- ações individuais;
- auditoria.

A exclusão física de registros históricos deverá ser evitada quando comprometer a rastreabilidade.

## 6.9. Arquivos externos ao banco

Imagens, vídeos, áudios e documentos não deverão ser armazenados diretamente como grandes objetos binários no MySQL.

O banco deverá guardar seus metadados e referências externas, como URI ou caminho, hash, formato, tamanho e informações necessárias para recuperação.

## 6.10. Gêmeo digital

O histórico do gêmeo digital deverá permitir reconstruir o estado conhecido do sistema em determinado momento.

Essa reconstrução deverá considerar:

- leituras;
- eventos;
- comandos;
- estados físicos confirmados;
- configurações;
- localização histórica;
- horários dos acontecimentos;
- lacunas de informação;
- estados desconhecidos.

O gêmeo digital será uma representação histórica e de consulta, não um mecanismo para reenviar comandos físicos.

# 7. INTEGRAÇÃO COM MQTT E ARQUITETURA IOT-EDGE

A organização da comunicação MQTT deverá permanecer independente da hierarquia física dos locais.

Os tópicos de referência definidos na modelagem são:

```text
iot-edge/devices/{device_id}/telemetry
iot-edge/devices/{device_id}/status
iot-edge/devices/{device_id}/events
iot-edge/devices/{device_id}/commands
iot-edge/devices/{device_id}/command-results
```

O identificador estável do dispositivo será utilizado para organizar a comunicação.

A localização será consultada no banco de dados, permitindo alterar a posição física de um equipamento sem modificar sua identidade ou seus tópicos MQTT.

A tabela `mensagens_integracao` deverá permitir rastrear mensagens recebidas e enviadas, com informações de correlação, identificação, tópico, conteúdo interpretado, estado de processamento e erros.

Também deverão ser definidos posteriormente:

- autenticação e autorização no broker;
- ACL dos tópicos;
- QoS;
- tratamento de mensagens duplicadas;
- idempotência;
- expiração;
- timeout;
- política de repetição;
- validação de payload;
- retenção de mensagens.

Arquivos de áudio não deverão ser enviados como grandes payloads MQTT. A comunicação deverá utilizar um mecanismo apropriado para transportar o arquivo, enquanto o MQTT poderá transportar os comandos e metadados necessários.

# 8. DECISÕES SOBRE HISTÓRICO E RETENÇÃO

A modelagem deverá preservar a capacidade de reconstruir o histórico operacional.

Entre as decisões já registradas estão:

- preservar leituras associadas a eventos;
- registrar o momento da medição e o momento do recebimento;
- diferenciar o horário do acontecimento do horário de registro;
- manter histórico de localização;
- preservar histórico de comandos;
- registrar versões de configuração;
- permitir identificar lacunas de informação;
- evitar que estados desconhecidos sejam tratados como sucesso ou falha.

A referência de preservar 100 amostras anteriores a um evento deverá ser mantida como quantidade de amostras, não como um intervalo de tempo fixo.

A política definitiva de retenção e arquivamento ainda deverá ser detalhada.

# 9. SITUAÇÃO DA AUTENTICAÇÃO E AUTORIZAÇÃO

As decisões do Relatório 009 permanecem como referência para a integração entre a aplicação e o Shield.

## 9.1. Grupos funcionais

| Grupo | Responsabilidade |
|---|---|
| Administrador | Gerenciamento geral do sistema |
| Operador | Consulta e execução de operações autorizadas |
| Visualizador | Consulta das informações permitidas |

O grupo padrão é `visualizador`.

O cadastro público permanece desabilitado.

## 9.2. Catálogo de permissões

O catálogo registrado no projeto contempla:

```text
dashboard.view
telemetry.view
history.view
digital_twin.view
video.view

devices.view
devices.manage

locations.view
locations.manage

events.view
events.acknowledge
events.manage

automations.view
automations.execute
automations.manage

commands.view
commands.execute

users.view
users.manage

audit.view
system.settings
```

A matriz de permissões deverá ser preservada no documento consolidado, incluindo a distinção entre consulta, execução e gerenciamento.

## 9.3. Validação realizada

Segundo os testes documentados no Relatório 009:

- instalação do Shield concluída;
- migrations do Shield executadas;
- grupos funcionais configurados;
- permissões cadastradas;
- contas demonstrativas criadas;
- associações dos usuários aos grupos confirmadas;
- login e logout testados;
- rotas protegidas por autenticação;
- identificação dinâmica do grupo no layout;
- páginas de consulta testadas com os três perfis.

O acesso dos três grupos às páginas de consulta está de acordo com a matriz de permissões documentada.

## 9.4. Pendências de autorização

Ainda será necessário validar as operações de escrita e execução quando suas funcionalidades estiverem implementadas.

Entre os pontos pendentes estão:

- cadastro, edição e exclusão de dispositivos;
- gerenciamento de locais;
- reconhecimento e gerenciamento de eventos;
- execução de automações;
- execução de comandos;
- gerenciamento de usuários;
- gerenciamento de configurações;
- acesso à auditoria;
- tentativas de acesso direto ao backend sem permissão.

A autorização deverá ser aplicada no backend. Ocultar botões ou elementos da interface não será considerado controle de segurança suficiente.

# 10. SITUAÇÃO GERAL DA ETAPA

| Atividade | Situação |
|---|---|
| Recuperação do Relatório 002 | Concluída |
| Recuperação das decisões do Relatório 007 | Concluída |
| Recuperação das decisões do Relatório 008 | Concluída |
| Recuperação do Relatório 009 | Concluída |
| Identificação das tabelas existentes | Concluída |
| Confirmação do banco `iot_edge_center` | Concluída |
| Conferência do `SHOW CREATE TABLE users` | Concluída |
| Confirmação do tipo de `users.id` | Concluída |
| Adaptação conceitual das referências a usuários | Concluída |
| Consolidação detalhada das 25 tabelas de domínio | Pendente |
| Revisão definitiva dos relacionamentos | Pendente |
| Revisão das restrições e índices | Pendente |
| Definição da ordem final das migrations | Pendente |
| Implementação das migrations de domínio | Não iniciada |

# 11. PRÓXIMA ETAPA — ESPECIFICAÇÃO DEFINITIVA DO BANCO

A próxima etapa será produzir o documento técnico consolidado da modelagem do banco de dados.

Esse documento deverá utilizar o Relatório 002 como base estrutural e incorporar as decisões posteriores.

A elaboração deverá seguir uma organização progressiva.

## 11.1. Estrutura geral

O documento deverá apresentar:

1. Introdução e objetivos;
2. Contextualização da arquitetura;
3. Tecnologias utilizadas;
4. Decisões arquiteturais;
5. Convenções de nomenclatura e tipos;
6. Integração com CodeIgniter Shield;
7. Visão geral do modelo de dados;
8. Descrição individual das tabelas;
9. Relacionamentos e cardinalidades;
10. Restrições, índices e integridade;
11. Fluxos operacionais;
12. Integração MQTT;
13. Automações e execução de ações;
14. Gêmeo digital e reconstrução histórica;
15. Auditoria, retenção e segurança;
16. Dependências entre tabelas;
17. Ordem recomendada das migrations;
18. Pendências e decisões que precisam de validação.

## 11.2. Detalhamento individual das tabelas

Cada tabela deverá ser documentada com:

- finalidade;
- responsabilidade;
- campos;
- tipos de dados;
- obrigatoriedade;
- valores padrão;
- chave primária;
- chaves estrangeiras;
- índices;
- restrições;
- relacionamentos;
- regras de integridade;
- comportamento histórico;
- exemplos de utilização, quando necessários.

Não será suficiente apresentar apenas uma lista de tabelas e seus campos. O objetivo é explicar por que cada entidade existe e como participa do funcionamento do sistema.

## 11.3. Revisão dos relacionamentos

Deverão ser analisados especialmente:

- relações entre eventos e dispositivos;
- relações entre eventos e locais;
- relações entre eventos e leituras;
- relações entre regras e ações;
- relações entre execuções e ações;
- relações entre ações e destinatários;
- relações entre comandos e destinatários;
- relações entre usuários e registros históricos;
- relações entre dispositivos e seu histórico de localização.

Também será necessário revisar dependências circulares e referências que possam exigir criação posterior de chaves estrangeiras.

## 11.4. Planejamento das migrations

Somente depois de concluir e validar a especificação será definida a ordem de implementação.

A ordem deverá considerar as dependências entre tabelas e as referências ao Shield.

Nenhuma migration de domínio deverá ser criada antes da aprovação da modelagem consolidada.

# 12. PONTO DE CONTROLE PARA CONTINUIDADE

Este relatório estabelece o estado do projeto em 20/11/2026.

**Estado atual:**

- Banco `iot_edge_center` existente e acessível pelo CodeIgniter.
- CodeIgniter Shield instalado e funcionando.
- Nove tabelas existentes, relacionadas à autenticação e ao suporte do framework.
- Tabela `users` examinada diretamente no MySQL Workbench.
- Chave primária `users.id` confirmada como `INT UNSIGNED`.
- Modelo original de 28 tabelas revisado conceitualmente.
- Três tabelas próprias de autenticação substituídas pela estrutura do Shield.
- Referência inicial de 25 tabelas de domínio IoT.
- Nenhuma migration de domínio criada.
- Especificação definitiva ainda não elaborada.

**Próximo marco:** concluir o documento técnico consolidado da modelagem do banco de dados, com todas as tabelas, relacionamentos, campos, restrições, índices e dependências revisados.

**Critério para avançar:** somente iniciar a implementação das migrations após a conclusão e validação da especificação.

---

**FIM DO RELATÓRIO DE CHECKPOINT**

**Referência de continuidade:** Consolidação da modelagem do banco de dados — IoT-Edge Command Center — após integração com CodeIgniter Shield.