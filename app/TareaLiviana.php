<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaLiviana extends Model
{
   // Nombre de la tabla
  protected $table = 'tareas_livianas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_trabajador', 'user', 'id_tipo', 'fecha_inicio', 'fecha_final', 'fecha_regreso_trabajar', 'archivo', 'hash_archivo'];


  protected $casts = [
  	'fecha_inicio'=>'date:d/m/Y',
    'fecha_final'=>'date:d/m/Y',
  ];

}
