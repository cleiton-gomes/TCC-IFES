# RELATÓRIO DE ACOMPANHAMENTO DO DESENVOLVIMENTO
## IoT-Edge Command Center — Integração MQTT com BitDogLab

**Projeto:** IoT-Edge Command Center  
**Etapa:** Primeiro ciclo de integração entre dispositivo embarcado, broker MQTT e backend PHP  
**Plataforma embarcada:** BitDogLab — RP2040 / Raspberry Pi Pico W  
**Firmware:** Raspberry Pi Pico SDK 2.1.1 / C  
**Backend:** CodeIgniter 4 / PHP 8.2  
**Protocolo de comunicação:** MQTT  
**Broker:** Mosquitto — Raspberry Pi  
**Banco de dados previsto:** MySQL  
**Situação geral:** Comunicação embarcada validada; consumer MQTT de teste implementado e validado no backend PHP; modelagem e persistência MySQL pendentes.

---

# 1. OBJETIVO DO DESENVOLVIMENTO

Esta etapa tem como objetivo estabelecer a comunicação entre dispositivos embarcados e a plataforma IoT-Edge Command Center, utilizando MQTT como protocolo principal para transmissão de telemetria.

A proposta é desenvolver uma arquitetura centralizada na qual os dispositivos enviem suas leituras ao broker MQTT, enquanto um processo independente do backend seja responsável por receber, validar, processar e disponibilizar essas informações às diferentes áreas do sistema.

O Dashboard será uma das primeiras páginas a utilizar os dados recebidos, mas não será responsável pelo processamento exclusivo ou pelo armazenamento central da telemetria.

Também foi definida uma estratégia de armazenamento controlado, com retenção das últimas 100 leituras por dispositivo e preservação de conjuntos de leituras relacionados a eventos.

O desenvolvimento está sendo realizado incrementalmente, preservando o firmware já funcional e a estrutura existente da aplicação web.

# 2. AMBIENTE E ESTRUTURA DO PROJETO

## 2.1. Dispositivo embarcado

O primeiro dispositivo utilizado nos testes é a BitDogLab, baseada no microcontrolador RP2040, com conectividade Wi-Fi.

O desenvolvimento do firmware está sendo realizado com:

- Raspberry Pi Pico SDK 2.1.1;
- CMake;
- Ninja;
- Visual Studio Code;
- Linguagem C.

Diretório do firmware:

`C:\Users\Cleiton\Documents\TCC-IFES\hardware\Atividade_5_MQTT_4`

Monitoramento serial:

- Porta COM16.

## 2.2. Infraestrutura MQTT

O broker Mosquitto está instalado na Raspberry Pi, com os seguintes parâmetros utilizados nos testes:

| Parâmetro | Configuração |
|---|---|
| Broker | Mosquitto |
| Endereço | 10.0.0.5 |
| Porta | 1883 |
| Autenticação | Sem autenticação no ambiente de testes |
| IP recebido pela BitDogLab | 10.0.0.8 |
| Comunicação | MQTT sobre rede local |

O endereço `10.0.0.5` corresponde ao broker utilizado no ambiente de desenvolvimento. A configuração definitiva de segurança, autenticação e acesso deverá ser avaliada antes de uma implantação em produção.

## 2.3. Aplicação web

A aplicação web existente utiliza:

- CodeIgniter 4;
- PHP 8.2;
- Bootstrap 5;
- Composer.

Diretório:

`C:\Users\Cleiton\Documents\TCC-IFES\web\iot-edge-command-center`

A aplicação possui estrutura MVC e páginas destinadas às diferentes funcionalidades da plataforma.

Controladores identificados:

- `Dashboard.php`;
- `Embarcados.php`;
- `Diagnostico.php`;
- `Historico.php`;
- `Locais.php`;
- `Automacoes.php`;
- `Gemeo.php`;
- `Videomonitoramento.php`;
- `Login.php`;
- `Home.php`.

As rotas correspondentes estão declaradas em `app\Config\Routes.php`.

Na inspeção realizada, a pasta `app\Models` estava vazia, sem modelos implementados.

## 2.4. Dependências e preparação do ambiente PHP

Foi verificado o ambiente PHP utilizado pelo projeto:

| Componente | Situação |
|---|---|
| PHP CLI | 8.2.33 |
| Composer | 2.10.3 |
| OpenSSL | Disponível |
| PDO MySQL | Disponível |
| Extensão sockets | Não apareceu na verificação inicial com `php -m` |
| Biblioteca MQTT | Instalada |

A biblioteca utilizada para a comunicação MQTT é:

`php-mqtt/client`

Instalação realizada na raiz do projeto:

```cmd
composer require php-mqtt/client
```

O Composer instalou:

