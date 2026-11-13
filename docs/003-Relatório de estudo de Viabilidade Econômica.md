# RELATÓRIO DE ESTUDO DE VIABILIDADE ECONÔMICA — SISTEMA DE INSPEÇÃO INDUSTRIAL ASSISTIDA POR DRONE

## 1. Identificação do estudo

**Projeto:** Sistema de inspeção industrial assistida por drone, sensores embarcados e plataforma de apoio à análise operacional  
**Empresa de referência:** Gasmar — Companhia Maranhense de Gás  
**Local de referência:** São Luís, Maranhão  
**Natureza do estudo:** Estimativa preliminar de viabilidade técnica e econômica

## 2. Resumo executivo

Este estudo avalia a viabilidade econômica da utilização de um drone como ferramenta complementar para inspeções industriais, comparando o método convencional com uma alternativa baseada em drone, aquisição de dados e análise das condições dos equipamentos.

A análise considera o investimento em equipamentos, o desenvolvimento do sistema, os custos de mão de obra, a manutenção complementar e o impacto financeiro das interrupções operacionais.

Para a simulação, adota-se o valor de **R$ 10.000,00 por hora de parada operacional**. Esse valor é uma premissa econômica de referência e não representa uma tarifa oficial da Gasmar ou um custo de parada confirmado pela empresa.

A principal mudança em relação ao cenário anterior é a seguinte:

- **Inspeção convencional:** considera-se uma hora de parada operacional em cada inspeção.
- **Inspeção com drone:** considera-se que 60% das inspeções podem ser realizadas sem parada operacional e que somente 40% dos ciclos exigem intervenção com parada.
- **Manutenção complementar:** considera-se que 40% dos ciclos necessitam de manutenção, com quatro horas de intervenção e parada operacional.

Assim, no cenário projetado, o drone elimina a parada de uma hora associada à inspeção inicial, mas não elimina automaticamente as paradas necessárias para manutenção.

Com cinco inspeções por semana, vinte por mês e duzentas e quarenta por ano, o modelo estima uma economia operacional líquida potencial de **R$ 2.392.788,00 por ano**, considerando o impacto hipotético das paradas evitadas e os custos diretos estimados dos dois métodos.

Esse resultado depende de uma hipótese fundamental: a inspeção com drone deverá realmente permitir que a operação continue durante a inspeção inicial na maior parte dos casos. A economia não está comprovada em campo e deverá ser validada antes de ser apresentada como resultado real da empresa.

## 3. Objetivo

Avaliar preliminarmente os custos de implantação e operação de um sistema de inspeção industrial assistida por drone, considerando:

- Investimento inicial em equipamentos e desenvolvimento.
- Custos diretos da inspeção convencional e da inspeção com drone.
- Mão de obra necessária para inspeções e manutenções.
- Impacto financeiro das paradas operacionais.
- Redução potencial de interrupções com a adoção do drone.
- Economia potencial semanal, mensal, trimestral, semestral e anual.
- Condições necessárias para demonstrar a viabilidade econômica da solução.

## 4. Escopo e aplicação geral da solução

A proposta consiste em utilizar um drone para apoiar a inspeção visual de equipamentos e estruturas industriais, reduzindo a necessidade de exposição direta dos profissionais a determinados pontos de difícil acesso e fornecendo informações para a avaliação técnica.

Conforme as necessidades de cada aplicação, o sistema poderá integrar:

- Drone para aquisição de imagens e vídeos.
- Sensores complementares para medições específicas.
- Microcontroladores e comunicação sem fio.
- Software para organização, consulta e análise dos dados.
- Recursos para geração de relatórios e apoio à tomada de decisão.
- Procedimentos de inspeção, avaliação técnica e encaminhamento para manutenção.

Embora a Gasmar, localizada em São Luís, Maranhão, seja utilizada como referência contextual, a arquitetura proposta não se limita a uma empresa ou setor específico. A solução poderá ser adaptada a indústrias químicas, instalações de energia, mineração, manufatura, logística, infraestrutura e outras operações.

A adaptação exigirá a revisão dos equipamentos, dos sensores, das integrações, das regras de negócio, dos procedimentos de segurança e dos parâmetros econômicos de cada aplicação.

