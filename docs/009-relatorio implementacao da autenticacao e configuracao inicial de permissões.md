# RELATÓRIO TÉCNICO — IMPLEMENTAÇÃO DA AUTENTICAÇÃO E CONFIGURAÇÃO INICIAL DE PERMISSÕES COM CODEIGNITER SHIELD

==================================================================

## IoT-Edge Command Center

**Banco:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4.7.5  
**Biblioteca de autenticação:** CodeIgniter Shield  
**Ambiente:** Desenvolvimento local  
**Situação:** Shield instalado e configurado; autenticação funcional; grupos e permissões definidos; validação das restrições operacionais pendente.

---

# 11. INSTALAÇÃO E CONFIGURAÇÃO DO CODEIGNITER SHIELD

Após a definição tecnológica documentada anteriormente, foi iniciada a implementação do CodeIgniter Shield no projeto IoT-Edge Command Center.

A biblioteca foi instalada por meio do Composer, passando a integrar oficialmente a estrutura de autenticação e autorização da aplicação.

A configuração foi realizada utilizando os mecanismos disponibilizados pelo próprio framework, evitando a criação de uma estrutura paralela para gerenciamento de credenciais.

O Shield passou a ser responsável pelos processos de autenticação, gerenciamento de sessões, identificação dos usuários, associação aos grupos e verificação de permissões.

A aplicação permanece responsável pelas regras específicas de negócio e pela implementação das funcionalidades dos módulos IoT-Edge.

## 11.1. Configuração do ambiente

A implementação foi realizada no ambiente local de desenvolvimento, utilizando:

- CodeIgniter 4.7.5;
- CodeIgniter Shield;
- PHP;
- Composer;
- MySQL 8.0;
- Banco de dados `iot_edge_center`.

A integração foi realizada preservando a estrutura existente do projeto.

## 11.2. Execução das migrations

Após a instalação, foram executadas as migrations disponibilizadas pelo Shield.

A verificação por meio do comando `php spark migrate:status` confirmou a execução das migrations de autenticação e configurações.

Entre as migrations identificadas estão:

| Migration | Situação |
|---|---|
| `2020-12-28-223112 create_auth_tables` | Executada |
| Migrations de configurações do Shield | Executadas |

As tabelas foram criadas no banco de dados `iot_edge_center`, sem necessidade de reproduzir manualmente as estruturas fornecidas pela biblioteca.

## 11.3. Estrutura de tabelas criada

Após a execução das migrations, foram identificadas as seguintes tabelas:

| Tabela | Finalidade |
|---|---|
| `users` | Armazenamento dos registros principais dos usuários |
| `auth_identities` | Identidades e credenciais de autenticação |
| `auth_logins` | Registros relacionados às autenticações |
| `auth_token_logins` | Registros de autenticação por token |
| `auth_remember_tokens` | Gerenciamento de sessões persistentes |
| `auth_groups_users` | Associação entre usuários e grupos |
| `auth_permissions_users` | Permissões individuais |
| `settings` | Configurações gerenciadas pelo sistema |
| `migrations` | Controle das migrations executadas |

Não foram criadas tabelas próprias de usuários, perfis ou credenciais em paralelo às estruturas do Shield.

As tabelas específicas dos módulos IoT-Edge ainda não fazem parte desta etapa de implementação.

---

# 12. CONFIGURAÇÃO DOS GRUPOS E PERMISSÕES

Com a infraestrutura de autenticação disponível, foi realizada a configuração dos grupos funcionais da plataforma.

Essa configuração foi centralizada no arquivo:

`app/Config/AuthGroups.php`

Foram definidos três grupos, mantendo a separação entre administração, operação e consulta.

## 12.1. Grupos implementados

| Grupo | Identificação | Responsabilidade |
|---|---|---|
| `administrador` | Administrador | Gerenciamento da plataforma e de seus recursos |
| `operador` | Operador | Execução das atividades operacionais autorizadas |
| `visualizador` | Visualizador | Consulta e acompanhamento das informações |

O grupo padrão foi definido como `visualizador`.

Essa decisão evita que novos usuários recebam automaticamente privilégios administrativos ou operacionais.

