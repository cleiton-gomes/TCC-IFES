<?php

namespace App\Controllers;

class Locais extends BaseController
{
    /**
     * Exibe a página de gerenciamento de locais.
     *
     * Nesta etapa, os dados da interface são fictícios
     * e estão definidos no JavaScript da view.
     *
     * A integração com Model e banco de dados será
     * implementada posteriormente.
     */
    public function index()
    {
        return view('pages/locais');
    }
}