<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAutomacao extends Migration
{
    public function up()
    {
        /*
         * 1. REGRAS_AUTOMACAO
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE regras_automacao (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                descricao TEXT NULL,
                tipo_gatilho VARCHAR(40) NOT NULL,
                condicoes JSON NULL,
                prioridade INT UNSIGNED NOT NULL DEFAULT 0,
                ativa TINYINT(1) NOT NULL DEFAULT 1,
                cooldown_segundos INT UNSIGNED NOT NULL DEFAULT 0,
                vigente_desde DATETIME(3) NULL,
                vigente_ate DATETIME(3) NULL,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_regras_automacao_codigo (codigo),

                KEY idx_ra_ativa_prioridade (ativa, prioridade),
                KEY idx_ra_tipo_gatilho (tipo_gatilho),
                KEY idx_ra_vigencia (vigente_desde, vigente_ate),
                KEY idx_ra_usuario (usuario_id),

                CONSTRAINT fk_ra_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_ra_vigencia
                    CHECK (
                        vigente_desde IS NULL
                        OR vigente_ate IS NULL
                        OR vigente_ate > vigente_desde
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. ACOES_AUTOMACAO
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE acoes_automacao (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                regra_id BIGINT UNSIGNED NOT NULL,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                tipo VARCHAR(40) NOT NULL,
                configuracao JSON NULL,
                ordem_execucao INT UNSIGNED NOT NULL DEFAULT 0,
                obrigatoria TINYINT(1) NOT NULL DEFAULT 1,
                ativa TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_aa_regra_codigo
                    (regra_id, codigo),

                KEY idx_aa_regra_ordem
                    (regra_id, ordem_execucao),

                KEY idx_aa_tipo_ativa
                    (tipo, ativa),

                CONSTRAINT fk_aa_regra
                    FOREIGN KEY (regra_id)
                    REFERENCES regras_automacao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. EXECUCOES_AUTOMACAO
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE execucoes_automacao (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                regra_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                codigo VARCHAR(80) NOT NULL,
                chave_idempotencia VARCHAR(120) NOT NULL,
                status VARCHAR(30) NOT NULL,
                entrada JSON NULL,
                resultado JSON NULL,
                erro TEXT NULL,
                iniciada_em DATETIME(3) NOT NULL,
                concluida_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_ea_codigo (codigo),

                UNIQUE KEY uq_ea_regra_idempotencia
                    (regra_id, chave_idempotencia),

                KEY idx_ea_regra_inicio
                    (regra_id, iniciada_em),

                KEY idx_ea_status_inicio
                    (status, iniciada_em),

                KEY idx_ea_evento (evento_id),

                CONSTRAINT fk_ea_regra
                    FOREIGN KEY (regra_id)
                    REFERENCES regras_automacao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ea_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_ea_periodo
                    CHECK (
                        concluida_em IS NULL
                        OR concluida_em >= iniciada_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. EXECUCOES_ACOES
         *
         * Preserva a configuracao utilizada em cada tentativa,
         * independentemente de alteracoes posteriores na regra.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE execucoes_acoes (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                execucao_automacao_id BIGINT UNSIGNED NOT NULL,
                acao_id BIGINT UNSIGNED NOT NULL,
                status VARCHAR(30) NOT NULL,
                tentativa INT UNSIGNED NOT NULL DEFAULT 1,
                entrada JSON NULL,
                resultado JSON NULL,
                erro TEXT NULL,
                iniciada_em DATETIME(3) NULL,
                concluida_em DATETIME(3) NULL,
                configuracao_snapshot JSON NOT NULL,
                hash_configuracao CHAR(64) NOT NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_exa_execucao_acao_tentativa
                    (execucao_automacao_id, acao_id, tentativa),

                KEY idx_exa_execucao_status
                    (execucao_automacao_id, status),

                KEY idx_exa_acao (acao_id),

                CONSTRAINT fk_exa_execucao
                    FOREIGN KEY (execucao_automacao_id)
                    REFERENCES execucoes_automacao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_exa_acao
                    FOREIGN KEY (acao_id)
                    REFERENCES acoes_automacao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_exa_tentativa
                    CHECK (tentativa >= 1),

                CONSTRAINT chk_exa_periodo
                    CHECK (
                        concluida_em IS NULL
                        OR (
                            iniciada_em IS NOT NULL
                            AND concluida_em >= iniciada_em
                        )
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 5. EXECUCAO_DESTINATARIOS
         *
         * Acompanhamento individual dos dispositivos
         * selecionados para uma execucao de acao.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE execucao_destinatarios (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                execucao_acao_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                local_id BIGINT UNSIGNED NULL,
                status VARCHAR(30) NOT NULL,
                tentativas INT UNSIGNED NOT NULL DEFAULT 0,
                detalhes JSON NULL,
                erro TEXT NULL,
                selecionado_em DATETIME(3) NOT NULL,
                enviado_em DATETIME(3) NULL,
                confirmado_em DATETIME(3) NULL,
                concluido_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_ed_execucao_dispositivo
                    (execucao_acao_id, dispositivo_id),

                KEY idx_ed_dispositivo_status
                    (dispositivo_id, status),

                KEY idx_ed_execucao_status
                    (execucao_acao_id, status),

                KEY idx_ed_local (local_id),

                CONSTRAINT fk_ed_execucao_acao
                    FOREIGN KEY (execucao_acao_id)
                    REFERENCES execucoes_acoes (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ed_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ed_local
                    FOREIGN KEY (local_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS execucao_destinatarios');
        $this->db->query('DROP TABLE IF EXISTS execucoes_acoes');
        $this->db->query('DROP TABLE IF EXISTS execucoes_automacao');
        $this->db->query('DROP TABLE IF EXISTS acoes_automacao');
        $this->db->query('DROP TABLE IF EXISTS regras_automacao');
    }
}