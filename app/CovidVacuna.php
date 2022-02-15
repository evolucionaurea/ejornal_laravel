<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidVacuna extends Model
{

  // Nombre de la tabla
  protected $table = 'covid_vacunas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_nomina', 'id_tipo', 'fecha', 'institucion'];

}
