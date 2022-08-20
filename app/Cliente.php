<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

  // Nombre de la tabla
  protected $table = 'clientes';

  // Campos habilitados para ingresar
  protected $fillable = ['logo', 'direccion', 'nombre', 'token', 'id_grupo'];


}
