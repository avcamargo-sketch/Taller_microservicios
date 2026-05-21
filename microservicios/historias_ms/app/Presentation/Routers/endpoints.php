<?php

use App\Controllers\SprintController;
use App\Controllers\HistoriaController;
use App\Controllers\InformeController;

return function ($app) {
    
    // Ruta de prueba
    $app->get('/', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'mensaje' => 'API Gestor de Historias de Usuario'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Instanciar controllers
    $sprintController = new SprintController();
    $historiaController = new HistoriaController();
    $informeController = new InformeController();

    // ==========================================
    // RUTAS DE SPRINTS
    // ==========================================
    $app->get('/api/sprints', [$sprintController, 'index']);
    $app->get('/api/sprints/{id}', [$sprintController, 'show']);
    $app->post('/api/sprints', [$sprintController, 'store']);
    $app->put('/api/sprints/{id}', [$sprintController, 'update']);
    $app->delete('/api/sprints/{id}', [$sprintController, 'destroy']);

    // ==========================================
    // RUTAS DE HISTORIAS
    // ==========================================
    $app->get('/api/historias', [$historiaController, 'index']);
    $app->get('/api/historias/{id}', [$historiaController, 'show']);
    $app->post('/api/historias', [$historiaController, 'store']);
    $app->put('/api/historias/{id}', [$historiaController, 'update']);
    $app->delete('/api/historias/{id}', [$historiaController, 'destroy']);
    $app->get('/api/sprints/{id}/historias', [$historiaController, 'porSprint']);

    // ==========================================
    // RUTAS DE INFORMES
    // ==========================================
    $app->get('/api/informes/sprints', [$informeController, 'porSprint']);
    $app->get('/api/informes/responsables', [$informeController, 'porResponsable']);
};