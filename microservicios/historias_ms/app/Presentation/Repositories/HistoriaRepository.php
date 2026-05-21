<?php

namespace App\Presentation\Repositories;

use App\Models\Historia;

class HistoriaRepository
{
    public function all()
    {
        return Historia::all();
    }

    public function find($id)
    {
        return Historia::find($id);
    }

    public function create(array $data)
    {
        return Historia::create($data);
    }

    public function update($id, array $data)
    {
        $historia = Historia::find($id);
        if ($historia) {
            $historia->update($data);
            return $historia;
        }
        return null;
    }

    public function delete($id)
    {
        $historia = Historia::find($id);
        if ($historia) {
            $historia->delete();
            return true;
        }
        return false;
    }

    public function porSprint($sprintId)
    {
        return Historia::where('sprint_id', $sprintId)->get();
    }

    public function responsablesUnicos()
    {
        return Historia::select('responsable')->distinct()->get();
    }

    public function porResponsable($responsable)
    {
        return Historia::where('responsable', $responsable)->get();
    }
}