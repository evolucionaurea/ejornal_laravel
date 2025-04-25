<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    
    // Nombre de la tabla
    protected $table = 'configuraciones';

    // Campos habilitados para ingresar
    protected $fillable = ['online'];
    
}