O drone não substitui automaticamente inspeções regulamentares, ensaios especializados ou avaliações de profissionais habilitados. Sua utilização dependerá da adequação técnica e operacional ao ambiente inspecionado.

## 5. Metodologia e premissas econômicas

Os valores utilizados foram organizados em três categorias:

1. **Valores de orçamento:** valores informados para os equipamentos do projeto.
2. **Estimativas de custos:** valores calculados a partir de referências salariais e hipóteses de desenvolvimento e operação.
3. **Premissas de simulação:** frequência das inspeções, percentual de intervenções, duração das paradas e custo operacional por hora, sujeitos à validação.

### 5.1. Frequência estimada das inspeções

| Período | Quantidade de inspeções |
|---|---:|
| Semana | 5 |
| Mês | 20 |
| Trimestre | 60 |
| Semestre | 120 |
| Ano | 240 |

A estimativa mensal considera quatro semanas, e os períodos trimestral e semestral são calculados com base em três e seis meses de vinte inspeções, respectivamente. Trata-se de uma simplificação para planejamento, não de um calendário operacional confirmado da Gasmar.

### 5.2. Custo de parada operacional adotado

Para os cálculos deste estudo, utiliza-se o valor de **R$ 10.000,00 por hora de parada operacional**.

Esse valor é uma premissa de simulação. Não representa uma tarifa oficial de transporte ou distribuição de gás natural, nem um custo financeiro confirmado da Gasmar.

É importante distinguir duas grandezas:

- **Tarifa de movimentação do gás:** valor comercial ou regulatório relacionado ao serviço de movimentação, conforme a modalidade contratual e as regras aplicáveis.
- **Custo de parada operacional:** impacto econômico decorrente da interrupção de uma atividade, que pode incluir perda de produção, custos de retomada, mão de obra ociosa e outras consequências.

Uma tarifa de movimentação não pode ser utilizada diretamente como se fosse o custo total de uma hora de parada industrial. Da mesma forma, o custo de parada não corresponde necessariamente ao valor integral do gás que deixou de ser movimentado.

O valor adotado deverá ser validado com dados operacionais e financeiros da instalação analisada.

| Tempo de parada | Impacto financeiro estimado |
|---|---:|
| 15 minutos | R$ 2.500,00 |
| 30 minutos | R$ 5.000,00 |
| 1 hora | R$ 10.000,00 |
| 2 horas | R$ 20.000,00 |
| 4 horas | R$ 40.000,00 |

Os valores são resultados matemáticos da premissa adotada, e não prejuízos observados na empresa.

### 5.3. Premissas de duração e manutenção

| Parâmetro | Método convencional | Método com drone |
|---|---|---|
| Duração estimada da inspeção inicial | 1 hora | 1 hora |
| Parada na inspeção inicial | 100% dos ciclos | 0% dos ciclos no cenário projetado |
| Ciclos que exigem manutenção complementar | 40% | 40% |
| Ciclos sem intervenção imediata | 60% | 60% |
| Duração da manutenção complementar | 4 horas | 4 horas |
| Profissionais na manutenção | 2 técnicos | 2 técnicos |
| Participação adicional do engenheiro | 1 hora por intervenção | 1 hora por intervenção |

No método convencional, considera-se que cada inspeção exige uma hora de parada operacional. No cenário com drone, considera-se que a inspeção inicial pode ocorrer sem interromper a operação.

Nos dois métodos, 40% dos ciclos exigem manutenção complementar, com quatro horas de parada operacional. Portanto, o drone reduz a parada inicial, mas não elimina a parada necessária para executar uma intervenção que exija indisponibilidade do equipamento.

A hipótese de que o drone permite realizar a inspeção sem parada deverá ser confirmada em campo. Ela depende das condições de segurança, dos procedimentos operacionais, da configuração da instalação e da possibilidade de realizar a atividade durante a operação normal.

## 6. Orçamento inicial de equipamentos

O orçamento de equipamentos informado para o projeto contempla os seguintes itens:

