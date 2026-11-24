# REVISÃO TÉCNICA — AUTENTICAÇÃO E PERMISSÕES COM CODEIGNITER SHIELD
## IoT-Edge Command Center

**Banco:** `iot_edge_center`  
**SGBD:** MySQL 8.0  
**Framework:** CodeIgniter 4  
**Biblioteca de autenticação:** CodeIgniter Shield  
**Tabelas analisadas:** usuários, identidades, grupos e permissões  
**Situação:** Decisão tecnológica definida; instalação pendente.

---

# 1. DECISÃO TECNOLÓGICA

Foi definida a utilização do CodeIgniter Shield como mecanismo oficial de autenticação e autorização do IoT-Edge Command Center.

A escolha substitui a proposta anterior de desenvolver um mecanismo próprio de autenticação.

O Shield será responsável por funcionalidades como:

- Autenticação por e-mail e senha.
- Gerenciamento de sessões.
- Armazenamento e validação de credenciais.
- Recuperação de acesso.
- Gerenciamento de grupos.
- Controle de permissões.
- Tokens de acesso, quando necessários.
- Recursos adicionais de segurança, como autenticação em dois fatores.

A aplicação continuará responsável pelas regras específicas de negócio e pela autorização de acesso aos módulos do IoT-Edge Command Center.

# 2. ESTRUTURA DE DADOS DO SHIELD

O Shield fornece suas próprias tabelas para gerenciamento de usuários e autenticação.

A configuração padrão contempla:

| Tabela | Finalidade |
|---|---|
| `users` | Dados principais dos usuários |
| `auth_identities` | Identidades de autenticação, incluindo credenciais |
| `auth_logins` | Registro de tentativas e informações de login |
| `auth_token_logins` | Registro de autenticações por token |
| `auth_remember_tokens` | Tokens de sessões persistentes |
| `auth_groups_users` | Associação de usuários aos grupos |
| `auth_permissions_users` | Permissões individuais concedidas aos usuários |

Essas tabelas serão administradas pelas migrations e pelos mecanismos próprios do Shield.

Não será criada uma segunda estrutura de autenticação paralela.

# 3. SUBSTITUIÇÃO DA MODELAGEM ANTERIOR

A proposta inicial previa três tabelas próprias:

- `usuarios`;
- `perfis`;
- `usuario_perfis`.

Com a adoção do Shield, essa estrutura será substituída pela solução fornecida pela biblioteca.

| Modelagem anterior | Nova implementação |
|---|---|
| `usuarios` | `users` e `auth_identities` |
| `perfis` | Grupos definidos em `AuthGroups` |
| `usuario_perfis` | `auth_groups_users` |

A tabela `auth_permissions_users` complementará o controle de autorização, permitindo permissões específicas por usuário.

Essa substituição evita duplicação de dados e de responsabilidades.

Os dados específicos do usuário que não forem contemplados pela estrutura padrão do Shield, como informações adicionais de identificação utilizadas pela aplicação, deverão ser incorporados por meio de extensão compatível do modelo de usuário ou de uma tabela complementar própria, caso necessário.

Não será criada uma tabela complementar antes de identificar os campos efetivamente necessários.

# 4. GRUPOS DE ACESSO DA PLATAFORMA

Os grupos serão definidos na configuração `app/Config/AuthGroups.php`.

Inicialmente, serão utilizados três grupos funcionais:

| Grupo | Finalidade |
|---|---|
| `administrador` | Gerenciamento geral da plataforma, usuários, configurações e permissões |
| `operador` | Operação dos módulos e recursos autorizados |
| `visualizador` | Consulta de informações e funcionalidades permitidas |

A associação entre usuários e grupos será realizada pelo Shield.

Um usuário poderá pertencer a mais de um grupo.

O grupo padrão de novos usuários deverá ser definido como `visualizador`, evitando a concessão automática de privilégios administrativos.

# 5. PERMISSÕES

As permissões serão declaradas na configuração do Shield e verificadas pelo backend.

