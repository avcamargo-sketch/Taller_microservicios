<?php

namespace App\Controllers;

use App\Models\Historia;
use Exception;

class HistoriaController
{
    function getHistorias()
    {
        return Historia::all();
    }

    function guardarHistoria($data)
    {
        if (empty($data['titulo']) || empty($data['descripcion']) || 
            empty($data['responsable']) || empty($data['puntos']) || 
            empty($data['sprint_id']) || empty($data['fecha_creacion'])) {
            throw new Exception("Faltan campos obligatorios", 1);
        }
        $historia = new Historia();
        $historia->titulo = $data['titulo'];
        $historia->descripcion = $data['descripcion'];
        $historia->responsable = $data['responsable'];
        $historia->estado = $data['estado'] ?? 'nueva';
        $historia->puntos = $data['puntos'];
        $historia->fecha_creacion = $data['fecha_creacion'];
        $historia->fecha_finalizacion = empty($data['fecha_finalizacion']) ? null : $data['fecha_finalizacion'];
        $historia->sprint_id = $data['sprint_id'];
        $historia->save();
        return $historia;
    }

    function getHistoria($id)
    {
        $historia = Historia::find($id);
        if (empty($historia)) {
            throw new Exception("Historia $id no existe", 2);
        }
        return $historia;
    }

    function modificarHistoria($id, $data)
    {
        $historia = $this->getHistoria($id);
        if (isset($data['titulo'])) $historia->titulo = $data['titulo'];
        if (isset($data['descripcion'])) $historia->descripcion = $data['descripcion'];
        if (isset($data['responsable'])) $historia->responsable = $data['responsable'];
        if (isset($data['estado'])) $historia->estado = $data['estado'];
        if (isset($data['puntos'])) $historia->puntos = $data['puntos'];
        if (isset($data['fecha_finalizacion'])) $historia->fecha_finalizacion = $data['fecha_finalizacion'];
        if (isset($data['sprint_id'])) $historia->sprint_id = $data['sprint_id'];
        $historia->save();
        return $historia;
    }

    function borrarHistoria($id)
    {
        $historia = $this->getHistoria($id);
        $historia->delete();
        return true;
    }

    function getHistoriasPorSprint($sprintId)
    {
        return Historia::where('sprint_id', $sprintId)->get();
    }

    function getResponsablesUnicos()
    {
        return Historia::select('responsable')->distinct()->get();
    }

    function getHistoriasPorResponsable($responsable)
    {
        return Historia::where('responsable', $responsable)->get();
    }
}