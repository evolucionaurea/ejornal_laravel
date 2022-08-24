<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cliente;
use App\User;

class ClienteUser extends Model
{

  // Nombre de la tabla
  protected $table = 'cliente_user';

  // Campos habilitados para ingresar
  protected $fillable = ['id_cliente', 'id_user', 'id_grupo'];

  public function cliente()
  {
  	return $this->belongsTo(Cliente::class, 'id_cliente');
  }
  public function user()
  {
  	return $this->belongsTo(User::class, 'id_user');
  }

}
