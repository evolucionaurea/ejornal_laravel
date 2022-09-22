<?php

namespace App\Http\Traits;
use App\ClienteGrupo;
use App\Grupo;
use App\Cliente;

trait ClientesGrupo {

	public function getClientesGrupo(){

		//simplifico la query y traigo los clientes dentro del grupo
		$grupo = Grupo::where('id',auth()->user()->id_grupo)
		->with('clientes.nominas')
		//->withCount('clientes.nomina_total')
		//->with('clientes.nomina_total')
		->first();


    $cliente_actual = Cliente::where('id',auth()->user()->id_cliente_actual)->first();
		///dd($grupo);
		///$clientes_grupo = ClienteGrupo::where('id_grupo',auth()->user()->id_grupo)->with('cliente')->get();
		//$clientes_vinculados = ClienteGrupo::where('id_grupo',auth()->user()->id_grupo)
		//->join('clientes', 'cliente_grupo.id_cliente', 'clientes.id')
		//->select('clientes.nombre')
		//->get();

		return compact('grupo','cliente_actual');
	}


}
