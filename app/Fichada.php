<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Fichada extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'fichadas';

  // Campos habilitados para ingresar
  protected $fillable = ['horario_ingreso', 'horario_egreso', 'fecha_actual', 'id_user', 'id_cliente'];

}
