<?php

namespace App\Controllers;

use App\Presentation\Repositories\SprintRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SprintController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new SprintRepository();
    }

    public function index(Request $request, Response $response)
    {
        $sprints = $this->repository->all();
        $response->getBody()->write(json_encode($sprints));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, $args)
    {
        $sprint = $this->repository->find($args['id']);
        if (!$sprint) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode($sprint));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        if (empty($data['nombre']) || empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            $response->getBody()->write(json_encode(['error' => 'Faltan campos obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        $sprint = $this->repository->create($data);
        $response->getBody()->write(json_encode($sprint));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $sprint = $this->repository->update($args['id'], $data);
        
        if (!$sprint) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        $response->getBody()->write(json_encode($sprint));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $eliminado = $this->repository->delete($args['id']);
        
        if (!$eliminado) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        $response->getBody()->write(json_encode(['mensaje' => 'Sprint eliminado correctamente']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}