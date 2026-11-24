<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInteligenciaArtificial extends Migration
{
    public function up()
    {
        /*
         * 1. ARTEFATOS_IA
         *
         * Cadastro logico de scripts, modelos, servicos,
         * coordenadores e especialistas de IA.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE artefatos_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(150) NOT NULL,
                descricao TEXT NULL,
                tipo VARCHAR(40) NOT NULL,
                finalidade VARCHAR(50) NOT NULL,
                linguagem VARCHAR(40) NULL,
                framework VARCHAR(80) NULL,
                status VARCHAR(30) NOT NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_aia_codigo (codigo),

                KEY idx_aia_tipo (tipo),
                KEY idx_aia_finalidade (finalidade),
                KEY idx_aia_status (status),
                KEY idx_aia_ativo (ativo),
                KEY idx_aia_usuario (usuario_id),

                CONSTRAINT fk_aia_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. VERSOES_ARTEFATOS_IA
         *
         * Versionamento dos artefatos executaveis.
         * Versoes publicadas devem ser imutaveis.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE versoes_artefatos_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                artefato_id BIGINT UNSIGNED NOT NULL,
                versao VARCHAR(40) NOT NULL,
                descricao_versao TEXT NULL,
                hash_sha256 CHAR(64) NOT NULL,
                uri_armazenamento VARCHAR(500) NOT NULL,
                dependencias JSON NULL,
                configuracao JSON NULL,
                status VARCHAR(30) NOT NULL,
                usuario_id INT UNSIGNED NULL,
                publicada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_vaia_artefato_versao
                    (artefato_id, versao),

                KEY idx_vaia_artefato_status
                    (artefato_id, status),

                KEY idx_vaia_hash (hash_sha256),
                KEY idx_vaia_usuario (usuario_id),
                KEY idx_vaia_publicada (publicada_em),

                CONSTRAINT fk_vaia_artefato
                    FOREIGN KEY (artefato_id)
                    REFERENCES artefatos_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vaia_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 3. PIPELINES_IA
         *
         * Cadastro logico dos pipelines de processamento.
         *
         * O coordenador pertence a versao do pipeline,
         * nao ao cadastro logico.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE pipelines_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                nome VARCHAR(150) NOT NULL,
                descricao TEXT NULL,
                finalidade VARCHAR(50) NOT NULL,
                status VARCHAR(30) NOT NULL,
                ativo TINYINT(1) NOT NULL DEFAULT 1,
                usuario_id INT UNSIGNED NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_pia_codigo (codigo),

                KEY idx_pia_finalidade (finalidade),
                KEY idx_pia_status (status),
                KEY idx_pia_ativo (ativo),
                KEY idx_pia_usuario (usuario_id),

                CONSTRAINT fk_pia_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 4. VERSOES_PIPELINES_IA
         *
         * Preserva a composicao versionada do pipeline
         * e a versao exata do artefato coordenador.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE versoes_pipelines_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                pipeline_id BIGINT UNSIGNED NOT NULL,
                coordenador_versao_id BIGINT UNSIGNED NOT NULL,
                versao VARCHAR(40) NOT NULL,
                descricao_versao TEXT NULL,
                hash_configuracao CHAR(64) NOT NULL,
                configuracao JSON NULL,
                status VARCHAR(30) NOT NULL,
                usuario_id INT UNSIGNED NULL,
                publicada_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_vpia_pipeline_versao
                    (pipeline_id, versao),

                KEY idx_vpia_pipeline_status
                    (pipeline_id, status),

                KEY idx_vpia_coordenador
                    (coordenador_versao_id),

                KEY idx_vpia_hash (hash_configuracao),
                KEY idx_vpia_usuario (usuario_id),
                KEY idx_vpia_publicada (publicada_em),

                CONSTRAINT fk_vpia_pipeline
                    FOREIGN KEY (pipeline_id)
                    REFERENCES pipelines_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vpia_coordenador
                    FOREIGN KEY (coordenador_versao_id)
                    REFERENCES versoes_artefatos_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_vpia_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 5. PIPELINE_COMPONENTES_IA
         *
         * Composicao de uma versao de pipeline.
         *
         * Um mesmo artefato pode ser reutilizado em
         * diferentes componentes e responsabilidades.
         *
         * As metricas esperadas pertencem a configuracao,
         * nao ao resultado efetivo da execucao.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE pipeline_componentes_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                pipeline_versao_id BIGINT UNSIGNED NOT NULL,
                artefato_versao_id BIGINT UNSIGNED NOT NULL,
                codigo VARCHAR(80) NOT NULL,
                papel VARCHAR(40) NOT NULL,
                ordem_execucao INT UNSIGNED NOT NULL DEFAULT 0,
                grupo_logico VARCHAR(80) NULL,
                dependencias JSON NULL,
                configuracao JSON NULL,
                parametros JSON NULL,
                obrigatorio TINYINT(1) NOT NULL DEFAULT 1,
                habilitado TINYINT(1) NOT NULL DEFAULT 1,
                metricas_esperadas JSON NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_pcia_pipeline_codigo
                    (pipeline_versao_id, codigo),

                KEY idx_pcia_pipeline_ordem
                    (pipeline_versao_id, ordem_execucao),

                KEY idx_pcia_pipeline_grupo
                    (pipeline_versao_id, grupo_logico),

                KEY idx_pcia_artefato
                    (artefato_versao_id),

                KEY idx_pcia_papel (papel),

                KEY idx_pcia_pipeline_habilitado
                    (pipeline_versao_id, habilitado),

                CONSTRAINT fk_pcia_pipeline
                    FOREIGN KEY (pipeline_versao_id)
                    REFERENCES versoes_pipelines_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_pcia_artefato
                    FOREIGN KEY (artefato_versao_id)
                    REFERENCES versoes_artefatos_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 6. IMPLANTACOES_IA
         *
         * Registra a implantacao de uma versao de
         * artefato OU de uma versao de pipeline.
         *
         * Exatamente uma das referencias deve existir.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE implantacoes_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                artefato_versao_id BIGINT UNSIGNED NULL,
                pipeline_versao_id BIGINT UNSIGNED NULL,
                usuario_id INT UNSIGNED NULL,
                status VARCHAR(30) NOT NULL,
                configuracao JSON NULL,
                observacoes TEXT NULL,
                implantada_em DATETIME(3) NULL,
                inicio_vigencia DATETIME(3) NULL,
                fim_vigencia DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
                updated_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3)
                    ON UPDATE CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_iia_codigo (codigo),

                KEY idx_iia_dispositivo_status
                    (dispositivo_id, status),

                KEY idx_iia_dispositivo_vigencia
                    (dispositivo_id, inicio_vigencia, fim_vigencia),

                KEY idx_iia_artefato
                    (artefato_versao_id),

                KEY idx_iia_pipeline
                    (pipeline_versao_id),

                KEY idx_iia_usuario (usuario_id),
                KEY idx_iia_implantada (implantada_em),

                CONSTRAINT fk_iia_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_iia_artefato
                    FOREIGN KEY (artefato_versao_id)
                    REFERENCES versoes_artefatos_ia (id),

                CONSTRAINT fk_iia_pipeline
                    FOREIGN KEY (pipeline_versao_id)
                    REFERENCES versoes_pipelines_ia (id),

                CONSTRAINT fk_iia_usuario
                    FOREIGN KEY (usuario_id)
                    REFERENCES users (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_iia_referencia_exclusiva
                    CHECK (
                        (
                            artefato_versao_id IS NOT NULL
                            AND pipeline_versao_id IS NULL
                        )
                        OR
                        (
                            artefato_versao_id IS NULL
                            AND pipeline_versao_id IS NOT NULL
                        )
                    ),

                CONSTRAINT chk_iia_vigencia
                    CHECK (
                        fim_vigencia IS NULL
                        OR (
                            inicio_vigencia IS NOT NULL
                            AND fim_vigencia > inicio_vigencia
                        )
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 7. ANALISES_IA
         *
         * Registra analises principais e analises filhas,
         * preservando a rastreabilidade das execucoes.
         *
         * As metricas representam resultados efetivos.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE analises_ia (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                codigo VARCHAR(80) NOT NULL,
                analise_pai_id BIGINT UNSIGNED NULL,
                evento_id BIGINT UNSIGNED NULL,
                dispositivo_id BIGINT UNSIGNED NULL,
                implantacao_id BIGINT UNSIGNED NULL,
                pipeline_versao_id BIGINT UNSIGNED NULL,
                artefato_versao_id BIGINT UNSIGNED NULL,
                pipeline_componente_id BIGINT UNSIGNED NULL,
                tipo VARCHAR(40) NOT NULL,
                servico VARCHAR(100) NOT NULL,
                modelo VARCHAR(120) NULL,
                versao_modelo VARCHAR(80) NULL,
                status VARCHAR(30) NOT NULL,
                entrada JSON NULL,
                resultado JSON NULL,
                metricas JSON NULL,
                confianca DECIMAL(8,6) NULL,
                duracao_ms BIGINT UNSIGNED NULL,
                erro TEXT NULL,
                iniciada_em DATETIME(3) NOT NULL,
                concluida_em DATETIME(3) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_anaia_codigo (codigo),

                KEY idx_anaia_pai (analise_pai_id),
                KEY idx_anaia_evento (evento_id),
                KEY idx_anaia_dispositivo (dispositivo_id),
                KEY idx_anaia_implantacao (implantacao_id),
                KEY idx_anaia_pipeline (pipeline_versao_id),
                KEY idx_anaia_artefato (artefato_versao_id),
                KEY idx_anaia_componente (pipeline_componente_id),

                KEY idx_anaia_tipo_status
                    (tipo, status),

                KEY idx_anaia_iniciada (iniciada_em),
                KEY idx_anaia_concluida (concluida_em),

                KEY idx_anaia_dispositivo_inicio
                    (dispositivo_id, iniciada_em),

                CONSTRAINT fk_anaia_pai
                    FOREIGN KEY (analise_pai_id)
                    REFERENCES analises_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_anaia_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_anaia_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_anaia_implantacao
                    FOREIGN KEY (implantacao_id)
                    REFERENCES implantacoes_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_anaia_pipeline
                    FOREIGN KEY (pipeline_versao_id)
                    REFERENCES versoes_pipelines_ia (id),

                CONSTRAINT fk_anaia_artefato
                    FOREIGN KEY (artefato_versao_id)
                    REFERENCES versoes_artefatos_ia (id),

                CONSTRAINT fk_anaia_componente
                    FOREIGN KEY (pipeline_componente_id)
                    REFERENCES pipeline_componentes_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT chk_anaia_referencia_exclusiva
                    CHECK (
                        pipeline_versao_id IS NULL
                        OR artefato_versao_id IS NULL
                    ),

                CONSTRAINT chk_anaia_periodo
                    CHECK (
                        concluida_em IS NULL
                        OR concluida_em >= iniciada_em
                    )

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 8. ANALISE_ANEXOS
         *
         * Relaciona analises de IA aos seus arquivos
         * de entrada, evidencias, frames e resultados.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE analise_anexos (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                analise_id BIGINT UNSIGNED NOT NULL,
                anexo_id BIGINT UNSIGNED NOT NULL,
                papel VARCHAR(30) NOT NULL,
                ordem INT UNSIGNED NULL,
                observacoes VARCHAR(255) NULL,
                created_at DATETIME(3) NULL DEFAULT CURRENT_TIMESTAMP(3),

                PRIMARY KEY (id),

                UNIQUE KEY uq_aa_analise_anexo_papel
                    (analise_id, anexo_id, papel),

                KEY idx_aa_anexo (anexo_id),

                KEY idx_aa_analise_ordem
                    (analise_id, ordem),

                CONSTRAINT fk_aa_analise
                    FOREIGN KEY (analise_id)
                    REFERENCES analises_ia (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_aa_anexo
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
        /*
         * Exclusao na ordem inversa das dependencias.
         */
        $this->db->query(
            'DROP TABLE IF EXISTS analise_anexos'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS analises_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS implantacoes_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS pipeline_componentes_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS versoes_pipelines_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS pipelines_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS versoes_artefatos_ia'
        );

        $this->db->query(
            'DROP TABLE IF EXISTS artefatos_ia'
        );
    }
}