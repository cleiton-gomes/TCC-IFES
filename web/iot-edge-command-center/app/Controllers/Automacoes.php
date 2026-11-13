<?php

namespace App\Controllers;

class Automacoes extends BaseController
{
    /**
     * Exibe a página de Automações e Operações.
     *
     * Nesta etapa, a interface utiliza dados fictícios
     * e interações executadas no próprio navegador.
     *
     * A integração com Models, banco de dados e MQTT
     * será implementada posteriormente.
     */
    public function index()
    {
        return view('pages/automacoes');
    }
}