<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\ClienteUser;
use App\Cliente;
use App\Grupo;
use App\Rol;

class User extends Authenticatable
{
	use Notifiable;

	// Nombre de la tabla
	protected $table = 'users';

	// Campos habilitados para ingresar
	protected $fillable = [
	   'id_rol', 'nombre', 'apellido', 'estado', 'personal_interno', 'email', 'permiso_desplegables', 'onedrive', 'cuil'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function clientes_user()
	{
		return $this->hasManyThrough(Cliente::class, ClienteUser::class, 'id_user', 'id', 'id', 'id_cliente');
	}


	public function grupo()
	{
		return $this->belongsTo(Grupo::class,'id_grupo');
	}
	public function cliente_relacionar()
	{
		return $this->belongsTo(Cliente::class,'id_cliente_relacionar');
	}
	public function rol(){
		return $this->belongsTo(Rol::class,'id_rol');
	}


}
