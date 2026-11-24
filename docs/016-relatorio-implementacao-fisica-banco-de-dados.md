# RELATÓRIO TÉCNICO --- IMPLEMENTAÇÃO FÍSICA DO BANCO DE DADOS

## IoT-Edge Command Center

**Instituição:** IFES --- Campus Serra\
**Projeto:** IoT-Edge Command Center\
**Responsável:** Cleiton Gomes dos Santos\
**Data da implementação:** 24/11/2026\
**Etapa:** Implementação, versionamento e validação da estrutura física do banco de dados

------------------------------------------------------------------------

# 1. INTRODUÇÃO

O IoT-Edge Command Center é uma plataforma de gerenciamento de dispositivos embarcados, telemetria, eventos, inteligência artificial, automação e operações integradas. Sua arquitetura busca permitir a reutilização em diferentes ambientes organizacionais, sem restringir o modelo de dados a uma aplicação industrial específica.

Após a especificação física do banco de dados, iniciou-se a materialização do modelo relacional por meio das migrations do CodeIgniter 4. Esta etapa estabelece a infraestrutura persistente necessária para a posterior implementação dos models, serviços, controllers e integrações do backend.

O presente relatório documenta a execução efetiva das migrations e os resultados observados no ambiente de desenvolvimento. A existência das tabelas não implica que as funcionalidades operacionais da plataforma já estejam implementadas.

# 2. OBJETIVOS

-   Implementar as 45 tabelas de domínio previstas.
-   Preservar a infraestrutura de autenticação e configurações existente.
-   Versionar a estrutura física por migrations reproduzíveis.
-   Estabelecer chaves primárias, estrangeiras, índices e restrições de integridade.
-   Realizar backup preventivo antes das alterações estruturais.
-   Validar a estrutura criada diretamente no MySQL.
-   Executar testes representativos de integridade sem persistir registros fictícios.
-   Preparar o banco para o desenvolvimento do backend.

# 3. AMBIENTE DE DESENVOLVIMENTO

  Elemento              Configuração
  --------------------- -----------------------
  Sistema operacional   Windows, terminal CMD
  PHP CLI               8.2.33
  Composer              2.10.3
  Framework             CodeIgniter 4.7.5
  Autenticação          CodeIgniter Shield
  Configurações         CodeIgniter Settings
  SGBD                  MySQL 8.0.45
  Driver                MySQLi
  Servidor              localhost
  Porta                 3306
  Banco                 `iot_edge_center`
  Engine                InnoDB
  Collation             `utf8mb4_general_ci`

Diretório do projeto:

``` text
C:\Users\Cleiton\Documents\TCC-IFES\web\iot-edge-command-center
```

As migrations encontram-se em `app/Database/Migrations/`. O formato de versionamento configurado é `Y-m-d-His_`. O ambiente virtual Python não é necessário para executar as migrations PHP.

# 4. PREMISSAS E DECISÕES ARQUITETURAIS

## 4.1. Separação entre infraestrutura e domínio

O banco possuía nove tabelas de infraestrutura antes desta etapa: `users`, `auth_groups_users`, `auth_identities`, `auth_logins`, `auth_permissions_users`, `auth_remember_tokens`, `auth_token_logins`, `settings` e `migrations`.

As migrations do domínio foram organizadas separadamente, preservando o gerenciamento de autenticação pelo Shield e de configurações pelo Settings.

## 4.2. Padronização física

Foram adotadas chaves primárias de domínio `BIGINT UNSIGNED AUTO_INCREMENT` e referências compatíveis. As referências a `users.id` utilizam `INT UNSIGNED`, conforme o tipo existente na infraestrutura de autenticação.

A estrutura utiliza InnoDB, `utf8mb4_general_ci`, campos temporais `DATETIME(3)` onde aplicável, campos JSON para estruturas flexíveis e códigos estáveis para identificação lógica. A convenção temporal UTC deve ser respeitada pelo backend; o tipo DATETIME não realiza conversão automática de fuso horário.

Estados operacionais são representados por campos textuais, com validação de domínio a cargo do backend quando não houver restrição SQL específica. Anexos são representados por metadados e referências de armazenamento externo, evitando a necessidade de armazenar arquivos binários diretamente nas tabelas de domínio.

