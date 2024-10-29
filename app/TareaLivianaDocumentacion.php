<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TareaLiviana;

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


  public function tareaLiviana(){
  	return $this->belongsTo(TareaLiviana::class,'id_tarea_liviana');
  }

  public function getCreatedAtFormattedAttribute()
	{
		return $this->created_at->format('d/m/Y H:i:s \h\s.');
	}

}
