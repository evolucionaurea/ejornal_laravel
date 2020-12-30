<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

    // Nombre de la tabla
    protected $table = 'roles';

    // Campos habilitados para ingresar
    protected $fillable = ['nombre'];

}
