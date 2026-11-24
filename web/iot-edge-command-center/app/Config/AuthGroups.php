<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * Grupo atribuído automaticamente a novos usuários,
     * quando nenhum grupo específico for informado.
     */
    public string $defaultGroup = 'visualizador';

    /**
     * Grupos disponíveis na plataforma.
     */
    public array $groups = [
        'administrador' => [
            'title'       => 'Administrador',
            'description' => 'Gerenciamento completo da plataforma IoT-Edge Command Center.',
        ],

        'operador' => [
            'title'       => 'Operador',
            'description' => 'Acompanhamento e execução de operações autorizadas.',
        ],

        'visualizador' => [
            'title'       => 'Visualizador',
            'description' => 'Acesso de consulta, monitoramento e acompanhamento das informações.',
        ],
    ];

    /**
     * Catálogo de permissões disponíveis.
     */
    public array $permissions = [

        // Dashboard e monitoramento
        'dashboard.view'       => 'Visualizar o dashboard principal.',
        'telemetry.view'       => 'Consultar telemetria dos dispositivos.',

        // Histórico e gêmeo digital
        'history.view'         => 'Consultar históricos e registros da plataforma.',
        'digital_twin.view'    => 'Visualizar o gêmeo digital e seu histórico.',

        // Videomonitoramento
        'video.view'           => 'Visualizar câmeras e transmissões autorizadas.',

        // Dispositivos e componentes
        'devices.view'         => 'Consultar dispositivos e seus componentes.',
        'devices.manage'       => 'Cadastrar, editar e gerenciar dispositivos.',

        // Estrutura física
        'locations.view'       => 'Consultar a estrutura hierárquica de locais.',
        'locations.manage'     => 'Cadastrar e gerenciar locais.',

        // Eventos
        'events.view'          => 'Consultar eventos e ocorrências.',
        'events.acknowledge'   => 'Reconhecer e confirmar o recebimento de eventos.',
        'events.manage'        => 'Gerenciar eventos e suas classificações.',

        // Automações
        'automations.view'     => 'Consultar regras e automações.',
        'automations.execute'  => 'Executar automações autorizadas.',
        'automations.manage'   => 'Criar, editar e gerenciar regras de automação.',

        // Comandos
        'commands.view'        => 'Consultar comandos e seus históricos.',
        'commands.execute'    => 'Solicitar execução de comandos autorizados.',

        // Usuários e segurança
        'users.view'           => 'Consultar usuários e seus perfis.',
        'users.manage'         => 'Criar, editar, desativar e gerenciar usuários e grupos.',

        // Administração
        'audit.view'           => 'Consultar registros de auditoria.',
        'system.settings'      => 'Gerenciar configurações gerais da plataforma.',
    ];

    /**
     * Matriz de permissões por grupo.
     */
    public array $matrix = [

        /**
         * Administrador:
         * acesso integral às funcionalidades declaradas.
         */
        'administrador' => [
            'dashboard.view',
            'telemetry.view',
            'history.view',
            'digital_twin.view',
            'video.view',
            'devices.view',
            'devices.manage',
            'locations.view',
            'locations.manage',
            'events.view',
            'events.acknowledge',
            'events.manage',
            'automations.view',
            'automations.execute',
            'automations.manage',
            'commands.view',
            'commands.execute',
            'users.view',
            'users.manage',
            'audit.view',
            'system.settings',
        ],

        /**
         * Operador:
         * consulta informações e executa operações autorizadas.
         * Não administra usuários nem configurações estruturais.
         */
        'operador' => [
            'dashboard.view',
            'telemetry.view',
            'history.view',
            'digital_twin.view',
            'video.view',
            'devices.view',
            'locations.view',
            'events.view',
            'events.acknowledge',
            'automations.view',
            'automations.execute',
            'commands.view',
            'commands.execute',
        ],

        /**
         * Visualizador:
         * acesso exclusivamente de consulta.
         */
        'visualizador' => [
            'dashboard.view',
            'telemetry.view',
            'history.view',
            'digital_twin.view',
            'video.view',
            'devices.view',
            'locations.view',
            'events.view',
            'automations.view',
            'commands.view',
        ],
    ];
}