## 4.3. Integridade e preservação histórica

A política referencial do domínio é restritiva, evitando exclusões em cascata que comprometam evidências, históricos ou rastreabilidade. Versionamento de configurações, artefatos de IA, pipelines, cenas e cenários permite registrar referências a versões específicas.

Imutabilidade de versões publicadas, prevenção de ciclos hierárquicos, coerência entre dispositivos e componentes, transições de estado e comportamento append-only dos históricos dependem de regras adicionais do backend. A criação das tabelas não comprova essas propriedades por si só.

## 4.4. Integração e comunicação

A modelagem separa mensagens de integração, comandos, histórico de comandos, telemetria e estado do gêmeo digital. MQTT permanece como protocolo de integração com dispositivos quando aplicável; WebSocket pode atender atualizações da interface, sem substituir MQTT. O histórico do gêmeo digital tem finalidade consultiva e não implica reenvio automático de comandos.

## 4.5. Formalizações complementares

Durante a implementação foram formalizados detalhes físicos anteriormente incompletos de `versoes_artefatos_ia`, `implantacoes_ia`, `pipelines_ia`, `versoes_pipelines_ia`, `pipeline_componentes_ia`, `cenas` e `cenarios`. Foram também incluídas referências `execucao_acao_id` nas execuções de cenas e cenários para rastreabilidade da automação. Essas decisões complementam a especificação anterior e não devem ser apresentadas como transcrição literal de definições físicas já completas.

# 5. ORGANIZAÇÃO DAS MIGRATIONS

  Ordem       Arquivo (sem extensão .php)                          Tabelas
  ----------- -------------------------------------------------- ---------
  01          `2026-11-24-133922_CreateEstruturaFisica`                  4
  02          `2026-11-24-140002_CreateEventosEvidencias`                6
  03          `2026-11-24-140003_CreateTelemetriaEColeta`                6
  04          `2026-11-24-140004_CreateInteligenciaArtificial`           8
  05          `2026-11-24-140005_CreateAutomacao`                        5
  06          `2026-11-24-140006_CreateCenasECenarios`                   9
  07          `2026-11-24-140007_CreateIntegracaoEComandos`              3
  08          `2026-11-24-140008_CreateAudioEAuditoria`                  3
  09          `2026-11-24-140009_AddForeignKeysPendentes`                1
  **Total**                                                         **45**

As migrations utilizam instruções SQL executadas por `$this->db->query()` nos métodos `up()`. Os métodos `down()` foram organizados para remover os objetos em ordem inversa de dependência. A reversão das migrations não foi executada neste ambiente.

## 5.1. Inventário das tabelas e responsabilidades

### 5.1.1. Estrutura física

  --------------------------------------------------------------------------------
  Tabela                                Responsabilidade
  ------------------------------------- ------------------------------------------
  `locais`                              Hierarquia de locais e coordenadas.

  `dispositivos`                        Cadastro e estado dos dispositivos.

  `componentes_dispositivo`             Componentes vinculados aos dispositivos.

  `historico_localizacao_dispositivo`   Vigência e histórico das localizações.
  --------------------------------------------------------------------------------

### 5.1.2. Eventos e evidências

  Tabela                  Responsabilidade
  ----------------------- ----------------------------------------------
  `eventos`               Registro de ocorrências e seu ciclo de vida.
  `anexos`                Metadados de arquivos e evidências.
  `evento_locais`         Associação entre eventos e locais.
  `evento_dispositivos`   Associação entre eventos e dispositivos.
  `evento_componentes`    Associação entre eventos e componentes.
  `evento_anexos`         Associação entre eventos e anexos.

### 5.1.3. Telemetria e coleta

  Tabela                           Responsabilidade
  -------------------------------- ------------------------------------------
  `leituras`                       Medições numéricas ou textuais.
  `historico_status_dispositivo`   Histórico de mudanças de estado.
  `versoes_configuracao`           Snapshots versionados de configuração.
  `sessoes_coleta`                 Controle de sessões de coleta.
  `sessao_dispositivos`            Participação de dispositivos em sessões.
  `evento_leituras`                Vínculo entre ocorrências e medições.

