<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AgendaEstado;
use App\Nomina;
use App\Cliente;

class Agenda extends Model
{
	// Nombre de la tabla
	protected $table = 'agenda';

	// Campos habilitados para ingresar
	protected $fillable = ['user_id','cliente_id','nomina_id','fecha_inicio','fecha_final','estado_id'];

	protected $casts = [
		'fecha_inicio'=>'datetime',
		'fecha_final'=>'datetime'
	];


	public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}
	public function user_registra()
	{
		return $this->belongsTo(User::class,'registra_user_id');
	}
	public function estado()
	{
		return $this->belongsTo(AgendaEstado::class,'estado_id');
	}
	public function trabajador()
	{
		return $this->belongsTo(Nomina::class,'nomina_id');
	}
	public function cliente()
	{
		return $this->belongsTo(Cliente::class,'cliente_id');
	}

	public function getFechaInicioFormattedAttribute()
	{
		return $this->fecha_inicio->format('d/m/Y H:i \h\s.');
	}
	public function getFechaFinalFormattedAttribute()
	{
		return $this->fecha_final->format('d/m/Y H:i \h\s.');
	}


}