- `php-mqtt/client` — versão 2.3.2;
- `myclabs/php-enum` — versão 1.8.5.

Os arquivos `composer.json`, `composer.lock` e o diretório `vendor` foram atualizados.

A instalação foi concluída com sucesso, sem indicação de vulnerabilidades pelo Composer.

Também foi verificada a conectividade TCP entre o computador de desenvolvimento e o broker:

```powershell
Test-NetConnection 10.0.0.5 -Port 1883
```

Resultado observado:

`TcpTestSucceeded: True`

Isso confirmou que o computador consegue alcançar a porta MQTT do broker na rede local.

A ausência da extensão `sockets` na verificação inicial foi registrada como um ponto de atenção do ambiente PHP. Apesar disso, o consumer de teste conseguiu estabelecer a conexão MQTT e receber mensagens. A necessidade de habilitar ou revisar extensões deverá ser reavaliada caso surjam problemas em operações específicas da biblioteca.

# 3. DESENVOLVIMENTO DO FIRMWARE

## 3.1. Comunicação Wi-Fi e MQTT

Foi desenvolvido o firmware inicial para conectar a BitDogLab à rede Wi-Fi e estabelecer comunicação com o broker Mosquitto.

O firmware já realizava publicações periódicas no tópico:

`pico/PING`

Também foi implementada a publicação de status com retenção (*retained*), permitindo que o estado publicado permaneça disponível para novos assinantes, conforme as características do MQTT.

## 3.2. Leitura analógica do joystick

Posteriormente, o firmware foi ampliado para realizar a leitura dos dois eixos analógicos do joystick da BitDogLab.

Configuração utilizada:

| Eixo | GPIO | Canal ADC |
|---|---|---|
| X | GPIO26 | ADC0 |
| Y | GPIO27 | ADC1 |

Para reduzir oscilações nas leituras, foi implementada uma rotina de aquisição com 100 amostras e cálculo da mediana.

O procedimento consiste em coletar as amostras, ordená-las e utilizar o valor central ou a média dos dois valores centrais, conforme a quantidade de amostras.

A publicação dos dados ocorre aproximadamente a cada 500 milissegundos.

## 3.3. Dependências de compilação

Para utilizar o conversor analógico, foi incluído:

```c
#include "hardware/adc.h"
```

Também foi adicionada a dependência `hardware_adc` à configuração `target_link_libraries` do CMakeLists.

Após essa alteração, o firmware foi compilado com sucesso e a leitura analógica passou a funcionar.

## 3.4. Estrutura da mensagem MQTT

O tópico utilizado para a telemetria é:

`iot-edge/dispositivos/bitdoglab01/dados`

O payload publicado possui a seguinte estrutura:

```json
{
  "dispositivo": "bitdoglab01",
  "origem": "simulador",
  "sensores": {
    "joystick": {
      "x": 2027,
      "y": 2142
    }
  }
}
```

O campo `dispositivo` identifica a origem lógica da mensagem, enquanto `sensores` agrupa os valores coletados.

O campo `origem` está sendo utilizado como identificação do contexto de teste e deverá ser revisto quando o dispositivo passar a operar em sua configuração definitiva.

O valor `bitdoglab01` representa a identificação utilizada neste teste. A plataforma deverá permitir o cadastro dinâmico de diferentes dispositivos, sem depender de um identificador fixo no código do backend.

# 4. TESTES E VALIDAÇÕES DO DISPOSITIVO E DO BROKER

## 4.1. Teste de recepção no broker

Na Raspberry Pi, foi utilizado o comando:

```bash
mosquitto_sub -h localhost -p 1883 -t '#' -v -R
```

Esse comando permitiu acompanhar as publicações recebidas pelo broker.

Também foram realizadas verificações direcionadas ao tópico de telemetria:

`iot-edge/dispositivos/bitdoglab01/dados`

## 4.2. Variação dos valores do joystick

Durante os testes, foram observados valores próximos de 20 até 4084, dependendo da posição do joystick.

Em repouso, foram observadas leituras próximas de:

| Eixo | Valor aproximado |
|---|---:|
| X | 2027 |
| Y | 2142 |

A movimentação do joystick provocou alterações nos valores publicados, confirmando a aquisição dos sinais analógicos.

## 4.3. Ocorrência temporária de falha na publicação

Durante uma das sessões de testes, as mensagens deixaram temporariamente de chegar ao broker, embora o firmware continuasse apresentando registros de tentativa de publicação.

Foi verificado que o serviço Mosquitto estava em execução e que o broker permanecia no endereço `10.0.0.5`.

Após reiniciar os terminais de teste e a Raspberry Pi, a publicação foi restabelecida.

Não foi necessário modificar o firmware para solucionar essa ocorrência.

O episódio foi registrado como uma observação do ambiente de testes, não como evidência de falha permanente no firmware ou no protocolo.

