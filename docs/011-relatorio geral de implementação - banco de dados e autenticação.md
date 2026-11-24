# RELATÓRIO GERAL DE IMPLEMENTAÇÃO — BANCO DE DADOS E AUTENTICAÇÃO

**Projeto:** IoT-Edge Command Center  
**Instituição:** IFES — Campus Serra  
**Banco de dados:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4.7.5  
**Linguagem:** PHP 8.2  
**Autenticação:** CodeIgniter Shield  
**Data de atualização:** 20/11/2026

---

# 1. OBJETIVO DO RELATÓRIO

Este documento reúne o histórico técnico e a situação atual da implementação do banco de dados do projeto IoT-Edge Command Center.

Seu propósito é servir como referência central para a continuidade do desenvolvimento, evitando a necessidade de consultar separadamente todos os relatórios anteriores para compreender as decisões já tomadas.

O documento reúne quatro etapas anteriores:

- Relatório 002 — Modelagem original do banco de dados.
- Relatório 007 — Planejamento das migrations e implementação.
- Relatório 008 — Decisão de utilização do CodeIgniter Shield.
- Relatório 009 — Instalação, configuração e testes do Shield.

Também incorpora as verificações realizadas diretamente no ambiente de desenvolvimento em 20/11/2026.

A partir deste checkpoint, o trabalho deixa de se concentrar na elaboração de documentação teórica e passa a priorizar a implementação efetiva do banco de dados.

A documentação será atualizada progressivamente conforme cada etapa for executada e validada.

# 2. CONTEXTO DO PROJETO

O IoT-Edge Command Center é uma plataforma web destinada ao gerenciamento de dispositivos IoT, monitoramento de telemetria, identificação de eventos, execução de automações, gerenciamento de comandos e acompanhamento histórico dos equipamentos.

A arquitetura considera:

- CodeIgniter 4 para o backend;
- MySQL para persistência;
- CodeIgniter Shield para autenticação e autorização;
- MQTT para comunicação com dispositivos;
- WebSocket ou mecanismo equivalente para atualização da interface;
- Piper TTS para geração de mensagens de áudio;
- armazenamento externo para arquivos;
- organização física dos equipamentos por locais e hierarquias.

O sistema deverá permitir que vários dispositivos estejam associados a diferentes ambientes físicos, que eventos afetem vários equipamentos simultaneamente e que regras de automação executem ações individualizadas.

Também deverá preservar o histórico operacional para possibilitar auditoria e reconstrução do estado conhecido do gêmeo digital.

# 3. AMBIENTE DE DESENVOLVIMENTO

## 3.1. Configuração identificada

| Componente | Configuração |
|---|---|
| Sistema operacional | Windows |
| Ambiente de desenvolvimento | Local |
| Framework | CodeIgniter 4.7.5 |
| Linguagem | PHP 8.2 |
| SGBD | MySQL 8.0 |
| Driver | MySQLi |
| Banco | `iot_edge_center` |
| Host | `localhost` |
| Porta | `3306` |
| Usuário de conexão | `root` |
| Engine das tabelas verificadas | InnoDB |
| Charset das tabelas verificadas | utf8mb4 |

Diretório do projeto:

```text
C:\Users\Cleiton\Documents\TCC-IFES\web\iot-edge-command-center
```

## 3.2. Conexão com o banco

A conexão foi conferida com:

```bash
php spark db:table
```

Resultado confirmado:

```text
hostname: localhost
database: iot_edge_center
username: root
DBDriver: MySQLi
DBPrefix:
port: 3306
```

O CodeIgniter consegue acessar o banco de dados corretamente.

# 4. IMPLEMENTAÇÃO JÁ REALIZADA

## 4.1. Instalação do CodeIgniter Shield

O CodeIgniter Shield foi instalado por meio do Composer.

As migrations oficiais do Shield foram executadas com sucesso.

O projeto passou a utilizar o mecanismo oficial de autenticação e autorização do CodeIgniter.

Essa decisão substituiu a proposta original de criar um sistema próprio de usuários e perfis.

**Situação: implementado e testado.**

## 4.2. Tabelas existentes no banco

A execução de `php spark db:table` apresentou nove tabelas:

