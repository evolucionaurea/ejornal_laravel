<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{

  // Nombre de la tabla
  protected $table = 'medicamentos';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];

}
