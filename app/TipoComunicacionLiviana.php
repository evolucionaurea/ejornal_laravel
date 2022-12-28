<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComunicacionLiviana extends Model
{
    // Nombre de la tabla
  protected $table = 'tipos_comunicaciones_livianas';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];
}