A estrutura utilizará identificadores específicos por funcionalidade, por exemplo:

- `dashboard.access`;
- `devices.view`;
- `devices.create`;
- `devices.update`;
- `devices.delete`;
- `events.view`;
- `events.manage`;
- `automations.view`;
- `automations.manage`;
- `users.view`;
- `users.manage`;
- `settings.manage`.

A lista definitiva deverá acompanhar as funcionalidades reais da plataforma.

O frontend poderá ocultar funcionalidades não autorizadas, mas a verificação de segurança deverá ocorrer obrigatoriamente no backend.

As permissões individuais serão utilizadas somente quando houver necessidade concreta de exceções às permissões dos grupos.

# 6. CADASTRO E GERENCIAMENTO DE USUÁRIOS

O gerenciamento de contas deverá ser controlado.

A configuração de cadastro público será desabilitada inicialmente, pois a plataforma possui perfis de acesso operacionais e administrativos.

Novos usuários deverão ser cadastrados por um processo autorizado, com atribuição controlada de grupos.

O formulário de solicitação de acesso poderá continuar existindo na interface, mas uma solicitação não concederá automaticamente acesso à plataforma.

A aprovação e a criação efetiva da conta serão operações distintas.

# 7. PERSONALIZAÇÃO DA ESTRUTURA

A aplicação poderá utilizar extensões compatíveis do modelo e da entidade de usuário fornecidos pelo Shield.

A personalização deverá preservar:

- O gerenciamento de credenciais pelo Shield.
- O processo de autenticação oficial.
- O gerenciamento de grupos e permissões.
- A compatibilidade com as migrations da biblioteca.

Não será implementado armazenamento manual de senhas nem autenticação paralela.

# 8. IMPACTO NO PLANEJAMENTO DAS MIGRATIONS

A adoção do Shield modifica o planejamento inicial do grupo de usuários e permissões.

As tabelas da biblioteca deverão ser criadas pelas migrations próprias do Shield, em vez de reproduzirmos manualmente suas estruturas.

A sequência será:

1. Conferir as dependências atuais do Composer.
2. Instalar `codeigniter4/shield`.
3. Executar o comando oficial de configuração.
4. Revisar os arquivos de configuração gerados.
5. Definir grupos e permissões do IoT-Edge Command Center.
6. Conferir as migrations disponibilizadas pela biblioteca.
7. Executar as migrations em ambiente controlado.
8. Verificar as tabelas criadas no MySQL.
9. Testar cadastro, login, logout e autorização.

As tabelas fornecidas pelo Shield serão consideradas infraestrutura de autenticação do projeto, e não novas entidades de negócio da modelagem IoT-Edge.

# 9. ESTADO DA DECISÃO

| Item | Situação |
|---|---|
| Escolha do CodeIgniter Shield | Definida |
| Autenticação própria | Descartada |
| Gerenciamento de credenciais | Delegado ao Shield |
| Grupos e permissões | Delegados ao Shield |
| Grupos administrador, operador e visualizador | Definidos inicialmente |
| Cadastro público | Desabilitado inicialmente |
| Extensão dos dados do usuário | Pendente de análise |
| Instalação via Composer | Pendente |
| Configuração do Shield | Pendente |
| Migrations da biblioteca | Pendentes |
| Testes de autenticação | Pendentes |

# 10. CONCLUSÃO

A adoção do CodeIgniter Shield estabelece uma solução oficial, integrada e extensível para autenticação e autorização do IoT-Edge Command Center.

A mudança elimina a necessidade de manter uma implementação própria de autenticação e evita duplicação de tabelas e mecanismos de gerenciamento de credenciais.

A estrutura de usuários e permissões deverá ser implementada sobre as tabelas e configurações da biblioteca, preservando a possibilidade de extensão para atender às necessidades específicas da plataforma.

A próxima atividade será instalar e configurar o Shield, validar suas migrations e somente depois concluir a estrutura de usuários e permissões no banco de dados `iot_edge_center`.