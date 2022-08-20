<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteUser extends Model
{

  // Nombre de la tabla
  protected $table = 'cliente_user';

  // Campos habilitados para ingresar
  protected $fillable = ['id_cliente', 'id_user', 'id_grupo'];

}
