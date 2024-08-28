<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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


  public function user(){
  	return $this->belongsTo(User::class, 'id_user');
  }
  public function cliente(){
  	return $this->belongsTo(Cliente::class, 'id_cliente');
  }

  public function getIngresoCarbonAttribute()
  {
  	return Carbon::parse($this->ingreso);
  }
  public function getIngresoFormattedAttribute()
  {
    $date = Carbon::parse($this->ingreso);
    return mb_convert_case($date->translatedFormat('l'),MB_CASE_TITLE,'UTF-8') . ', '. $date->format('d/m/Y') . ' - ' . $date->format('H:i:s') . ' hs.';
  }

  public function getEgresoCarbonAttribute()
  {
  	return Carbon::parse($this->egreso);
  }
  public function getEgresoFormattedAttribute()
  {
    if(is_null($this->egreso)) return 'aún trabajando';
    $date = Carbon::parse($this->egreso);
    return mb_convert_case($date->translatedFormat('l'),MB_CASE_TITLE,'UTF-8') . ', '. $date->format('d/m/Y') . ' '. $date->format('H:i:s') . ' hs.';
  }

  public function getHorasMinutosTrabajadoAttribute(){
  	if(is_null($this->egreso)) return 'aún trabajando';

  	return $this->ingreso_carbon->diff($this->egreso_carbon)->format('%H:%I');
  }

}
