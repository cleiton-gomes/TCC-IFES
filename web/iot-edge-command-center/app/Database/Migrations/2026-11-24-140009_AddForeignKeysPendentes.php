<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeysPendentes extends Migration
{
    public function up()
    {
        /*
         * 1. HISTORICO_ESTADO_GEMEO
         *
         * Preserva os estados historicos do gemeo digital
         * de cada dispositivo.
         *
         * Os estados podem ser relacionados a eventos,
         * leituras, comandos e versoes de configuracao.
         */
        $this->db->query(<<<'SQL'
            CREATE TABLE historico_estado_gemeo (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                dispositivo_id BIGINT UNSIGNED NOT NULL,
                evento_id BIGINT UNSIGNED NULL,
                leitura_id BIGINT UNSIGNED NULL,
                comando_id BIGINT UNSIGNED NULL,
                versao_configuracao_id BIGINT UNSIGNED NULL,
                tipo_estado VARCHAR(40) NOT NULL,
                estado JSON NOT NULL,
                origem VARCHAR(30) NOT NULL,
                ocorrido_em DATETIME(3) NOT NULL,
                registrado_em DATETIME(3) NOT NULL,

                PRIMARY KEY (id),

                KEY idx_heg_dispositivo_ocorrido
                    (dispositivo_id, ocorrido_em),

                KEY idx_heg_evento (evento_id),
                KEY idx_heg_leitura (leitura_id),
                KEY idx_heg_comando (comando_id),
                KEY idx_heg_versao_configuracao
                    (versao_configuracao_id),

                KEY idx_heg_tipo_estado (tipo_estado),
                KEY idx_heg_registrado (registrado_em),

                CONSTRAINT fk_heg_dispositivo
                    FOREIGN KEY (dispositivo_id)
                    REFERENCES dispositivos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_heg_evento
                    FOREIGN KEY (evento_id)
                    REFERENCES eventos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_heg_leitura
                    FOREIGN KEY (leitura_id)
                    REFERENCES leituras (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_heg_comando
                    FOREIGN KEY (comando_id)
                    REFERENCES comandos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT,

                CONSTRAINT fk_heg_versao_configuracao
                    FOREIGN KEY (versao_configuracao_id)
                    REFERENCES versoes_configuracao (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT

            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET=utf8mb4
              COLLATE=utf8mb4_general_ci
        SQL);

        /*
         * 2. CHAVE ESTRANGEIRA PENDENTE
         *
         * A tabela mensagens_integracao foi criada antes
         * da tabela comandos na migration 07.
         *
         * Neste momento, ambas as tabelas ja existem,
         * permitindo estabelecer a integridade referencial.
         */
        $this->db->query(<<<'SQL'
            ALTER TABLE mensagens_integracao
                ADD CONSTRAINT fk_mi_comando
                    FOREIGN KEY (comando_id)
                    REFERENCES comandos (id)
                    ON DELETE RESTRICT ON UPDATE RESTRICT
        SQL);
    }

    public function down()
    {
        /*
         * Remove primeiro a chave estrangeira adicionada
         * por esta migration.
         */
        $this->db->query(<<<'SQL'
            ALTER TABLE mensagens_integracao
                DROP FOREIGN KEY fk_mi_comando
        SQL);

        /*
         * Remove a tabela de historico do gemeo digital.
         */
        $this->db->query(
            'DROP TABLE IF EXISTS historico_estado_gemeo'
        );
    }
}