### 5.1.4. Inteligência artificial

  Tabela                      Responsabilidade
  --------------------------- ----------------------------------------
  `artefatos_ia`              Cadastro lógico de artefatos.
  `versoes_artefatos_ia`      Versões específicas dos artefatos.
  `pipelines_ia`              Cadastro lógico de pipelines.
  `versoes_pipelines_ia`      Versões e coordenadores dos pipelines.
  `pipeline_componentes_ia`   Composição versionada dos pipelines.
  `implantacoes_ia`           Implantações em dispositivos.
  `analises_ia`               Execuções e resultados de análises.
  `analise_anexos`            Arquivos associados às análises.

### 5.1.5. Automação

  Tabela                     Responsabilidade
  -------------------------- -------------------------------------
  `regras_automacao`         Gatilhos, condições e prioridades.
  `acoes_automacao`          Ações associadas às regras.
  `execucoes_automacao`      Histórico de execução das regras.
  `execucoes_acoes`          Execuções e tentativas das ações.
  `execucao_destinatarios`   Destinatários das ações executadas.

### 5.1.6. Cenas e cenários

  ------------------------------------------------------------------------------------
  Tabela                              Responsabilidade
  ----------------------------------- ------------------------------------------------
  `cenas`                             Cadastro lógico de cenas.

  `cenarios`                          Cadastro lógico de cenários.

  `versoes_cenas`                     Versionamento de cenas.

  `cena_dispositivos`                 Configurações dos dispositivos na cena.

  `versoes_cenarios`                  Versionamento de cenários.

  `cenario_cenas`                     Composição de cenários por cenas.

  `cenario_dispositivos`              Configurações complementares ou substitutivas.

  `execucoes_cenarios`                Histórico de execução dos cenários.

  `execucoes_cenas`                   Histórico de execução das cenas.
  ------------------------------------------------------------------------------------

### 5.1.7. Integração e comandos

  Tabela                   Responsabilidade
  ------------------------ ---------------------------------------------
  `mensagens_integracao`   Rastreabilidade de mensagens de integração.
  `comandos`               Solicitações de atuação em dispositivos.
  `historico_comandos`     Mudanças de estado dos comandos.

### 5.1.8. Áudio e auditoria

  ----------------------------------------------------------------------------------
  Tabela                              Responsabilidade
  ----------------------------------- ----------------------------------------------
  `mensagens_audio`                   Solicitação e geração de mensagens de áudio.

  `mensagem_audio_destinatarios`      Distribuição e acompanhamento da reprodução.

  `auditoria`                         Registro de ações e alterações.
  ----------------------------------------------------------------------------------

### 5.1.9. Chaves pendentes e gêmeo digital

  --------------------------------------------------------------------------------------
  Tabela                              Responsabilidade
  ----------------------------------- --------------------------------------------------
  `historico_estado_gemeo`            Histórico consultivo do estado do gêmeo digital.

  --------------------------------------------------------------------------------------

## 5.2. Resolução de dependências entre migrations

A migration 07 cria `mensagens_integracao.comando_id` como `BIGINT UNSIGNED NULL`, com índice `idx_mi_comando`. A migration 09 completa a dependência após a criação das tabelas envolvidas:

``` sql
ALTER TABLE mensagens_integracao
    ADD CONSTRAINT fk_mi_comando
        FOREIGN KEY (comando_id)
        REFERENCES comandos (id)
        ON DELETE RESTRICT ON UPDATE RESTRICT;
```

Na migration 04, as quatro referências de versões de artefatos e pipelines envolvidas em condições CHECK foram declaradas sem ações referenciais explícitas. O MySQL registrou `NO ACTION / NO ACTION`, equivalente a RESTRICT no InnoDB. Essa decisão compatibilizou as restrições sem introduzir exclusões em cascata.

# 6. PREPARAÇÃO E VALIDAÇÃO PHP

Os nove arquivos foram submetidos à validação de sintaxe PHP. Durante a preparação do primeiro arquivo, foi corrigido um espaço em branco anterior à abertura PHP que interferia na declaração do namespace. O procedimento de normalização e lint utilizado é exemplificado abaixo:

``` cmd
php -r "$p='app/Database/Migrations/2026-11-24-140004_CreateInteligenciaArtificial.php'; file_put_contents($p, ltrim(file_get_contents($p)));"
php -l app\Database\Migrations\2026-11-24-140004_CreateInteligenciaArtificial.php
php spark migrate:status
```

Resultado: nove arquivos reconhecidos e sintaticamente válidos. Antes da aplicação, as nove migrations App estavam pendentes; quatro migrations de infraestrutura já estavam registradas no Batch 1. O lint PHP não verifica a validade do SQL no MySQL, razão pela qual a execução real foi necessária.

# 7. BACKUP PREVENTIVO

Antes da aplicação, foi criado um backup lógico utilizando mysqldump.

``` cmd
where mysqldump
mkdir "%USERPROFILE%\Documents\TCC-IFES\backups"
mysqldump -h localhost -P 3306 -u root -p --single-transaction --routines --triggers --events --no-tablespaces --databases iot_edge_center --result-file="%USERPROFILE%\Documents\TCC-IFES\backups\iot_edge_center_antes_migrations_2026-11-24.sql"
echo %ERRORLEVEL%
dir "%USERPROFILE%\Documents\TCC-IFES\backups"
```

O executável foi localizado em `C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe`.

  Indicador           Resultado observado
  ------------------- ---------------------------------------------------
  Código de saída     0
  Arquivo             `iot_edge_center_antes_migrations_2026-11-24.sql`
  Tamanho             15.282 bytes
  Data e hora local   24/11/2026, 11:12

O parâmetro `--single-transaction` permite obter um snapshot consistente das tabelas transacionais, desde que não ocorram alterações DDL concorrentes. As opções adicionais incluem rotinas, triggers e eventos, e evitam a exportação de tablespaces. A senha foi fornecida interativamente e não é reproduzida neste relatório.

O backup foi gerado com sucesso, mas sua restauração não foi testada.

# 8. EXECUÇÃO DAS MIGRATIONS

``` cmd
php spark migrate
```

Saída observada:

``` text
CodeIgniter v4.7.5 Command Line Tool - Server Time: 2026-11-24 14:13:37 UTC+00:00

Running all new migrations...
        Running: (App) 2026-11-24-133922_App\Database\Migrations\CreateEstruturaFisica
        Running: (App) 2026-11-24-140002_App\Database\Migrations\CreateEventosEvidencias
        Running: (App) 2026-11-24-140003_App\Database\Migrations\CreateTelemetriaEColeta
        Running: (App) 2026-11-24-140004_App\Database\Migrations\CreateInteligenciaArtificial
        Running: (App) 2026-11-24-140005_App\Database\Migrations\CreateAutomacao
        Running: (App) 2026-11-24-140006_App\Database\Migrations\CreateCenasECenarios
        Running: (App) 2026-11-24-140007_App\Database\Migrations\CreateIntegracaoEComandos
        Running: (App) 2026-11-24-140008_App\Database\Migrations\CreateAudioEAuditoria
        Running: (App) 2026-11-24-140009_App\Database\Migrations\AddForeignKeysPendentes
Migrations complete.
```

As nove migrations foram concluídas sem erros reportados. Não foi necessária correção após a execução.

# 9. VALIDAÇÃO PÓS-MIGRATION

## 9.1. Histórico de versionamento

``` cmd
php spark migrate:status
```

  Migration                      Horário UTC em 24/11/2026     Batch
  ------------------------------ --------------------------- -------
  CreateEstruturaFisica          14:13:37                          2
  CreateEventosEvidencias        14:13:38                          2
  CreateTelemetriaEColeta        14:13:38                          2
  CreateInteligenciaArtificial   14:13:39                          2
  CreateAutomacao                14:13:39                          2
  CreateCenasECenarios           14:13:40                          2
  CreateIntegracaoEComandos      14:13:40                          2
  CreateAudioEAuditoria          14:13:40                          2
  AddForeignKeysPendentes        14:13:40                          2

As quatro migrations de infraestrutura permaneceram no Batch 1: `create_auth_tables`, `CreateSettingsTable`, `AddContextColumn` e `ConvertSqlsrvValueColumn`, registradas em 20/11/2026. O intervalo entre os registros da primeira e última migration de domínio foi de aproximadamente três segundos, não constituindo benchmark de desempenho.