## 4.4. Resultado da validação embarcada

Foram validados:

- Inicialização e funcionamento do firmware;
- Conexão Wi-Fi da BitDogLab;
- Comunicação MQTT com o Mosquitto;
- Publicação periódica de mensagens;
- Leitura analógica dos eixos X e Y;
- Tratamento das amostras por mediana;
- Estrutura JSON da telemetria;
- Recepção das mensagens na Raspberry Pi;
- Manutenção das publicações anteriores de PING.

**Conclusão:** a comunicação entre o dispositivo embarcado e o broker MQTT está funcionando no ambiente de testes.

# 5. INSPEÇÃO E PREPARAÇÃO DO BACKEND MQTT

Esta etapa foi iniciada após a validação da comunicação entre a BitDogLab e o broker.

## 5.1. Verificação da aplicação CodeIgniter

Foi inspecionada a estrutura da aplicação web, incluindo:

- Controladores;
- Rotas;
- Views;
- Pasta de modelos;
- Configuração geral do projeto.

Foi identificado que o controlador `Dashboard.php` possui implementação simples, retornando a respectiva View:

```php
<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('pages/dashboard');
    }
}
```

A pasta `app\Models` estava vazia na inspeção realizada.

A estrutura existente foi preservada, sem substituição da arquitetura MVC.

## 5.2. Preparação da biblioteca MQTT

Foi instalada a biblioteca `php-mqtt/client` por meio do Composer, conforme registrado na seção 2.4.

Essa instalação preparou o ambiente PHP para estabelecer a comunicação MQTT sem depender de uma implementação própria do protocolo.

## 5.3. Criação do primeiro consumer MQTT

Foi criado o diretório:

`scripts`

Na raiz do projeto, foi criado o arquivo:

`scripts\mqtt-consumer.php`

O script utiliza o autoload do Composer e a classe `MqttClient` da biblioteca instalada.

Sua configuração inicial utiliza:

| Parâmetro | Valor |
|---|---|
| Broker | 10.0.0.5 |
| Porta | 1883 |
| Client ID | iot-edge-command-center-consumer-test |
| Tópico | iot-edge/dispositivos/+/dados |
| QoS | 0 |

O caractere `+` é utilizado como curinga de nível único no tópico MQTT. Dessa forma, o consumer consegue receber mensagens de diferentes dispositivos que sigam a estrutura definida, sem precisar cadastrar cada identificador diretamente no código de assinatura.

O script executa as seguintes operações:

1. Inicializa a biblioteca MQTT;
2. Estabelece conexão com o broker;
3. Assina o tópico de telemetria;
4. Aguarda novas mensagens;
5. Exibe o tópico recebido;
6. Informa se a mensagem é retida;
7. Decodifica o payload JSON;
8. Exibe os dados recebidos em formato organizado;
9. Informa erros de conexão ou processamento.

O consumer é executado de forma independente das requisições HTTP:

```cmd
php scripts\mqtt-consumer.php
```

A decisão de manter o processo independente evita que uma página web precise permanecer aberta ou executar uma conexão MQTT contínua para receber telemetria.

## 5.4. Teste de recepção pelo PHP

O consumer foi executado com sucesso e apresentou:

```text
============================================
 IoT-Edge Command Center - MQTT Consumer
============================================
Broker: 10.0.0.5:1883
Topico: iot-edge/dispositivos/+/dados
Conectando...
Conexao MQTT estabelecida.
Aguardando mensagens...
```

Posteriormente, foram recebidas mensagens publicadas pela BitDogLab.

Exemplo observado no terminal:

```text
Recebido em: 19/11/2026 05:36:00
Topico: iot-edge/dispositivos/bitdoglab01/dados
Retida: Nao
Payload JSON:
{
    "dispositivo": "bitdoglab01",
    "origem": "simulador",
    "sensores": {
        "joystick": {
            "x": 2028,
            "y": 2142
        }
    }
}
```

Foram observadas diversas mensagens, com intervalo aproximado de 0,5 a 1 segundo.

O JSON foi decodificado corretamente pelo PHP e seus campos foram exibidos no terminal.

## 5.5. Resultado da validação MQTT com PHP

A recepção ponta a ponta foi validada:

```text
BITDOGLAB
    |
    | MQTT
    v
MOSQUITTO — RASPBERRY PI
    |
    | MQTT
    v
PHP — CONSUMER MQTT
    |
    v
RECEPÇÃO E DECODIFICAÇÃO JSON
```

**Conclusão:** o backend PHP já conseguiu conectar ao broker e receber as mensagens publicadas pela BitDogLab.

Entretanto, o script atual é um consumer de teste. Ele ainda não realiza cadastro, validação de dispositivos, persistência no MySQL ou disponibilização de dados para as páginas web.

