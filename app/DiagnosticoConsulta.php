<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagnosticoConsulta extends Model
{

  // Nombre de la tabla
  protected $table = 'diagnostico_consulta';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