| Tabela | Situação |
|---|---|
| `users` | Criada pelo Shield |
| `auth_identities` | Criada pelo Shield |
| `auth_groups_users` | Criada pelo Shield |
| `auth_permissions_users` | Criada pelo Shield |
| `auth_logins` | Criada pelo Shield |
| `auth_remember_tokens` | Criada pelo Shield |
| `auth_token_logins` | Criada pelo Shield |
| `settings` | Criada pelo Shield |
| `migrations` | Controle das migrations |

Até o último checkpoint, nenhuma tabela de domínio IoT havia sido criada.

Isso significa que a estrutura de autenticação está implementada, mas a estrutura operacional do banco ainda precisa ser desenvolvida.

## 4.3. Estrutura real da tabela users

Foi realizada uma verificação diretamente no MySQL Workbench:

```sql
USE iot_edge_center;

SHOW CREATE TABLE users;
```

A estrutura retornada foi:

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

### Decisão técnica confirmada

A chave primária de usuários é:

```sql
INT UNSIGNED
```

Consequentemente, todas as futuras FKs que referenciarem `users.id` deverão utilizar o mesmo tipo.

Convenção estabelecida:

| Elemento | Tipo de referência |
|---|---|
| PK das tabelas de domínio | `BIGINT UNSIGNED` |
| FK para outra tabela de domínio | Mesmo tipo da PK referenciada |
| FK para `users.id` | `INT UNSIGNED` |

Não deverá ser utilizada uma FK `BIGINT UNSIGNED` para referenciar diretamente `users.id`.

## 4.4. Contas demonstrativas

Foram criadas três contas de demonstração:

| Usuário | Grupo |
|---|---|
| `admin` | `administrador` |
| `operador` | `operador` |
| `visualizador` | `visualizador` |

E-mails demonstrativos:

```text
admin@iot-edge.example.com
operador@iot-edge.example.com
visualizador@iot-edge.example.com
```

Senha de teste local documentada:

```text
Iot@1234
```

Essa senha é exclusivamente demonstrativa e não deve ser utilizada em produção.

As associações dos usuários aos grupos foram confirmadas em `auth_groups_users`.

**Situação: implementado e testado.**

# 5. CONFIGURAÇÃO DE GRUPOS E PERMISSÕES

## 5.1. Grupos funcionais

Foram definidos três grupos:

- `administrador`;
- `operador`;
- `visualizador`.

O grupo padrão é `visualizador`.

O cadastro público permanece desabilitado.

## 5.2. Catálogo de permissões

O projeto possui o seguinte catálogo:

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

## 5.3. Matriz funcional

| Permissão | Administrador | Operador | Visualizador |
|---|:---:|:---:|:---:|
| `dashboard.view` | Sim | Sim | Sim |
| `telemetry.view` | Sim | Sim | Sim |
| `history.view` | Sim | Sim | Sim |
| `digital_twin.view` | Sim | Sim | Sim |
| `video.view` | Sim | Sim | Sim |
| `devices.view` | Sim | Sim | Sim |
| `devices.manage` | Sim | Não | Não |
| `locations.view` | Sim | Sim | Sim |
| `locations.manage` | Sim | Não | Não |
| `events.view` | Sim | Sim | Sim |
| `events.acknowledge` | Sim | Sim | Não |
| `events.manage` | Sim | Não | Não |
| `automations.view` | Sim | Sim | Sim |
| `automations.execute` | Sim | Sim | Não |
| `automations.manage` | Sim | Não | Não |
| `commands.view` | Sim | Sim | Sim |
| `commands.execute` | Sim | Sim | Não |
| `users.view` | Sim | Não | Não |
| `users.manage` | Sim | Não | Não |
| `audit.view` | Sim | Não | Não |
| `system.settings` | Sim | Não | Não |

A matriz deverá ser respeitada tanto na interface quanto no backend.

Ocultar botões não será considerado suficiente para proteger uma operação.

# 6. ROTAS E AUTENTICAÇÃO DA APLICAÇÃO

Foram implementados e testados:

- instalação e configuração do Shield;
- autenticação por sessão;
- login;
- logout;
- proteção de rotas;
- redirecionamento para autenticação;
- identificação dinâmica do usuário;
- identificação dinâmica do grupo;
- CSRF global;
- integração com o layout principal.

As rotas de consulta registradas incluem:

