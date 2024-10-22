<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;
use App\AusentismoTipo;
use App\Nomina;
use App\Cliente;
use App\AusentismoDocumentacion;
use App\Comunicacion;

class Ausentismo extends Model
{

	//use \OwenIt\Auditing\Auditable;

	// Nombre de la tabla
	protected $table = 'ausentismos';

	// Campos habilitados para ingresar
	protected $fillable = [
		'id_trabajador', 'user', 'id_tipo', 'fecha_inicio', 'fecha_final', 'fecha_regreso_trabajar',
		'archivo', 'hash_archivo', 'comentario'
	];

	protected $casts = [
		'fecha_inicio'=>'date:d/m/Y',
		'fecha_final'=>'date:d/m/Y',
		'fecha_regreso_trabajar'=>'date:d/m/Y'
	];

	protected $appends = ['created_at_formatted'];

	public function tipo(){
		return $this->belongsTo(AusentismoTipo::class,'id_tipo');
	}
	public function trabajador(){
		return $this->belongsTo(Nomina::class,'id_trabajador')->withTrashed();
	}

	/*public function cliente(){
		return $this->hasOneThrough(Cliente::class,Nomina::class,'id','id','id_trabajador','id_cliente');
	}*/
	public function cliente(){
		return $this->belongsTo(Cliente::class,'id_cliente');
	}

	public function documentaciones(){
		return $this->hasMany(AusentismoDocumentacion::class,'id_ausentismo');
	}

	public function comunicaciones(){
		return $this->hasMany(Comunicacion::class,'id_ausentismo');
	}
	// public function comunicaciones(){
	// 	return $this->hasMany(Comunicacion::class,'id_ausentismo');
	// }

	public function getCreatedAtFormattedAttribute()
	{
		return $this->created_at->format('d/m/Y H:i:s \h\s.');
	}

}
