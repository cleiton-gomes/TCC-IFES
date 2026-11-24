<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEstruturaFisica extends Migration
{
    public function up()
    {
        /*
         * 1. LOCAIS
         * Estrutura hierarquica dos ambientes fisicos.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE locais (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                local_pai_id BIGINT UNSIGNED NULL,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                tipo VARCHAR(30) NOT NULL,
                descricao TEXT NULL,
                latitude DECIMAL(10,7) NULL,
                longitude DECIMAL(10,7) NULL,
                referencia VARCHAR(190) NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_locais_codigo (codigo),

                KEY idx_locais_local_pai (local_pai_id),
                KEY idx_locais_tipo (tipo),
                KEY idx_locais_ativo (ativo),

                CONSTRAINT fk_locais_local_pai
                    FOREIGN KEY (local_pai_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT,

                CONSTRAINT chk_locais_latitude
                    CHECK (
                        latitude IS NULL
                        OR latitude BETWEEN -90 AND 90
                    ),

                CONSTRAINT chk_locais_longitude
                    CHECK (
                        longitude IS NULL
                        OR longitude BETWEEN -180 AND 180
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. DISPOSITIVOS
         * Cadastro dos equipamentos fisicos da plataforma.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE dispositivos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                local_id BIGINT UNSIGNED NULL,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                categoria VARCHAR(40) NOT NULL,
                fabricante VARCHAR(100) NULL,
                modelo VARCHAR(100) NULL,
                numero_serie VARCHAR(120) NULL,
                protocolo VARCHAR(30) NOT NULL,
                topico_base_mqtt VARCHAR(255) NULL,
                endereco_rede VARCHAR(255) NULL,
                status VARCHAR(30) NOT NULL,
                ultimo_contato DATETIME(3) NULL,
                configuracao JSON NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_dispositivos_codigo (codigo),
                UNIQUE KEY uq_dispositivos_topico_mqtt (topico_base_mqtt),

                KEY idx_dispositivos_local (local_id),
                KEY idx_dispositivos_categoria (categoria),
                KEY idx_dispositivos_status (status),
                KEY idx_dispositivos_ativo (ativo),

                CONSTRAINT fk_dispositivos_local
                    FOREIGN KEY (local_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. COMPONENTES_DISPOSITIVO
         * Componentes individuais pertencentes aos dispositivos.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE componentes_dispositivo (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(120) NOT NULL,
                tipo VARCHAR(40) NOT NULL,
                fabricante VARCHAR(100) NULL,
                modelo VARCHAR(100) NULL,
                numero_serie VARCHAR(120) NULL,
                descricao TEXT NULL,
                configuracao JSON NULL,
                status VARCHAR(30) NOT NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_componentes_dispositivo_codigo
                    (dispositivo_id, codigo),

                KEY idx_componentes_tipo (tipo),
                KEY idx_componentes_status (status),
                KEY idx_componentes_ativo (ativo),

                CONSTRAINT fk_componentes_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. HISTORICO_LOCALIZACAO_DISPOSITIVO
         * Preserva o historico de movimentacao dos dispositivos.
         *
         * A coluna gerada garante que exista somente
         * uma localizacao vigente por dispositivo.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE historico_localizacao_dispositivo (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                local_id BIGINT UNSIGNED NOT NULL,
                usuario_id INT UNSIGNED NULL,
                inicio_vigencia DATETIME(3) NOT NULL,
                fim_vigencia DATETIME(3) NULL,
                motivo VARCHAR(255) NULL,
                observacoes TEXT NULL,

                dispositivo_vigente_id BIGINT UNSIGNED
                    GENERATED ALWAYS AS (
                        CASE
                            WHEN fim_vigencia IS NULL
                            THEN dispositivo_id
                            ELSE NULL
                        END
                    ) STORED,

                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_hld_dispositivo_vigente
                    (dispositivo_vigente_id),

                KEY idx_hld_dispositivo (dispositivo_id),
                KEY idx_hld_local (local_id),
                KEY idx_hld_inicio (inicio_vigencia),
                KEY idx_hld_fim (fim_vigencia),

                CONSTRAINT fk_hld_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT,

                CONSTRAINT fk_hld_local
                    FOREIGN KEY (local_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT,

                CONSTRAINT fk_hld_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT
                    ON UPDATE RESTRICT,

                CONSTRAINT chk_hld_vigencia
                    CHECK (
                        fim_vigencia IS NULL
                        OR fim_vigencia > inicio_vigencia
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        /*
         * Remove as tabelas na ordem inversa
         * de suas dependencias.
         */
        $this->db->query(
            'DROP TABLE IF EXISTS historico_localizacao_dispositivo'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS componentes_dispositivo'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS dispositivos'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS locais'
        );
    }
}
