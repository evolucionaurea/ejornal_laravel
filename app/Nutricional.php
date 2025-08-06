<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nutricional extends Model
{
    
    // Nombre de la tabla
    protected $table = 'nutricionales';

    // Campos habilitados para ingresar
    protected $fillable = [
        'id_patologia',
        'objetivos',
        'medicacion',
        'descanso',
        'act_fisica',
        'peso',
        'talla',
        'circunferencia_cintura',
        'porcent_masa_grasa',
        'porcent_masa_muscular',
        'gustos_alimentarios',
        'tolerancia_digestiva',
        'comidas_diarias',
        'evolucion',
        'medicaciones'
    ];

}
