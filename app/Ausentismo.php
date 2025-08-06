<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;
use Carbon\CarbonImmutable;
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

	protected $appends = ['created_at_formatted','trabajador_perfil_url','comentario_shortened'];

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
		if(is_null($this->created_at)) return $this->created_at;
		return $this->created_at->format('d/m/Y H:i:s \h\s.');
	}

	public function getTrabajadorPerfilUrlAttribute()
	{
		return url('/empleados/nominas/'.$this->id_trabajador);
	}

	public function getTotalDaysAttribute()
	{
		$fin = $this->fecha_final ?? CarbonImmutable::now();
		$diff = date_diff($this->fecha_inicio,$fin);

		return $diff->days;
	}
	public function getComentarioShortenedAttribute(){
		return \Str::words($this->comentario,25);
	}

}
