<?php

namespace App\Controllers;

use App\Presentation\Repositories\HistoriaRepository;
use App\Models\Sprint;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InformeController
{
    private $historiaRepo;

    public function __construct()
    {
        $this->historiaRepo = new HistoriaRepository();
    }

    public function porSprint(Request $request, Response $response)
    {
        $sprints = Sprint::all();
        $informe = [];
        
        foreach ($sprints as $sprint) {
            $historias = $this->historiaRepo->porSprint($sprint->id);
            $total = $historias->count();
            $finalizadas = $historias->where('estado', 'finalizada')->count();
            $pendientes = $historias->whereIn('estado', ['nueva', 'activa'])->count();
            $impedimentos = $historias->where('estado', 'impedimento')->count();
            $puntosTotales = $historias->sum('puntos');
            
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
    }

    public function porResponsable(Request $request, Response $response)
    {
        $responsables = $this->historiaRepo->responsablesUnicos();
        $informe = [];
        
        foreach ($responsables as $r) {
            $historias = $this->historiaRepo->porResponsable($r->responsable);
            $total = $historias->count();
            $finalizadas = $historias->where('estado', 'finalizada')->count();
            $pendientes = $historias->whereIn('estado', ['nueva', 'activa'])->count();
            $impedimentos = $historias->where('estado', 'impedimento')->count();
            $puntosTotales = $historias->sum('puntos');
            
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
    }
}