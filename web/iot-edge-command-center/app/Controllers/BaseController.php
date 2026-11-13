<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController fornece recursos comuns aos controllers do projeto.
 *
 * Os controllers da aplicação devem estender esta classe.
 */
abstract class BaseController extends Controller
{
    /**
     * Helpers disponibilizados para os controllers que estendem esta classe.
     *
     * O helper url disponibiliza site_url() e base_url().
     */
    protected $helpers = ['url'];

    /**
     * Inicializa o controller.
     *
     * @param CLIRequest|IncomingRequest $request
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        // Não remova esta chamada: ela inicializa o controller pai.
        parent::initController($request, $response, $logger);

        // Modelos, bibliotecas e serviços compartilhados podem ser
        // inicializados aqui futuramente, caso sejam necessários.
    }
}
