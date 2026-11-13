# Especificação do Front-end — IoT-Edge Command Center

**Projeto:** IoT-Edge Command Center  
**Stack prevista:** CodeIgniter 4 + PHP + Bootstrap 5 + MySQL  
**Banco:** `iot_edge_center`  
**Finalidade deste documento:** orientar a conclusão das páginas do front-end com dados fictícios coerentes, antes da criação das tabelas, migrations, models, controllers de persistência e integração real.

> **Estado do projeto:** este documento é uma especificação de interface e de relacionamento com a proposta de banco. As telas devem continuar em modo demonstrativo enquanto os dados forem fictícios. Não considerar comandos, conectividade, reprodução de áudio, eventos ou estados físicos como reais até que exista integração implementada e confirmada.

---

## 1. Objetivo e estratégia de desenvolvimento

A estratégia será terminar primeiro as páginas do sistema usando dados fictícios, revisar navegação, formulários, tabelas, filtros, abas e estados visuais e, somente depois, construir a estrutura física do banco.

A proposta atual mantém as páginas existentes e cria apenas **três novas áreas principais**:

1. **Locais** — nova página `locais.php`.
2. **Automações e operações** — nova página `automacoes.php` ou `operacoes.php`, reunindo regras, ações, execuções, comandos e mensagens de áudio em abas.
3. **Histórico e auditoria** — nova página `historico.php`, reunindo consultas históricas transversais.

As demais funcionalidades devem ser incorporadas às páginas que já existem, sem criar uma página independente para cada tabela.

### 1.1 Páginas existentes que serão aproveitadas

| View atual | Nome recomendado no menu | Diretriz |
|---|---|---|
| `main.php` | Layout compartilhado | Manter como base visual comum, com navegação, identidade da aplicação e área de conteúdo. Não é uma página de cadastro. |
| `login.php` | Login | Finalizar visualmente agora; implementar autenticação real depois da decisão entre CodeIgniter Shield e autenticação própria. |
| `dashboard.php` | Dashboard | Manter a visão geral e substituir gradualmente os números fictícios por consultas reais. |
| `embarcados.php` | Dispositivos | Manter o arquivo e, inicialmente, as rotas para não quebrar a navegação; mudar o nome apresentado na interface. |
| `videomonitoramento.php` | Videomonitoramento e eventos | Integrar a visualização de vídeo com a listagem e os detalhes das ocorrências. |
| `diagnostico.php` | Diagnóstico | Reunir saúde, conectividade, integrações e sessões de coleta. |
| `gemeo_digital.php` | Gêmeo digital | Manter visualização espacial e acrescentar timeline/reprodução histórica somente de leitura. |

### 1.2 Novas páginas sugeridas

| View nova | Finalidade | Por que vale ser separada |
|---|---|---|
| `locais.php` | Gerenciar a hierarquia física dos locais | Os locais são entidades próprias e são referenciados por dispositivos, eventos e histórico de localização. |
| `automacoes.php` ou `operacoes.php` | Regras, ações, execuções, comandos e mensagens de áudio | São partes de um mesmo fluxo operacional e cabem em abas sem misturar seus registros no banco. |
| `historico.php` | Consultar trilhas históricas e auditoria | Permite pesquisas transversais por período, usuário, dispositivo, evento, local e resultado. |

**Não é necessário criar agora páginas exclusivas para componentes, execuções, comandos, áudio, auditoria, versões de configuração, sessões de coleta ou histórico de localização.** Essas funções serão acessadas dentro das páginas acima, em abas, painéis de detalhes, modais ou filtros.

---

## 2. Princípios obrigatórios da interface

### 2.1 Dispositivo, componente e local são conceitos diferentes

- **Dispositivo:** equipamento completo cadastrado no sistema, por exemplo, estação ESP32-S3, câmera IP, drone ou equipamento de áudio.
- **Componente:** parte de um dispositivo, por exemplo, sensor MQ-4, controlador ESP32-S3, módulo RA-01S/LoRa, relé ou módulo de áudio.
- **Local:** espaço físico, por exemplo, prédio, andar, zona, sala, corredor ou área externa.

Um dispositivo pode ter vários componentes. Um local pode abrigar vários dispositivos. Cada dispositivo tem um local atual e pode ter vários registros de localização ao longo do tempo.

A página Dispositivos não deve oferecer um seletor genérico que grave um local como se fosse um dispositivo. Ela deve ter um campo `local_id` que selecione um local previamente cadastrado. A página Locais pode oferecer um botão **“Adicionar dispositivo neste local”**, que abre o cadastro de dispositivos com o local já selecionado.

### 2.2 Uma página pode ter abas sem fundir as tabelas

A interface pode agrupar funcionalidades relacionadas; o banco continua normalizado. Por exemplo, a página Automações e operações pode ter abas para Regras, Execuções, Comandos e Áudio, mas cada registro continua sendo armazenado em sua tabela apropriada.

### 2.3 MQTT não deve ser substituído por WebSocket

- **MQTT:** comunicação de mensageria com os dispositivos — telemetria, comandos, confirmações e mensagens de integração.
- **WebSocket:** opcional para atualizar a interface em tempo real, se essa camada for implementada.
- O navegador não deve publicar comandos MQTT diretamente com credenciais privilegiadas. O backend valida a permissão, grava o pedido, publica a mensagem e acompanha a confirmação.

### 2.4 Estados demonstrativos devem estar identificados

Enquanto as telas usam dados fictícios:

- Exibir uma indicação consistente, como **“Modo demonstração — dados fictícios”**.
- Não afirmar que um dispositivo está realmente conectado ou que um comando foi fisicamente executado.
- Diferenciar “solicitado”, “enviado”, “confirmado”, “executado”, “falhou”, “expirado” e “desconhecido” conforme os estados que forem definidos.
- Para RSSI e SNR, exibir qualidade do enlace; não converter esses valores em distância estimada sem método validado.
- Uma leitura ADC do MQ-4 não equivale diretamente a ppm sem calibração adequada.
- O estado “reprodução confirmada” só deve ser exibido com confirmação do dispositivo ou evidência definida no protocolo; o envio de um áudio não prova que alguém o ouviu.

### 2.5 Identificadores estáveis

