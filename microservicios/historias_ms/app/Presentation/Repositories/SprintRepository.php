<?php

namespace App\Presentation\Repositories;

use App\Controllers\SprintController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SprintRepository
{
    function list(Request $request, Response $response)
    {
        $controller = new SprintController();
        $sprints = $controller->getSprints();
        $dataJson = $sprints->toJson();
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
            $controller = new SprintController();
            $sprint = $controller->guardarSprint($data);
            $dataJson = $sprint->toJson();
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
        $controller = new SprintController();
        $sprint = $controller->modificarSprint($id, $data);
        $dataResponse = $sprint->toJson();
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $controller = new SprintController();
        $estado = $controller->borrarSprint($id);
        $dataResponse = json_encode(['msg' => 'Sprint borrado']);
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }

    function detail(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $controller = new SprintController();
        $sprint = $controller->getSprint($id);
        $dataResponse = $sprint->toJson();
        $response->getBody()->write($dataResponse);
        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json');
    }
}