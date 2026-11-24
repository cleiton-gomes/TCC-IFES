# MAPA DE DEPENDÊNCIAS E PLANEJAMENTO DAS MIGRATIONS
## IoT-Edge Command Center

**Banco de dados:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4  
**Modelagem de referência:** Relatório 2 — Modelagem completa do banco de dados  
**Quantidade prevista:** 28 tabelas  
**Situação:** Planejamento técnico anterior à implementação das migrations

---

# 1. OBJETIVO

Este documento estabelece o mapa de dependências entre as 28 tabelas previstas na modelagem do banco de dados do IoT-Edge Command Center.

O objetivo é definir uma sequência segura para a implementação das migrations no CodeIgniter 4, respeitando as chaves estrangeiras, os relacionamentos e as dependências entre as entidades.

O Relatório 2 representa o refinamento e a consolidação da modelagem anteriormente documentada no Relatório 1. Portanto, esta etapa não tem como objetivo refazer a modelagem, mas preparar sua implementação técnica.

A estrutura será criada no banco existente `iot_edge_center`, sem necessidade de criar outro banco de dados.

# 2. PREMISSAS DA IMPLEMENTAÇÃO

A implementação seguirá estas premissas:

- Preservar as 28 tabelas previstas no Relatório 2.
- Manter os relacionamentos e as responsabilidades definidos na modelagem.
- Utilizar migrations do CodeIgniter 4 para versionar a estrutura do banco.
- Utilizar chaves estrangeiras para garantir a integridade referencial.
- Criar índices adequados às consultas e aos relacionamentos.
- Evitar dependências circulares entre tabelas.
- Preservar registros históricos, priorizando desativação em vez de exclusão física.
- Utilizar transações nas operações que envolvam alterações relacionadas em várias tabelas.
- Armazenar datas em UTC e realizar a conversão para o horário local na aplicação.
- Não inserir dispositivos, eventos ou leituras fictícias como dados reais.

# 3. RELAÇÃO DAS 28 TABELAS

A estrutura consolidada está organizada nos seguintes grupos:

| Grupo | Tabelas |
|---|---|
| Usuários e permissões | `usuarios`, `perfis`, `usuario_perfis` |
| Estrutura física | `locais` |
| Dispositivos e componentes | `dispositivos`, `componentes_dispositivo` |
| Telemetria e estado | `leituras`, `historico_status_dispositivo`, `versoes_configuracao`, `historico_estado_gemeo` |
| Eventos e associações | `eventos`, `evento_dispositivos`, `evento_componentes`, `evento_leituras`, `evento_locais` |
| Arquivos e integrações | `anexos`, `mensagens_integracao` |
| Comandos e áudio | `comandos`, `historico_comandos`, `mensagens_audio` |
| Auditoria | `auditoria` |
| Automação | `regras_automacao`, `acoes_automacao`, `execucoes_automacao`, `execucoes_acoes`, `execucao_destinatarios` |
| Coleta e retenção | `sessoes_coleta` |
| Histórico espacial | `historico_localizacao_dispositivo` |

# 4. MAPA DE DEPENDÊNCIAS

A sequência de criação deve respeitar os relacionamentos entre as entidades.

## 4.1. Usuários e permissões

```text
perfis
   |
   |
usuarios
   |
   |
usuario_perfis
```

Dependências:

- `usuarios`: entidade principal dos usuários.
- `perfis`: entidade que representa os perfis de acesso.
- `usuario_perfis`: tabela associativa entre usuários e perfis.

A tabela `usuario_perfis` depende de `usuarios` e `perfis`.

A relação permite que um usuário possua mais de um perfil, conforme definido na modelagem.

## 4.2. Estrutura física e dispositivos

```text
locais
   |
   |
dispositivos
   |
   |
componentes_dispositivo
```

Dependências:

- `locais`: estrutura hierárquica dos ambientes físicos.
- `dispositivos`: equipamentos cadastrados na plataforma, com referência à localização atual.
- `componentes_dispositivo`: componentes pertencentes a um dispositivo.

A tabela `dispositivos` depende de `locais`, considerando que a localização atual será referenciada por uma chave estrangeira.

A tabela `componentes_dispositivo` depende de `dispositivos`.

A hierarquia de `locais` utiliza uma referência à própria tabela por meio de `local_pai_id`.

## 4.3. Telemetria, configuração e estado do gêmeo digital

```text
dispositivos
   |
   |---- componentes_dispositivo
   |
   |---- leituras
   |
   |---- historico_status_dispositivo
   |
   |---- versoes_configuracao
   |
   |---- historico_estado_gemeo
```

Dependências:

