<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ausentismo;
use App\Cliente;
use Carbon\Carbon;

class Nomina extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // Nombre de la tabla
  protected $table = 'nominas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_cliente', 'nombre', 'email', 'telefono', 'dni', 'estado', 'foto', 'hash_foto'];


  public function ausentismos()
  {
  	return $this->hasMany(Ausentismo::class,'id_trabajador');
  }
  public function scopeWithAusentismoEstado($query)
  {
    $today = Carbon::now();
    $query->addSelect(['fecha_regreso_trabajar'=>Ausentismo::select('fecha_regreso_trabajar')
      ->whereColumn('id_trabajador','nominas.id')
      ->where('fecha_regreso_trabajar',null)
      ->orWhere('fecha_regreso_trabajar','>',$today)
    ]);
  }

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }


}
