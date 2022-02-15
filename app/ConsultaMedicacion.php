<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaMedicacion extends Model
{

  // Nombre de la tabla
  protected $table = 'consulta_medicacion';

  // Campos habilitados para ingresar
  protected $fillable = ['id_consulta_medica', 'id_consulta_enfermeria', 'id_medicamento', 'id_cliente', 'suministrados'];

}
