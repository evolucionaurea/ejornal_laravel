<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Contracts\Auditable;

class AusentismoDocumentacion extends Model
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'ausentismo_documentacion';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'user', 'institucion', 'medico', 'matricula_provincial', 'matricula_nacional', 'fecha_documento', 'diagnostico', 'observaciones', 'archivo'];



  protected $casts = [
  	'fecha_documento'=>'date:d/m/Y'
  ];


  public function ausentismo()
  {
  	return $this->belongsTo(Ausentismo::class,'id_ausentismo');
  }

}
