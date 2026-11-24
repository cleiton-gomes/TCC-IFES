<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIntegracaoEComandos extends Migration
{
    public function up()
    {
        /*
         * 1. MENSAGENS_INTEGRACAO
         *
         * Registro das mensagens recebidas e enviadas
         * pelos diferentes protocolos de integracao.
         *
         * A FK comando_id sera adicionada na migration 09.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE mensagens_integracao (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                dispositivo_id BIGINT UNSIGNED NULL,
                comando_id BIGINT UNSIGNED NULL,
                evento_id BIGINT UNSIGNED NULL,
                analise_id BIGINT UNSIGNED NULL,
                protocolo VARCHAR(30) NOT NULL,
                direcao VARCHAR(10) NOT NULL,
                topico VARCHAR(255) NULL,
                identificador_mensagem VARCHAR(120) NULL,
                correlation_id VARCHAR(120) NULL,
                chave_idempotencia VARCHAR(120) NULL,
                payload JSON NULL,
                payload_bruto LONGTEXT NULL,
                status VARCHAR(30) NOT NULL,
                erro TEXT NULL,
                recebida_em DATETIME(3) NULL,
                processada_em DATETIME(3) NULL,
                enviada_em DATETIME(3) NULL,
                confirmada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_mi_codigo (codigo),

                KEY idx_mi_protocolo_direcao_created
                    (protocolo, direcao, created_at),

                KEY idx_mi_topico (topico),
                KEY idx_mi_dispositivo (dispositivo_id),
                KEY idx_mi_comando (comando_id),
                KEY idx_mi_evento (evento_id),
                KEY idx_mi_analise (analise_id),
                KEY idx_mi_correlation (correlation_id),
                KEY idx_mi_status (status),
                KEY idx_mi_idempotencia (chave_idempotencia),
                KEY idx_mi_identificador (identificador_mensagem),

                CONSTRAINT fk_mi_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mi_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mi_analise
                    FOREIGN KEY (analise_id)
                    REFERENCES analises_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. COMANDOS
         *
         * Representa uma solicitacao operacional
         * destinada a um dispositivo ou componente.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE comandos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                componente_id BIGINT UNSIGNED NULL,
                evento_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                tipo VARCHAR(60) NOT NULL,
                parametros JSON NULL,
                status VARCHAR(30) NOT NULL,
                correlation_id VARCHAR(120) NOT NULL,
                chave_idempotencia VARCHAR(120) NOT NULL,
                solicitado_em DATETIME(3) NOT NULL,
                enviado_em DATETIME(3) NULL,
                recebido_em DATETIME(3) NULL,
                executado_em DATETIME(3) NULL,
                expira_em DATETIME(3) NULL,
                execucao_destinatario_id BIGINT UNSIGNED NULL,
                execucao_cena_id BIGINT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_comandos_codigo (codigo),

                UNIQUE KEY uq_comandos_dispositivo_idempotencia
                    (dispositivo_id, chave_idempotencia),

                KEY idx_comandos_dispositivo_solicitado
                    (dispositivo_id, solicitado_em),

                KEY idx_comandos_status_solicitado
                    (status, solicitado_em),

                KEY idx_comandos_componente (componente_id),
                KEY idx_comandos_evento (evento_id),
                KEY idx_comandos_usuario (usuario_id),
                KEY idx_comandos_correlation (correlation_id),
                KEY idx_comandos_destinatario (execucao_destinatario_id),
                KEY idx_comandos_execucao_cena (execucao_cena_id),
                KEY idx_comandos_expiracao (expira_em),

                CONSTRAINT fk_comandos_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_comandos_componente
                    FOREIGN KEY (componente_id)
                    REFERENCES componentes_dispositivo (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_comandos_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_comandos_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_comandos_destinatario
                    FOREIGN KEY (execucao_destinatario_id)
                    REFERENCES execucao_destinatarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_comandos_execucao_cena
                    FOREIGN KEY (execucao_cena_id)
                    REFERENCES execucoes_cenas (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_comandos_expiracao
                    CHECK (
                        expira_em IS NULL
                        OR expira_em > solicitado_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. HISTORICO_COMANDOS
         *
         * Preserva as transicoes de estado de cada comando
         * e as mensagens de integracao relacionadas.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE historico_comandos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                comando_id BIGINT UNSIGNED NOT NULL,
                status_anterior VARCHAR(30) NULL,
                status_novo VARCHAR(30) NOT NULL,
                origem VARCHAR(30) NOT NULL,
                mensagem_integracao_id BIGINT UNSIGNED NULL,
                detalhes JSON NULL,
                erro TEXT NULL,
                ocorrido_em DATETIME(3) NOT NULL,
                registrado_em DATETIME(3) NOT NULL,

                PRIMARY KEY (id),

                KEY idx_hc_comando_ocorrido
                    (comando_id, ocorrido_em),

                KEY idx_hc_status_novo (status_novo),
                KEY idx_hc_registrado (registrado_em),
                KEY idx_hc_mensagem (mensagem_integracao_id),

                CONSTRAINT fk_hc_comando
                    FOREIGN KEY (comando_id)
                    REFERENCES comandos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_hc_mensagem
                    FOREIGN KEY (mensagem_integracao_id)
                    REFERENCES mensagens_integracao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS historico_comandos');
        $this->db->query('DROP TABLE IF EXISTS comandos');
        $this->db->query('DROP TABLE IF EXISTS mensagens_integracao');
    }
}