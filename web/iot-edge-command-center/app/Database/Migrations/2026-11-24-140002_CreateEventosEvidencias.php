<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventosEvidencias extends Migration
{
    public function up()
    {
        /*
         * 1. EVENTOS
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE eventos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_pai_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                codigo VARCHAR(80) NOT NULL,
                tipo VARCHAR(60) NOT NULL,
                severidade VARCHAR(20) NOT NULL,
                status VARCHAR(30) NOT NULL,
                origem VARCHAR(40) NOT NULL,
                titulo VARCHAR(180) NOT NULL,
                descricao TEXT NULL,
                dados JSON NULL,
                ocorrido_em DATETIME(3) NOT NULL,
                registrado_em DATETIME(3) NOT NULL,
                reconhecido_em DATETIME(3) NULL,
                encerrado_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_eventos_codigo (codigo),

                KEY idx_eventos_pai (evento_pai_id),
                KEY idx_eventos_usuario (usuario_id),
                KEY idx_eventos_status_severidade (status, severidade),
                KEY idx_eventos_tipo_ocorrido (tipo, ocorrido_em),
                KEY idx_eventos_registrado (registrado_em),

                CONSTRAINT fk_eventos_pai
                    FOREIGN KEY (evento_pai_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_eventos_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. EVENTO_LOCAIS
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE evento_locais (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NOT NULL,
                local_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                observacoes VARCHAR(255) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_evento_locais (evento_id, local_id),

                KEY idx_evento_locais_local (local_id),

                CONSTRAINT fk_evento_locais_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_evento_locais_local
                    FOREIGN KEY (local_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. EVENTO_DISPOSITIVOS
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE evento_dispositivos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_evento_dispositivos
                    (evento_id, dispositivo_id),

                KEY idx_evento_dispositivos_dispositivo
                    (dispositivo_id),

                CONSTRAINT fk_evento_dispositivos_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_evento_dispositivos_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. EVENTO_COMPONENTES
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE evento_componentes (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NOT NULL,
                componente_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_evento_componentes
                    (evento_id, componente_id),

                KEY idx_evento_componentes_componente
                    (componente_id),

                CONSTRAINT fk_evento_componentes_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_evento_componentes_componente
                    FOREIGN KEY (componente_id)
                    REFERENCES componentes_dispositivo (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 5. ANEXOS
         * Armazena metadados de arquivos externos.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE anexos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome_original VARCHAR(255) NOT NULL,
                nome_armazenado VARCHAR(255) NOT NULL,
                tipo_mime VARCHAR(120) NOT NULL,
                categoria VARCHAR(30) NOT NULL,
                extensao VARCHAR(15) NULL,
                tamanho_bytes BIGINT UNSIGNED NOT NULL,
                hash_sha256 CHAR(64) NULL,
                uri_armazenamento VARCHAR(500) NOT NULL,
                descricao TEXT NULL,
                metadados JSON NULL,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_anexos_codigo (codigo),

                KEY idx_anexos_categoria (categoria),
                KEY idx_anexos_tipo_mime (tipo_mime),
                KEY idx_anexos_hash (hash_sha256),
                KEY idx_anexos_created (created_at),
                KEY idx_anexos_usuario (usuario_id),

                CONSTRAINT fk_anexos_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 6. EVENTO_ANEXOS
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE evento_anexos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                evento_id BIGINT UNSIGNED NOT NULL,
                anexo_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                observacoes VARCHAR(255) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_evento_anexos
                    (evento_id, anexo_id),

                KEY idx_evento_anexos_anexo (anexo_id),

                CONSTRAINT fk_evento_anexos_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_evento_anexos_anexo
                    FOREIGN KEY (anexo_id)
                    REFERENCES anexos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS evento_anexos');
        $this->db->query('DROP TABLE IF EXISTS anexos');
        $this->db->query('DROP TABLE IF EXISTS evento_componentes');
        $this->db->query('DROP TABLE IF EXISTS evento_dispositivos');
        $this->db->query('DROP TABLE IF EXISTS evento_locais');
        $this->db->query('DROP TABLE IF EXISTS eventos');
    }
}