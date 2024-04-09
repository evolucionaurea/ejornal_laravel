<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AusentismoDocumentacionArchivos extends Model
{


  protected $table = 'ausentismo_documentacion_archivos';

  protected $fillable = ['ausentismo_documentacion_id', 'archivo', 'hash_archivo'];


  public function ausentismo_documentacion()
  {
  	return $this->belongsTo(AusentismoDocumentacion::class);
  }

}