Essa distinção deverá ser mantida no acompanhamento do desenvolvimento.

# 6. INSPEÇÃO DO DASHBOARD EXISTENTE

Antes de modificar a interface, foi realizada uma inspeção do arquivo:

`app\Views\pages\dashboard.php`

O arquivo possui aproximadamente 56 KB e 1.379 linhas na inspeção realizada.

Também foram identificadas outras Views existentes:

| Arquivo | Tamanho aproximado |
|---|---:|
| automacoes.php | 95.999 bytes |
| diagnostico.php | 24.670 bytes |
| embarcados.php | 79.930 bytes |
| gemeo.php | 35.298 bytes |
| historico.php | 32.942 bytes |
| locais.php | 69.884 bytes |
| videomonitoramento.php | 40.475 bytes |

Esses arquivos foram identificados para orientar a integração futura, sem que isso represente uma implementação de comunicação MQTT nessas páginas.

## 6.1. Estrutura visual e recursos existentes

O Dashboard possui:

- Indicadores gerais;
- Contagem de dispositivos;
- Estados de comunicação;
- Informações de sensores;
- Gráficos;
- Filtros;
- Pesquisa de dispositivos;
- Detalhamento individual;
- Indicadores de alertas;
- Informações demonstrativas de equipamentos.

Foi identificada a utilização da biblioteca Chart.js por CDN.

Entre os elementos e funções encontrados estão:

- `readingsChart`;
- `statusChart`;
- `deviceDetailChart`;
- `updateHistoryChart()`;
- `createReadingsChart()`;
- `initDeviceDetailChart()`;
- `createStatusChart()`;
- `updateMetrics()`;
- `refreshDemo()`;
- `initDashboard()`.

## 6.2. Identificação dos dados demonstrativos

A interface utiliza estruturas JavaScript com dados fictícios, incluindo uma lista de dispositivos de demonstração:

- `EMB-GAS-001`;
- `AUD-001`;
- `REL-001`;
- `CAM-001`;
- `DRONE-001`.

Também existem informações demonstrativas de sensores, RSSI, SNR, status, gráficos e históricos.

A função `refreshDemo()` recalcula os valores da simulação, sem consultar dispositivos reais.

Na inspeção, não foram identificadas chamadas `fetch`, AJAX, WebSocket ou conexões MQTT no arquivo analisado.

Os elementos de interface existentes deverão ser preservados durante a integração. A substituição dos dados demonstrativos deverá ocorrer gradualmente, conforme as informações reais forem disponibilizadas pelo backend.

## 6.3. Decisão sobre o cadastro dinâmico

Os dispositivos presentes na interface são exemplos demonstrativos, não uma lista definitiva de equipamentos da plataforma.

A área Embarcados deverá ser responsável pelo cadastro e gerenciamento dinâmico dos dispositivos.

O backend não deverá depender de uma lista fixa de identificadores escrita diretamente no código.

A BitDogLab será utilizada como primeiro dispositivo de teste, mas a arquitetura deverá permitir a inclusão futura de outros equipamentos.

# 7. ARQUITETURA DE INTEGRAÇÃO DEFINIDA

Durante o desenvolvimento, foi definida uma arquitetura centralizada para evitar que cada página da aplicação tenha seu próprio consumidor MQTT.

O fluxo planejado é:

```text
BITDOGLAB — RP2040
       |
       | MQTT
       v
MOSQUITTO — RASPBERRY PI
       |
       | MQTT
       v
CONSUMER MQTT CENTRALIZADO
       |
       v
BACKEND — CODEIGNITER 4 / PHP
       |
       v
VALIDAÇÃO E PROCESSAMENTO
       |
       v
MYSQL — PERSISTÊNCIA CENTRAL
       |
       v
CAMADA DE ACESSO AOS DADOS
       |
       +-----------------------+
       |           |           |
       v           v           v
   DASHBOARD  DIAGNÓSTICO  HISTÓRICO
       |
       +-----------------------+
                   |
                   v
          DEMAIS FUNCIONALIDADES
```

O consumer MQTT deverá funcionar como um processo independente, não vinculado à abertura de uma página ou à duração de uma requisição HTTP.

O banco MySQL será utilizado como fonte persistente e compartilhada dos dados, permitindo que diferentes páginas consultem as mesmas informações.

A estratégia de armazenamento temporário em memória poderá ser utilizada em operações internas específicas, mas não será considerada mecanismo suficiente para compartilhar dados entre um processo PHP CLI e as requisições HTTP da aplicação.

Como esses processos possuem espaços de memória distintos, o MySQL deverá ser a fonte central de dados compartilhados.

## 7.1. Responsabilidades do consumer

A implementação definitiva deverá realizar:

1. Conexão contínua ao broker;
2. Assinatura dos tópicos de telemetria;
3. Recepção das mensagens;
4. Validação da estrutura JSON;
5. Extração do identificador do dispositivo;
6. Validação do cadastro do dispositivo;
7. Identificação dos sensores;
8. Tratamento dos valores recebidos;
9. Registro da data e hora de recebimento pelo servidor;
10. Persistência das leituras;
11. Atualização das informações de comunicação do dispositivo;
12. Tratamento de erros e reconexões.

O consumer não deverá assumir que toda mensagem recebida pertence a um dispositivo autorizado ou cadastrado.

## 7.2. Estratégia de implementação no CodeIgniter

O primeiro consumer foi criado como script independente em `scripts\mqtt-consumer.php`.

Para a implementação definitiva, deverá ser avaliada a criação de um comando CLI próprio do CodeIgniter 4, por exemplo:

`php spark mqtt:consume`

Essa abordagem poderá aproveitar a configuração, os serviços e a conexão com o banco já disponíveis na aplicação.

A decisão será tomada após a inspeção da configuração atual do banco e das possibilidades de inicialização do framework em um processo contínuo.

Independentemente da forma escolhida, o consumer continuará independente das requisições HTTP e não será implementado dentro do controlador do Dashboard.

# 8. RESPONSABILIDADES DAS PÁGINAS

Foi reforçada a separação entre as funcionalidades das páginas existentes.

| Página | Responsabilidade planejada |
|---|---|
| Dashboard | Apresentar indicadores gerais, estados atuais e informações resumidas dos dispositivos. |
| Embarcados | Cadastrar, consultar, editar e excluir dispositivos. |
| Diagnóstico | Analisar telemetria, conectividade e condições dos dispositivos. |
| Histórico | Consultar leituras e eventos preservados no banco de dados. |
| Locais | Gerenciar edifícios, andares, salas, zonas e demais localizações. |
| Automações | Gerenciar regras e ações automatizadas. |
| Gêmeo Digital | Representar os dispositivos e seus estados no ambiente digital. |
| Videomonitoramento | Gerenciar e visualizar os recursos de monitoramento por câmeras. |

O Dashboard não será responsável pelo cadastro dos dispositivos.

O cadastro será realizado pela área Embarcados, com informações armazenadas no banco de dados.

O backend utilizará essa identificação para validar as mensagens recebidas.

Dessa forma, uma mensagem MQTT não será considerada automaticamente válida apenas porque foi recebida pelo broker.

# 9. MODELAGEM E ESTRATÉGIA DE ARMAZENAMENTO

Uma das decisões importantes desta etapa foi estabelecer que o banco de dados não deverá armazenar indefinidamente todas as mensagens MQTT recebidas.

A proposta é trabalhar com uma janela móvel de 100 leituras recentes por dispositivo e preservar conjuntos específicos de leituras quando ocorrerem eventos de interesse.

A implementação dessa política depende da modelagem definitiva do banco de dados.

## 9.1. Estruturas inicialmente previstas

Como planejamento inicial, foram identificadas as seguintes entidades:

| Estrutura | Finalidade |
|---|---|
| `dispositivos` | Armazenar o cadastro e a identificação dos dispositivos. |
| `sensores` | Registrar os sensores associados aos dispositivos e suas características. |
| `leituras` | Armazenar os valores de telemetria, data/hora e origem da leitura. |
| `eventos` | Registrar eventos, horários de início e encerramento e estado da captura. |
| `evento_leituras` | Relacionar as leituras preservadas aos respectivos eventos. |

Essa relação representa uma proposta inicial, não uma lista definitiva de tabelas.

A modelagem deverá considerar a estrutura geral da plataforma, especialmente o gerenciamento de locais e o cadastro dinâmico de dispositivos, evitando duplicação de informações.

Também será necessário avaliar se os valores dos sensores serão armazenados em registros individuais ou em uma estrutura de payload com múltiplas variáveis, considerando a flexibilidade da plataforma e a eficiência das consultas.

## 9.2. Funcionamento normal

Enquanto não houver evento ativo, o sistema deverá manter as 100 leituras mais recentes de cada dispositivo.

A cada nova leitura:

1. A mensagem será recebida e validada;
2. O dispositivo será identificado;
3. A leitura será processada;
4. A janela de leituras recentes será atualizada;
5. A leitura mais antiga deixará a janela quando o limite de 100 for ultrapassado;
6. Os dados serão disponibilizados às páginas que necessitem da telemetria atual.

A retenção definitiva dessas leituras no MySQL deverá seguir a política definida na implementação.

## 9.3. Captura de eventos

Quando um evento for disparado, o sistema deverá preservar:

- As 100 leituras anteriores ao disparo;
- Todas as leituras produzidas durante o evento;
- As 100 leituras posteriores ao encerramento.

O fluxo será:

```text
FUNCIONAMENTO NORMAL
       |
       v
JANELA DAS 100 ÚLTIMAS LEITURAS
       |
       v
EVENTO DISPARADO
       |
       v
PRESERVAR 100 LEITURAS ANTERIORES
       |
       v
REGISTRAR TODAS AS LEITURAS DO EVENTO
       |
       v
EVENTO ENCERRADO
       |
       v
COLETAR MAIS 100 LEITURAS
       |
       v
CAPTURA CONCLUÍDA
       |
       v
PRESERVAR CONJUNTO NO HISTÓRICO
```

Por exemplo, se um evento produzir 30 leituras durante sua ocorrência, o conjunto preservado terá:

100 leituras anteriores + 30 leituras durante o evento + 100 leituras posteriores = 230 leituras.

O número de leituras durante o evento será variável, conforme a duração da ocorrência e a frequência de publicação do dispositivo.

As 100 leituras representam quantidade de amostras, não duração fixa. Com uma publicação a cada 500 milissegundos, 100 leituras correspondem aproximadamente a 50 segundos, desde que a frequência seja mantida.

## 9.4. Recuperação após falhas

Foi identificada a necessidade de não depender exclusivamente da memória RAM para controlar capturas de eventos.

Se o backend reiniciar durante uma captura, o sistema deverá conseguir recuperar:

- O evento em andamento;
- O estado da captura;
- As leituras anteriores já preservadas;
- A quantidade de leituras posteriores ainda necessária.

Por isso, o estado dos eventos ativos deverá ser persistido no banco de dados.

A janela circular de leituras recentes também deverá ser considerada no planejamento de recuperação, evitando a perda do pré-evento em situações de reinicialização.

## 9.5. Política de retenção ainda pendente de detalhamento

A regra das 100 leituras está definida conceitualmente, mas ainda será necessário determinar:

- Como será implementada a janela móvel;
- Como serão preservadas as leituras relacionadas a eventos;
- Como serão removidas leituras que não pertençam a capturas preservadas;
- Como serão tratados eventos simultâneos;
- Como serão tratados dispositivos com frequências diferentes de publicação;
- Como serão recuperadas capturas interrompidas;
- Como serão mantidas a integridade e a associação entre leituras e eventos.

Essas decisões deverão ser documentadas durante a implementação das migrations e da camada de processamento.

# 10. O QUE FOI IMPLEMENTADO E O QUE AINDA ESTÁ PENDENTE

É importante distinguir as funcionalidades já implementadas e testadas daquelas que ainda pertencem ao planejamento.

## 10.1. Implementado e validado

- Firmware da BitDogLab com conexão Wi-Fi;
- Leitura analógica dos eixos X e Y;
- Tratamento das amostras por mediana;
- Publicação periódica MQTT;
- Estrutura JSON da telemetria;
- Broker Mosquitto em funcionamento nos testes;
- Recepção das mensagens na Raspberry Pi;
- Instalação da biblioteca `php-mqtt/client`;
- Preparação do diretório `scripts`;
- Criação do consumer MQTT de teste;
- Conexão do consumer PHP ao broker;
- Assinatura do tópico com curinga;
- Recepção das mensagens publicadas pela BitDogLab;
- Decodificação do JSON pelo PHP;
- Inspeção inicial do Dashboard e identificação de seus dados demonstrativos.

## 10.2. Planejado, mas ainda não implementado

- Consumer MQTT definitivo integrado aos serviços do CodeIgniter;
- Validação do cadastro dos dispositivos;
- Modelagem definitiva do banco MySQL;
- Criação das migrations;
- Implementação dos modelos;
- Cadastro dinâmico de dispositivos e sensores;
- Persistência das leituras;
- Atualização da última comunicação dos dispositivos;
- Camada central para consulta dos dados;
- Integração do Dashboard com telemetria real;
- Janela móvel das 100 leituras;
- Preservação do pré-evento;
- Registro das leituras durante o evento;
- Captura das 100 leituras posteriores;
- Recuperação de capturas após reinicialização;
- Integração das demais páginas;
- Política definitiva de retenção e limpeza dos dados.

# 11. PLANO DE CONTINUIDADE DO DESENVOLVIMENTO

A continuidade será realizada em etapas, priorizando a modelagem do banco antes da integração definitiva da persistência.

## Etapa 1 — Inspeção da configuração MySQL

Verificar:

- Se o banco `iot_edge_center` já existe;
- Qual driver de banco está configurado;
- Qual host e porta são utilizados;
- Se existem migrations;
- Se existem tabelas já implementadas;
- Se a conexão do CodeIgniter com o banco está funcionando.

