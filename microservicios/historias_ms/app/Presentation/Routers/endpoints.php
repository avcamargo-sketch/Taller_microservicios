<?php

use App\Presentation\Repositories\SprintRepository;
use App\Presentation\Repositories\HistoriaRepository;
use App\Presentation\Repositories\InformeRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->get('/', [SprintRepository::class, 'list']);

    // ==========================================
    // SPRINTS
    // ==========================================
    $app->get('/api/sprints', [SprintRepository::class, 'list']);
    $app->get('/api/sprints/{id}', [SprintRepository::class, 'detail']);
    $app->post('/api/sprints', [SprintRepository::class, 'create']);
    $app->put('/api/sprints/{id}', [SprintRepository::class, 'update']);
    $app->delete('/api/sprints/{id}', [SprintRepository::class, 'delete']);

    // ==========================================
    // HISTORIAS
    // ==========================================
    $app->get('/api/historias', [HistoriaRepository::class, 'list']);
    $app->get('/api/historias/{id}', [HistoriaRepository::class, 'detail']);
    $app->post('/api/historias', [HistoriaRepository::class, 'create']);
    $app->put('/api/historias/{id}', [HistoriaRepository::class, 'update']);
    $app->delete('/api/historias/{id}', [HistoriaRepository::class, 'delete']);
    $app->get('/api/sprints/{id}/historias', [HistoriaRepository::class, 'porSprint']);

    // ==========================================
    // INFORMES
    // ==========================================
    $app->get('/api/informes/sprints', [InformeRepository::class, 'porSprint']);
    $app->get('/api/informes/responsables', [InformeRepository::class, 'porResponsable']);

};