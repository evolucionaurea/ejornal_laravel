<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;
use App\NominaHistorial;
use App\Ausentismo;
use App\ClienteGrupo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\CarbonImmutable;

class Cliente extends Model
{

  use SoftDeletes;

  // Nombre de la tabla
  protected $table = 'clientes';

  // Campos habilitados para ingresar
  protected $fillable = ['logo', 'direccion', 'nombre', 'token', 'id_grupo'];

  protected $appends = ['cantidad_nomina'];


  public function nominas()
  {
  	return $this->hasMany(Nomina::class,'id_cliente')->where('estado',1);
  }
  public function nominas_historial()
  {
    return $this->hasMany(NominaHistorial::class,'cliente_id');
  }

  public function ausentismos()
  {
  	return $this->hasManyThrough(Ausentismo::class, Nomina::class, 'id_cliente', 'id_trabajador', 'id', 'id');
  }

  public function cliente_grupo()
  {
    return $this->hasMany(ClienteGrupo::class,'id_cliente');
  }

  public function getCantidadNominaAttribute()
  {
    $today = CarbonImmutable::now();

    $q_nomina = NominaHistorial::select('*')
      ->where('year_month',$today->format('Ym'))
      ->where('cliente_id',$this->id);

    $h_nomina = $q_nomina->first();

    if(!$h_nomina){
      \Artisan::call('db:seed', [
        '--class' => 'NominaHistorialSeeder',
        '--force'=>true
      ]);

      $h_nomina = $q_nomina->first();
    }

    return $h_nomina->cantidad;
  }


}
