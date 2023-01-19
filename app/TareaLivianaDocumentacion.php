<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaLivianaDocumentacion extends Model
{

   // Nombre de la tabla
  protected $table = 'tarea_liviana_documentacion';

  // Campos habilitados para ingresar
  protected $fillable = [
    'id_tarea_liviana', 'user', 'institucion', 
  'medico', 'matricula_provincial', 'matricula_nacional', 'fecha_documento', 
  'diagnostico', 'observaciones', 'archivo'
];

}