| Equipamento | Valor de referência |
|---|---:|
| Drone DJI Mini 4 Pro | R$ 6.250,00 |
| Dois sensores MH-440D NDIR | R$ 1.251,02 |
| Dois ESP32 com LoRa | R$ 797,80 |
| **Orçamento total de equipamentos adotado** | **R$ 8.339,80** |

Para manter uma abordagem conservadora, foi adotado o valor total de R$ 8.339,80 como orçamento de equipamentos.

O orçamento é preliminar e não contempla necessariamente todos os itens exigidos para uma aplicação industrial, como treinamento, seguros, licenças, acessórios, baterias adicionais, equipamentos de proteção ou soluções específicas para ambientes classificados.

A adequação do drone e dos sensores ao ambiente industrial precisa ser avaliada antes de qualquer operação. Não se deve presumir que o equipamento selecionado seja apropriado para atmosferas explosivas ou áreas classificadas.

## 7. Estimativa de desenvolvimento do sistema

O custo de desenvolvimento foi estimado utilizando valores de referência de custo empresarial por hora de trabalho.

As referências salariais utilizadas como base são provenientes do Portal Salário. Os custos horários adotados são estimativas de planejamento e não representam propostas comerciais ou custos contábeis efetivamente apurados.

| Profissional | Custo horário estimado |
|---|---:|
| Programador de sistemas | R$ 56,62 |
| Analista de desenvolvimento de sistemas | R$ 79,01 |
| Engenheiro eletricista | R$ 103,16 |
| Técnico de manutenção industrial | R$ 33,48 |

### 7.1. Cenário A — Desenvolvimento concentrado no programador

Considera-se a dedicação estimada de 120 horas de programação.

| Item | Cálculo | Custo |
|---|---|---:|
| Programador | 120 horas × R$ 56,62 | R$ 6.794,40 |
| Equipamentos | Orçamento adotado | R$ 8.339,80 |
| **Investimento inicial estimado** | | **R$ 15.134,20** |

### 7.2. Cenário B — Desenvolvimento multidisciplinar

Este cenário considera a participação de programador, analista e engenheiro eletricista.

| Profissional | Horas estimadas | Custo |
|---|---:|---:|
| Programador | 80 | R$ 4.529,60 |
| Analista de desenvolvimento | 40 | R$ 3.160,40 |
| Engenheiro eletricista | 40 | R$ 4.126,40 |
| **Total de desenvolvimento** | **160** | **R$ 11.816,40** |
| Equipamentos | | R$ 8.339,80 |
| **Investimento inicial estimado** | | **R$ 20.156,20** |

As horas de desenvolvimento são hipóteses de planejamento. O esforço real deverá ser validado após o detalhamento dos requisitos, da arquitetura, das integrações e dos testes.

## 8. Custo da inspeção convencional

A inspeção convencional foi estimada com uma hora de trabalho de um técnico e uma hora de trabalho de um engenheiro.

| Recurso | Tempo | Custo horário | Custo por inspeção |
|---|---:|---:|---:|
| Técnico de manutenção | 1 hora | R$ 33,48 | R$ 33,48 |
| Engenheiro eletricista | 1 hora | R$ 103,16 | R$ 103,16 |
| **Total de mão de obra** | | | **R$ 136,64** |

Esse valor representa o custo estimado de mão de obra da inspeção inicial. Não inclui a parada operacional, a montagem de andaimes, materiais, deslocamentos ou outras despesas específicas.

### 8.1. Custo da parada durante a inspeção convencional

No cenário-base, considera-se que a inspeção convencional exige uma hora de parada operacional em todos os ciclos.

| Componente | Valor |
|---|---:|
| Mão de obra da inspeção | R$ 136,64 |
| Parada operacional de 1 hora | R$ 10.000,00 |
| **Custo total estimado da inspeção convencional, incluindo parada** | **R$ 10.136,64** |

### 8.2. Projeção do custo da inspeção inicial convencional

| Período | Mão de obra | Impacto da parada | Total estimado |
|---|---:|---:|---:|
| Semana — 5 inspeções | R$ 683,20 | R$ 50.000,00 | R$ 50.683,20 |
| Mês — 20 inspeções | R$ 2.732,80 | R$ 200.000,00 | R$ 202.732,80 |
| Trimestre — 60 inspeções | R$ 8.198,40 | R$ 600.000,00 | R$ 608.198,40 |
| Semestre — 120 inspeções | R$ 16.396,80 | R$ 1.200.000,00 | R$ 1.216.396,80 |
| Ano — 240 inspeções | R$ 32.793,60 | R$ 2.400.000,00 | R$ 2.432.793,60 |

