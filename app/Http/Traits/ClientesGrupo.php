<?php

namespace App\Http\Traits;
use App\ClienteGrupo;
use App\Grupo;
use App\Cliente;

trait ClientesGrupo {

	public function getClientesGrupo(){

		//simplifico la query y traigo los clientes dentro del grupo
		$grupo = Grupo::where('id',auth()->user()->id_grupo)
		->with('clientes')
		->first();

    $cliente_actual = Cliente::where('id',auth()->user()->id_cliente_actual)->first();

		return compact('grupo','cliente_actual');
	}


}
