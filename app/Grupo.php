<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
  // Nombre de la tabla
  protected $table = 'grupos';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre', 'direccion'];
}
