<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TipoComunicacion;
// use OwenIt\Auditing\Contracts\Auditable;

class Comunicacion extends Model
// class Comunicacion extends Model implements Auditable
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'comunicaciones';

  // Campos habilitados para ingresar
  protected $fillable = ['id_ausentismo', 'id_tipo', 'user', 'descripcion'];

  protected $casts = [
  	'created_at'=>'date:d/m/Y'
  ];

  public function tipo(){
  	return $this->belongsTo(TipoComunicacion::class,'id_tipo');
  }



}
