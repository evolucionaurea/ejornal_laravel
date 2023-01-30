<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Contracts\Auditable;

class ConsultaEnfermeria extends Model
// class ConsultaEnfermeria extends Model implements Auditable
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'consultas_enfermerias';

  // Campos habilitados para ingresar
  protected $fillable = [
    'id_nomina', 'user', 'fecha', 'medicacion', 'amerita_salida', 'peso', 'altura', 'imc', 'glucemia', 'saturacion_oxigeno',
    'id_diagnostico_consulta', 'tension_arterial', 'frec_cardiaca', 'derivacion_consulta', 'observaciones'
  ];


  protected $casts = [
    'fecha'=>'date:d/m/Y'
  ];

}
