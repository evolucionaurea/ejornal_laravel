<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComunicacionLiviana extends Model
{
    // Nombre de la tabla
  protected $table = 'comunicaciones_livianas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'id_tipo', 'user', 'descripcion'];
}