## 12.2. Catálogo de permissões

Foi estabelecido um catálogo de permissões específicas para os módulos e funcionalidades da plataforma.

As permissões foram organizadas por domínio funcional:

| Domínio | Permissões |
|---|---|
| Dashboard | `dashboard.view` |
| Telemetria | `telemetry.view` |
| Histórico | `history.view` |
| Gêmeo digital | `digital_twin.view` |
| Videomonitoramento | `video.view` |
| Dispositivos | `devices.view`, `devices.manage` |
| Locais | `locations.view`, `locations.manage` |
| Eventos | `events.view`, `events.acknowledge`, `events.manage` |
| Automações | `automations.view`, `automations.execute`, `automations.manage` |
| Comandos | `commands.view`, `commands.execute` |
| Usuários | `users.view`, `users.manage` |
| Auditoria | `audit.view` |
| Sistema | `system.settings` |

A utilização de permissões específicas permite diferenciar a consulta de informações das operações que modificam configurações ou executam ações.

## 12.3. Matriz de permissões

A matriz foi configurada considerando as responsabilidades de cada grupo.

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

A definição procura estabelecer uma separação clara:

- **Administrador:** responsável pela governança, configuração e gerenciamento da plataforma.
- **Operador:** responsável pela execução das atividades operacionais autorizadas.
- **Visualizador:** responsável pelo acompanhamento e consulta, sem autorização para executar operações.

Um ponto importante é que a autorização para visualizar determinado módulo não implica autorização para modificar seus dados ou executar comandos relacionados.

---

# 13. CONFIGURAÇÃO DA AUTENTICAÇÃO

Após a configuração dos grupos, foi realizada a integração do processo de autenticação com as rotas e interfaces existentes.

A configuração principal foi mantida no arquivo:

`app/Config/Auth.php`

## 13.1. Configurações de autenticação

Foram estabelecidos os seguintes parâmetros:

| Configuração | Valor ou comportamento |
|---|---|
| Provedor de usuários | `UserModel` |
| Autenticador padrão | Session |
| Campo de identificação | E-mail |
| Cadastro público | Desabilitado |
| Comprimento mínimo da senha | 8 caracteres |
| Lembrar sessão | Habilitado |
| Registro de atividade | Habilitado |
| Login por Magic Link | Habilitado na configuração |
| Algoritmo de hash | `PASSWORD_DEFAULT` |

O gerenciamento de senhas permanece sob responsabilidade do Shield, sem armazenamento manual de credenciais pela aplicação.

## 13.2. Redirecionamentos

Foram ajustados os redirecionamentos para adequar o comportamento da autenticação ao fluxo de navegação do projeto.

A configuração de login passou a direcionar para:

`dashboard`

O logout passou a direcionar para:

`login`

Também foi analisado o comportamento de redirecionamento para páginas protegidas acessadas antes da autenticação.

Foi identificado que o Shield pode preservar temporariamente a URL originalmente solicitada, utilizando essa informação como prioridade no redirecionamento após o login.

Esse comportamento foi considerado durante os testes, evitando alterações desnecessárias na lógica interna da biblioteca.

## 13.3. Tela personalizada de login

A interface existente foi integrada ao processo oficial de autenticação do Shield.

A tela está localizada em:

`app/Views/auth/login.php`

O formulário passou a utilizar a rota oficial de autenticação:

`url_to('login')`

Também foi incorporado o campo CSRF por meio de `csrf_field()`.

Foram preservados os elementos visuais da interface, incluindo:

- Tema escuro;
- Bootstrap 5.3.3;
- Font Awesome 6.5.2;
- Identidade visual do IoT-Edge Command Center;
- Campos de e-mail e senha;
- Opção de visualização da senha;
- Exibição de mensagens de autenticação;
- Modal demonstrativo de solicitação de acesso.

O formulário de solicitação de acesso permanece demonstrativo, sem criação efetiva de contas ou persistência de solicitações.

O cadastro público continua desabilitado na configuração do Shield.

---

# 14. PROTEÇÃO DAS ROTAS E CONFIGURAÇÃO DO CSRF

