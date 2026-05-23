<?php

namespace App\Controllers;

use App\Models\Sprint;
use App\Models\Historia;

class InformeController
{
    function getInformePorSprint()
    {
        $sprints = Sprint::all();
        $informe = [];
        
        foreach ($sprints as $sprint) {
            $historias = Historia::where('sprint_id', $sprint->id)->get();
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
        return $informe;
    }

    function getInformePorResponsable()
    {
        $responsables = Historia::select('responsable')->distinct()->get();
        $informe = [];
        
        foreach ($responsables as $r) {
            $historias = Historia::where('responsable', $r->responsable)->get();
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
        return $informe;
    }
}