Usar códigos estáveis e independentes do local físico, por exemplo `EMB-GAS-001`, `CAM-001` ou `AUD-001`. O tópico MQTT canônico deve ser associado ao identificador estável do dispositivo, e não ao nome da sala em que está instalado. Uma mudança de local não deve exigir renomear a identidade lógica do equipamento.

---

## 3. Arquitetura sugerida do menu

Ordem recomendada do menu principal:

1. Dashboard
2. Dispositivos
3. Locais
4. Videomonitoramento e eventos
5. Automações e operações
6. Diagnóstico
7. Gêmeo digital
8. Histórico e auditoria

Login e layout compartilhado ficam fora da lista de módulos operacionais.

O menu deve continuar simples. Funções secundárias — como componentes, versões de configuração, histórico de comandos e sessões de coleta — devem aparecer no contexto em que fazem sentido, e não como entradas de menu independentes.

---

## 4. Especificação por página

## 4.1 `login.php` — Login

**Objetivo:** fornecer a entrada ao sistema e, futuramente, aplicar autenticação e autorização reais.

### Elementos de interface

- Campos de e-mail/usuário e senha.
- Ação de entrar e apresentação de erros de validação.
- Indicação de ambiente de demonstração enquanto a autenticação for simulada.
- Depois da autenticação real, apresentação do usuário e do perfil no layout compartilhado.

### Tabelas relacionadas

- `usuarios`: identidade, e-mail, hash da senha se a autenticação for própria, estado da conta e último acesso.
- `perfis`: papéis de autorização, como administrador, operador e visualizador.
- `usuario_perfis`: associação entre usuários e perfis.
- `auditoria`: tentativas de login e ações relevantes, caso essa política seja implementada.

### Observação

Antes de criar as migrations, decidir entre CodeIgniter Shield e autenticação própria. Se Shield for adotado, não duplicar sem necessidade o mecanismo de usuários e senhas em `usuarios`. A compatibilidade do modelo de perfis com a solução escolhida deve ser revisada antes da implementação.

---

## 4.2 `main.php` — Layout compartilhado

**Objetivo:** fornecer estrutura visual consistente a todas as views.

### Elementos de interface

- Navbar/menu com os módulos definidos neste documento.
- Área para o título e conteúdo da view ativa.
- Identificação do usuário autenticado quando esse recurso existir.
- Aviso global de demonstração, enquanto a aplicação estiver usando dados fictícios.
- Indicadores de estado global somente se forem alimentados por dados reais; no modo de demonstração, devem ter rótulo explícito.

### Tabelas relacionadas

O layout não é proprietário de uma tabela de negócio. Ele pode exibir informações derivadas de `usuarios`, `perfis` e dados agregados de `dispositivos`, `eventos` ou mensagens de integração, mas as consultas devem pertencer aos controllers/serviços correspondentes.

---

## 4.3 `dashboard.php` — Dashboard

**Objetivo:** oferecer visão resumida do funcionamento do sistema, sem substituir as páginas de gestão e de detalhes.

### Elementos de interface

- Quantidade de dispositivos por estado operacional.
- Dispositivos com contato recente e dispositivos sem contato esperado.
- Eventos recentes por severidade e estado.
- Gráfico de telemetria (por exemplo, `gas_level`, RSSI e SNR) com período selecionável.
- Resumo de comandos e de execuções de automação.
- Alertas recentes e atalhos para abrir o dispositivo, local ou evento correspondente.
- Filtros de período e, quando viável, local.

### Tabelas relacionadas

- `dispositivos`: inventário e estado operacional resumido.
- `locais`: agrupamento e filtros por local.
- `leituras`: séries de telemetria e indicadores.
- `historico_status_dispositivo`: mudanças de estado e conectividade.
- `eventos`: ocorrências abertas, críticas e recentes.
- `regras_automacao`, `execucoes_automacao`, `execucoes_acoes` e `execucao_destinatarios`: resumo das automações e de seus resultados.
- `comandos` e `historico_comandos`: resumo do ciclo de vida dos comandos.
- `mensagens_integracao`: falhas ou rejeições recentes no processamento de mensagens.

### Regras

- Todo número deve ser calculado a partir de um conjunto claramente definido; evitar somar entidades distintas como se fossem a mesma coisa.
- Os gráficos fictícios devem usar dados coerentes com os dispositivos e eventos demonstrados nas outras páginas.
- Não mostrar um comando como executado apenas porque a aplicação o solicitou ou publicou.

---

## 4.4 `embarcados.php` — Dispositivos

**Nome no menu:** Dispositivos.  
**Arquivo:** manter `embarcados.php` inicialmente para não quebrar o projeto; uma eventual renomeação física pode ser feita depois, atualizando rotas e referências em conjunto.

**Objetivo:** cadastrar e administrar os equipamentos completos, sem transformar esta tela em um formulário genérico de locais, dispositivos e componentes.

### Área A — Listagem de dispositivos

Colunas recomendadas:

- Código estável.
- Nome.
- Categoria: embarcado, câmera, drone, equipamento de áudio etc.
- Local atual.
- Protocolo principal.
- Estado operacional.
- Último contato.
- Ações: visualizar, editar, abrir detalhes e desativar.

Filtros recomendados: texto/código, categoria, local, estado operacional e protocolo.

### Área B — Cadastro/edição

Campos que devem ser alinhados com `dispositivos`:

- `codigo` — obrigatório e único.
- `nome` — obrigatório.
- `descricao`.
- `categoria`.
- `modelo`.
- `fabricante`.
- `local_id` — seleção de local já cadastrado.
- `protocolo_principal`.
- `topico_mqtt`, quando aplicável.
- `identificador_externo`, quando aplicável.
- `status_operacional`.
- `configuracoes`, apresentados preferencialmente por formulário estruturado; evitar exigir JSON bruto de um usuário comum.

`ultimo_contato_em` tende a ser dado operacional atualizado pelo backend, e não um campo livre que o usuário modifica no cadastro.

### Área C — Detalhes do dispositivo

Usar página interna de detalhes, painel expansível, modal amplo ou rota própria. Não é obrigatório criar uma nova entrada de menu. Sugestão de abas:

1. **Resumo:** cadastro, local atual e estado.
2. **Componentes:** sensores, controladores, comunicação e atuadores.
3. **Configuração:** configuração atual e versões anteriores.
4. **Telemetria:** leituras recentes.
5. **Histórico:** mudanças de estado e mudanças de localização.
6. **Comandos:** solicitações e resultados, quando o perfil de acesso permitir.

