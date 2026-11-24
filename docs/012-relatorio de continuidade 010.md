# RELATÓRIO DE CONTINUIDADE 010
## IoT-Edge Command Center — Consolidação da Modelagem Física do Banco de Dados

**Data:** 20/11/2026  
**Projeto:** IoT-Edge Command Center  
**Ambiente:** CodeIgniter 4.7.5, PHP 8.2 e MySQL 8.0  
**Banco de dados:** `iot_edge_center`

---

## 1. OBJETIVO DESTE REGISTRO

Este relatório registra o estado atual do desenvolvimento da camada de persistência do IoT-Edge Command Center.

O objetivo é preservar as verificações realizadas, as decisões arquiteturais aprovadas e o planejamento das entidades que comporão o banco de dados.

A etapa atual é de **consolidação da modelagem física**. Ainda não serão criadas migrations das tabelas de domínio. Primeiro, serão definidos os campos, tipos, restrições, índices, chaves primárias, chaves estrangeiras e regras de integridade de todas as entidades.

Somente depois da revisão conjunta da modelagem será iniciada a implementação das migrations.

## 2. AMBIENTE E SITUAÇÃO ATUAL DO BANCO

O banco de dados existente foi verificado por meio do CodeIgniter e do MySQL Workbench.

Configuração confirmada:

| Propriedade | Valor |
|---|---|
| SGBD | MySQL 8.0 |
| Banco | `iot_edge_center` |
| Host | `localhost` |
| Porta | `3306` |
| Usuário | `root` |
| Driver | MySQLi |
| Framework | CodeIgniter 4.7.5 |
| PHP | 8.2 |
| Autenticação | CodeIgniter Shield |

### 2.1 Tabelas existentes

Atualmente, o banco possui nove tabelas:

- `users`
- `auth_identities`
- `auth_logins`
- `auth_token_logins`
- `auth_remember_tokens`
- `auth_groups_users`
- `auth_permissions_users`
- `settings`
- `migrations`

Essas tabelas pertencem à infraestrutura do CodeIgniter Shield e do CodeIgniter Settings.

Nenhuma tabela de domínio IoT foi criada até o momento.

### 2.2 Estrutura confirmada de `users`

A consulta `SHOW CREATE TABLE users` confirmou:

- `id INT UNSIGNED NOT NULL AUTO_INCREMENT`;
- `username VARCHAR(30)`, com restrição UNIQUE;
- campos de status, atividade e timestamps;
- engine InnoDB;
- charset `utf8mb4`;
- collation `utf8mb4_general_ci`.

Consequentemente, toda futura chave estrangeira que referenciar `users.id` deverá utilizar `INT UNSIGNED`, respeitando o tipo real da chave primária do Shield.

Para as entidades próprias do domínio, permanece como referência inicial o uso de `BIGINT UNSIGNED` nas chaves primárias. As respectivas chaves estrangeiras deverão reproduzir exatamente o tipo da chave referenciada.

### 2.3 Estado das migrations

A execução de `php spark migrate:status` confirmou quatro migrations de infraestrutura executadas:

- CodeIgniter Shield: `create_auth_tables`;
- CodeIgniter Settings: `CreateSettingsTable`;
- CodeIgniter Settings: `AddContextColumn`;
- CodeIgniter Settings: `ConvertSqlsrvValueColumn`.

Todas estão registradas no batch 1.

A pasta `app/Database/Migrations` contém somente `.gitkeep`, sem migrations próprias de domínio.

A configuração `app/Config/Migrations.php` foi verificada:

- migrations habilitadas;
- tabela de controle `migrations`;
- formato de timestamp `Y-m-d-His_`;
- lock desabilitado.

Não há necessidade identificada de modificar essa configuração neste momento.

**Conclusão:** a infraestrutura de autenticação e migrations já está instalada. A implementação das tabelas de domínio ainda não começou.

## 3. DECISÕES ARQUITETURAIS CONSOLIDADAS

### 3.1 Autenticação e usuários

O CodeIgniter Shield será a única infraestrutura de autenticação e autorização.

Não serão criadas tabelas próprias de `usuarios`, `perfis` ou `usuario_perfis`.

Os grupos definidos são:

- `administrador`;
- `operador`;
- `visualizador`.

As tabelas de domínio que precisarem registrar usuários deverão referenciar a tabela `users` do Shield.

### 3.2 Dispositivos e componentes

Um dispositivo representa um equipamento físico completo.

Sensores, controladores, módulos e atuadores poderão ser registrados individualmente em `componentes_dispositivo`.

Essa separação permitirá identificar o equipamento e, ao mesmo tempo, registrar as características e funções de seus componentes.

### 3.3 Locais e hierarquia

A localização será representada por entidades próprias no banco.

A estrutura deverá permitir hierarquias como:

- edifício;
- andar;
- zona;
- sala;
- corredor;
- área externa.

A localização atual de um dispositivo será consultada no banco, sem precisar incorporar sua localização física ao identificador estável ou ao tópico MQTT.

Mudanças de localização deverão preservar o histórico.

### 3.4 MQTT e integração

A comunicação MQTT será registrada em `mensagens_integracao`.

A modelagem deverá distinguir:

- mensagem recebida;
- mensagem publicada;
- solicitação de comando;
- confirmação de recebimento;
- confirmação de execução;
- falha;
- expiração;
- estado desconhecido.

Não se deve considerar que a publicação de uma mensagem representa a execução bem-sucedida de um comando.

### 3.5 Vídeos, imagens e arquivos

O servidor de vídeo permanece independente do MySQL.

O banco não armazenará diretamente os arquivos binários de imagens, vídeos ou áudios.

A entidade `anexos` guardará metadados e referências externas quando houver necessidade de preservar evidências ou documentos.

Não será criado um registro de anexo para cada frame ou transmissão ao vivo.

### 3.6 Inteligência artificial

Foi aprovada a inclusão da entidade `analises_ia`.

Ela registrará execuções individuais de serviços de inteligência artificial, incluindo:

- visão computacional;
- interpretação contextual;
- modelo e versão utilizados;
- serviço responsável;
- entrada analisada;
- resultado estruturado;
- confiança, quando aplicável;
- tempo de processamento;
- status e erros;
- evento relacionado, quando houver.

As frases produzidas pelo LLM serão armazenadas como parte do resultado da análise correspondente.

Quando uma frase for encaminhada ao Piper TTS, a entidade `mensagens_audio` registrará o conteúdo efetivamente enviado à síntese e os metadados do áudio produzido.

### 3.7 Histórico e rastreabilidade

A arquitetura deverá preservar o histórico de eventos, comandos, estados, localizações e execuções.

A exclusão física de registros históricos deverá ser evitada quando comprometer a rastreabilidade.

Os horários serão armazenados preferencialmente em UTC, utilizando `DATETIME(3)` quando a precisão de milissegundos for relevante.

Os dados JSON serão validados pela aplicação antes da persistência.

Também deverão ser consideradas idempotência, mensagens duplicadas, retenção de dados e integridade transacional.

## 4. INVENTÁRIO ATUAL DAS ENTIDADES DE DOMÍNIO

O planejamento atual prevê **26 tabelas de domínio**, além das tabelas já fornecidas pelo Shield e pelo CodeIgniter.

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

### Grupo 3 — Eventos e evidências

10. `eventos`
11. `evento_locais`
12. `evento_dispositivos`
13. `evento_componentes`
14. `evento_leituras`
15. `anexos`

### Grupo 4 — Inteligência artificial

16. `analises_ia`

### Grupo 5 — Integração e comandos

17. `mensagens_integracao`
18. `comandos`
19. `historico_comandos`

### Grupo 6 — Automação

20. `regras_automacao`
21. `acoes_automacao`
22. `execucoes_automacao`
23. `execucoes_acoes`
24. `execucao_destinatarios`

### Grupo 7 — Áudio e auditoria

25. `mensagens_audio`
26. `auditoria`

A quantidade representa o inventário de planejamento atual. Poderá sofrer ajustes durante a modelagem física, desde que tecnicamente justificados.

## 5. PONTOS QUE AINDA PRECISAM SER DEFINIDOS

A matriz de dependências já foi esboçada, mas ainda não é uma especificação definitiva.

Precisam ser detalhados, principalmente:

- relações entre análises de IA, eventos, leituras, dispositivos e anexos;
- associação de anexos com diferentes entidades;
- relação entre mensagens de integração e comandos;
- relação entre mensagens de áudio e execuções de automação;
- estrutura dos registros do gêmeo digital;
- associação das sessões de coleta com eventos e leituras;
- estrutura da auditoria e identificação do ator;
- regras de integridade para histórico de localização;
- política de exclusão, desativação e retenção;
- índices necessários para consultas operacionais e históricas.

Também será necessário avaliar se determinados resultados de IA podem permanecer em JSON ou se alguma informação exigirá uma tabela filha para permitir consultas mais específicas.

Essas decisões ainda não devem ser tratadas como concluídas.

## 6. METODOLOGIA DE IMPLEMENTAÇÃO DEFINIDA

A estratégia de trabalho foi ajustada para consolidar a modelagem completa antes da execução das migrations.

O processo será dividido em quatro etapas.

### Etapa 1 — Modelagem física

Definir todas as tabelas, incluindo:

- campos;
- tipos de dados;
- nulabilidade;
- valores padrão;
- chaves primárias;
- chaves estrangeiras;
- restrições UNIQUE;
- índices;
- regras de integridade;
- relacionamentos e dependências.

