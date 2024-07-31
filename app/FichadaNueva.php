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
    'ingreso' => 'datetime:d/m/Y - H:i:s',
    'egreso' => 'datetime:d/m/Y - H:i:s'
  ];

}