## 9.2. Inventário efetivo

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT COUNT(*) AS total_tabelas FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'iot_edge_center' AND TABLE_TYPE = 'BASE TABLE';"
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SHOW TABLES;"
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT COUNT(*) AS total_usuarios FROM users;"
```

Resultados: 54 tabelas, sendo 45 de domínio e nove de infraestrutura, e três usuários existentes. A listagem SHOW TABLES confirmou todos os nomes do inventário da seção 5 e as nove tabelas da seção 4.1.

# 10. VALIDAÇÃO ESTRUTURAL

## 10.1. Engine e collation

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT ENGINE, TABLE_COLLATION, COUNT(*) AS quantidade FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'iot_edge_center' AND TABLE_TYPE = 'BASE TABLE' GROUP BY ENGINE, TABLE_COLLATION;"
```

  ENGINE   TABLE_COLLATION        quantidade
  -------- -------------------- ------------
  InnoDB   utf8mb4_general_ci             54

## 10.2. Restrições e índices

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT CONSTRAINT_TYPE, COUNT(*) AS quantidade FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'iot_edge_center' GROUP BY CONSTRAINT_TYPE;"
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT COUNT(DISTINCT TABLE_NAME, INDEX_NAME) AS total_indices FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = 'iot_edge_center';"
```

  Restrição       Domínio   Infraestrutura     Total
  ------------- --------- ---------------- ---------
  PRIMARY KEY          45                9        54
  UNIQUE               44                3        47
  FOREIGN KEY         113                4       117
  CHECK                18                0        18
  **Total**       **220**           **16**   **236**

Foram contabilizados 296 índices distintos por tabela e nome, evitando contar cada coluna de um índice composto separadamente. Esse número inclui índices associados a restrições e não deve ser somado ao total de restrições como se fossem objetos independentes.

Consulta de separação entre domínio e infraestrutura:

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT CASE WHEN TABLE_NAME IN ('users','auth_groups_users','auth_identities','auth_logins','auth_permissions_users','auth_remember_tokens','auth_token_logins','settings','migrations') THEN 'Infraestrutura' ELSE 'Dominio' END AS grupo, CONSTRAINT_TYPE, COUNT(*) AS quantidade FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'iot_edge_center' GROUP BY grupo, CONSTRAINT_TYPE ORDER BY grupo, CONSTRAINT_TYPE;"
```

## 10.3. Políticas referenciais

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT DELETE_RULE, UPDATE_RULE, COUNT(*) AS quantidade FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'iot_edge_center' GROUP BY DELETE_RULE, UPDATE_RULE;"
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT TABLE_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, DELETE_RULE, UPDATE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'iot_edge_center' AND (DELETE_RULE <> 'RESTRICT' OR UPDATE_RULE <> 'RESTRICT') ORDER BY TABLE_NAME, CONSTRAINT_NAME;"
```

  DELETE_RULE   UPDATE_RULE     Quantidade
  ------------- ------------- ------------
  RESTRICT      RESTRICT               109
  NO ACTION     NO ACTION                4
  CASCADE       NO ACTION                4

As quatro referências CASCADE pertencem exclusivamente às tabelas `auth_groups_users`, `auth_identities`, `auth_permissions_users` e `auth_remember_tokens`, todas referenciando `users`.

As quatro referências NO ACTION são `fk_anaia_artefato`, `fk_anaia_pipeline`, `fk_iia_artefato` e `fk_iia_pipeline`. As 113 chaves estrangeiras do domínio apresentam comportamento restritivo no InnoDB.

## 10.4. Chave estrangeira adicionada posteriormente

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'iot_edge_center' AND CONSTRAINT_NAME = 'fk_mi_comando';"
```

Resultado observado: `fk_mi_comando`, `mensagens_integracao.comando_id` referenciando `comandos.id`.

