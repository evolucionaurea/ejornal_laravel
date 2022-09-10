<?php

namespace App\Http\Traits;
use App\ClienteGrupo;
use App\Grupo;
use App\Cliente;

trait ClientesGrupo {

	public function getClientesGrupo(){

		$grupo = Grupo::where('id',auth()->user()->id_grupo)->first();
    $clientes_grupo = ClienteGrupo::where('id_grupo',auth()->user()->id_grupo)->with('cliente')->get();
    $cliente_actual = Cliente::where('id',auth()->user()->id_cliente_actual)->first();
		$clientes_vinculados = ClienteGrupo::where('id_grupo',auth()->user()->id_grupo)
		->join('clientes', 'cliente_grupo.id_cliente', 'clientes.id')
		->select('clientes.nombre')
		->get();

		return compact('grupo','clientes_grupo','cliente_actual', 'clientes_vinculados');
	}


}
