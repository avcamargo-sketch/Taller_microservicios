<?php

namespace App\Controllers;

use App\Presentation\Repositories\HistoriaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HistoriaController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new HistoriaRepository();
    }

    public function index(Request $request, Response $response)
    {
        $historias = $this->repository->all();
        $response->getBody()->write(json_encode($historias));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, $args)
    {
        $historia = $this->repository->find($args['id']);
        if (!$historia) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode($historia));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        
        if (empty($data['titulo']) || empty($data['descripcion']) || 
            empty($data['responsable']) || empty($data['puntos']) || 
            empty($data['sprint_id']) || empty($data['fecha_creacion'])) {
            $response->getBody()->write(json_encode(['error' => 'Faltan campos obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        $historia = $this->repository->create($data);
        $response->getBody()->write(json_encode($historia));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $historia = $this->repository->update($args['id'], $data);
        
        if (!$historia) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        $response->getBody()->write(json_encode($historia));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $eliminado = $this->repository->delete($args['id']);
        
        if (!$eliminado) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        $response->getBody()->write(json_encode(['mensaje' => 'Historia eliminada correctamente']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function porSprint(Request $request, Response $response, $args)
    {
        $historias = $this->repository->porSprint($args['id']);
        $response->getBody()->write(json_encode($historias));
        return $response->withHeader('Content-Type', 'application/json');
    }
}