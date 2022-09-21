<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cliente;
use App\ClienteGrupo;

class Grupo extends Model
{
  // Nombre de la tabla
  protected $table = 'grupos';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre', 'direccion'];

  public function clientes()
  {
  	return $this->hasManyThrough(Cliente::class, ClienteGrupo::class, 'id_grupo', 'id', 'id', 'id_cliente');
  }
}