Foi realizada a integração entre as rotas do projeto e os filtros de autenticação e autorização do Shield.

## 14.1. Habilitação do CSRF

No arquivo:

`app/Config/Filters.php`

Foi habilitado o filtro CSRF global para as requisições compatíveis com sua proteção.

Essa configuração acrescenta uma camada de proteção contra requisições forjadas, especialmente importante para futuras operações de cadastro, edição, exclusão e execução de comandos.

## 14.2. Proteção das páginas

As rotas dos módulos foram configuradas com os filtros de sessão e permissões correspondentes.

Exemplo da rota do módulo de dispositivos:

`GET /embarcados`

Filtros:

- `session`
- `permission:devices.view`

As demais páginas de consulta receberam permissões específicas de seus respectivos módulos.

Entre elas:

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

As rotas oficiais de autenticação do Shield foram registradas por meio de:

`service('auth')->routes($routes);`

Isso permitiu utilizar os controladores fornecidos pela biblioteca para os processos de login e logout.

## 14.3. Verificação das rotas

A listagem das rotas foi conferida por meio do comando:

`php spark routes`

Foi possível identificar as rotas de login, logout e as rotas protegidas dos módulos.

Também foi confirmada a associação dos filtros de sessão e permissões às páginas correspondentes.

---

# 15. CRIAÇÃO DAS CONTAS DE DEMONSTRAÇÃO

Para permitir a validação dos diferentes perfis, foram criadas três contas de demonstração utilizando os comandos disponibilizados pelo Shield.

As contas foram associadas diretamente aos respectivos grupos.

## 15.1. Contas cadastradas

| ID | Usuário | E-mail | Grupo |
|---|---|---|---|
| 1 | `admin` | `admin@iot-edge.example.com` | `administrador` |
| 2 | `operador` | `operador@iot-edge.example.com` | `operador` |
| 3 | `visualizador` | `visualizador@iot-edge.example.com` | `visualizador` |

As contas utilizam endereços fictícios do domínio `example.com`, exclusivamente para demonstração no ambiente local.

As senhas foram definidas durante a criação das contas por meio da interação com o comando do Shield, sem inclusão de credenciais diretamente nos comandos apresentados.

A senha de demonstração utilizada é:

`Iot@1234`

Essa credencial destina-se exclusivamente aos testes locais e deverá ser substituída antes de qualquer utilização em ambiente real.

## 15.2. Associação aos grupos

A tabela `auth_groups_users` foi consultada para verificar as associações.

O resultado confirmou:

| Usuário | Grupo associado |
|---|---|
| `admin` | `administrador` |
| `operador` | `operador` |
| `visualizador` | `visualizador` |

Dessa forma, as três contas passaram a representar os perfis definidos para a plataforma.

---

# 16. IDENTIFICAÇÃO DINÂMICA DO USUÁRIO AUTENTICADO

Uma das adequações necessárias após a integração do Shield foi a substituição das informações demonstrativas utilizadas anteriormente no cabeçalho da aplicação.

O layout principal estava utilizando uma informação personalizada de sessão, que não era preenchida automaticamente pelo mecanismo de autenticação.

Por esse motivo, o cabeçalho apresentava o perfil Operador independentemente da conta utilizada.

A situação foi corrigida no arquivo:

`app/Views/layouts/main.php`

## 16.1. Integração com a entidade de usuário do Shield

O layout passou a recuperar diretamente o usuário autenticado por meio de:

`auth()->user()`

A identificação da conta passou a utilizar os dados reais disponibilizados pela entidade de usuário do Shield.

Foram utilizados:

- Nome de usuário;
- E-mail;
- Grupo ao qual o usuário pertence.

A identificação do perfil utiliza os métodos de autorização disponibilizados pela entidade, incluindo `inGroup()`.

## 16.2. Exibição dinâmica dos perfis

O cabeçalho passou a reconhecer os três grupos:

| Grupo autenticado | Identificação exibida |
|---|---|
| `administrador` | Administrador |
| `operador` | Operador |
| `visualizador` | Visualizador |

Além da identificação do perfil, o menu do usuário passou a apresentar as informações da conta autenticada.