| Rota | Permissão |
|---|---|
| `/dashboard` | `dashboard.view` |
| `/embarcados` | `devices.view` |
| `/diagnostico` | `telemetry.view` |
| `/gemeo` | `digital_twin.view` |
| `/videomonitoramento` | `video.view` |
| `/locais` | `locations.view` |
| `/automacoes` | `automations.view` |
| `/historico` | `history.view` |

A configuração utiliza os filtros de sessão e permissão do Shield.

As páginas de consulta foram testadas com os três grupos.

O acesso de todos os perfis às páginas de consulta está de acordo com a matriz estabelecida.

## 6.1. Pendências de autorização

Ainda deverão ser implementadas e testadas as operações de escrita:

- cadastro e edição de dispositivos;
- gerenciamento de locais;
- gerenciamento de eventos;
- execução de comandos;
- execução de automações;
- gerenciamento de usuários;
- gerenciamento de configurações;
- consulta de auditoria.

A autorização efetiva dessas operações será validada quando os respectivos endpoints e funcionalidades existirem.

# 7. MODELAGEM ORIGINAL DO DOMÍNIO IOT

O Relatório 002 estabeleceu 28 tabelas lógicas.

Com a adoção do Shield, três tabelas próprias de autenticação foram retiradas da implementação:

- `usuarios`;
- `perfis`;
- `usuario_perfis`.

A referência atual passa a ser de 25 tabelas de domínio.

## 7.1. Relação consolidada

| Nº | Tabela | Responsabilidade |
|---:|---|---|
| 1 | `locais` | Hierarquia física |
| 2 | `dispositivos` | Cadastro de equipamentos |
| 3 | `componentes_dispositivo` | Componentes dos equipamentos |
| 4 | `leituras` | Telemetria |
| 5 | `historico_status_dispositivo` | Histórico de estados |
| 6 | `versoes_configuracao` | Versões de configuração |
| 7 | `historico_estado_gemeo` | Histórico do gêmeo digital |
| 8 | `eventos` | Ocorrências operacionais |
| 9 | `evento_dispositivos` | Associação entre eventos e dispositivos |
| 10 | `evento_componentes` | Associação entre eventos e componentes |
| 11 | `evento_leituras` | Associação entre eventos e leituras |
| 12 | `anexos` | Metadados de arquivos |
| 13 | `mensagens_integracao` | Mensagens MQTT e integrações |
| 14 | `comandos` | Comandos enviados aos dispositivos |
| 15 | `historico_comandos` | Histórico dos comandos |
| 16 | `mensagens_audio` | Metadados de mensagens de áudio |
| 17 | `auditoria` | Auditoria operacional |
| 18 | `regras_automacao` | Definição de regras |
| 19 | `execucoes_automacao` | Execuções de regras |
| 20 | `sessoes_coleta` | Sessões de coleta |
| 21 | `evento_locais` | Associação entre eventos e locais |
| 22 | `acoes_automacao` | Ações individuais |
| 23 | `execucoes_acoes` | Execução de cada ação |
| 24 | `execucao_destinatarios` | Destinatários de cada ação |
| 25 | `historico_localizacao_dispositivo` | Histórico de localização |

Essa relação é a base de implementação, não uma declaração de que todas as tabelas já foram criadas.

# 8. DECISÕES DE MODELAGEM QUE DEVEM SER PRESERVADAS

## 8.1. Dispositivos

Um dispositivo representa o equipamento físico completo.

Seus componentes serão cadastrados separadamente em `componentes_dispositivo`.

O dispositivo possuirá identificação estável, independente de sua localização física.

## 8.2. Locais

A hierarquia física será representada por `locais`, com autorrelacionamento entre local pai e local filho.

Os tipos considerados são:

- edifício;
- andar;
- zona;
- sala;
- corredor;
- área externa.

A localização atual será associada ao dispositivo, enquanto as mudanças serão registradas no histórico.

## 8.3. Telemetria

A tabela `leituras` armazenará informações de sensores.

Deverá diferenciar:

- dispositivo;
- componente;
- métrica;
- valor;
- unidade;
- qualidade;
- horário da medição;
- horário do recebimento;
- payload original.

A validação deverá garantir que um componente associado a uma leitura pertença ao dispositivo informado.

Leituras analógicas não devem ser apresentadas como unidades físicas calibradas sem um processo de calibração.

## 8.4. Eventos

Um evento poderá envolver múltiplos dispositivos, componentes, leituras e locais.

As associações serão mantidas em tabelas próprias.

## 8.5. Comandos