Não deverão ser compartilhadas senhas ou outras credenciais durante a inspeção.

**Resultado esperado:** conhecer o estado real do banco e evitar criar estruturas duplicadas.

## Etapa 2 — Modelagem do banco de dados

Definir:

- Cadastro dinâmico de dispositivos;
- Associação dos dispositivos aos locais;
- Cadastro dos sensores;
- Estrutura de leituras;
- Estrutura de eventos;
- Associação entre eventos e leituras;
- Índices e restrições;
- Política de retenção;
- Recuperação de capturas ativas.

A modelagem deverá considerar a estrutura geral do IoT-Edge Command Center, não somente a BitDogLab.

**Resultado esperado:** modelo de dados consistente com as funcionalidades da plataforma.

## Etapa 3 — Criação das migrations e modelos

- Criar ou atualizar as migrations do CodeIgniter;
- Implementar as tabelas aprovadas;
- Definir chaves primárias e estrangeiras;
- Criar índices necessários;
- Implementar os modelos;
- Validar a estrutura no MySQL.

**Resultado esperado:** banco preparado para cadastrar dispositivos e armazenar telemetria.

## Etapa 4 — Implementação do consumer MQTT definitivo

- Reaproveitar a biblioteca MQTT já instalada;
- Avaliar a implementação como comando CLI do CodeIgniter;
- Manter o processo independente das requisições HTTP;
- Conectar ao Mosquitto;
- Receber os tópicos de telemetria;
- Validar o JSON;
- Validar o dispositivo cadastrado;
- Identificar os sensores;
- Registrar data e hora de recebimento;
- Persistir as leituras no MySQL;
- Tratar desconexões, erros e reconexões.

**Resultado esperado:** consumer centralizado recebendo e persistindo mensagens válidas.

## Etapa 5 — Disponibilização dos dados pelo backend

- Implementar a camada de acesso aos dados;
- Disponibilizar a última leitura de cada dispositivo;
- Consultar estado e última comunicação;
- Disponibilizar dados históricos;
- Definir como as páginas acessarão essas informações;
- Garantir que diferentes páginas utilizem a mesma fonte central.

**Resultado esperado:** dados reais acessíveis às funcionalidades da aplicação.

## Etapa 6 — Integração do Dashboard

- Preservar a interface existente;
- Identificar os componentes que utilizam dados fictícios;
- Conectar os indicadores às informações reais;
- Exibir os dispositivos cadastrados dinamicamente;
- Apresentar os valores dos sensores;
- Atualizar os estados de comunicação;
- Integrar os gráficos aos dados disponíveis;
- Diferenciar informações reais e demonstrativas.

**Resultado esperado:** Dashboard exibindo telemetria real sem reconstruir desnecessariamente a interface.

## Etapa 7 — Validação ponta a ponta

Testar todo o percurso:

```text
BITDOGLAB
   |
   v
MOSQUITTO
   |
   v
CONSUMER MQTT
   |
   v
BACKEND CODEIGNITER
   |
   v
MYSQL
   |
   v
DASHBOARD
```

Verificar:

- Frequência de recebimento;
- Integridade dos valores;
- Identificação do dispositivo;
- Persistência das leituras;
- Atualização das informações;
- Comportamento em caso de desconexão;
- Recuperação após reinicialização do consumer;
- Recuperação após reinicialização do backend.

**Resultado esperado:** primeiro ciclo completo de integração com persistência validado.

## Etapa 8 — Implementação da retenção e captura de eventos

- Implementar a janela móvel das 100 últimas leituras;
- Preservar o pré-evento;
- Registrar todas as leituras durante o evento;
- Continuar a captura após o encerramento;
- Finalizar após 100 leituras posteriores;
- Persistir o estado das capturas ativas;
- Implementar recuperação após falhas;
- Validar a preservação dos dados históricos.

**Resultado esperado:** captura controlada de telemetria associada aos eventos.

## Etapa 9 — Integração das demais páginas

- Integrar a página Embarcados ao cadastro dinâmico;
- Integrar a página Diagnóstico;
- Integrar a página Histórico;
- Integrar a página Gêmeo Digital;
- Integrar as informações necessárias ao Dashboard;
- Utilizar a mesma camada central de dados.

**Resultado esperado:** diferentes funcionalidades consumindo a mesma infraestrutura de telemetria.

# 12. DIRETRIZES TÉCNICAS A SEREM PRESERVADAS

Durante a continuidade do desenvolvimento, deverão ser mantidas as seguintes diretrizes:

