<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCenasECenarios extends Migration
{
    public function up()
    {
        /*
         * 1. CENAS
         * Cadastro logico das cenas operacionais.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE cenas (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                descricao TEXT NULL,
                categoria VARCHAR(40) NOT NULL,
                status VARCHAR(30) NOT NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_cenas_codigo (codigo),

                KEY idx_cenas_categoria (categoria),
                KEY idx_cenas_status (status),
                KEY idx_cenas_ativo (ativo),
                KEY idx_cenas_usuario (usuario_id),

                CONSTRAINT fk_cenas_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. VERSOES_CENAS
         * Preserva as diferentes versoes de uma cena.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE versoes_cenas (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cena_id BIGINT UNSIGNED NOT NULL,
                versao VARCHAR(40) NOT NULL,
                descricao_versao TEXT NULL,
                status VARCHAR(30) NOT NULL,
                hash_configuracao CHAR(64) NOT NULL,
                usuario_id INT UNSIGNED NULL,
                publicada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_vcenas_cena_versao
                    (cena_id, versao),

                KEY idx_vcenas_cena_status
                    (cena_id, status),

                KEY idx_vcenas_hash (hash_configuracao),
                KEY idx_vcenas_usuario (usuario_id),
                KEY idx_vcenas_publicada (publicada_em),

                CONSTRAINT fk_vcenas_cena
                    FOREIGN KEY (cena_id)
                    REFERENCES cenas (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vcenas_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. CENA_DISPOSITIVOS
         *
         * Define o estado desejado dos dispositivos
         * participantes de uma versao de cena.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE cena_dispositivos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cena_versao_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                configuracao JSON NOT NULL,
                ordem_aplicacao INT UNSIGNED NOT NULL DEFAULT 0,
                obrigatorio TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_cd_versao_dispositivo
                    (cena_versao_id, dispositivo_id),

                KEY idx_cd_dispositivo (dispositivo_id),

                KEY idx_cd_versao_ordem
                    (cena_versao_id, ordem_aplicacao),

                CONSTRAINT fk_cd_cena_versao
                    FOREIGN KEY (cena_versao_id)
                    REFERENCES versoes_cenas (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_cd_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. CENARIOS
         * Cadastro logico dos cenarios operacionais.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE cenarios (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                descricao TEXT NULL,
                categoria VARCHAR(40) NOT NULL,
                status VARCHAR(30) NOT NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_cenarios_codigo (codigo),

                KEY idx_cenarios_categoria (categoria),
                KEY idx_cenarios_status (status),
                KEY idx_cenarios_ativo (ativo),
                KEY idx_cenarios_usuario (usuario_id),

                CONSTRAINT fk_cenarios_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 5. VERSOES_CENARIOS
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE versoes_cenarios (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cenario_id BIGINT UNSIGNED NOT NULL,
                versao VARCHAR(40) NOT NULL,
                descricao_versao TEXT NULL,
                status VARCHAR(30) NOT NULL,
                hash_configuracao CHAR(64) NOT NULL,
                usuario_id INT UNSIGNED NULL,
                publicada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_vcenarios_cenario_versao
                    (cenario_id, versao),

                KEY idx_vcenarios_cenario_status
                    (cenario_id, status),

                KEY idx_vcenarios_hash (hash_configuracao),
                KEY idx_vcenarios_usuario (usuario_id),
                KEY idx_vcenarios_publicada (publicada_em),

                CONSTRAINT fk_vcenarios_cenario
                    FOREIGN KEY (cenario_id)
                    REFERENCES cenarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vcenarios_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 6. CENARIO_CENAS
         *
         * Relaciona uma versao de cenario com
         * versoes especificas de cenas.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE cenario_cenas (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cenario_versao_id BIGINT UNSIGNED NOT NULL,
                cena_versao_id BIGINT UNSIGNED NOT NULL,
                ordem_execucao INT UNSIGNED NOT NULL DEFAULT 0,
                obrigatoria TINYINT(1) NOT NULL DEFAULT 1,
                condicao_ativacao JSON NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_cc_cenario_cena
                    (cenario_versao_id, cena_versao_id),

                KEY idx_cc_cena_versao (cena_versao_id),

                KEY idx_cc_cenario_ordem
                    (cenario_versao_id, ordem_execucao),

                CONSTRAINT fk_cc_cenario_versao
                    FOREIGN KEY (cenario_versao_id)
                    REFERENCES versoes_cenarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_cc_cena_versao
                    FOREIGN KEY (cena_versao_id)
                    REFERENCES versoes_cenas (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 7. CENARIO_DISPOSITIVOS
         *
         * Permite complementar ou sobrescrever
         * configuracoes definidas pelas cenas.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE cenario_dispositivos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cenario_versao_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                configuracao JSON NOT NULL,
                estrategia_aplicacao VARCHAR(20) NOT NULL,
                obrigatorio TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_cend_versao_dispositivo
                    (cenario_versao_id, dispositivo_id),

                KEY idx_cend_dispositivo (dispositivo_id),

                CONSTRAINT fk_cend_cenario_versao
                    FOREIGN KEY (cenario_versao_id)
                    REFERENCES versoes_cenarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_cend_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_cend_estrategia
                    CHECK (
                        estrategia_aplicacao IN (
                            'complementar',
                            'sobrescrever'
                        )
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 8. EXECUCOES_CENARIOS
         * Historico das execucoes de cenarios.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE execucoes_cenarios (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                cenario_versao_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                execucao_acao_id BIGINT UNSIGNED NULL,
                status VARCHAR(30) NOT NULL,
                entrada JSON NULL,
                resultado JSON NULL,
                erro TEXT NULL,
                iniciada_em DATETIME(3) NOT NULL,
                concluida_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_excen_codigo (codigo),

                KEY idx_excen_versao_inicio
                    (cenario_versao_id, iniciada_em),

                KEY idx_excen_status_inicio
                    (status, iniciada_em),

                KEY idx_excen_evento (evento_id),
                KEY idx_excen_usuario (usuario_id),
                KEY idx_excen_acao (execucao_acao_id),

                CONSTRAINT fk_excen_cenario_versao
                    FOREIGN KEY (cenario_versao_id)
                    REFERENCES versoes_cenarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excen_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excen_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excen_acao
                    FOREIGN KEY (execucao_acao_id)
                    REFERENCES execucoes_acoes (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_excen_periodo
                    CHECK (
                        concluida_em IS NULL
                        OR concluida_em >= iniciada_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 9. EXECUCOES_CENAS
         *
         * Uma cena pode ser executada individualmente
         * ou como parte da execucao de um cenario.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE execucoes_cenas (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                execucao_cenario_id BIGINT UNSIGNED NULL,
                cena_versao_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                execucao_acao_id BIGINT UNSIGNED NULL,
                status VARCHAR(30) NOT NULL,
                entrada JSON NULL,
                resultado JSON NULL,
                erro TEXT NULL,
                iniciada_em DATETIME(3) NOT NULL,
                concluida_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_excena_codigo (codigo),

                KEY idx_excena_cenario (execucao_cenario_id),

                KEY idx_excena_versao_inicio
                    (cena_versao_id, iniciada_em),

                KEY idx_excena_status_inicio
                    (status, iniciada_em),

                KEY idx_excena_evento (evento_id),
                KEY idx_excena_usuario (usuario_id),
                KEY idx_excena_acao (execucao_acao_id),

                CONSTRAINT fk_excena_execucao_cenario
                    FOREIGN KEY (execucao_cenario_id)
                    REFERENCES execucoes_cenarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excena_cena_versao
                    FOREIGN KEY (cena_versao_id)
                    REFERENCES versoes_cenas (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excena_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excena_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_excena_acao
                    FOREIGN KEY (execucao_acao_id)
                    REFERENCES execucoes_acoes (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_excena_periodo
                    CHECK (
                        concluida_em IS NULL
                        OR concluida_em >= iniciada_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        /*
         * Exclusao na ordem inversa das dependencias.
         */
        $this->db->query('DROP TABLE IF EXISTS execucoes_cenas');
        $this->db->query('DROP TABLE IF EXISTS execucoes_cenarios');
        $this->db->query('DROP TABLE IF EXISTS cenario_dispositivos');
        $this->db->query('DROP TABLE IF EXISTS cenario_cenas');
        $this->db->query('DROP TABLE IF EXISTS versoes_cenarios');
        $this->db->query('DROP TABLE IF EXISTS cenarios');
        $this->db->query('DROP TABLE IF EXISTS cena_dispositivos');
        $this->db->query('DROP TABLE IF EXISTS versoes_cenas');
        $this->db->query('DROP TABLE IF EXISTS cenas');
    }
}