Esses resultados dependem da hipótese de que cada inspeção convencional efetivamente interrompe a operação por uma hora.

## 9. Custo da inspeção com drone

A inspeção com drone também foi estimada com uma hora de trabalho de um técnico e uma hora de trabalho de um engenheiro.

A mão de obra inicial foi mantida igual à do método convencional para evitar atribuir ao drone uma redução de tempo de trabalho ainda não demonstrada.

### 9.1. Mão de obra e custos operacionais estimados

| Componente | Critério | Custo por inspeção |
|---|---|---:|
| Mão de obra inicial | 1 hora de técnico e 1 hora de engenheiro | R$ 136,64 |
| Depreciação dos equipamentos | R$ 8.339,80 ao longo de 3 anos, com 240 inspeções anuais | R$ 11,58 |
| Provisão para manutenção dos equipamentos | 10% do investimento por ano, distribuídos por 240 inspeções | R$ 3,47 |
| Outros custos operacionais | Hipótese de planejamento | R$ 15,00 |
| **Custo estimado da inspeção com drone** | | **R$ 166,69** |

A provisão para manutenção e o custo operacional de R$ 15,00 por inspeção são hipóteses para o estudo. Deverão ser substituídos por custos efetivos quando houver dados de operação.

A depreciação representa a distribuição econômica do investimento ao longo da vida útil estimada. Ela não é um novo desembolso de caixa a cada inspeção.

### 9.2. Custo da parada durante a inspeção com drone

No cenário projetado, considera-se que a inspeção inicial com drone pode ser realizada sem interromper a operação.

| Componente | Valor |
|---|---:|
| Custo estimado da inspeção com drone | R$ 166,69 |
| Parada operacional durante a inspeção inicial | R$ 0,00 |
| **Custo total estimado da inspeção com drone, sem parada inicial** | **R$ 166,69** |

Esse cenário pressupõe que a operação poderá continuar durante a aquisição das imagens e a realização da inspeção. A hipótese deverá ser confirmada mediante avaliação técnica e de segurança.

### 9.3. Projeção do custo da inspeção inicial com drone

| Período | Custo direto da inspeção | Impacto da parada inicial | Total estimado |
|---|---:|---:|---:|
| Semana — 5 inspeções | R$ 833,45 | R$ 0,00 | R$ 833,45 |
| Mês — 20 inspeções | R$ 3.333,80 | R$ 0,00 | R$ 3.333,80 |
| Trimestre — 60 inspeções | R$ 10.001,40 | R$ 0,00 | R$ 10.001,40 |
| Semestre — 120 inspeções | R$ 20.002,80 | R$ 0,00 | R$ 20.002,80 |
| Ano — 240 inspeções | R$ 40.005,60 | R$ 0,00 | R$ 40.005,60 |

A tabela considera somente a inspeção inicial. As paradas necessárias para manutenção complementar são contabilizadas na comparação econômica completa.

## 10. Custo da manutenção complementar

Para representar os casos em que a inspeção identifica a necessidade de uma intervenção, considera-se uma manutenção complementar de quatro horas, com dois técnicos, além de uma hora de participação de um engenheiro.

### 10.1. Custo de mão de obra por intervenção

| Recurso | Cálculo | Custo |
|---|---|---:|
| Dois técnicos | 2 × 4 horas × R$ 33,48 | R$ 267,84 |
| Engenheiro eletricista | 1 hora × R$ 103,16 | R$ 103,16 |
| **Custo de mão de obra por intervenção** | | **R$ 371,00** |

Este cálculo considera a participação do engenheiro por uma hora adicional de avaliação ou acompanhamento. Não inclui andaimes, materiais, equipamentos especiais, deslocamentos ou outras despesas.

### 10.2. Impacto da parada durante a manutenção

No cenário-base, considera-se que as quatro horas de manutenção complementar provocam a interrupção da operação.

