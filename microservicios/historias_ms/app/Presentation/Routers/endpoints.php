<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

return function ($app) {
    
    // ==========================================
    // RUTA DE PRUEBA
    // ==========================================
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(['mensaje' => 'API Gestor de Historias de Usuario']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ==========================================
    // ENDPOINTS DE SPRINTS
    // ==========================================
    
    // GET /api/sprints - Listar todos los sprints
    $app->get('/api/sprints', function (Request $request, Response $response) {
        $sprints = Capsule::table('sprints')->get();
        $response->getBody()->write(json_encode($sprints));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /api/sprints/{id} - Ver un sprint específico
    $app->get('/api/sprints/{id}', function (Request $request, Response $response, $args) {
        $sprint = Capsule::table('sprints')->where('id', $args['id'])->first();
        if (!$sprint) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode($sprint));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // POST /api/sprints - Crear un sprint
    $app->post('/api/sprints', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        
        if (empty($data['nombre']) || empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            $response->getBody()->write(json_encode(['error' => 'Faltan campos obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        $id = Capsule::table('sprints')->insertGetId([
            'nombre' => $data['nombre'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin']
        ]);
        
        $sprint = Capsule::table('sprints')->where('id', $id)->first();
        $response->getBody()->write(json_encode($sprint));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    });

    // PUT /api/sprints/{id} - Editar un sprint
    $app->put('/api/sprints/{id}', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $id = $args['id'];
        
        $sprint = Capsule::table('sprints')->where('id', $id)->first();
        if (!$sprint) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        Capsule::table('sprints')->where('id', $id)->update([
            'nombre' => $data['nombre'] ?? $sprint->nombre,
            'fecha_inicio' => $data['fecha_inicio'] ?? $sprint->fecha_inicio,
            'fecha_fin' => $data['fecha_fin'] ?? $sprint->fecha_fin
        ]);
        
        $sprintActualizado = Capsule::table('sprints')->where('id', $id)->first();
        $response->getBody()->write(json_encode($sprintActualizado));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // DELETE /api/sprints/{id} - Eliminar un sprint
    $app->delete('/api/sprints/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $sprint = Capsule::table('sprints')->where('id', $id)->first();
        
        if (!$sprint) {
            $response->getBody()->write(json_encode(['error' => 'Sprint no encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        Capsule::table('sprints')->where('id', $id)->delete();
        $response->getBody()->write(json_encode(['mensaje' => 'Sprint eliminado correctamente']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ==========================================
    // ENDPOINTS DE HISTORIAS DE USUARIO
    // ==========================================
    
    // GET /api/historias - Listar todas las historias
    $app->get('/api/historias', function (Request $request, Response $response) {
        $historias = Capsule::table('historias')->get();
        $response->getBody()->write(json_encode($historias));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /api/historias/{id} - Ver una historia específica
    $app->get('/api/historias/{id}', function (Request $request, Response $response, $args) {
        $historia = Capsule::table('historias')->where('id', $args['id'])->first();
        if (!$historia) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode($historia));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // POST /api/historias - Crear una historia
    $app->post('/api/historias', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        
        if (empty($data['titulo']) || empty($data['descripcion']) || 
            empty($data['responsable']) || empty($data['puntos']) || 
            empty($data['sprint_id']) || empty($data['fecha_creacion'])) {
            $response->getBody()->write(json_encode(['error' => 'Faltan campos obligatorios']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        
        $id = Capsule::table('historias')->insertGetId([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'responsable' => $data['responsable'],
            'estado' => $data['estado'] ?? 'nueva',
            'puntos' => $data['puntos'],
            'fecha_creacion' => $data['fecha_creacion'],
            'fecha_finalizacion' => $data['fecha_finalizacion'] ?? null,
            'sprint_id' => $data['sprint_id']
        ]);
        
        $historia = Capsule::table('historias')->where('id', $id)->first();
        $response->getBody()->write(json_encode($historia));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    });

    // PUT /api/historias/{id} - Editar una historia
    $app->put('/api/historias/{id}', function (Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $id = $args['id'];
        
        $historia = Capsule::table('historias')->where('id', $id)->first();
        if (!$historia) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        Capsule::table('historias')->where('id', $id)->update([
            'titulo' => $data['titulo'] ?? $historia->titulo,
            'descripcion' => $data['descripcion'] ?? $historia->descripcion,
            'responsable' => $data['responsable'] ?? $historia->responsable,
            'estado' => $data['estado'] ?? $historia->estado,
            'puntos' => $data['puntos'] ?? $historia->puntos,
            'fecha_finalizacion' => $data['fecha_finalizacion'] ?? $historia->fecha_finalizacion,
            'sprint_id' => $data['sprint_id'] ?? $historia->sprint_id
        ]);
        
        $historiaActualizada = Capsule::table('historias')->where('id', $id)->first();
        $response->getBody()->write(json_encode($historiaActualizada));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // DELETE /api/historias/{id} - Eliminar una historia
    $app->delete('/api/historias/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $historia = Capsule::table('historias')->where('id', $id)->first();
        
        if (!$historia) {
            $response->getBody()->write(json_encode(['error' => 'Historia no encontrada']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
        
        Capsule::table('historias')->where('id', $id)->delete();
        $response->getBody()->write(json_encode(['mensaje' => 'Historia eliminada correctamente']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /api/sprints/{id}/historias - Listar historias de un sprint
    $app->get('/api/sprints/{id}/historias', function (Request $request, Response $response, $args) {
        $sprintId = $args['id'];
        $historias = Capsule::table('historias')->where('sprint_id', $sprintId)->get();
        $response->getBody()->write(json_encode($historias));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ==========================================
    // ENDPOINTS DE INFORMES
    // ==========================================
    
    // GET /api/informes/sprints - Resumen por sprint
    $app->get('/api/informes/sprints', function (Request $request, Response $response) {
        $sprints = Capsule::table('sprints')->get();
        $informe = [];
        
        foreach ($sprints as $sprint) {
            $historias = Capsule::table('historias')->where('sprint_id', $sprint->id)->get();
            $total = count($historias);
            $finalizadas = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'finalizada'));
            $pendientes = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'nueva' || $h->estado === 'activa'));
            $impedimentos = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'impedimento'));
            $puntosTotales = array_sum(array_column($historias->toArray(), 'puntos'));
            
            $informe[] = [
                'sprint_id' => $sprint->id,
                'sprint_nombre' => $sprint->nombre,
                'total_historias' => $total,
                'finalizadas' => $finalizadas,
                'pendientes' => $pendientes,
                'impedimentos' => $impedimentos,
                'puntos_totales' => $puntosTotales
            ];
        }
        
        $response->getBody()->write(json_encode($informe));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // GET /api/informes/responsables - Resumen por responsable
    $app->get('/api/informes/responsables', function (Request $request, Response $response) {
        $responsables = Capsule::table('historias')->select('responsable')->distinct()->get();
        $informe = [];
        
        foreach ($responsables as $r) {
            $historias = Capsule::table('historias')->where('responsable', $r->responsable)->get();
            $total = count($historias);
            $finalizadas = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'finalizada'));
            $pendientes = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'nueva' || $h->estado === 'activa'));
            $impedimentos = count(array_filter($historias->toArray(), fn($h) => $h->estado === 'impedimento'));
            $puntosTotales = array_sum(array_column($historias->toArray(), 'puntos'));
            
            $informe[] = [
                'responsable' => $r->responsable,
                'total_historias' => $total,
                'finalizadas' => $finalizadas,
                'pendientes' => $pendientes,
                'impedimentos' => $impedimentos,
                'puntos_totales' => $puntosTotales
            ];
        }
        
        $response->getBody()->write(json_encode($informe));
        return $response->withHeader('Content-Type', 'application/json');
    });
};