- `leituras`: depende de `dispositivos` e pode depender de `componentes_dispositivo`.
- `historico_status_dispositivo`: depende de `dispositivos`.
- `versoes_configuracao`: depende de `dispositivos` e pode depender de `componentes_dispositivo`.
- `historico_estado_gemeo`: depende de `dispositivos` e pode relacionar componentes, eventos e comandos, conforme as referências efetivamente previstas em seu DDL.

Essas tabelas preservam informações com finalidades distintas. Leituras de sensores não substituem o histórico de estados operacionais, configurações ou snapshots do gêmeo digital.

## 4.4. Eventos e associações

```text
dispositivos
   |
   |
eventos
   |
   |---- evento_dispositivos
   |
   |---- evento_componentes
   |
   |---- evento_leituras
   |
   |---- evento_locais
```

Dependências:

- `eventos`: pode referenciar um dispositivo de origem e também outro evento como evento pai.
- `evento_dispositivos`: associa eventos a dispositivos.
- `evento_componentes`: associa eventos a componentes.
- `evento_leituras`: associa eventos às leituras relacionadas.
- `evento_locais`: associa eventos aos locais envolvidos.

As quatro tabelas associativas dependem de `eventos` e das respectivas entidades relacionadas.

A associação `evento_locais` permite representar situações nas quais um evento envolve mais de um ambiente físico.

## 4.5. Arquivos e integração

```text
eventos
   |
   |---- anexos

dispositivos / eventos
   |
   |---- mensagens_integracao
```

Dependências:

- `anexos`: registra metadados de arquivos relacionados aos eventos, conforme os vínculos definidos no modelo.
- `mensagens_integracao`: registra mensagens recebidas e enviadas pelas integrações, incluindo informações de processamento, correlação e deduplicação.

Os arquivos físicos permanecerão fora do MySQL. O banco armazenará seus metadados, identificadores, hashes e caminhos ou URIs.

`mensagens_integracao` não substituirá as tabelas especializadas de leituras, eventos e comandos.

## 4.6. Regras e execução de automações

```text
regras_automacao
   |
   |---- acoes_automacao
   |
   |---- execucoes_automacao
              |
              |
         execucoes_acoes
              |
              |
       execucao_destinatarios
```

Dependências:

- `regras_automacao`: representa as regras e condições de automação.
- `acoes_automacao`: registra as ações associadas a uma regra.
- `execucoes_automacao`: registra cada execução de uma regra.
- `execucoes_acoes`: registra a execução individual de cada ação.
- `execucao_destinatarios`: registra os dispositivos selecionados para receber uma ação.

A separação permite que uma regra possua várias ações, que cada ação tenha seu próprio resultado e que uma ação possa envolver vários dispositivos destinatários.

## 4.7. Comandos e histórico

```text
execucao_destinatarios
   |
   |
comandos
   |
   |
historico_comandos
```

Dependências:

- `comandos`: registra solicitações e comandos destinados aos dispositivos.
- `historico_comandos`: registra as transições de estado de cada comando.

A relação entre `comandos` e `execucao_destinatarios` será implementada em uma única direção, caso seja mantida a referência `comandos.execucao_destinatario_id`.

Dessa forma, o destinatário poderá existir antes da geração do comando, evitando uma dependência circular entre as duas tabelas.

## 4.8. Áudio, coleta e histórico de localização

```text
mensagens_audio

eventos
   |
   |---- sessoes_coleta
              |
              |
           leituras

dispositivos
   |
   |---- historico_localizacao_dispositivo
```

Dependências:

- `mensagens_audio`: registra mensagens de áudio e metadados de síntese e reprodução, podendo ser relacionada às execuções e aos comandos conforme os vínculos definidos no modelo.
- `sessoes_coleta`: depende dos eventos e dispositivos relacionados e pode referenciar componentes.
- `historico_localizacao_dispositivo`: depende de `dispositivos` e `locais`.

A tabela `sessoes_coleta` será responsável por registrar o ciclo de coleta associado a eventos, incluindo as políticas de preservação das amostras.

A tabela `historico_localizacao_dispositivo` preservará as mudanças de localização dos equipamentos sem alterar sua identidade ou seu código estável.

## 4.9. Auditoria

A tabela `auditoria` possui finalidade transversal.

Ela registra operações realizadas por usuários, dispositivos ou processos da aplicação.

Como a modelagem prevê identificação polimórfica da entidade auditada, por meio de informações como tipo de entidade e identificador, nem todos esses vínculos serão representados por chaves estrangeiras convencionais.

A tabela poderá ser criada sem depender da existência de todas as entidades auditadas, desde que os vínculos polimórficos sejam tratados pela aplicação.

