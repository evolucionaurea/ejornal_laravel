<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;

class Cliente extends Model
{

  // Nombre de la tabla
  protected $table = 'clientes';

  // Campos habilitados para ingresar
  protected $fillable = ['logo', 'direccion', 'nombre', 'token', 'id_grupo'];


  public function nominas()
  {
  	return $this->hasMany(Nomina::class,'id_cliente'); //->count();
  	//return Nomina::where('id_cliente','=',$this->id)->count();
  }


}
