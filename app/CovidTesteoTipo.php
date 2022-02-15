<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidTesteoTipo extends Model
{

  // Nombre de la tabla
  protected $table = 'covid_testeos_tipo';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
