<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;
use App\NominaHistorial;
use App\Ausentismo;
use App\ClienteGrupo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{

  use SoftDeletes;

  // Nombre de la tabla
  protected $table = 'clientes';

  // Campos habilitados para ingresar
  protected $fillable = ['logo', 'direccion', 'nombre', 'token', 'id_grupo'];


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


}
