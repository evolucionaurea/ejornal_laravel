<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preocupacional extends Model
{

  // Nombre de la tabla
  protected $table = 'preocupacionales';

  // Campos habilitados para ingresar
  protected $fillable = ['id_nomina', 'fecha', 'observaciones', 'archivo', 'hash_archivo'];

}
