<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Contracts\Auditable;

class Comunicacion extends Model
// class Comunicacion extends Model implements Auditable
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'comunicaciones';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'id_tipo', 'user', 'descripcion'];

}