### Tabelas relacionadas

- `dispositivos`: entidade principal da página.
- `locais`: seleção do local atual.
- `componentes_dispositivo`: componentes vinculados ao dispositivo.
- `versoes_configuracao`: versões imutáveis da configuração.
- `historico_status_dispositivo`: mudanças de estado.
- `historico_localizacao_dispositivo`: transferências entre locais ao longo do tempo.
- `leituras`: telemetria.
- `historico_estado_gemeo`: estados relevantes para a reconstrução do gêmeo digital.
- `comandos` e `historico_comandos`: histórico de atuação relacionado ao equipamento.

### Regra de interface

Componentes devem ser administrados dentro do contexto do dispositivo, por exemplo na aba **Componentes**, mas permanecem registros separados na tabela `componentes_dispositivo`. Não cadastrar sensor MQ-4, rádio LoRa ou relé como se fossem automaticamente três dispositivos independentes, a menos que tenham identidade e ciclo de vida próprios no sistema.

---

## 4.5 `locais.php` — Locais (**nova página**)

**Objetivo:** cadastrar e organizar a estrutura física do ambiente e permitir consultar os dispositivos existentes em cada local.

### Área A — Hierarquia

Tipos de local previstos:

- `edificio`
- `andar`
- `zona`
- `sala`
- `corredor`
- `area_externa`

A hierarquia deve permitir, por exemplo: Prédio A → Andar 1 → Zona de monitoramento → Sala de controle.

### Área B — Cadastro/edição

Campos previstos para a versão ampliada de `locais`:

- `codigo` — identificador único do local.
- `nome`.
- `descricao`.
- `tipo`.
- `local_pai_id` — local superior, quando aplicável.
- `latitude` e `longitude`, quando fizer sentido geográfico.
- `referencia` — referência física ou observações de localização.
- `ativo` — indica se o local continua disponível para uso.

A estrutura ampliada de `locais` deve ser consolidada antes das migrations: o modelo inicial de 23 tabelas tinha apenas campos mais simples, enquanto esta versão de trabalho inclui hierarquia e código estável.

### Área C — Dispositivos no local

- Listar os dispositivos cujo `local_id` atual corresponde ao local.
- Permitir abrir os detalhes do dispositivo.
- Oferecer o atalho **Adicionar dispositivo neste local**.
- Exibir histórico de dispositivos que já estiveram no local, usando `historico_localizacao_dispositivo`.
- Em filtros por prédio/andar/zona, o backend deverá resolver a hierarquia descendente para encontrar os equipamentos localizados nas áreas filhas.

### Tabelas relacionadas

- `locais`: cadastro e hierarquia.
- `dispositivos`: equipamentos atualmente vinculados a um local.
- `historico_localizacao_dispositivo`: histórico de transferências.
- `eventos` e `evento_locais`: ocorrências associadas a um ou mais locais.
- `auditoria`: alterações de cadastro de local, caso sejam auditadas.

### Regra importante

Uma ocorrência pode afetar mais de um local. Por isso, não usar somente um campo `local_id` em `eventos` como representação de todas as associações: na proposta de 28 tabelas, o relacionamento N:N será feito por `evento_locais`.

---

## 4.6 `videomonitoramento.php` — Videomonitoramento e eventos

**Objetivo:** reunir visualização de câmeras/análises e o acompanhamento de ocorrências. A página não deve ficar limitada à imagem de vídeo, pois uma detecção precisa estar ligada a um evento consultável.

### Área A — Monitoramento visual

- Grade de câmeras e fontes de vídeo.
- Estado da fonte com distinção entre real, simulado, indisponível e desconhecido.
- Visualização ou placeholder de vídeo/imagem.
- Se houver drone ou análise visual, mostrar claramente se o conteúdo é gravado, reproduzido, demonstrativo ou vindo de integração real.

### Área B — Lista de eventos

Colunas/itens:

- Código e título.
- Tipo.
- Data/hora do ocorrido.
- Severidade.
- Estado de atendimento.
- Origem.
- Local ou locais afetados.
- Dispositivos envolvidos.
- Indicador de teste (`is_teste`).

Filtros: intervalo de tempo, severidade, estado, tipo de evento, local, dispositivo, origem e ocorrência real/teste.

### Área C — Detalhes do evento

- Descrição e linha do tempo do evento.
- Locais afetados.
- Dispositivos, componentes e leituras associadas.
- Anexos de imagem, vídeo, áudio ou documentos.
- Comandos, sessões de coleta e execuções de automação relacionadas, quando houver.
- Estado do atendimento: aberto, em acompanhamento, normalizado, encerrado ou interrompido, conforme lista final aprovada.

### Tabelas relacionadas

- `eventos`: entidade principal da ocorrência.
- `evento_locais`: associação N:N entre eventos e locais.
- `evento_dispositivos`: dispositivos envolvidos e papel de cada um.
- `evento_componentes`: componentes relacionados.
- `evento_leituras`: leituras que dispararam ou ajudam a diagnosticar o evento.
- `anexos`: metadados e referências aos arquivos.
- `dispositivos`, `componentes_dispositivo`, `locais` e `leituras`: dados usados nos detalhes e filtros.
- `comandos`, `mensagens_audio`, `sessoes_coleta` e `execucoes_automacao`: relações operacionais com o evento, quando existentes.

### Armazenamento de evidências

Vídeos, imagens e arquivos grandes ficam fora do MySQL. A tabela `anexos` guarda caminho/URI protegido, nome, tipo, MIME, tamanho, hash e metadados. A interface não deve guardar binários grandes diretamente em colunas comuns do banco.

### Cuidados

- `eventos` registra uma ocorrência; `auditoria` registra ações e resultados de usuários/sistemas; mensagens de transporte ficam em `mensagens_integracao`.
- Uma detecção gerada por modelo de visão deve ser identificada como inferência do modelo, com metadados/evidências adequados. Não tratar inferência como verdade incontestável.
- Instruções para emergências devem vir de regras e procedimentos previamente aprovados, não ser inventadas dinamicamente.

---

## 4.7 `automacoes.php` ou `operacoes.php` — Automações e operações (**nova página**)

