<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;

class FichadaNueva extends Model
{

  //use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'fichadas_nuevas';

  // Campos habilitados para ingresar
  protected $fillable = ['ingreso', 'egreso', 'tiempo_dedicado', 'id_user', 'id_cliente', 'ip', 'dispositivo'];


  protected $casts = [
    'ingreso'=>'date:d/m/Y - H:i \h\s.',
    'egreso'=>'date:d/m/Y - H:i \h\s.'
  ];

}
