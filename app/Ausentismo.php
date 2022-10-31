<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\AusentismoTipo;
use App\Nomina;

class Ausentismo extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'ausentismos';

  // Campos habilitados para ingresar
  protected $fillable = ['id_trabajador', 'user', 'id_tipo', 'fecha_inicio', 'fecha_final', 'fecha_regreso_trabajar', 'archivo', 'hash_archivo'];

  protected $casts = [
    'fecha_inicio'=>'date:d/m/Y',
    'fecha_final'=>'date:d/m/Y',
    'fecha_regreso_trabajar'=>'date:d/m/Y',
  ];

  public function tipo(){
  	return $this->belongsTo(AusentismoTipo::class,'id_tipo');
  }
  public function trabajador(){
  	return $this->belongsTo(Nomina::class,'id_trabajador');
  }

}
