<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoComunicacion extends Model
{

  // Nombre de la tabla
  protected $table = 'tipo_comunicacion';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
