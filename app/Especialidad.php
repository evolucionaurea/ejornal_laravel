<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{

  // Nombre de la tabla
  protected $table = 'especialidades';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
