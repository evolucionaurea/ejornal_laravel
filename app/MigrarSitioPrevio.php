<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MigrarSitioPrevio extends Model
{

  // Nombre de la tabla
  protected $table = 'migrar_sitio_previo';

  // Campos habilitados para ingresar
  protected $fillable = ['clientes', 'user_empleados', 'nominas'];

}