## 10.5. Restrições CHECK

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT TABLE_NAME, CONSTRAINT_NAME, ENFORCED FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'iot_edge_center' AND CONSTRAINT_TYPE = 'CHECK' ORDER BY TABLE_NAME, CONSTRAINT_NAME;"
```

  --------------------------------------------------------------------------------------------------------------------------------
  Tabela                                Restrição                          Finalidade                            ENFORCED
  ------------------------------------- ---------------------------------- ------------------------------------- -----------------
  `analises_ia`                         `chk_anaia_periodo`                Período da análise                    YES

  `analises_ia`                         `chk_anaia_referencia_exclusiva`   Referências mutuamente exclusivas     YES

  `cenario_dispositivos`                `chk_cend_estrategia`              Estratégia de aplicação               YES

  `comandos`                            `chk_comandos_expiracao`           Expiração do comando                  YES

  `execucoes_acoes`                     `chk_exa_periodo`                  Período da ação                       YES

  `execucoes_acoes`                     `chk_exa_tentativa`                Número de tentativas                  YES

  `execucoes_automacao`                 `chk_ea_periodo`                   Período da automação                  YES

  `execucoes_cenarios`                  `chk_excen_periodo`                Período do cenário                    YES

  `execucoes_cenas`                     `chk_excena_periodo`               Período da cena                       YES

  `historico_localizacao_dispositivo`   `chk_hld_vigencia`                 Vigência da localização               YES

  `implantacoes_ia`                     `chk_iia_referencia_exclusiva`     Exatamente uma referência de versão   YES

  `implantacoes_ia`                     `chk_iia_vigencia`                 Vigência da implantação               YES

  `leituras`                            `chk_leituras_valor`               Existência de valor                   YES

  `locais`                              `chk_locais_latitude`              Latitude válida                       YES

  `locais`                              `chk_locais_longitude`             Longitude válida                      YES

  `regras_automacao`                    `chk_ra_vigencia`                  Vigência da regra                     YES

  `sessao_dispositivos`                 `chk_sd_periodo`                   Período de participação               YES

  `sessoes_coleta`                      `chk_sc_periodo`                   Período da sessão                     YES
  --------------------------------------------------------------------------------------------------------------------------------

Todas as 18 restrições estão habilitadas. A consulta comprova sua presença e configuração, mas não testa individualmente todas as expressões.

# 11. TESTES PRÁTICOS DE INTEGRIDADE

## 11.1. Metodologia

Foi selecionada a tabela `locais` para testar inserções válidas, unicidade, coordenadas geográficas, relacionamento hierárquico e reversão transacional. O script utiliza um código temporário gerado por UUID e executa as operações em uma única transação.

``` cmd
mkdir testes_banco
notepad testes_banco\validar_integridade.sql
mysql -h localhost -P 3306 -u root -p --force --verbose -D iot_edge_center < testes_banco\validar_integridade.sql
```

A opção `--force` permite continuar após as violações esperadas, chegando ao ROLLBACK. O resultado deve ser avaliado pela saída de cada teste, não apenas pelo encerramento do cliente.

## 11.2. Script SQL (operações executadas)

Abaixo estão as operações do script, com os comentários e mensagens de etapa condensados para documentação.

``` sql
USE iot_edge_center;
SELECT 'INICIO DOS TESTES DE INTEGRIDADE' AS etapa;
START TRANSACTION;
SET @codigo_teste = CONCAT('TESTE-', UUID());

-- Teste 01: inserção válida
INSERT INTO locais (codigo, nome, tipo, latitude, longitude, ativo)
VALUES (@codigo_teste, 'Local temporario de teste', 'edificio', -20.2000000, -40.3000000, 1);
SET @local_teste = LAST_INSERT_ID();
SELECT id, codigo, nome, latitude, longitude FROM locais WHERE id = @local_teste;

-- Teste 02: código duplicado
INSERT INTO locais (codigo, nome, tipo)
VALUES (@codigo_teste, 'Local duplicado', 'edificio');

-- Testes 03 e 04: coordenadas inválidas
UPDATE locais SET latitude = 95.0000000 WHERE id = @local_teste;
UPDATE locais SET longitude = 190.0000000 WHERE id = @local_teste;

-- Teste 05: relacionamento válido
INSERT INTO locais (local_pai_id, codigo, nome, tipo)
VALUES (@local_teste, CONCAT(@codigo_teste, '-FILHO'), 'Sala temporaria de teste', 'sala');
SET @local_filho = LAST_INSERT_ID();
SELECT id, local_pai_id, codigo, nome FROM locais WHERE id = @local_filho;