## 16.3. Correção da opção de saída

O item anteriormente identificado como "Tela de login" foi substituído pela opção "Sair".

A opção passou a utilizar a rota oficial:

`/logout`

Essa rota é atendida pelo controlador de logout do Shield.

Quando não existe usuário autenticado, o menu apresenta a opção de entrada.

A alteração permite que o usuário encerre sua sessão pelo próprio menu da aplicação.

---

# 17. TESTES REALIZADOS

Após a implementação, foram realizados testes de autenticação, navegação e identificação dos grupos.

O objetivo foi verificar se o Shield estava efetivamente integrado à aplicação e se as contas de demonstração estavam sendo reconhecidas corretamente.

## 17.1. Teste de acesso sem autenticação

Foi realizada uma tentativa de acessar diretamente a página protegida do dashboard sem uma sessão autenticada.

O sistema redirecionou o acesso para a tela de login.

Esse comportamento confirmou a aplicação do filtro de sessão nas rotas protegidas.

## 17.2. Teste de autenticação

Foram utilizadas as três contas criadas:

- Administrador;
- Operador;
- Visualizador.

As autenticações foram realizadas individualmente.

Durante os testes, também foi observado que o Shield impede uma nova autenticação quando já existe uma sessão autenticada ativa.

Para alternar entre as contas, foi necessário encerrar a sessão anterior por meio do logout.

Esse comportamento foi considerado esperado para o gerenciamento de sessões.

## 17.3. Teste de identificação no cabeçalho

Após a correção do layout principal, os três perfis foram autenticados e verificados.

O resultado foi satisfatório:

| Conta utilizada | Perfil exibido | Resultado |
|---|---|---|
| Administrador | Administrador | Funcionando |
| Operador | Operador | Funcionando |
| Visualizador | Visualizador | Funcionando |

Também foi verificada a apresentação das informações individuais da conta e da opção de logout.

**Resultado:** identificação dinâmica dos três perfis funcionando corretamente.

## 17.4. Teste de acesso às páginas de consulta

Foram testadas as páginas dos módulos existentes utilizando as três contas.

As páginas verificadas foram:

| Página | Administrador | Operador | Visualizador |
|---|:---:|:---:|:---:|
| Dashboard | Acesso | Acesso | Acesso |
| Embarcados | Acesso | Acesso | Acesso |
| Diagnóstico | Acesso | Acesso | Acesso |
| Gêmeo Digital | Acesso | Acesso | Acesso |
| Videomonitoramento | Acesso | Acesso | Acesso |
| Locais | Acesso | Acesso | Acesso |
| Automações | Acesso | Acesso | Acesso |
| Histórico | Acesso | Acesso | Acesso |

O resultado está de acordo com a matriz configurada, pois os três grupos possuem permissões de consulta para esses módulos.

Portanto, a igualdade de acesso às páginas de consulta não representa um erro de configuração.

A diferenciação entre os grupos deverá ocorrer principalmente nas operações disponíveis dentro de cada módulo.

---

# 18. SITUAÇÃO ATUAL DO CONTROLE DE ACESSO

Com os testes realizados, foi possível concluir a integração inicial do Shield e estabelecer a estrutura de autenticação e autorização da plataforma.

Entretanto, é necessário distinguir a configuração das permissões da sua aplicação efetiva em todas as operações do sistema.

## 18.1. Funcionalidades implementadas e verificadas

| Item | Situação |
|---|---|
| Instalação do CodeIgniter Shield | Concluída |
| Migrations de autenticação | Executadas |
| Criação das tabelas de autenticação | Confirmada |
| Configuração dos grupos | Concluída |
| Definição do catálogo de permissões | Concluída |
| Definição da matriz de acesso | Concluída |
| Criação das contas de demonstração | Concluída |
| Associação das contas aos grupos | Confirmada |
| Login integrado ao Shield | Funcionando |
| Logout integrado ao Shield | Funcionando |
| Proteção de páginas por sessão | Testada |
| Identificação dinâmica do usuário | Funcionando |
| Identificação dinâmica do perfil | Funcionando |
| Proteção CSRF global | Configurada |
| Acesso às páginas de consulta | Testado |