**Objetivo:** reunir funcionalidades de regras, ações, execuções, comandos e mensagens de áudio em uma página com abas. Apesar do agrupamento na interface, cada conceito continua tendo sua tabela e seu histórico.

### Aba 1 — Regras

Campos/elementos:

- Nome e descrição da regra.
- Tipo de gatilho: leitura, mudança de estado, comando ou tempo, conforme os tipos aprovados.
- Condições validadas.
- Prioridade.
- Ativa/inativa.
- Autor e datas de criação/alteração, quando disponíveis.
- Lista ordenada das ações da regra.

Tabelas: `regras_automacao`, `acoes_automacao`, `usuarios`.

**Decisão de modelagem:** a versão de 28 tabelas normaliza as ações em `acoes_automacao`. O campo JSON `acoes` da proposta anterior de `regras_automacao` deixa de ser a fonte de verdade e deve ser removido ou tratado apenas como legado de migração, nunca como duplicação independente das ações normalizadas.

### Aba 2 — Execuções

- Lista de execuções por regra, evento, período e estado.
- Estado geral da execução: iniciada, concluída, falhou, parcial ou cancelada, conforme lista final.
- Entrada avaliada e resultado resumido.
- Ações executadas na ordem definida.
- Erros, tentativas, horário de início e conclusão.
- Chave de idempotência ou identificador de correlação, quando definido pelo contrato da aplicação.

Tabelas: `execucoes_automacao`, `execucoes_acoes`, `execucao_destinatarios`, `regras_automacao`, `acoes_automacao` e `eventos`.

### Aba 3 — Comandos

- Código do comando.
- Dispositivo destinatário.
- Componente-alvo opcional.
- Tipo do comando e parâmetros apresentados de forma legível.
- Origem: usuário, automação, sistema ou dispositivo.
- Estado atual.
- Linha do tempo do comando: solicitado, enviado, confirmado, executado, falha, expiração ou cancelamento, conforme evidências existentes.
- Código de correlação e mensagem de erro, quando houver.

Tabelas: `comandos`, `historico_comandos`, `dispositivos`, `componentes_dispositivo`, `eventos`, `historico_estado_gemeo` e, na nova modelagem, relação opcional com `execucao_destinatarios`.

### Aba 4 — Mensagens de áudio

- Texto pretendido.
- Texto efetivamente enviado ao sintetizador, quando diferente.
- Origem e provedor/modelo de LLM, se aplicável.
- Motor TTS (por exemplo, Piper).
- Estado da síntese.
- URI e hash do áudio gerado, duração e erro.
- Evento, comando e dispositivo associado, quando aplicáveis.
- Resultado de envio/reprodução com indicação clara de confirmação ou desconhecimento.

Tabela principal: `mensagens_audio`, relacionada a `eventos`, `comandos`, `dispositivos` e `usuarios`, conforme contexto.

### Tabelas relacionadas à página

- `regras_automacao`
- `acoes_automacao`
- `execucoes_automacao`
- `execucoes_acoes`
- `execucao_destinatarios`
- `comandos`
- `historico_comandos`
- `mensagens_audio`
- `dispositivos`
- `componentes_dispositivo`
- `eventos`
- `usuarios`
- `auditoria`

### Regras de operação

- O backend resolve locais e sua hierarquia para encontrar os dispositivos concretos que devem receber uma ação. A página não deve publicar mensagens MQTT com curingas `+` ou `#` como se fossem endereços de broadcast.
- Enviar para vários equipamentos deve resultar em acompanhamento por destinatário, por exemplo um registro em `execucao_destinatarios` por dispositivo e ação.
- Usar identificadores de correlação e idempotência para reduzir execução duplicada.
- Comandos devem ter permissões, destinos permitidos, prazo de validade e parâmetros validados.
- O comando enviado não prova execução física. A interface deve distinguir pedido, envio, ACK e resultado de execução.
- O MQTT deve transportar os metadados/instruções necessários. Arquivos de áudio grandes devem ser buscados por endpoint autorizado ou mecanismo equivalente, não enviados como payload MQTT indiscriminadamente.
- Para regras associadas a segurança/emergência, usar textos e ações previamente aprovados.

---

## 4.8 `diagnostico.php` — Diagnóstico

**Objetivo:** explicar a condição técnica dos dispositivos e das integrações, sem confundir saúde do enlace com estado funcional confirmado.

### Abas recomendadas

1. **Dispositivos:** online/offline/desconhecido, último contato e mudanças de estado.
2. **Conectividade:** RSSI, SNR e demais métricas de rede; o significado deve indicar a tecnologia e a unidade.
3. **Componentes:** estado de sensores, controladores, módulos de comunicação e atuadores.
4. **Integrações:** mensagens recebidas/enviadas, processamento e falhas.
5. **Coletas:** sessões ativas, encerradas, interrompidas ou com falha; motivo e política após normalização.

### Tabelas relacionadas

- `dispositivos`
- `componentes_dispositivo`
- `leituras`
- `historico_status_dispositivo`
- `mensagens_integracao`
- `comandos` e `historico_comandos`
- `sessoes_coleta`
- `eventos`
- `execucoes_automacao` e `execucoes_acoes`, quando a falha técnica for parte de uma execução.

### Regras de interface

- Mostrar horário da última mensagem e idade do dado.
- Distinguir “sem mensagem recente” de “comprovadamente offline”, conforme a política de heartbeat definida.
- RSSI/SNR são métricas de qualidade do enlace, não distância.
- Mensagens de integração são registros de transporte/processamento; não substituem leituras, eventos ou auditoria.
- A aba Coletas é suficiente inicialmente: não criar uma página exclusiva de coletas.

---

## 4.9 `gemeo_digital.php` — Gêmeo digital

**Objetivo:** representar o ambiente e os dispositivos em seu estado atual conhecido, e permitir reconstrução histórica de forma somente leitura.

### Elementos da tela

- Representação do ambiente, locais e dispositivos.
- Seleção de local, dispositivo, intervalo de tempo ou evento.
- Camadas/indicadores de sensores, alarmes, conectividade, relés e outros estados relevantes.
- Linha do tempo com pausa, avanço, retrocesso e velocidade, se a reprodução for implementada.
- Identificação visual de estado confirmado, inferido, desconhecido ou sem dados suficientes.
- Aviso de lacunas históricas quando não houver dados disponíveis.

