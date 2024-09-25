<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComunicacionArchivo extends Model
{
    
      // Nombre de la tabla
  protected $table = 'comunicaciones_archivos';

  // Campos habilitados para ingresar
  protected $fillable = ['id_comunicacion', 'archivo', 'hash_archivo'];

}