| Componente | Valor |
|---|---:|
| Mão de obra da intervenção | R$ 371,00 |
| Parada operacional de 4 horas | R$ 40.000,00 |
| **Custo total estimado por intervenção, incluindo parada** | **R$ 40.371,00** |

O valor de R$ 40.000,00 representa o impacto econômico hipotético de quatro horas de parada, calculado a partir da premissa de R$ 10.000,00 por hora.

### 10.3. Custo esperado da manutenção por ciclo de inspeção

Adota-se a hipótese de que 40% dos ciclos de inspeção exigem manutenção complementar.

O custo esperado de mão de obra por ciclo é de R$ 148,40, calculado pela multiplicação de R$ 371,00 por 40%.

A duração esperada de parada para manutenção é de 1,6 hora por ciclo, calculada pela multiplicação de quatro horas por 40%.

| Componente esperado por ciclo | Cálculo | Valor |
|---|---|---:|
| Mão de obra de manutenção | 40% × R$ 371,00 | R$ 148,40 |
| Parada esperada da manutenção | 40% × 4 horas | 1,6 hora |
| Impacto esperado da parada de manutenção | 1,6 hora × R$ 10.000,00 | R$ 16.000,00 |
| **Custo esperado total da manutenção, incluindo parada** | | **R$ 16.148,40** |

A estimativa de 1,6 hora é uma média matemática por ciclo de inspeção. Nas ocorrências que realmente exigem manutenção, a hipótese continua sendo de quatro horas.

## 11. Comparação econômica completa entre os métodos

Esta seção consolida os custos da inspeção inicial e da manutenção complementar esperada, incluindo o impacto financeiro das paradas.

### 11.1. Método convencional

No método convencional, cada inspeção exige uma hora de parada operacional. Além disso, 40% dos ciclos necessitam de manutenção complementar com quatro horas de parada.

| Componente | Custo esperado por ciclo |
|---|---:|
| Mão de obra da inspeção inicial | R$ 136,64 |
| Parada da inspeção inicial — 1 hora por ciclo | R$ 10.000,00 |
| Mão de obra esperada da manutenção complementar | R$ 148,40 |
| Parada esperada da manutenção complementar | R$ 16.000,00 |
| **Custo total esperado por ciclo** | **R$ 26.285,04** |

### 11.2. Método com drone

No método com drone, considera-se que a inspeção inicial ocorre sem parada operacional. Entretanto, 40% dos ciclos ainda exigem manutenção complementar com quatro horas de parada.

| Componente | Custo esperado por ciclo |
|---|---:|
| Custo da inspeção com drone, incluindo depreciação e provisões | R$ 166,69 |
| Parada da inspeção inicial | R$ 0,00 |
| Mão de obra esperada da manutenção complementar | R$ 148,40 |
| Parada esperada da manutenção complementar | R$ 16.000,00 |
| **Custo total esperado por ciclo** | **R$ 16.315,09** |

### 11.3. Economia esperada por ciclo

| Componente | Método convencional | Método com drone | Diferença |
|---|---:|---:|---:|
| Custo direto da inspeção inicial | R$ 136,64 | R$ 166,69 | Drone: +R$ 30,05 |
| Parada da inspeção inicial | R$ 10.000,00 | R$ 0,00 | Economia: R$ 10.000,00 |
| Mão de obra esperada da manutenção | R$ 148,40 | R$ 148,40 | R$ 0,00 |
| Parada esperada da manutenção | R$ 16.000,00 | R$ 16.000,00 | R$ 0,00 |
| **Custo total esperado por ciclo** | **R$ 26.285,04** | **R$ 16.315,09** | **Economia líquida: R$ 9.969,95** |

O custo direto adicional do drone é estimado em R$ 30,05 por ciclo. Entretanto, no cenário projetado, evita-se uma hora de parada operacional, equivalente a R$ 10.000,00.

Após descontar o custo direto adicional, a economia líquida esperada é de **R$ 9.969,95 por ciclo de inspeção**.

## 12. Projeção da economia semanal, mensal, trimestral, semestral e anual