1. MQTT continuará sendo o protocolo principal de telemetria dos dispositivos.
2. O recebimento MQTT será centralizado no backend.
3. As páginas não deverão implementar consumidores MQTT independentes.
4. O consumer deverá funcionar de forma independente das requisições HTTP.
5. O cadastro dos dispositivos será responsabilidade da área Embarcados.
6. O backend deverá validar a identificação dos dispositivos antes de associar suas leituras.
7. A plataforma deverá permitir cadastro dinâmico, sem depender de dispositivos fixos no código.
8. O Dashboard será consumidor dos dados, não o responsável por recebê-los e armazená-los exclusivamente.
9. O MySQL será utilizado como fonte persistente e compartilhada dos dados.
10. A retenção normal será limitada às 100 leituras mais recentes por dispositivo, conforme a política definitiva de armazenamento.
11. Capturas de eventos deverão preservar o pré-evento, o período do evento e o pós-evento.
12. O estado das capturas ativas não deverá depender exclusivamente da memória RAM.
13. As funcionalidades existentes da interface web deverão ser preservadas durante a integração.
14. Dados de teste e dados reais deverão ser diferenciados.
15. O firmware já validado não deverá ser alterado sem necessidade técnica identificada.
16. A implementação deverá ocorrer de forma incremental, com validação em cada etapa.

A utilização de WebSocket ou outros mecanismos de atualização entre backend e navegador poderá ser avaliada futuramente, caso seja necessária. Isso não altera a decisão de manter MQTT como transporte da telemetria dos dispositivos.

# 13. SITUAÇÃO GERAL DO PROJETO

| Componente | Situação |
|---|---|
| Firmware BitDogLab | Implementado e testado |
| Leitura do joystick X/Y | Implementada e testada |
| Tratamento por mediana | Implementado e testado |
| Conexão Wi-Fi | Validada |
| Publicação MQTT | Validada |
| Broker Mosquitto | Validado no ambiente de testes |
| Recepção MQTT na Raspberry Pi | Validada |
| Biblioteca MQTT para PHP | Instalada |
| Consumer MQTT de teste | Implementado e validado |
| Recepção MQTT pelo PHP | Validada |
| Decodificação JSON pelo PHP | Validada |
| Estrutura MVC da aplicação web | Existente |
| Rotas das páginas | Declaradas |
| Inspeção inicial do Dashboard | Realizada |
| Identificação dos dados demonstrativos | Realizada |
| Consumer MQTT definitivo | Pendente |
| Validação do dispositivo cadastrado | Pendente |
| Inspeção da configuração MySQL | Próxima etapa |
| Modelagem definitiva do banco | Pendente |
| Migrations e modelos | Pendentes |
| Persistência das leituras | Pendente |
| Integração real do Dashboard | Pendente |
| Retenção das últimas 100 leituras | Planejada |
| Captura e preservação de eventos | Planejada |
| Recuperação de capturas ativas | Planejada |
| Integração das demais páginas | Pendente |

# 14. CONSIDERAÇÕES FINAIS

O desenvolvimento permitiu concluir a primeira integração entre o dispositivo embarcado, a infraestrutura MQTT e o backend PHP.

Inicialmente, foi validada a aquisição de dados analógicos pela BitDogLab, sua transmissão em formato JSON e a recepção pelo broker Mosquitto.

Posteriormente, foi instalada a biblioteca `php-mqtt/client`, criado um consumer independente e validada a conexão do PHP com o broker. O backend recebeu diversas mensagens publicadas pela BitDogLab e conseguiu interpretar corretamente sua estrutura JSON.

Também foi realizada uma inspeção inicial da aplicação CodeIgniter 4 e do Dashboard, identificando a presença de dados demonstrativos e os pontos que deverão ser conectados à telemetria real.

Uma decisão arquitetural importante foi manter o recebimento MQTT centralizado, evitando que cada página implemente seu próprio consumidor. O processo de recebimento deverá ser independente das requisições HTTP, enquanto o MySQL será a fonte persistente e compartilhada das informações.

A estratégia de armazenamento também foi definida conceitualmente para priorizar informações relevantes: manutenção das últimas 100 leituras por dispositivo e preservação de conjuntos completos de leituras associados a eventos, contemplando o período anterior, durante e posterior à ocorrência.

**Próximo passo imediato:**

Inspecionar a configuração e o estado atual do banco MySQL, verificar a existência de migrations e tabelas e, em seguida, definir a modelagem de dados do IoT-Edge Command Center.

Somente depois dessa definição deverá ser implementada a persistência definitiva no consumer MQTT.

O desenvolvimento seguirá de forma incremental, com testes e validações em cada etapa, preservando o firmware já funcional e a estrutura existente da aplicação web.

---

**Registro de continuidade:** este documento deverá ser utilizado como referência para retomar o desenvolvimento em futuras sessões, permitindo identificar o que foi implementado, quais decisões foram tomadas, quais validações foram realizadas e quais etapas ainda precisam ser concluídas.
