<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TipoComunicacionLiviana;
use App\TareaLiviana;

class ComunicacionLiviana extends Model
{
    // Nombre de la tabla
  protected $table = 'comunicaciones_livianas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'id_tipo', 'user', 'descripcion'];

  protected $appends = ['created_at_formatted'];


  public function tipo(){
  	return $this->belongsTo(TipoComunicacionLiviana::class,'id_tipo');
  }

  public function tareaLiviana(){
  	return $this->belongsTo(TareaLiviana::class,'id_tarea_liviana');
  }

  public function getCreatedAtFormattedAttribute()
  {
    return $this->created_at->format('d/m/Y H:i:s \h\s.');
  }

}