### Tabelas relacionadas

- `locais`: geometria/estrutura espacial e hierarquia.
- `dispositivos` e `componentes_dispositivo`: objetos representados.
- `leituras`: telemetria histórica.
- `historico_estado_gemeo`: snapshots e mudanças relevantes.
- `versoes_configuracao`: configuração vigente em cada instante.
- `historico_status_dispositivo`: histórico operacional.
- `historico_localizacao_dispositivo`: local ocupado em cada período.
- `eventos`, `evento_locais`, `evento_dispositivos`, `evento_componentes`, `evento_leituras`: ocorrências e associações.
- `comandos` e `historico_comandos`: tentativas e estados de atuação.

### Decisão ainda necessária: coordenadas 3D

O gêmeo digital atual utiliza posições X/Y/Z, mas o desenho ampliado das tabelas precisa formalizar onde essas coordenadas serão armazenadas. Se tanto o ambiente quanto os equipamentos tiverem posição 3D própria, recomenda-se prever coordenadas locais do ambiente em `locais` e posição do equipamento em `dispositivos`, além de manter latitude/longitude para georreferenciamento quando necessário.

Os nomes e tipos exatos dos campos (por exemplo, `posicao_x`, `posicao_y`, `posicao_z`, unidade e sistema de referência) precisam ser aprovados antes das migrations. Isso é uma extensão pendente, não uma das 28 tabelas atuais.

### Regra inviolável

A reprodução histórica é somente leitura. Mover a linha do tempo para um instante passado não pode republicar um comando, ligar um relé ou executar qualquer ação física. Se faltarem dados para reconstruir um estado, mostrar a lacuna em vez de inventar o valor.

---

## 4.10 `historico.php` — Histórico e auditoria (**nova página**)

**Objetivo:** oferecer uma área de pesquisa histórica transversal. Ela consulta os históricos das demais áreas; não duplica os registros em uma nova tabela genérica.

### Abas recomendadas

1. **Auditoria:** quem fez o quê, quando, por qual canal e com qual resultado.
2. **Comandos:** linha do tempo de solicitações, envios, confirmações, execuções e falhas.
3. **Dispositivos e locais:** mudanças de status, configurações e localizações anteriores.
4. **Gêmeo digital:** snapshots e estados históricos.
5. **Integrações:** mensagens, processamentos, rejeições e falhas.
6. **Eventos e coletas:** linha do tempo e sessões associadas.

### Filtros gerais

- Período.
- Usuário/ator.
- Dispositivo e componente.
- Local.
- Evento.
- Tipo de ação ou mensagem.
- Resultado/estado.
- `correlation_id`, se disponível.

### Tabelas relacionadas

- `auditoria`: atividade auditável.
- `historico_comandos`: transições dos comandos.
- `historico_status_dispositivo`: mudanças de estado operacional.
- `historico_localizacao_dispositivo`: transferências de equipamento.
- `versoes_configuracao`: versões de configuração.
- `historico_estado_gemeo`: snapshots e mudanças reconstruíveis.
- `mensagens_integracao`: tráfego e resultado do processamento.
- `eventos` e suas tabelas de associação.
- `execucoes_automacao`, `execucoes_acoes` e `execucao_destinatarios`.
- `sessoes_coleta`.

### Regras

- Não permitir editar ou apagar históricos por meio de uma tela de consulta comum.
- Filtrar e paginar resultados no backend quando houver integração real.
- Restringir dados por perfil de usuário.
- A tabela `auditoria` aumenta a rastreabilidade, mas sua mera existência não garante imutabilidade absoluta; políticas de acesso, backup e proteção também são necessárias.

---

## 5. Inventário das 28 tabelas e sua utilização no front-end

Esta seção é a matriz principal para conferir se todas as tabelas têm uma representação útil na interface. Uma tabela pode ser consultada por mais de uma página, sem que seja necessário criar uma página exclusiva para ela.

| Nº | Tabela | Responsabilidade | Página(s)/área(s) que a utilizam |
|---:|---|---|---|
| 1 | `usuarios` | Contas e identidade de acesso | Login; layout; Automações e operações; Histórico e auditoria |
| 2 | `perfis` | Papéis de autorização | Login/autorização; administração futura; layout |
| 3 | `usuario_perfis` | Associação N:N entre usuários e perfis | Login/autorização; administração futura |
| 4 | `locais` | Cadastro e hierarquia dos locais | Locais; Dispositivos; Dashboard; Videomonitoramento e eventos; Gêmeo digital; Histórico |
| 5 | `dispositivos` | Equipamentos completos | Dispositivos; Dashboard; Locais; Diagnóstico; Videomonitoramento e eventos; Automações e operações; Gêmeo digital; Histórico |
| 6 | `componentes_dispositivo` | Sensores, controladores, módulos e atuadores de cada equipamento | Detalhes do dispositivo; Diagnóstico; eventos; operações/comandos; Gêmeo digital; Histórico |
| 7 | `leituras` | Telemetria numérica/textual | Dashboard; Diagnóstico; detalhes do dispositivo; eventos; Gêmeo digital; coletas/histórico |
| 8 | `historico_status_dispositivo` | Mudanças de estado operacional | Diagnóstico; Dispositivos; Dashboard; Histórico; Gêmeo digital |
| 9 | `versoes_configuracao` | Snapshots versionados de configuração | Detalhes do dispositivo; Histórico; Gêmeo digital |
| 10 | `historico_estado_gemeo` | Snapshots/mudanças usados na reconstrução | Gêmeo digital; Histórico; detalhes do dispositivo quando relevante |
| 11 | `eventos` | Ocorrências e seu ciclo de atendimento | Videomonitoramento e eventos; Dashboard; Diagnóstico; Automações e operações; Gêmeo digital; Histórico |
| 12 | `evento_dispositivos` | Associação de eventos a dispositivos e seus papéis | Detalhes do evento; Gêmeo digital; Histórico |
| 13 | `evento_componentes` | Associação de eventos a componentes e seus papéis | Detalhes do evento; Diagnóstico; Histórico |
| 14 | `evento_leituras` | Associação entre eventos e leituras de evidência/gatilho | Detalhes do evento; telemetria; Gêmeo digital; Histórico |
| 15 | `anexos` | Metadados/URIs de imagens, vídeos, áudios e documentos | Videomonitoramento e eventos; detalhes do evento; Histórico |
| 16 | `mensagens_integracao` | Mensagens MQTT/API e resultado de processamento | Diagnóstico; Histórico; Dashboard de falhas |
| 17 | `comandos` | Comandos solicitados aos dispositivos | Automações e operações; detalhes do dispositivo; eventos; Gêmeo digital; Histórico |
| 18 | `historico_comandos` | Transições e tentativas de comandos | Automações e operações; Diagnóstico; Gêmeo digital; Histórico |
| 19 | `mensagens_audio` | Texto, síntese, arquivo de áudio e estado de reprodução | Automações e operações/aba Áudio; detalhes do evento; Histórico |
| 20 | `auditoria` | Registro de ações e resultados | Histórico e auditoria; ações sensíveis em outras páginas |
| 21 | `regras_automacao` | Definição de gatilhos e condições de regras | Automações e operações/aba Regras; Dashboard |
| 22 | `execucoes_automacao` | Execuções globais das regras | Automações e operações/aba Execuções; Dashboard; Histórico |
| 23 | `sessoes_coleta` | Coletas por evento, estudo ou diagnóstico | Diagnóstico/aba Coletas; detalhes do evento; Histórico |
| 24 | `evento_locais` | Associação N:N entre eventos e locais afetados | Videomonitoramento e eventos; Locais; Gêmeo digital; Histórico |
| 25 | `acoes_automacao` | Ações normalizadas e ordenadas de uma regra | Automações e operações/aba Regras |
| 26 | `execucoes_acoes` | Resultado/estado de cada ação de uma execução | Automações e operações/aba Execuções; Dashboard; Histórico |
| 27 | `execucao_destinatarios` | Resultado por destinatário concreto de uma ação | Automações e operações; detalhes de comandos; Histórico |
| 28 | `historico_localizacao_dispositivo` | Períodos e mudanças de localização do dispositivo | Dispositivos/detalhes; Locais; Gêmeo digital; Histórico |

