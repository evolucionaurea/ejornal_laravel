<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaLivianaTipo extends Model
{
   // Nombre de la tabla
  protected $table = 'tareas_livianas_tipos';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre', 'agrupamiento'];
}
