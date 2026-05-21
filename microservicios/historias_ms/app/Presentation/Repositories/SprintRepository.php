<?php

namespace App\Presentation\Repositories;

use App\Models\Sprint;

class SprintRepository
{
    public function all()
    {
        return Sprint::all();
    }

    public function find($id)
    {
        return Sprint::find($id);
    }

    public function create(array $data)
    {
        return Sprint::create($data);
    }

    public function update($id, array $data)
    {
        $sprint = Sprint::find($id);
        if ($sprint) {
            $sprint->update($data);
            return $sprint;
        }
        return null;
    }

    public function delete($id)
    {
        $sprint = Sprint::find($id);
        if ($sprint) {
            $sprint->delete();
            return true;
        }
        return false;
    }
}