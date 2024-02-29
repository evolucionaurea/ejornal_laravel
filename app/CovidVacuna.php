<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CovidVacunaTipo;

class CovidVacuna extends Model
{

  // Nombre de la tabla
  protected $table = 'covid_vacunas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_nomina', 'id_tipo', 'fecha', 'institucion'];


  public function tipo(){
    return $this->belongsTo(CovidVacunaTipo::class,'id_tipo');
  }

}
