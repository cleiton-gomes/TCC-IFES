<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAudioEAuditoria extends Migration
{
    public function up()
    {
        /*
         * 1. MENSAGENS_AUDIO
         *
         * Registro das mensagens textuais destinadas
         * a sintetizacao de voz.
         *
         * Suporta mecanismos como Piper e Flite.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE mensagens_audio (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                analise_id BIGINT UNSIGNED NULL,
                evento_id BIGINT UNSIGNED NULL,
                execucao_acao_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                texto LONGTEXT NOT NULL,
                mecanismo_tts VARCHAR(30) NOT NULL,
                provedor VARCHAR(80) NULL,
                modelo_voz VARCHAR(120) NULL,
                idioma VARCHAR(20) NULL,
                formato_audio VARCHAR(15) NULL,
                anexo_id BIGINT UNSIGNED NULL,
                status VARCHAR(30) NOT NULL,
                duracao_ms BIGINT UNSIGNED NULL,
                erro TEXT NULL,
                solicitada_em DATETIME(3) NOT NULL,
                gerada_em DATETIME(3) NULL,
                disponibilizada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_ma_codigo (codigo),

                KEY idx_ma_analise (analise_id),
                KEY idx_ma_evento (evento_id),
                KEY idx_ma_execucao_acao (execucao_acao_id),
                KEY idx_ma_usuario (usuario_id),
                KEY idx_ma_anexo (anexo_id),
                KEY idx_ma_mecanismo (mecanismo_tts),

                KEY idx_ma_status_solicitada
                    (status, solicitada_em),

                CONSTRAINT fk_ma_analise
                    FOREIGN KEY (analise_id)
                    REFERENCES analises_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ma_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ma_execucao_acao
                    FOREIGN KEY (execucao_acao_id)
                    REFERENCES execucoes_acoes (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ma_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_ma_anexo
                    FOREIGN KEY (anexo_id)
                    REFERENCES anexos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. MENSAGEM_AUDIO_DESTINATARIOS
         *
         * Acompanhamento individual da distribuicao
         * e reproducao das mensagens de audio.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE mensagem_audio_destinatarios (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                mensagem_audio_id BIGINT UNSIGNED NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                local_id BIGINT UNSIGNED NULL,
                comando_id BIGINT UNSIGNED NULL,
                execucao_destinatario_id BIGINT UNSIGNED NULL,
                topico_destino VARCHAR(255) NULL,
                status VARCHAR(30) NOT NULL,
                tentativas INT UNSIGNED NOT NULL DEFAULT 0,
                detalhes JSON NULL,
                erro TEXT NULL,
                solicitado_em DATETIME(3) NOT NULL,
                enviado_em DATETIME(3) NULL,
                recebido_em DATETIME(3) NULL,
                iniciado_em DATETIME(3) NULL,
                reproduzido_em DATETIME(3) NULL,
                concluido_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_mad_mensagem_dispositivo
                    (mensagem_audio_id, dispositivo_id),

                KEY idx_mad_dispositivo (dispositivo_id),
                KEY idx_mad_local (local_id),
                KEY idx_mad_comando (comando_id),
                KEY idx_mad_execucao_destinatario
                    (execucao_destinatario_id),

                KEY idx_mad_mensagem_status
                    (mensagem_audio_id, status),

                CONSTRAINT fk_mad_mensagem
                    FOREIGN KEY (mensagem_audio_id)
                    REFERENCES mensagens_audio (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mad_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mad_local
                    FOREIGN KEY (local_id)
                    REFERENCES locais (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mad_comando
                    FOREIGN KEY (comando_id)
                    REFERENCES comandos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_mad_execucao_destinatario
                    FOREIGN KEY (execucao_destinatario_id)
                    REFERENCES execucao_destinatarios (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. AUDITORIA
         *
         * Registro transversal das operacoes realizadas
         * por usuarios, dispositivos e servicos.
         *
         * entidade_id possui referencia polimorfica,
         * sendo interpretada em conjunto com entidade.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE auditoria (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                usuario_id INT UNSIGNED NULL,
                dispositivo_id BIGINT UNSIGNED NULL,
                evento_id BIGINT UNSIGNED NULL,
                acao VARCHAR(80) NOT NULL,
                entidade VARCHAR(100) NOT NULL,
                entidade_id BIGINT UNSIGNED NULL,
                origem VARCHAR(40) NOT NULL,
                canal VARCHAR(30) NULL,
                resultado VARCHAR(30) NOT NULL,
                descricao TEXT NULL,
                dados_anteriores JSON NULL,
                dados_posteriores JSON NULL,
                correlation_id VARCHAR(120) NULL,
                endereco_ip VARCHAR(45) NULL,
                user_agent VARCHAR(500) NULL,
                ocorrido_em DATETIME(3) NOT NULL,
                registrado_em DATETIME(3) NOT NULL,

                PRIMARY KEY (id),

                UNIQUE KEY uq_auditoria_codigo (codigo),

                KEY idx_auditoria_entidade
                    (entidade, entidade_id),

                KEY idx_auditoria_usuario_ocorrido
                    (usuario_id, ocorrido_em),

                KEY idx_auditoria_dispositivo_ocorrido
                    (dispositivo_id, ocorrido_em),

                KEY idx_auditoria_acao_ocorrido
                    (acao, ocorrido_em),

                KEY idx_auditoria_resultado_ocorrido
                    (resultado, ocorrido_em),

                KEY idx_auditoria_evento (evento_id),
                KEY idx_auditoria_correlation (correlation_id),
                KEY idx_auditoria_registrado (registrado_em),

                CONSTRAINT fk_auditoria_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_auditoria_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_auditoria_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS auditoria');
        $this->db->query('DROP TABLE IF EXISTS mensagem_audio_destinatarios');
        $this->db->query('DROP TABLE IF EXISTS mensagens_audio');
    }
}