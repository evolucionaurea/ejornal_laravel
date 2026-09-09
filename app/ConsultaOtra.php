<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaOtra extends Model
{
  protected $table = 'consultas_otras';

	protected $casts = [
		'fecha' => 'date:d/m/Y',
	];

	protected $dates = ['fecha'];

	protected $fillable = [
		'id_nomina',
		'tipo_profesional_id',
		'fecha',
		'motivo',
		'desarrollo',
		'user'
	];

	public function nomina()
	{
		return $this->belongsTo(Nomina::class, 'nomina_id');
	}

	public function trabajador()
	{
		return $this->belongsTo(Nomina::class, 'nomina_id');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'cliente_id');
	}
	public function user(){
		return $this->belongsTo(User::class, 'user_id');
	}

	public function profesional_tipo()
	{
		return $this->belongsTo(ProfesionalTipo::class, 'tipo_profesional_id');
	}

	public function getCreatedAtFormattedAttribute()
	{
		if(is_null($this->created_at)) return '';
		return $this->created_at->format('d/m/Y H:i:s \h\s.');
	}
}