Esta é a tabela principal do estudo. Ela apresenta o custo estimado de cada método, a diferença entre eles e a economia potencial considerando a redução das paradas operacionais.

Os valores incluem os custos diretos das inspeções, a mão de obra esperada de manutenção e o impacto das paradas. Não incluem todos os custos adicionais de implantação, treinamento, seguros, licenças, materiais e outros itens ainda não quantificados.

| Período | Inspeções | Custo convencional | Custo com drone | Economia líquida potencial |
|---|---:|---:|---:|---:|
| Semana | 5 | R$ 131.425,20 | R$ 81.575,45 | **R$ 49.849,75** |
| Mês | 20 | R$ 525.700,80 | R$ 326.301,80 | **R$ 199.399,00** |
| Trimestre | 60 | R$ 1.577.102,40 | R$ 978.905,40 | **R$ 598.197,00** |
| Semestre | 120 | R$ 3.154.204,80 | R$ 1.957.810,80 | **R$ 1.196.394,00** |
| Ano | 240 | R$ 6.308.409,60 | R$ 3.915.621,60 | **R$ 2.392.788,00** |

### 12.1. De onde vem a economia?

A economia líquida é formada por dois componentes:

1. **Redução potencial do impacto das paradas:** uma hora evitada em cada ciclo de inspeção, equivalente a R$ 10.000,00 por ciclo.
2. **Custo direto adicional do drone:** R$ 30,05 por ciclo, devido à depreciação, à provisão de manutenção, aos outros custos operacionais e à diferença entre os custos diretos estimados.

Por isso, a economia líquida por ciclo é de R$ 10.000,00 menos R$ 30,05, totalizando R$ 9.969,95.

No horizonte anual, a projeção é a seguinte:

| Componente anual | Valor |
|---|---:|
| Paradas iniciais potencialmente evitadas — 240 horas | R$ 2.400.000,00 |
| Custo direto adicional estimado do drone | −R$ 7.212,00 |
| **Economia líquida operacional potencial** | **R$ 2.392.788,00** |

Essa economia é expressiva porque o modelo atribui um custo de R$ 10.000,00 a cada hora de parada evitada. Ela não significa que a empresa receberá esse valor diretamente em caixa. O benefício financeiro real dependerá de como a parada afeta a produção, os contratos, os custos de retomada e a operação.

## 13. Análise do potencial de redução das paradas

O principal benefício econômico potencial do drone pode estar na redução do tempo em que a operação fica indisponível, e não necessariamente na redução do custo de mão de obra da inspeção inicial.

### 13.1. Paradas operacionais estimadas por método

| Período | Paradas no método convencional | Paradas no método com drone | Horas potencialmente evitadas |
|---|---:|---:|---:|
| Semana | 13 horas | 8 horas | 5 horas |
| Mês | 52 horas | 32 horas | 20 horas |
| Trimestre | 156 horas | 96 horas | 60 horas |
| Semestre | 312 horas | 192 horas | 120 horas |
| Ano | 624 horas | 384 horas | 240 horas |

A tabela considera:

- Uma hora de parada inicial por inspeção convencional.
- Nenhuma parada inicial no método com drone.
- Quatro horas de parada de manutenção em 40% dos ciclos.

Por exemplo, no cenário anual convencional, são consideradas 240 horas de parada inicial e 384 horas de parada esperada para manutenção. No método com drone, são consideradas somente as 384 horas esperadas de manutenção.

A diferença é de 240 horas de parada potencialmente evitadas no ano.

### 13.2. Impacto financeiro das horas potencialmente evitadas

| Período | Horas potencialmente evitadas | Valor estimado |
|---|---:|---:|
| Semana | 5 horas | R$ 50.000,00 |
| Mês | 20 horas | R$ 200.000,00 |
| Trimestre | 60 horas | R$ 600.000,00 |
| Semestre | 120 horas | R$ 1.200.000,00 |
| Ano | 240 horas | R$ 2.400.000,00 |

Esses valores representam o impacto operacional potencialmente evitado antes de descontar os custos diretos adicionais do drone.

### 13.3. Por que a manutenção continua sendo contabilizada?

O drone pode ajudar a identificar condições que precisam de atenção e fornecer informações para o planejamento de intervenções. Entretanto, a existência de uma inspeção por drone não significa que a manutenção deixará de ser necessária.

