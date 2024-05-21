<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AusentismoDocumentacionArchivos extends Model
{

	protected $appends  = ['file_path'];


  protected $table = 'ausentismo_documentacion_archivos';

  protected $fillable = ['ausentismo_documentacion_id', 'archivo', 'hash_archivo'];


  public function ausentismo_documentacion()
  {
  	return $this->belongsTo(AusentismoDocumentacion::class);
  }

  public function getFilePathAttribute()
  {
  	return url('empleados/documentacion_ausentismo/archivo/'.$this->id);
  }

}