# 5. ORDEM SUGERIDA DAS MIGRATIONS

A sequência abaixo considera as dependências descritas na modelagem e procura evitar referências a tabelas que ainda não existem.

| Ordem | Tabela | Dependências principais |
|---:|---|---|
| 01 | `perfis` | Nenhuma |
| 02 | `usuarios` | Nenhuma |
| 03 | `locais` | Referência hierárquica à própria tabela |
| 04 | `usuario_perfis` | `usuarios`, `perfis` |
| 05 | `dispositivos` | `locais` |
| 06 | `componentes_dispositivo` | `dispositivos` |
| 07 | `eventos` | `dispositivos`, autorreferência de evento pai |
| 08 | `leituras` | `dispositivos`, `componentes_dispositivo` |
| 09 | `historico_status_dispositivo` | `dispositivos` |
| 10 | `versoes_configuracao` | `dispositivos`, `componentes_dispositivo` |
| 11 | `historico_localizacao_dispositivo` | `dispositivos`, `locais` |
| 12 | `evento_dispositivos` | `eventos`, `dispositivos` |
| 13 | `evento_componentes` | `eventos`, `componentes_dispositivo` |
| 14 | `evento_leituras` | `eventos`, `leituras` |
| 15 | `evento_locais` | `eventos`, `locais` |
| 16 | `anexos` | `eventos`, conforme os vínculos definidos |
| 17 | `mensagens_integracao` | Entidades relacionadas, conforme as FKs definidas |
| 18 | `regras_automacao` | Entidades de referência previstas no modelo |
| 19 | `acoes_automacao` | `regras_automacao` |
| 20 | `execucoes_automacao` | `regras_automacao`, `eventos` |
| 21 | `execucoes_acoes` | `execucoes_automacao`, `acoes_automacao` |
| 22 | `execucao_destinatarios` | `execucoes_acoes`, `dispositivos`, `locais` |
| 23 | `comandos` | `dispositivos`, destinatário da execução, usuário quando aplicável |
| 24 | `historico_comandos` | `comandos` |
| 25 | `mensagens_audio` | Entidades de execução e integração relacionadas, conforme as FKs definidas |
| 26 | `sessoes_coleta` | `eventos`, `dispositivos`, componentes quando aplicável |
| 27 | `historico_estado_gemeo` | `dispositivos`, componentes, eventos e comandos quando aplicável |
| 28 | `auditoria` | Sem dependência obrigatória de FKs polimórficas |

**Observação:** esta é uma ordem preliminar de implementação. A ordem definitiva será fechada após a conferência dos campos e das chaves estrangeiras de cada tabela. Referências opcionais ou dependências adicionais podem exigir pequenos ajustes.

# 6. PONTOS TÉCNICOS QUE EXIGEM ATENÇÃO

## 6.1. Dependência entre comandos e destinatários

Não será criada uma dependência circular entre `comandos` e `execucao_destinatarios`.

A proposta é manter a referência do comando para o destinatário, permitindo que um destinatário tenha mais de um comando caso o fluxo operacional exija isso.

A cardinalidade e a obrigatoriedade desse vínculo deverão ser confirmadas no DDL.

## 6.2. Índice único em versões de configuração

A combinação de `dispositivo_id`, `componente_id` e `versao` exige cuidado quando `componente_id` aceita `NULL`.

No MySQL, um índice `UNIQUE` não impede necessariamente várias linhas com `NULL` nessa coluna.

Será necessário implementar uma estratégia que também garanta unicidade das versões de configuração gerais do dispositivo, sem componente associado.

Uma alternativa é utilizar uma coluna gerada que normalize a ausência de componente para um valor reservado, como `0`, e criar o índice único sobre a combinação normalizada.

Essa estratégia depende de garantir que os identificadores reais dos componentes sejam sempre positivos.

## 6.3. Hierarquia dos locais

A chave estrangeira de `locais.local_pai_id` garante que o local pai exista, mas não impede ciclos na hierarquia.

A aplicação deverá validar mudanças de hierarquia por meio de consulta recursiva, impedindo que um local seja associado a si mesmo ou a um de seus descendentes.

A alteração deverá ocorrer em transação.

## 6.4. Histórico de localização

Uma mudança de localização deverá executar, de forma transacional:

1. Encerrar o registro anterior do histórico de localização.
2. Criar um novo registro com a localização de destino.
3. Atualizar `dispositivos.local_id`.
4. Registrar a operação na auditoria.

Também deverá ser definida uma restrição lógica para impedir períodos de localização conflitantes para o mesmo dispositivo.

## 6.5. Retenção de leituras

A política de retenção deverá respeitar os vínculos de `leituras` com eventos e sessões de coleta.