Por esse motivo, o modelo mantém a hipótese de que 40% dos ciclos necessitam de manutenção complementar e que cada intervenção exige quatro horas de parada.

O cenário não pressupõe que o drone elimine essas intervenções. A economia é calculada pela redução da parada inicial de inspeção, mantendo iguais as paradas de manutenção dos dois métodos.

Se, no futuro, for demonstrado que o sistema também reduz o tempo de manutenção ou permite realizar intervenções com menor impacto operacional, esse benefício poderá ser calculado separadamente. Ele não foi incluído na economia atual, para evitar superestimativas.

## 14. Investimento inicial e retorno financeiro

O investimento inicial estimado varia conforme a estratégia de desenvolvimento.

| Cenário | Equipamentos | Desenvolvimento | Investimento inicial |
|---|---:|---:|---:|
| A — Programador | R$ 8.339,80 | R$ 6.794,40 | R$ 15.134,20 |
| B — Equipe multidisciplinar | R$ 8.339,80 | R$ 11.816,40 | R$ 20.156,20 |

A economia operacional líquida potencial calculada para o cenário anual é de R$ 2.392.788,00. Em comparação com o investimento inicial estimado, esse resultado indica que a redução das paradas pode ser um fator determinante para a viabilidade econômica da solução.

Entretanto, **não se deve considerar esse valor como economia comprovada nem calcular um prazo de retorno definitivo antes de validar as premissas em campo**. A projeção depende diretamente da hipótese de que as inspeções convencionais provocam uma hora de parada e de que as inspeções com drone podem ser executadas sem essa interrupção.

Para estimar o retorno financeiro real, será necessário determinar:

1. Quantas horas de parada são efetivamente evitadas por ano.
2. Qual é o custo real por hora da operação afetada.
3. Quanto do impacto evitado representa economia financeira realizável.
4. Quais são os custos anuais de manutenção, treinamento, seguros, licenças e operação.
5. Qual é o investimento inicial definitivo.
6. Se o benefício ocorre de forma recorrente e pode ser comprovado por registros operacionais.

A depreciação deve ser considerada na análise econômica, mas não pode ser tratada como um novo desembolso de caixa a cada inspeção. A análise de retorno deverá separar os custos contábeis dos fluxos efetivos de caixa.

## 15. Limitações do estudo

Este relatório constitui uma estimativa preliminar e apresenta limitações que devem ser consideradas na interpretação dos resultados.

- A frequência de 240 inspeções anuais é uma hipótese de planejamento.
- A taxa de 40% de necessidade de manutenção complementar não foi validada por histórico operacional.
- O valor de R$ 10.000,00 por hora de parada é uma premissa de simulação, e não um custo real confirmado da Gasmar.
- A duração de uma hora para a inspeção inicial é uma hipótese a validar.
- A hipótese de que o drone permite realizar a inspeção inicial sem parada precisa ser demonstrada em campo.
- A duração de quatro horas para a manutenção complementar também é uma hipótese.
- Não foram incluídos custos específicos de andaimes, materiais, deslocamentos e equipamentos especiais.
- Não foram incluídos todos os custos de implantação, treinamento, seguros, licenças e adequação ao ambiente industrial.
- Não foi demonstrada, por medições reais, a quantidade de horas que o drone poderia evitar ou reduzir.
- O custo da parada pode variar significativamente entre uma instalação, uma linha de produção e um equipamento isolado.
- A economia estimada não corresponde necessariamente a uma redução de despesas em caixa no mesmo valor, pois o impacto de uma parada depende das condições produtivas e contratuais.

Essas limitações indicam quais premissas devem ser testadas para transformar a estimativa inicial em uma análise de viabilidade baseada em evidências.

## 16. Plano de validação em campo

Para consolidar os resultados, recomenda-se executar uma etapa de coleta de dados em ambiente controlado e autorizado.

### Etapa 1 — Levantamento operacional

Identificar os equipamentos a serem inspecionados, a frequência das inspeções, os métodos utilizados atualmente e os requisitos de segurança aplicáveis.

### Etapa 2 — Medição dos tempos

