<?php

namespace App\Http\Traits;
use App\ClienteUser;

trait Clientes {

	public function getClientesUser(){

		return ClienteUser::join('clientes', 'cliente_user.id_cliente', 'clientes.id')
		->where('cliente_user.id_user', '=', auth()->user()->id)
		->select('clientes.nombre', 'clientes.id')
		->get();

	}

}