### 5.1 Como interpretar a matriz

- Nem toda tabela precisa ter um formulário de cadastro manual. Muitas são registros produzidos pelo sistema a partir de uma mensagem MQTT, uma confirmação, uma mudança de estado ou uma execução.
- Tabelas históricas e de associação devem aparecer em visualizações de consulta, detalhes, filtros ou linhas do tempo, em vez de formulários CRUD irrestritos.
- `auditoria`, `historico_comandos`, `historico_status_dispositivo`, `historico_estado_gemeo` e `historico_localizacao_dispositivo` têm responsabilidades diferentes e não devem ser fundidas em uma única tabela de histórico.
- `eventos`, `mensagens_integracao` e `auditoria` também não são equivalentes: ocorrência de negócio, mensagem de transporte/processamento e trilha de ações respondem a perguntas distintas.

---

## 6. Modelo de dados fictícios para prototipação

Até o banco existir, todas as páginas devem usar conjuntos demonstrativos coerentes. A finalidade é testar os fluxos e o desenho da interface, não simular integração real como se já existisse.

### 6.1 Conjunto inicial de locais fictícios

| Código | Nome | Tipo | Local pai |
|---|---|---|---|
| `LOC-PREDIO-A` | Prédio A | `edificio` | — |
| `LOC-ANDAR-01` | Andar 1 | `andar` | `LOC-PREDIO-A` |
| `LOC-SALA-CTRL` | Sala de controle | `sala` | `LOC-ANDAR-01` |
| `LOC-CORREDOR-N` | Corredor norte | `corredor` | `LOC-ANDAR-01` |
| `LOC-EXT-01` | Área externa leste | `area_externa` | — |

Esses códigos são ilustrativos. Não representam uma instalação real e podem ser alterados na prototipação.

### 6.2 Conjunto inicial de dispositivos fictícios

| Código | Nome | Categoria | Local demonstrativo | Finalidade |
|---|---|---|---|---|
| `EMB-GAS-001` | Estação de monitoramento de gás | `embarcado` | Sala de controle | ESP32-S3, sensor MQ-4 e comunicação LoRa/MQTT demonstrados |
| `CAM-001` | Câmera de inspeção | `camera` | Corredor norte | Fonte para a tela de videomonitoramento |
| `AUD-001` | Módulo de áudio de alerta | `embarcado` | Sala de controle | Destino demonstrativo para mensagens/ações de áudio |
| `DRONE-001` | Drone de inspeção | `drone` | Área externa leste | Representação de equipamento móvel |

O `local_id` guarda a localização atual. As mudanças anteriores devem ser demonstradas em `historico_localizacao_dispositivo`, não simuladas sobrescrevendo o passado.

### 6.3 Componentes fictícios do `EMB-GAS-001`

| Código do componente | Nome | Tipo | Função demonstrativa |
|---|---|---|---|
| `GAS-CTRL` | Controlador ESP32-S3 | `controlador` | Leitura, lógica embarcada e comunicação |
| `GAS-MQ4` | Sensor MQ-4 | `sensor` | Leitura ADC demonstrativa de gás |
| `GAS-LORA` | Módulo RA-01S/LoRa | `comunicacao` | Comunicação de longo alcance |
| `GAS-RELE` | Módulo de relé | `atuador` | Representação de saída comandada |

O conjunto exato deve refletir o hardware que realmente será cadastrado. Componentes apenas conceituais devem estar identificados como demonstrativos.

### 6.4 Leituras fictícias

Exemplo de conjunto coerente para o gráfico de telemetria:

| Dispositivo | Métrica | Valor ilustrativo | Unidade/observação |
|---|---|---:|---|
| `EMB-GAS-001` | `gas_level` | 1850 | Contagem ADC fictícia |
| `EMB-GAS-001` | `gas_level` | 2780 | Contagem ADC fictícia, acima do limite demonstrativo de 2500 |
| `EMB-GAS-001` | `rssi` | -92 | dBm |
| `EMB-GAS-001` | `snr` | 7.5 | dB |

A leitura 2780 pode ser usada para demonstrar visualmente um evento fictício. Ela não é uma medição real e não deve ser apresentada como concentração em ppm sem calibração.

### 6.5 Evento demonstrativo de alerta

