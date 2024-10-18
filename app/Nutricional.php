<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nutricional extends Model
{
    
    // Nombre de la tabla
    protected $table = 'nutricionales';

    // Campos habilitados para ingresar
    protected $fillable = ['nombre'];

}
