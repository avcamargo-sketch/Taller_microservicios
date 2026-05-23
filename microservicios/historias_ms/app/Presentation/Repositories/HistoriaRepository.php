<?php

namespace App\Presentation\Repositories;

use App\Controllers\HistoriaController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HistoriaRepository
{
    function list(Request $request, Response $response)
    {
        $controller = new HistoriaController();
        $historias = $controller->getHistorias();
        $dataJson = $historias->toJson();
        $response->getBody()->write($dataJson);
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new HistoriaController();
            $historia = $controller->guardarHistoria($data);
            $dataJson = $historia->toJson();
            $response->getBody()->write($dataJson);
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = 400;
            if ($ex->getCode() == 1) {
                $code = 406;
                $response->getBody()->write(json_encode(['msg' => 'Datos erroneos']));
            } else {
                $response->getBody()->write(json_encode(['msg' => 'Error en el servicio']));
            }
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function update(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);
        $controller = new HistoriaController();
        $historia = $controller->modificarHistoria($id, $data);
        $dataResponse = $historia->toJson();
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $controller = new HistoriaController();
        $estado = $controller->borrarHistoria($id);
        $dataResponse = json_encode(['msg' => 'Historia borrada']);
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $controller = new HistoriaController();
        $historia = $controller->getHistoria($id);
        $dataResponse = $historia->toJson();
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function porSprint(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $controller = new HistoriaController();
        $historias = $controller->getHistoriasPorSprint($id);
        $dataResponse = $historias->toJson();
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }
}