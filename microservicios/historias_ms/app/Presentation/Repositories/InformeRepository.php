<?php

namespace App\Presentation\Repositories;

use App\Controllers\InformeController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InformeRepository
{
    function porSprint(Request $request, Response $response)
    {
        $controller = new InformeController();
        $informe = $controller->getInformePorSprint();
        $dataResponse = json_encode($informe);
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function porResponsable(Request $request, Response $response)
    {
        $controller = new InformeController();
        $informe = $controller->getInformePorResponsable();
        $dataResponse = json_encode($informe);
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }
}