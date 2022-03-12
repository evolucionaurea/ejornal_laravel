<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nomina extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // Nombre de la tabla
  protected $table = 'nominas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_cliente', 'nombre', 'email', 'telefono', 'dni', 'estado', 'foto', 'hash_foto'];

}