Nenhuma migration de domínio será criada nesta etapa.

### Etapa 2 — Revisão geral

Revisar as 26 entidades em conjunto.

Verificar:

- consistência dos tipos;
- compatibilidade das chaves;
- dependências circulares;
- integridade referencial;
- redundâncias;
- capacidade de consulta;
- preservação histórica;
- compatibilidade com o Shield;
- aderência à arquitetura MQTT, IA, vídeo e áudio.

### Etapa 3 — Criação das migrations

Depois da aprovação da modelagem, criar as migrations do CodeIgniter 4.

A ordem de criação deverá respeitar as dependências entre as tabelas.

As migrations deverão contemplar chaves estrangeiras, índices, restrições e mecanismos de integridade definidos durante a modelagem.

### Etapa 4 — Execução e validação

Executar as migrations no banco `iot_edge_center`.

Depois, verificar:

- estrutura efetiva de cada tabela;
- tipos e nulabilidade;
- chaves primárias e estrangeiras;
- índices;
- restrições;
- integridade referencial;
- estado registrado pelo comando `php spark migrate:status`.

A estrutura implementada deverá corresponder à modelagem aprovada.

## 7. PRÓXIMO PASSO: PRIMEIRO GRUPO DE ENTIDADES

A próxima atividade será iniciar a definição física do primeiro grupo, denominado **Estrutura Física**.

A ordem de trabalho será:

1. `locais`;
2. `dispositivos`;
3. `componentes_dispositivo`;
4. `historico_localizacao_dispositivo`.

A primeira entidade a ser detalhada será `locais`.

### 7.1 Objetivo da tabela `locais`

Representar a estrutura física e lógica dos ambientes monitorados.

Deverá permitir cadastrar locais independentes ou hierarquicamente relacionados, com identificação estável, tipo, descrição e informações geográficas quando aplicáveis.

### 7.2 Campos inicialmente propostos

Os seguintes campos constituem apenas um ponto de partida para discussão:

| Campo | Tipo inicialmente proposto | Observação |
|---|---|---|
| `id` | BIGINT UNSIGNED | Chave primária |
| `local_pai_id` | BIGINT UNSIGNED NULL | Referência ao local superior |
| `codigo` | VARCHAR(80) | Identificação estável |
| `nome` | VARCHAR(120) | Nome do local |
| `tipo` | VARCHAR(30) | Categoria do local |
| `descricao` | TEXT NULL | Descrição complementar |
| `latitude` | DECIMAL(10,7) NULL | Coordenada geográfica |
| `longitude` | DECIMAL(10,7) NULL | Coordenada geográfica |
| `referencia` | VARCHAR(190) NULL | Referência de localização |
| `ativo` | BOOLEAN | Situação operacional |

Os campos de auditoria temporal, como `created_at` e `updated_at`, também deverão ser avaliados.

Será necessário decidir se a tabela utilizará `deleted_at` ou se a desativação por `ativo = false` será suficiente para preservar o histórico.

O campo `tipo` deverá ter seus valores permitidos definidos, considerando a possibilidade de evolução da estrutura física.

A hierarquia deverá impedir referências inválidas e ciclos, com validações na aplicação e, quando apropriado, mecanismos adicionais no banco.

**Nenhum desses campos está ainda aprovado como estrutura definitiva.**

## 8. SITUAÇÃO DO PROJETO AO ENCERRAR ESTE CHECKPOINT

| Item | Situação |
|---|---|
| Banco `iot_edge_center` | Existente e acessível |
| CodeIgniter Shield | Instalado e migrado |
| Grupos e permissões | Configurados |
| Tipo real de `users.id` | Verificado: INT UNSIGNED |
| Migrations de infraestrutura | Executadas |
| Migrations de domínio | Ainda não criadas |
| Inventário de entidades | 26 previstas |
| Inclusão de `analises_ia` | Aprovada conceitualmente |
| Estrutura física de `locais` | Pendente de definição |
| Estrutura física das demais tabelas | Pendente |
| Revisão geral dos relacionamentos | Pendente |
| Execução das migrations de domínio | Pendente |

## 9. INSTRUÇÃO PARA RETOMADA

Ao retomar o desenvolvimento, continuar diretamente pela definição física da tabela `locais`.

Não repetir as verificações de conexão, Shield, estrutura de `users` ou migrations de infraestrutura já registradas neste relatório, salvo se surgir algum motivo técnico para revalidá-las.

Não criar migrations ainda.

A prioridade será fechar corretamente o primeiro grupo de entidades e, em seguida, prosseguir pelos demais grupos até completar a modelagem física das 26 tabelas previstas.

**Ponto de parada:** planejamento consolidado; início da modelagem física da tabela `locais` ainda pendente.