| Campo | Valor ilustrativo |
|---|---|
| Código | `EVT-GAS-0001` |
| Tipo | `GAS_ALERT` |
| Severidade | `alta` |
| Estado | `em_acompanhamento` |
| Dispositivo de origem | `EMB-GAS-001` |
| Componente de origem | `GAS-MQ4` |
| Leitura gatilho | `gas_level = 2780` |
| Local afetado | Sala de controle |
| `is_teste` | `true` |

O mesmo evento pode associar outras leituras de diagnóstico e mais de um local por meio das tabelas de associação. No protótipo, os vínculos devem ser coerentes com o código do evento e não apenas textos independentes nas telas.

### 6.6 Regra e ação demonstrativas

Regra fictícia: `gas_level > 2500`. A condição é avaliada por uma regra de automação e pode criar um evento e solicitar ações previamente cadastradas.

Exemplo de sequência de ações:

1. Criar ou atualizar o evento.
2. Solicitar ativação de um indicador/atuador permitido.
3. Preparar uma mensagem de áudio aprovada.
4. Solicitar a reprodução nos destinatários resolvidos pelo backend.

No front-end de demonstração, exibir isso como **simulação de fluxo**, sem publicar MQTT real. O resultado deve mostrar separadamente o estado global da execução, de cada ação e de cada destinatário.

---

## 7. Estratégia para manter os dados fictícios coerentes

### 7.1 Evitar cadastros fictícios contraditórios

Não definir os dispositivos de forma independente em cada página. Manter um conjunto de dados demonstrativos coerente por entidade, com os mesmos códigos e relações:

- Os dispositivos usados no Dashboard devem ser os mesmos da listagem Dispositivos.
- O nome do local exibido no dispositivo deve existir na página Locais.
- O evento de `GAS_ALERT` deve apontar para o mesmo dispositivo, componente, leitura e local em todas as páginas.
- A execução de automação deve conter ações e destinatários consistentes com o evento e os comandos exibidos.
- Os resultados de comandos apresentados no Histórico devem corresponder aos exibidos em Automações e operações.

### 7.2 Organização recomendada no protótipo

Quando for conveniente, centralizar os dados fictícios em arquivos ou serviços de demonstração próprios, em vez de duplicar grandes objetos em vários arquivos JavaScript. Uma estrutura possível, a adaptar ao projeto existente:

```text
app/
├── Views/
│   ├── main.php
│   ├── login.php
│   ├── dashboard.php
│   ├── embarcados.php
│   ├── locais.php
│   ├── videomonitoramento.php
│   ├── automacoes.php
│   ├── diagnostico.php
│   ├── gemeo_digital.php
│   └── historico.php
└── ...

```


A localização exata dos arquivos de dados demonstrativos depende de como o JavaScript e os assets do projeto já estão organizados. Não é necessário reorganizar tudo de uma vez só; prioridade é deixar clara a origem dos dados e evitar cópias inconsistentes.
7.3 Preparar cada tela para a integração futura
- Usar nomes de campos e conceitos compatíveis com o modelo proposto.
- Evitar IDs que dependam apenas da posição do item em um array.
- Separar a montagem visual dos dados da lógica de persistência.
- Formulários devem ter validação visual e mensagens de erro.
- Ações como excluir, executar e enviar comando devem pedir confirmação e informar claramente o resultado.
- Enquanto a persistência não existe, ações demonstrativas devem alterar apenas o estado local da interface e indicar que não foram gravadas no banco nem enviadas ao hardware.
- Na futura integração, os controllers e models deverão substituir a fonte fictícia, sem exigir redesenho completo da página.

8. Relacionamentos principais a refletir na interface
Esta seção resume as relações que precisam ser visíveis ou respeitadas nos fluxos da interface. Não é o script final de migrations.
- usuarios N:N perfis por usuario_perfis.
- locais possuem hierarquia por local_pai_id e podem conter vários dispositivos.
- dispositivos 1:N componentes_dispositivo, leituras, historico_status_dispositivo, versoes_configuracao, historico_estado_gemeo, sessoes_coleta e históricos de localização.
- historico_localizacao_dispositivo registra a mudança de local e seu intervalo temporal; o local atual continua em dispositivos.local_id.
- eventos N:N dispositivos por evento_dispositivos.
- eventos N:N componentes_dispositivo por evento_componentes.
- eventos N:N leituras por evento_leituras.
- eventos N:N locais por evento_locais.
- eventos 1:N anexos.
- regras_automacao 1:N acoes_automacao.
- regras_automacao 1:N execucoes_automacao.
- execucoes_automacao 1:N execucoes_acoes.
- execucoes_acoes 1:N execucao_destinatarios, com um resultado rastreável por destinatário concreto.
- comandos 1:N historico_comandos; comandos podem ser associados ao evento, dispositivo/componente e, na proposta ampliada, ao destinatário da execução correspondente.
- mensagens_audio podem se relacionar a evento, comando, dispositivo e usuário, de acordo com o fluxo.
Integridade que a aplicação deverá garantir
- Se uma leitura, comando, sessão ou versão de configuração referenciar um componente, o componente precisa pertencer ao mesmo dispositivo referenciado.
- Eventos relacionados em cadeia não podem formar ciclos em evento_pai_id.
- medido_em/ocorrido_em representam quando o fato aconteceu; recebido_em/registrado_em representam quando o sistema recebeu ou persistiu o fato.
- Não apagar por cascata indiscriminada leituras, anexos, comandos e históricos necessários para explicar um evento.
- A política de retenção deve preservar leituras necessárias a eventos e sessões ativas. Uma janela normal de 100 amostras é uma quantidade de amostras, não uma duração fixa.

