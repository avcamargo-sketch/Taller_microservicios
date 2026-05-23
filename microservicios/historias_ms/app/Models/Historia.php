<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';
    protected $fillable = [
        'titulo', 'descripcion', 'responsable', 'estado',
        'puntos', 'fecha_creacion', 'fecha_finalizacion', 'sprint_id'
    ];
    public $timestamps = true;
}