Registrar o tempo de preparação, inspeção, análise e liberação operacional em cada método. A medição deverá distinguir o tempo de trabalho dos profissionais do tempo durante o qual a operação realmente fica interrompida.

### Etapa 3 — Registro das intervenções

Registrar quantas inspeções resultam em manutenção complementar, qual é a duração real das intervenções e se a parada é total, parcial ou inexistente.

### Etapa 4 — Validação financeira

Obter com os responsáveis operacionais e financeiros uma estimativa documentada do impacto de uma hora de parada. O valor poderá variar conforme o equipamento, a linha de produção e o período da ocorrência.

### Etapa 5 — Comparação entre métodos

Comparar os resultados de inspeções convencionais e com drone em condições equivalentes, considerando segurança, qualidade da informação, tempo, custos diretos e impactos operacionais.

### Etapa 6 — Reavaliação econômica

Substituir as hipóteses iniciais pelos dados medidos e recalcular o investimento, o custo por inspeção, o impacto das paradas, a economia comprovada e o retorno financeiro.

## 17. Conclusão

O estudo indica que a redução das paradas operacionais pode ser o principal fator econômico favorável à adoção de um sistema de inspeção assistida por drone.

Adotando-se o valor hipotético de R$ 10.000,00 por hora de parada, o método convencional apresenta um impacto operacional esperado superior porque o cenário considera uma hora de parada em cada inspeção inicial. No método com drone, essa parada inicial é eliminada na simulação, enquanto as paradas de manutenção complementar são mantidas.

Com cinco inspeções por semana, vinte por mês e duzentas e quarenta por ano, o modelo estima uma economia operacional líquida potencial de R$ 49.849,75 por semana, R$ 199.399,00 por mês, R$ 598.197,00 por trimestre, R$ 1.196.394,00 por semestre e R$ 2.392.788,00 por ano.

Esses resultados dependem de hipóteses que ainda precisam ser validadas. A economia não deve ser apresentada como um resultado já obtido pela Gasmar, mas como um cenário de viabilidade que demonstra o impacto financeiro potencial da redução das paradas.

A solução poderá ser economicamente favorável se permitir realizar inspeções sem interromper a operação, mantendo os requisitos de segurança e a qualidade das informações coletadas. Sua aplicação deverá ser avaliada tecnicamente para cada equipamento e ambiente.

Por fim, a arquitetura proposta é genérica e adaptável a diferentes setores industriais. Os equipamentos, sensores, regras de negócio e parâmetros financeiros poderão ser modificados conforme as necessidades de cada empresa.

A recomendação é avançar para uma validação operacional, mantendo separados os custos diretos, o impacto econômico das paradas e os benefícios potenciais. Essa abordagem permitirá desenvolver uma avaliação de viabilidade mais confiável, transparente e tecnicamente defensável.

## 18. Referências

As referências abaixo servem como fontes de consulta para equipamentos e referências salariais. Os custos e as premissas operacionais específicos deste estudo deverão ser validados independentemente.

- Portal Salário — [Programador de Sistemas de Informação](https://www.salario.com.br/profissao/programador-de-sistemas-de-informacao-cbo-317110/)
- Portal Salário — [Analista de Desenvolvimento de Sistemas](https://www.salario.com.br/profissao/analista-de-desenvolvimento-de-sistemas-cbo-212405/)
- Portal Salário — [Engenheiro Eletricista de Manutenção](https://www.salario.com.br/profissao/engenheiro-eletricista-de-manutencao-cbo-214315/)
- Portal Salário — [Técnico de Manutenção Industrial](https://www.salario.com.br/profissao/tecnico-de-manutencao-industrial-cbo-313120/)
- DJI Brasil — [Drone DJI Mini 4 Pro](https://www.lojadji.com.br/drone-dji-mini-4-pro-rc-2-com-tela-br-dji042/p)

**Observação final:** o orçamento, as horas de desenvolvimento, os custos horários, a frequência das inspeções, o percentual de manutenção complementar e o valor de R$ 10.000,00 por hora de parada são parâmetros de estimativa. Os resultados não devem ser apresentados como dados históricos ou financeiros confirmados da Gasmar sem validação documental.