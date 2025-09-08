<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;

use App\AgendaEstado;
use App\Nomina;
use App\Cliente;

class Agenda extends Model
{
	// Nombre de la tabla
	protected $table = 'agenda';

	protected $appends  = ['horario','duracion','fecha_inicio_date'];

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

	public function getFechaInicioDateAttribute()
	{
		return $this->fecha_inicio->format('d/m/Y');
	}
	public function getFechaInicioFormattedAttribute()
	{
		return $this->fecha_inicio->format('d/m/Y H:i \h\s.');
	}
	public function getFechaFinalFormattedAttribute()
	{
		return $this->fecha_final->format('d/m/Y H:i \h\s.');
	}
	public function getTiempoFaltanteAttribute()
	{
		$ahora = CarbonImmutable::now();
		$fechaInicio = CarbonImmutable::parse($this->fecha_inicio);
		
		if ($ahora >= $fechaInicio) return '[Ya pasó]';
		
		if ($ahora->isSameDay($fechaInicio)) {
        $horas = $ahora->diffInHours($fechaInicio);
        $minutos = $ahora->diffInMinutes($fechaInicio) % 60;
        
        if ($horas == 0) {
            $verbo = $minutos == 1 ? 'Falta' : 'Faltan';
            return $verbo . ' ' . $minutos . ' minutos';
        }
        
        $verbo = $horas == 1 ? 'Falta' : 'Faltan';
        $texto = $verbo . ' ' . $horas . ' horas';
        if ($minutos > 0) {
            $texto .= ' y ' . $minutos . ' minutos';
        }
        return $texto;
    }
    
    $dias = $ahora->diffInDays($fechaInicio);
    $verbo = $dias == 1 || ($dias >= 7 && $dias<15) ? 'Falta' : 'Faltan';
    $diferencia = $ahora->diffForHumans($fechaInicio, true);
    
    return $verbo . ' ' . $diferencia;

	}
	public function getHorarioAttribute()
	{
		$fechaInicio = CarbonImmutable::parse($this->fecha_inicio);
		return $fechaInicio->format('H:i');
	}
	public function getDuracionAttribute()
	{
		$fechaInicio = CarbonImmutable::parse($this->fecha_inicio);
		$fechaFinal = CarbonImmutable::parse($this->fecha_final);
		return $fechaInicio->diffInMinutes($fechaFinal) % 60;
	}


}