9. Decisões a aprovar antes das migrations
A construção do front-end com dados fictícios pode continuar sem resolver tudo imediatamente. Entretanto, os itens a seguir devem ser definidos antes de criar a estrutura definitiva do banco.
9.1 Autenticação
- Usar CodeIgniter Shield ou autenticação própria?
- Como os perfis serão integrados ao mecanismo escolhido?
9.2 Estrutura ampliada da tabela locais
Confirmar codigo, tipo, local_pai_id, ativo e as regras para cada tipo de local, além dos campos geográficos e descritivos existentes no modelo preliminar.
9.3 Posição 3D do gêmeo digital
Decidir os campos de posição de locais e dispositivos, unidades e sistema de referência. Não confundir coordenadas locais X/Y/Z com latitude/longitude.
9.4 Estados válidos
Definir enums/listas permitidas para:
- estado operacional do dispositivo;
- estado do evento;
- estado do comando;
- estado de execução da regra;
- estado de cada ação e destinatário;
- estado da síntese e reprodução de áudio;
- estado de sessão de coleta;
- estado de processamento de mensagem de integração.
9.5 Regras de automação
- Quais tipos de gatilhos são suportados no primeiro ciclo?
- Quais ações são permitidas e quais exigem confirmação humana?
- Como será definida a ordem e a prioridade das ações?
- Como impedir execuções duplicadas?
- Qual a política após normalização do evento: encerrar, continuar até intervenção manual ou continuar por período definido?
9.6 Confirmação de comandos e áudio
Definir qual mensagem/ACK ou telemetria comprova cada resultado físico. “Publicado no MQTT”, “recebido pelo broker”, “recebido pelo dispositivo” e “executado no hardware” são estados diferentes.
9.7 Retenção e horário
- Definir UTC para armazenamento e conversão para horário local na interface.
- Definir retenção normal da telemetria e proteção das leituras vinculadas a eventos/coletas.
- Definir armazenamento, controle de acesso e expiração dos arquivos de mídia.
9.8 Campos e chaves das cinco tabelas adicionadas
A proposta de 28 tabelas amplia o documento anterior de 23 tabelas com:
- evento_locais;
- acoes_automacao;
- execucoes_acoes;
- execucao_destinatarios;
- historico_localizacao_dispositivo.
Antes das migrations, revisar campos, chaves primárias compostas quando aplicável, chaves estrangeiras, estados e índices das cinco tabelas com o restante do modelo. Elas são consideradas parte da proposta de 28 tabelas deste documento; seus tipos exatos ainda precisam ser consolidados no script final.

10. Ordem recomendada para construção do front-end
A ordem abaixo reduz retrabalho porque começa pelas entidades usadas em muitas outras telas.
Etapa	Página/atividade	Resultado esperado
1	Revisar main.php	Menu consistente e links para as áreas definidas; preservar as rotas atuais
2	Ajustar embarcados.php para Dispositivos	Cadastro e listagem alinhados a dispositivos, seleção de local e área de detalhes/componentes
3	Criar locais.php	Hierarquia, cadastro e listagem; dados fictícios compartilháveis com Dispositivos
4	Revisar dashboard.php	Indicadores e gráficos coerentes com os conjuntos fictícios de dispositivos, leituras, eventos e operações
5	Revisar videomonitoramento.php	Lista/detalhes de eventos com locais, dispositivos, componentes, leituras e anexos demonstrativos
6	Criar automacoes.php ou operacoes.php	Abas Regras, Execuções, Comandos e Áudio, com relações coerentes
7	Revisar diagnostico.php	Conectividade, saúde, mensagens de integração e sessões de coleta
8	Revisar gemeo_digital.php	Locais, dispositivos, estados e timeline demonstrativa; explicitar a decisão de coordenadas 3D
9	Criar historico.php	Consultas por período, dispositivo, usuário, local, evento e resultado
10	Revisar login.php	Aparência final e integração posterior com o mecanismo de autenticação escolhido
11	Revisão cruzada	Testar links, filtros, formulários, mensagens, responsividade e consistência de códigos/dados fictícios


11. Critérios de conclusão do protótipo
Considerar o front-end demonstrativo pronto para iniciar o banco quando:
- [ ] Todas as páginas existentes continuam acessíveis e a navegação não foi quebrada.
- [ ] As três novas áreas (locais.php, automacoes.php/operacoes.php, historico.php) estão navegáveis.
- [ ] Dispositivo, componente e local estão claramente separados na interface.
- [ ] Cadastro/edição de dispositivo seleciona um local existente; não cria local acidentalmente dentro do mesmo registro.
- [ ] Cada dispositivo demonstrativo tem um código estável e o mesmo registro aparece de forma consistente nas telas.
- [ ] Eventos demonstrativos relacionam locais, dispositivos, componentes e leituras coerentes.
- [ ] Regras de automação mostram ações normalizadas e ordenadas; execuções mostram resultado global, resultado por ação e resultado por destinatário.
- [ ] A tela de comandos diferencia solicitação, envio, confirmação e execução.
- [ ] Mensagens de áudio diferenciam síntese, envio e confirmação de reprodução.
- [ ] O gêmeo digital não executa comandos ao reproduzir o passado e mostra estados desconhecidos/lacunas quando aplicável.
- [ ] Dados fictícios estão identificados como demonstração e não são apresentados como telemetria real.
- [ ] A matriz de 28 tabelas foi revisada e cada tabela tem seu uso de interface identificado, ainda que várias sejam apenas históricas ou produzidas automaticamente.
- [ ] A decisão das coordenadas 3D e a escolha de autenticação estão registradas antes das migrations.

12. Escopo fora desta etapa
Enquanto o objetivo for finalizar primeiro as páginas com dados fictícios, não implementar ainda:
- criação de tabelas ou migrations definitivas;
- inserts/seeders vinculados aos IDs finais do banco;
- persistência real dos formulários;
- integração MQTT real;
- publicação real de comandos;
- reprodução real de áudio nos dispositivos;
- integração efetiva com câmeras, drone, modelo de visão ou servidor Piper;
- indicação de conectividade ou resultado físico sem confirmação.
Esses elementos serão implementados na etapa seguinte, depois da revisão do protótipo e da consolidação do esquema das 28 tabelas.

13. Resultado esperado
Ao terminar esta etapa, o IoT-Edge Command Center deverá contar com um front-end demonstrativo coerente, organizado em poucas páginas e com fluxos navegáveis. Cada página terá uma responsabilidade clara; as entidades ficarão separadas conceitualmente; as 28 tabelas estarão mapeadas às telas; e os dados fictícios poderão ser substituídos por consultas reais posteriormente, com mudanças concentradas nos controllers/models e na camada de dados, em vez de exigir a reconstrução de toda a interface.



**Observação importante:** esse documento é uma especificação de trabalho para o front-end e a proposta das 28 tabelas. Ele ainda deixa algumas decisões para consolidar antes das migrations, principalmente os campos definitivos das cinco tabelas adicionadas, a hierarquia de `locais` e as coordenadas 3D do gêmeo digital.