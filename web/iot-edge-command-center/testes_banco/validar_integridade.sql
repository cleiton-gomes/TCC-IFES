-- =====================================================
-- IoT-Edge Command Center
-- Testes praticos de integridade do banco de dados
-- =====================================================

USE iot_edge_center;

SELECT 'INICIO DOS TESTES DE INTEGRIDADE' AS etapa;

START TRANSACTION;

SET @codigo_teste = CONCAT('TESTE-', UUID());

-- =====================================================
-- TESTE 01 - Insercao valida
-- Resultado esperado: sucesso
-- =====================================================

SELECT 'TESTE 01 - Insercao valida' AS teste;

INSERT INTO locais (
    codigo,
    nome,
    tipo,
    latitude,
    longitude,
    ativo
) VALUES (
    @codigo_teste,
    'Local temporario de teste',
    'edificio',
    -20.2000000,
    -40.3000000,
    1
);

SET @local_teste = LAST_INSERT_ID();

SELECT id, codigo, nome, latitude, longitude
FROM locais
WHERE id = @local_teste;

-- =====================================================
-- TESTE 02 - Violacao de UNIQUE
-- Resultado esperado: ERROR 1062
-- =====================================================

SELECT 'TESTE 02 - Codigo duplicado' AS teste;

INSERT INTO locais (
    codigo,
    nome,
    tipo
) VALUES (
    @codigo_teste,
    'Local duplicado',
    'edificio'
);

-- =====================================================
-- TESTE 03 - Latitude invalida
-- Resultado esperado: ERROR 3819
-- =====================================================

SELECT 'TESTE 03 - Latitude invalida' AS teste;

UPDATE locais
SET latitude = 95.0000000
WHERE id = @local_teste;

-- =====================================================
-- TESTE 04 - Longitude invalida
-- Resultado esperado: ERROR 3819
-- =====================================================

SELECT 'TESTE 04 - Longitude invalida' AS teste;

UPDATE locais
SET longitude = 190.0000000
WHERE id = @local_teste;

-- =====================================================
-- TESTE 05 - Criacao de relacionamento valido
-- Resultado esperado: sucesso
-- =====================================================

SELECT 'TESTE 05 - Relacionamento valido' AS teste;

INSERT INTO locais (
    local_pai_id,
    codigo,
    nome,
    tipo
) VALUES (
    @local_teste,
    CONCAT(@codigo_teste, '-FILHO'),
    'Sala temporaria de teste',
    'sala'
);

SET @local_filho = LAST_INSERT_ID();

SELECT id, local_pai_id, codigo, nome
FROM locais
WHERE id = @local_filho;

-- =====================================================
-- TESTE 06 - Exclusao de registro referenciado
-- Resultado esperado: ERROR 1451
-- =====================================================

SELECT 'TESTE 06 - Exclusao restrita' AS teste;

DELETE FROM locais
WHERE id = @local_teste;

-- =====================================================
-- TESTE 07 - Referencia inexistente
-- Resultado esperado: ERROR 1452
-- =====================================================

SELECT 'TESTE 07 - Chave estrangeira invalida' AS teste;

UPDATE locais
SET local_pai_id = 0
WHERE id = @local_filho;

-- =====================================================
-- TESTE 08 - Preservacao dos valores validos
-- Resultado esperado: valores originais preservados
-- =====================================================

SELECT 'TESTE 08 - Verificacao dos registros' AS teste;

SELECT
    id,
    local_pai_id,
    codigo,
    nome,
    latitude,
    longitude
FROM locais
WHERE id IN (@local_teste, @local_filho);

-- =====================================================
-- TESTE 09 - Reversao da transacao
-- =====================================================

SELECT 'TESTE 09 - Rollback' AS teste;

ROLLBACK;

SELECT COUNT(*) AS registros_de_teste_restantes
FROM locais
WHERE codigo IN (
    @codigo_teste,
    CONCAT(@codigo_teste, '-FILHO')
);

SELECT 'FIM DOS TESTES DE INTEGRIDADE' AS etapa;