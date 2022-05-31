<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\AusentismoTipo;
use App\Nominas;

class Ausentismo extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'ausentismos';

  // Campos habilitados para ingresar
  protected $fillable = ['id_trabajador', 'user', 'id_tipo', 'fecha_inicio', 'fecha_final', 'fecha_regreso_trabajar', 'archivo', 'hash_archivo'];

  public function tipo(){
  	return $this->belongsTo(AusentismoTipo::class,'id_tipo');
  }
  public function user(){
  	return $this->belongsTo(Nominas::class,'id_trabajador');
  }

}