A janela normal de 100 leituras recentes não poderá remover amostras protegidas por eventos ou sessões ainda em andamento.

O processo de retenção deverá considerar:

- 100 leituras anteriores ao evento;
- todas as leituras durante o evento;
- 100 leituras posteriores ao encerramento;
- preservação do conjunto histórico relacionado ao evento.

A contagem de 100 leituras representa quantidade de amostras, não uma duração fixa.

## 6.6. Idempotência e comandos

O processamento de mensagens MQTT deverá possuir mecanismo de deduplicação.

A qualidade de serviço MQTT, isoladamente, não garante que uma mensagem ou ação física seja processada apenas uma vez.

O backend deverá utilizar identificadores de mensagem, chaves de idempotência e controle de estado para impedir repetições indevidas.

## 6.7. Histórico do gêmeo digital

O histórico do gêmeo digital deverá distinguir:

- comando solicitado;
- comando enviado;
- comando recebido;
- execução informada;
- estado físico confirmado.

A solicitação de um comando não deverá ser interpretada como confirmação de que o dispositivo executou a ação.

A reprodução histórica será somente de consulta e nunca deverá reenviar comandos aos dispositivos.

# 7. ESTRATÉGIA DE IMPLEMENTAÇÃO NO CODEIGNITER 4

As tabelas serão criadas por migrations independentes ou por grupos coerentes de migrations, mantendo uma sequência rastreável.

A implementação deverá observar:

1. Definir tipos, tamanhos e nulabilidade das colunas.
2. Definir chaves primárias e estrangeiras.
3. Definir índices de busca e índices únicos.
4. Definir regras de atualização e exclusão das referências.
5. Implementar autorreferências hierárquicas.
6. Resolver dependências que precisem ser adicionadas posteriormente.
7. Testar as migrations em ambiente controlado.
8. Conferir a estrutura efetivamente criada no MySQL.

Quando uma dependência circular ou tardia for inevitável, a tabela poderá ser criada primeiro e a chave estrangeira adicionada em uma migration posterior.

As migrations deverão possuir métodos `up()` e `down()` coerentes, considerando as dependências na ordem inversa durante a reversão.

# 8. VALIDAÇÕES ANTES DA CRIAÇÃO DAS TABELAS

Antes de executar as migrations, será necessário conferir:

- [ ] Os 28 nomes de tabelas e suas responsabilidades.
- [ ] Os campos e respectivos tipos de dados.
- [ ] A nulabilidade das referências opcionais.
- [ ] As cardinalidades dos relacionamentos.
- [ ] As chaves primárias.
- [ ] As chaves estrangeiras.
- [ ] Os índices simples e compostos.
- [ ] Os índices únicos e a questão dos valores `NULL`.
- [ ] As regras de atualização e exclusão.
- [ ] As dependências circulares.
- [ ] As colunas JSON e suas validações na aplicação.
- [ ] Os campos de data e horário.
- [ ] As regras de retenção e preservação histórica.
- [ ] Os vínculos entre comandos, destinatários e execuções.

# 9. ESTADO ATUAL E PRÓXIMA ETAPA

Até o momento, foram confirmados:

- MySQL Server 8.0 instalado no notebook.
- Serviço `MySQL80` em execução.
- Banco `iot_edge_center` existente.
- CodeIgniter 4 configurado para utilizar o banco por meio do driver `MySQLi`.
- Consulta `SHOW TABLES` sem retorno de tabelas.

Portanto, o banco está disponível para receber a estrutura planejada, mas ainda não foi iniciada a criação das 28 tabelas.

**Próxima etapa:** realizar a conferência técnica tabela por tabela, definindo os campos, tipos, nulabilidade, chaves primárias, chaves estrangeiras e índices. Após essa conferência, será elaborado o conjunto de migrations do CodeIgniter 4.

Nenhuma tabela deverá ser criada, alterada ou removida antes da conclusão dessa definição.

---

# 10. CONSIDERAÇÕES FINAIS

O mapa de dependências estabelece uma sequência inicial para implementar a modelagem consolidada do IoT-Edge Command Center.

A organização proposta procura preservar a separação entre dispositivos, componentes, leituras, eventos, automações, comandos, integrações e históricos.

O objetivo é implementar uma estrutura relacional consistente, preparada para receber dados reais da infraestrutura IoT, manter rastreabilidade das operações e permitir a evolução gradual das funcionalidades da plataforma.

A modelagem do Relatório 2 permanece como referência. Os ajustes identificados neste planejamento são cuidados técnicos necessários para sua implementação no MySQL e no CodeIgniter 4, e não uma reformulação da arquitetura.