-- Teste 06: exclusão restrita
DELETE FROM locais WHERE id = @local_teste;

-- Teste 07: referência inexistente
UPDATE locais SET local_pai_id = 0 WHERE id = @local_filho;

-- Teste 08: preservação dos registros
SELECT id, local_pai_id, codigo, nome, latitude, longitude
FROM locais WHERE id IN (@local_teste, @local_filho);

-- Teste 09: reversão
ROLLBACK;
SELECT COUNT(*) AS registros_de_teste_restantes
FROM locais WHERE codigo IN (@codigo_teste, CONCAT(@codigo_teste, '-FILHO'));
SELECT 'FIM DOS TESTES DE INTEGRIDADE' AS etapa;
```

## 11.3. Resultados experimentais

  Teste                           Esperado            Observado          Resultado
  ------------------------------- ------------------- ------------------ -----------
  01 --- Inserção válida          Sucesso             ID 1 criado        Aprovado
  02 --- Código duplicado         1062                ERROR 1062         Aprovado
  03 --- Latitude inválida        3819                ERROR 3819         Aprovado
  04 --- Longitude inválida       3819                ERROR 3819         Aprovado
  05 --- Relacionamento válido    Sucesso             ID 3, pai 1        Aprovado
  06 --- Exclusão restrita        1451                ERROR 1451         Aprovado
  07 --- Referência inexistente   1452                ERROR 1452         Aprovado
  08 --- Preservação              Valores originais   Valores mantidos   Aprovado
  09 --- Rollback                 Zero registros      0                  Aprovado

**Taxa de aprovação dos testes selecionados: 9/9 (100%).**

## 11.4. Evidências do terminal

Trechos relevantes da execução, com o código UUID abreviado na mensagem de duplicidade:

``` text
ERROR 1062 (23000) at line 50: Duplicate entry 'TESTE-...' for key 'locais.uq_locais_codigo'
ERROR 3819 (HY000) at line 67: Check constraint 'chk_locais_latitude' is violated.
ERROR 3819 (HY000) at line 78: Check constraint 'chk_locais_longitude' is violated.
ERROR 1451 (23000) at line 114: Cannot delete or update a parent row: a foreign key constraint fails (`iot_edge_center`.`locais`, CONSTRAINT `fk_locais_local_pai` FOREIGN KEY (`local_pai_id`) REFERENCES `locais` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT)
ERROR 1452 (23000) at line 124: Cannot add or update a child row: a foreign key constraint fails (`iot_edge_center`.`locais`, CONSTRAINT `fk_locais_local_pai` FOREIGN KEY (`local_pai_id`) REFERENCES `locais` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT)
```

Antes do ROLLBACK, o registro pai (ID 1) manteve latitude -20.2000000 e longitude -40.3000000. O filho (ID 3) manteve `local_pai_id = 1`, com coordenadas nulas. Os erros impediram as alterações inválidas sem eliminar os registros válidos da transação.

Resultado final:

``` text
registros_de_teste_restantes
0

etapa
FIM DOS TESTES DE INTEGRIDADE
```

A lacuna entre os IDs 1 e 3 é compatível com o consumo de identificadores AUTO_INCREMENT em tentativas de inserção rejeitadas. O ROLLBACK não garante a restauração do contador. Não foi necessário redefini-lo nem executar limpeza adicional.

# 12. PRESERVAÇÃO DA AUTENTICAÇÃO

``` cmd
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT u.id, u.username, u.active, g.group AS perfil FROM users u LEFT JOIN auth_groups_users g ON g.user_id = u.id ORDER BY u.id;"
mysql -h localhost -P 3306 -u root -p -D iot_edge_center -e "SELECT user_id, type AS tipo_identidade FROM auth_identities ORDER BY user_id;"
```

    ID Username         Active Perfil          Identidade
  ---- -------------- -------- --------------- ----------------
     1 admin                 0 administrador   email_password
     2 operador              0 operador        email_password
     3 visualizador          0 visualizador    email_password

As consultas confirmaram a presença dos três usuários, grupos e identidades. O responsável informou que os três perfis já realizavam login normalmente na configuração existente. Por esse motivo, não foi realizada alteração no campo `active`.

O procedimento indicado para teste funcional posterior foi iniciar o servidor com `php spark serve`, acessar `http://localhost:8080/` e realizar login e logout de cada perfil. Houve confirmação genérica de continuidade na conversa, mas não foram apresentados resultados individuais ou logs dos três novos logins. Assim, a preservação estrutural está comprovada; a validação funcional pós-migration não é contabilizada como evidência experimental independente neste relatório.

