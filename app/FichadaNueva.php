<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
//use OwenIt\Auditing\Contracts\Auditable;
use App\User;
use App\Cliente;

class FichadaNueva extends Model
{

	//use \OwenIt\Auditing\Auditable;

	// Nombre de la tabla
	protected $table = 'fichadas_nuevas';

	// Campos habilitados para ingresar
	protected $fillable = ['ingreso', 'egreso', 'tiempo_dedicado', 'id_user', 'id_cliente', 'ip', 'dispositivo'];

	protected $appends = ['ingreso_carbon', 'ingreso_formatted','egreso_carbon','egreso_formatted','horas_minutos_trabajado'];


	protected $casts = [
		'ingreso' => 'datetime:d/m/Y - H:i:s',
		'egreso' => 'datetime:d/m/Y - H:i:s'
	];


    public function setDispositivoAttribute($value)
    {
        $v = strtolower(trim((string)$value));
        // Mapear variantes comunes
        if (in_array($v, ['desktop','escritorio','pc','computer'], true)) $v = 'desktop';
        elseif (in_array($v, ['mobile','móvil','movil','phone','teléfono','telefono'], true)) $v = 'phone';
        elseif (in_array($v, ['tablet','ipad','galaxy tab'], true)) $v = 'tablet';
        elseif (in_array($v, ['bot','crawler','spider','robot'], true)) $v = 'robot';
        elseif ($v === 'other' || $v === 'otro') $v = 'other';
        else {
            // Cualquier cosa no reconocida o null -> escritorio (evita “desconocido” como solicitó Javier)
            $v = 'desktop';
        }
        $this->attributes['dispositivo'] = $v;
    }


    public function getDispositivoAttribute()
    {
        $raw = $this->attributes['dispositivo'] ?? 'desktop';
        switch ($raw) {
            case 'desktop': return 'Escritorio';
            case 'phone':   return 'Móvil';
            case 'tablet':  return 'Tablet';
            case 'robot':   return 'Robot';
            case 'other':   return 'Otro';
            default:        return 'Escritorio'; // nunca “Desconocido” (solicitado por Javier)
        }
    }


	public function user(){
		return $this->belongsTo(User::class, 'id_user');
	}
	public function cliente(){
		return $this->belongsTo(Cliente::class, 'id_cliente');
	}

	public function getIngresoCarbonAttribute()
	{
		return CarbonImmutable::parse($this->ingreso); //CHEQUEAR EN SERVIDOR
	}
	public function getIngresoFormattedAttribute()
	{
		$date = Carbon::parse($this->ingreso);
		return mb_convert_case($date->translatedFormat('l'),MB_CASE_TITLE,'UTF-8') . ', '. $date->format('d/m/Y') . ' - ' . $date->format('H:i:s') . ' hs.';
	}

	public function getEgresoCarbonAttribute()
	{
		return CarbonImmutable::parse($this->egreso);//->timezone('America/Argentina/Buenos_Aires');
	}
	public function getEgresoFormattedAttribute()
	{
		if(is_null($this->egreso)) return 'aún trabajando';
		$date = Carbon::parse($this->egreso);
		return mb_convert_case($date->translatedFormat('l'),MB_CASE_TITLE,'UTF-8') . ', '. $date->format('d/m/Y') . ' '. $date->format('H:i:s') . ' hs.';
	}

	public function getHorasMinutosTrabajadoAttribute(){
		if(is_null($this->egreso)) return 'aún trabajando';

		$diff = $this->ingreso_carbon->diff($this->egreso_carbon);

		$hours = $diff->format('%H');
		$minutes = $diff->format('%I');

		if( $diff->format('%d') > 1 ){
			$hours += $diff->format('%d')*24;
		}
		return $hours.'h '.$minutes.'m';
	}



}
