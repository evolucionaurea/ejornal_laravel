<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidVacunaTipo extends Model
{

  // Nombre de la tabla
  protected $table = 'covid_vacunas_tipo';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
