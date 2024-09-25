<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TipoComunicacion;
use App\Ausentismo;
// use OwenIt\Auditing\Contracts\Auditable;

class Comunicacion extends Model
// class Comunicacion extends Model implements Auditable
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'comunicaciones';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'id_tipo', 'user', 'descripcion', 'archivo', 'hash_archivo'];

  protected $casts = [
  	'created_at'=>'date:d/m/Y'
  ];

  public function tipo(){
  	return $this->belongsTo(TipoComunicacion::class,'id_tipo');
  }

  public function ausentismo(){
    return $this->belongsTo(Ausentismo::class,'id_ausentismo');
  }

  public function archivos()
  {
      return $this->hasMany(ComunicacionArchivo::class, 'id_comunicacion');
  }



}