Credenciais, senhas e hashes não são incluídos na documentação.

# 13. RESULTADOS CONSOLIDADOS

  Indicador                                        Resultado
  ---------------------------------------------- -----------
  Migrations de domínio executadas                       9/9
  Migrations de infraestrutura registradas                 4
  Tabelas de domínio                                   45/45
  Tabelas de infraestrutura                              9/9
  Total de tabelas                                        54
  Tabelas InnoDB / utf8mb4_general_ci                  54/54
  Chaves primárias                                        54
  Restrições UNIQUE                                       47
  Chaves estrangeiras                                    117
  CHECK habilitados                                    18/18
  Restrições de domínio                                  220
  Restrições de infraestrutura                            16
  Total de restrições                                    236
  Índices                                                296
  Usuários existentes                                      3
  Testes representativos aprovados                       9/9
  Registros temporários restantes                          0
  Erros reportados na aplicação das migrations             0
  Correções necessárias após aplicação                     0

As tabelas de domínio correspondem a 83,33% das tabelas existentes. As restrições de domínio correspondem a 93,22% das restrições contabilizadas. Esses percentuais descrevem a composição da estrutura, não a cobertura funcional da plataforma.

# 14. LIMITAÇÕES E ESCOPO DA VALIDAÇÃO

-   O backup foi criado, mas não submetido a restauração experimental.
-   Os métodos down() não foram executados. O ROLLBACK dos testes DML não equivale à reversão das migrations DDL; operações DDL do MySQL podem realizar commits implícitos.
-   Os nove testes são representativos da tabela locais e não cobrem individualmente as 220 restrições de domínio.
-   A presença dos índices não comprova desempenho em carga real. Não foram realizados benchmarks ou análises de planos de execução.
-   A presença de usuários, grupos e identidades não substitui testes funcionais de autenticação e autorização.
-   Não foram testadas integrações MQTT, serviços de IA, TTS, comandos físicos ou automações reais nesta etapa.
-   A consistência semântica entre entidades, prevenção de ciclos, imutabilidade, autorização e regras de negócio dependerão da implementação e dos testes do backend.

# 15. PRÓXIMA ETAPA DO DESENVOLVIMENTO

Com a estrutura física estabelecida, o desenvolvimento poderá avançar para os models do CodeIgniter, validação de entradas, serviços de domínio, controllers, filtros de autorização e integração das views existentes com dados persistidos.

Recomenda-se iniciar pelo cadastro de locais, dispositivos e componentes, respeitando as dependências do modelo relacional. Em seguida, implementar telemetria, eventos, integrações e os demais grupos funcionais de forma incremental. Essa sequência é uma proposta de continuidade, não uma implementação já realizada.

# 16. CONCLUSÃO

A implementação física do banco de dados IoT-Edge Command Center foi concluída com sucesso no ambiente de desenvolvimento. As nove migrations foram aplicadas no Batch 2, materializando 45 tabelas de domínio e preservando a presença das nove tabelas de infraestrutura anteriormente existentes.

As consultas ao MySQL confirmaram 54 tabelas, 296 índices e 236 restrições, das quais 220 pertencem ao domínio. As 18 restrições CHECK estão habilitadas e a chave estrangeira adicionada na última migration foi confirmada. Os nove testes representativos apresentaram os resultados esperados, incluindo a rejeição de operações inválidas e a remoção dos registros temporários por ROLLBACK.

A etapa estabelece uma base relacional versionada para a implementação do backend. Os resultados demonstram a viabilidade da estrutura física no ambiente utilizado, sem extrapolar para desempenho, segurança funcional ou completude das regras de negócio ainda não testadas.

------------------------------------------------------------------------

**Base documental:** comandos, saídas do terminal e decisões de implementação registrados durante o desenvolvimento em 24/11/2026. Este relatório complementa a especificação física anterior e não substitui os arquivos originais das migrations.
