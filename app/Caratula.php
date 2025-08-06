<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caratula extends Model
{
	
	// Nombre de la tabla
	protected $table = 'caratulas';

	protected $appends = ['created_at_formatted'];

	// Campos habilitados para ingresar
	protected $fillable = [
		'id_nomina',
		'medicacion_habitual', 
		'antecedentes', 
		'alergias', 
		'peso',
		'altura',
		'imc',
		'user'
	];

	public function patologias()
	{
		return $this->belongsToMany(Patologia::class, 'caratula_patologia', 'id_caratula', 'id_patologia')->withTimestamps();
	}

	public function nomina()
	{
		return $this->belongsTo(Nomina::class, 'id_nomina');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'id_cliente'); 
	}
	public function getCreatedAtFormattedAttribute()
	{
		return $this->created_at->format('d/m/Y H:i');
	}


}