A solicitação, o envio, o recebimento e a execução de um comando são etapas diferentes.

Os estados previstos são:

```text
solicitado
enviado
recebido
executado
falhou
expirado
cancelado
desconhecido
```

O envio de uma mensagem MQTT não representa confirmação automática de execução física.

## 8.6. Automações

Uma regra poderá conter várias ações.

Cada ação será armazenada individualmente em `acoes_automacao`.

A execução da regra será registrada em `execucoes_automacao`.

Cada ação terá seu próprio registro de execução em `execucoes_acoes`.

Os dispositivos selecionados serão registrados em `execucao_destinatarios`.

## 8.7. Gêmeo digital

O histórico deverá permitir consultar estados conhecidos do sistema em momentos anteriores.

Não deverá reenviar comandos físicos ao reconstruir o histórico.

## 8.8. Arquivos e áudio

Áudios, vídeos, imagens e documentos ficarão em armazenamento externo.

O banco armazenará os metadados e as referências necessárias.

## 8.9. Auditoria

As operações relevantes deverão registrar:

- ator;
- ação;
- entidade afetada;
- identificador;
- evento relacionado;
- resultado;
- informações anteriores e posteriores;
- canal;
- correlação;
- horários.

## 8.10. Idempotência

Mensagens MQTT duplicadas e comandos repetidos deverão ser tratados de maneira idempotente, evitando processamentos duplicados indevidos.

# 9. O QUE AINDA NÃO FOI IMPLEMENTADO

Até a data deste relatório, ainda não foram criadas as tabelas de domínio IoT.

Portanto, permanecem pendentes:

1. Criação da tabela `locais`.
2. Criação da tabela `dispositivos`.
3. Criação da tabela `componentes_dispositivo`.
4. Criação da tabela `leituras`.
5. Criação das tabelas históricas.
6. Criação da tabela `eventos`.
7. Criação das tabelas associativas de eventos.
8. Criação da tabela `anexos`.
9. Criação da tabela `mensagens_integracao`.
10. Criação das tabelas de comandos.
11. Criação da tabela `mensagens_audio`.
12. Criação da tabela `auditoria`.
13. Criação das tabelas de automação.
14. Criação da tabela de sessões de coleta.
15. Criação da tabela de histórico de localização.
16. Implementação dos relacionamentos e restrições.
17. Implementação dos índices.
18. Testes de integridade referencial.
19. Testes de operações CRUD.
20. Validação de permissões sobre as operações de escrita.

Nenhuma dessas implementações deverá ser considerada concluída antes da execução e verificação no ambiente real.

# 10. PLANO DE IMPLEMENTAÇÃO

A implementação será realizada de maneira incremental, respeitando as dependências entre as entidades.

## Etapa 1 — Preparação e validação do ambiente

- Confirmar a conexão com o banco.
- Conferir o estado das migrations.
- Confirmar que as tabelas do Shield continuam funcionando.
- Verificar a configuração do sistema de migrations do CodeIgniter.
- Confirmar os tipos das chaves primárias necessárias.

**Resultado esperado:** ambiente preparado para receber as tabelas de domínio sem comprometer a autenticação existente.

## Etapa 2 — Estrutura física dos locais

Implementar:

- `locais`;
- autorrelacionamento;
- índices;
- restrições;
- validação hierárquica.

**Resultado esperado:** cadastro e consulta da hierarquia física.

## Etapa 3 — Dispositivos e componentes

Implementar:

- `dispositivos`;
- `componentes_dispositivo`;
- relacionamento com `locais`;
- referências a usuários quando necessárias;
- índices e restrições.

**Resultado esperado:** cadastro de equipamentos e seus componentes.

## Etapa 4 — Telemetria e histórico operacional

Implementar:

- `leituras`;
- `historico_status_dispositivo`;
- `versoes_configuracao`;
- `historico_localizacao_dispositivo`;
- `sessoes_coleta`.

**Resultado esperado:** persistência de medições e histórico operacional.

## Etapa 5 — Eventos e associações

Implementar:

- `eventos`;
- `evento_dispositivos`;
- `evento_componentes`;
- `evento_leituras`;
- `evento_locais`;
- `anexos`.

**Resultado esperado:** registro de eventos e suas entidades relacionadas.

## Etapa 6 — Integração e comandos

Implementar:

- `mensagens_integracao`;
- `comandos`;
- `historico_comandos`.

**Resultado esperado:** rastreabilidade das mensagens e comandos.

## Etapa 7 — Automação

Implementar:

- `regras_automacao`;
- `acoes_automacao`;
- `execucoes_automacao`;
- `execucoes_acoes`;
- `execucao_destinatarios`.

**Resultado esperado:** estrutura persistente para regras, ações e seus destinatários.

## Etapa 8 — Áudio, auditoria e gêmeo digital

Implementar:

- `mensagens_audio`;
- `auditoria`;
- `historico_estado_gemeo`.

**Resultado esperado:** suporte ao histórico do gêmeo digital, mensagens de áudio e rastreabilidade.

## Etapa 9 — Validação geral

Executar:

- verificação das tabelas;
- verificação das chaves estrangeiras;
- testes de integridade;
- testes de inserção;
- testes de atualização;
- testes de consultas;
- testes de exclusão e desativação;
- testes de permissões;
- testes de consistência histórica.

**Resultado esperado:** banco de domínio implementado e validado.

A ordem definitiva poderá ser ajustada quando as dependências reais das chaves estrangeiras forem analisadas. Em particular, relacionamentos circulares deverão ser resolvidos com uma sequência apropriada de migrations e, quando necessário, adição posterior de constraints.

# 11. METODOLOGIA DE EXECUÇÃO

O desenvolvimento seguirá um processo incremental.

Para cada etapa:

1. Analisar a estrutura necessária.
2. Conferir dependências.
3. Definir os campos e tipos.
4. Definir chaves primárias e estrangeiras.
5. Definir índices e restrições.
6. Criar a migration completa.
7. Executar a migration.
8. Verificar a estrutura criada no MySQL.
9. Realizar testes de integridade.
10. Registrar o resultado no relatório.

Não serão criadas várias migrations de uma só vez sem validar as etapas anteriores.

As migrations deverão utilizar os mecanismos do CodeIgniter 4 e preservar as tabelas já existentes do Shield.

Os arquivos de migration deverão ser apresentados completos, prontos para inclusão no projeto.

# 12. PRIMEIRO CHECKPOINT DE IMPLEMENTAÇÃO

## Estado em 20/11/2026

| Item | Estado |
|---|---|
| Projeto CodeIgniter | Implementado |
| Banco MySQL | Implementado |
| Conexão com banco | Testada |
| CodeIgniter Shield | Instalado |
| Migrations do Shield | Executadas |
| Tabelas de autenticação | Criadas |
| Grupos funcionais | Configurados |
| Permissões | Configuradas |
| Contas demonstrativas | Criadas |
| Login e logout | Testados |
| Proteção das páginas de consulta | Testada |
| Estrutura de `users` | Conferida |
| Tipo de `users.id` | `INT UNSIGNED`, confirmado |
| Tabelas de domínio IoT | Ainda não criadas |
| Migrations de domínio | Ainda não iniciadas |
| CRUD das entidades IoT | Pendente |
| Integração operacional com banco | Pendente |

## Próxima atividade

Iniciar a implementação efetiva da primeira tabela de domínio:

`locais`

Antes de criar sua migration, deverá ser conferido o estado atual do controle de migrations e definidos os campos, relacionamentos e restrições necessários.

Após a execução, será realizada a conferência no MySQL.

Somente depois da validação da tabela `locais` será iniciada a implementação de `dispositivos`.

---

# 13. REGISTRO DE CONTINUIDADE

Este documento representa o checkpoint geral da implementação do banco de dados do IoT-Edge Command Center.

Ele consolida o que foi realizado nos relatórios anteriores e estabelece o ponto de partida para a implementação das tabelas de domínio.

A partir deste momento, os próximos relatórios deverão registrar principalmente:

- arquivos criados ou modificados;
- migrations executadas;
- comandos utilizados;
- resultados reais;
- estruturas SQL verificadas;
- erros encontrados e respectivas correções;
- testes realizados;
- decisões técnicas novas;
- pendências para a próxima etapa.

**Critério de conclusão:** uma tabela somente será considerada implementada depois que sua migration tiver sido executada e sua estrutura real tiver sido conferida no banco de dados.

**Próximo checkpoint previsto:** implementação e validação da tabela `locais`.

**FIM DO RELATÓRIO GERAL DE IMPLEMENTAÇÃO.**