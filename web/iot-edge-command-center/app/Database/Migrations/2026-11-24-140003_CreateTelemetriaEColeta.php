<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTelemetriaEColeta extends Migration
{
    public function up()
    {
        /*
         * 1. LEITURAS
         * Telemetria produzida pelos dispositivos e componentes.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE leituras (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                componente_id BIGINT UNSIGNED NULL,
                metrica VARCHAR(80) NOT NULL,
                valor_numerico DECIMAL(20,8) NULL,
                valor_textual TEXT NULL,
                unidade VARCHAR(30) NULL,
                qualidade VARCHAR(30) NOT NULL,
                payload_original JSON NULL,
                medido_em DATETIME(3) NOT NULL,
                recebido_em DATETIME(3) NOT NULL,
                protegida TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                KEY idx_leituras_dispositivo_medido
                    (dispositivo_id, medido_em),

                KEY idx_leituras_componente_medido
                    (componente_id, medido_em),

                KEY idx_leituras_metrica_medido
                    (metrica, medido_em),

                KEY idx_leituras_recebido (recebido_em),

                CONSTRAINT fk_leituras_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_leituras_componente
                    FOREIGN KEY (componente_id)
                    REFERENCES componentes_dispositivo (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_leituras_valor
                    CHECK (
                        valor_numerico IS NOT NULL
                        OR valor_textual IS NOT NULL
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. HISTORICO_STATUS_DISPOSITIVO
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE historico_status_dispositivo (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                status_anterior VARCHAR(30) NULL,
                status_novo VARCHAR(30) NOT NULL,
                motivo VARCHAR(255) NULL,
                origem VARCHAR(30) NOT NULL,
                detalhes JSON NULL,
                ocorrido_em DATETIME(3) NOT NULL,
                registrado_em DATETIME(3) NOT NULL,

                PRIMARY KEY (id),

                KEY idx_hsd_dispositivo_ocorrido
                    (dispositivo_id, ocorrido_em),

                KEY idx_hsd_evento (evento_id),
                KEY idx_hsd_status_novo (status_novo),
                KEY idx_hsd_registrado (registrado_em),

                CONSTRAINT fk_hsd_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_hsd_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. VERSOES_CONFIGURACAO
         * Preserva snapshots completos de configuracao.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE versoes_configuracao (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                versao INT UNSIGNED NOT NULL,
                configuracao JSON NOT NULL,
                hash_configuracao CHAR(64) NOT NULL,
                origem VARCHAR(30) NOT NULL,
                descricao VARCHAR(255) NULL,
                vigente_desde DATETIME(3) NOT NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_vc_dispositivo_versao
                    (dispositivo_id, versao),

                KEY idx_vc_dispositivo_vigencia
                    (dispositivo_id, vigente_desde),

                KEY idx_vc_evento (evento_id),
                KEY idx_vc_usuario (usuario_id),
                KEY idx_vc_hash (hash_configuracao),

                CONSTRAINT fk_vc_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vc_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vc_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. SESSOES_COLETA
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE sessoes_coleta (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NULL,
                dispositivo_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                codigo VARCHAR(80) NOT NULL,
                tipo VARCHAR(40) NOT NULL,
                status VARCHAR(30) NOT NULL,
                iniciada_em DATETIME(3) NOT NULL,
                encerrada_em DATETIME(3) NULL,
                parametros JSON NULL,
                resumo JSON NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_sessoes_coleta_codigo (codigo),

                KEY idx_sc_evento (evento_id),
                KEY idx_sc_dispositivo (dispositivo_id),
                KEY idx_sc_usuario (usuario_id),
                KEY idx_sc_tipo_status (tipo, status),
                KEY idx_sc_iniciada (iniciada_em),

                CONSTRAINT fk_sc_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_sc_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_sc_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_sc_periodo
                    CHECK (
                        encerrada_em IS NULL
                        OR encerrada_em >= iniciada_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 5. SESSAO_DISPOSITIVOS
         * Participantes de uma sessao de coleta.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE sessao_dispositivos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                sessao_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                status VARCHAR(30) NOT NULL,
                inicio_participacao DATETIME(3) NULL,
                fim_participacao DATETIME(3) NULL,
                detalhes JSON NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_sd_sessao_dispositivo
                    (sessao_id, dispositivo_id),

                KEY idx_sd_dispositivo (dispositivo_id),
                KEY idx_sd_sessao_status (sessao_id, status),

                CONSTRAINT fk_sd_sessao
                    FOREIGN KEY (sessao_id)
                    REFERENCES sessoes_coleta (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_sd_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_sd_periodo
                    CHECK (
                        fim_participacao IS NULL
                        OR (
                            inicio_participacao IS NOT NULL
                            AND fim_participacao >= inicio_participacao
                        )
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 6. EVENTO_LEITURAS
         * Relaciona leituras de telemetria aos eventos.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE evento_leituras (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NOT NULL,
                leitura_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_evento_leituras
                    (evento_id, leitura_id),

                KEY idx_evento_leituras_leitura (leitura_id),

                CONSTRAINT fk_evento_leituras_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_evento_leituras_leitura
                    FOREIGN KEY (leitura_id)
                    REFERENCES leituras (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS evento_leituras');
        $this->db->query('DROP TABLE IF EXISTS sessao_dispositivos');
        $this->db->query('DROP TABLE IF EXISTS sessoes_coleta');
        $this->db->query('DROP TABLE IF EXISTS versoes_configuracao');
        $this->db->query('DROP TABLE IF EXISTS historico_status_dispositivo');
        $this->db->query('DROP TABLE IF EXISTS leituras');
    }
}