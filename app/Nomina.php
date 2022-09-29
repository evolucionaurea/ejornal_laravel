<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ausentismo;
use App\Cliente;

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

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }

}