## 18.2. Funcionalidades que ainda precisam ser validadas

Embora o catálogo de permissões esteja definido, ainda é necessário testar sua aplicação nas operações específicas dos módulos.

Entre as atividades pendentes estão:

- Verificar a restrição de cadastro de dispositivos;
- Verificar a restrição de edição de dispositivos;
- Verificar a restrição de exclusão de dispositivos;
- Validar a execução de comandos por operadores;
- Impedir a execução de comandos por visualizadores;
- Validar o reconhecimento e gerenciamento de eventos;
- Restringir o gerenciamento de automações;
- Restringir o gerenciamento de usuários;
- Validar as operações diretamente no backend, inclusive por requisições realizadas fora da interface.

Na verificação das rotas do módulo de dispositivos, foi identificada a rota de consulta:

`GET /embarcados`

Protegida pela permissão:

`devices.view`

Até o momento dessa revisão, não foram identificadas rotas específicas de cadastro, edição ou exclusão de dispositivos.

Assim, não é possível considerar essas operações como implementadas ou testadas.

## 18.3. Separação de responsabilidades

A arquitetura de autorização seguirá a seguinte lógica:

**Administrador:** gerenciamento e configuração da plataforma, usuários, permissões e recursos.

**Operador:** execução de atividades operacionais autorizadas, incluindo comandos e tratamento de eventos.

**Visualizador:** consulta de informações e acompanhamento dos módulos, sem autorização para modificar configurações ou executar operações.

Essa separação deverá ser aplicada tanto na interface quanto no backend.

Ocultar botões no frontend não será considerado suficiente para garantir a segurança. Cada operação deverá verificar a permissão correspondente no servidor.

---

# 19. PRÓXIMA ETAPA DO DESENVOLVIMENTO

Considerando que a infraestrutura de autenticação já está integrada e que os módulos da plataforma ainda estão em desenvolvimento, foi definida a continuidade da implementação funcional do IoT-Edge Command Center.

A validação completa das restrições de acesso será retomada posteriormente, quando as operações dos respectivos módulos estiverem implementadas.

Essa decisão evita realizar testes artificiais sobre funcionalidades que ainda não existem.

Na retomada da etapa de autorização, deverão ser realizados testes específicos com as três contas de demonstração, verificando:

1. Operações permitidas ao administrador;
2. Operações permitidas ao operador;
3. Operações permitidas ao visualizador;
4. Tentativas de acesso não autorizado diretamente pelas rotas;
5. Verificação das permissões no backend;
6. Proteção CSRF nas operações que alteram dados;
7. Registro adequado das operações administrativas e operacionais, conforme a implementação do módulo de auditoria.

A conclusão dessa etapa dependerá da validação efetiva das operações, e não apenas da existência das permissões na configuração.

---

# 20. CONCLUSÃO

A implementação do CodeIgniter Shield representa a conclusão da infraestrutura inicial de autenticação e a definição da estrutura de autorização do IoT-Edge Command Center.

Foram realizadas a instalação da biblioteca, a execução das migrations, a configuração dos grupos e permissões, a criação das contas demonstrativas e a integração do processo de autenticação com as interfaces e rotas existentes.

Os testes confirmaram o funcionamento do login, logout, identificação dos usuários, identificação dinâmica dos perfis e acesso às páginas de consulta.

Também foi estabelecida uma matriz de responsabilidades que diferencia a administração da plataforma, a operação dos recursos e a consulta das informações.

Entretanto, a validação integral das restrições de autorização permanece pendente, principalmente nas operações de gerenciamento de dispositivos, execução de comandos, tratamento de eventos e administração de usuários.

Dessa forma, a infraestrutura de autenticação encontra-se implementada e funcional, enquanto a conclusão do controle de acesso por perfil será realizada em uma etapa posterior, acompanhando a implementação dos módulos e suas respectivas operações.

A próxima atividade do projeto será dar continuidade ao desenvolvimento funcional dos módulos IoT-Edge Command Center, preservando a estrutura de autenticação e autorização já estabelecida.