<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;
// use App\Http\Traits\Clientes;
use App\Http\Traits\Preocupacionales;
use App\Preocupacional;
use App\PreocupacionalArchivo;
use App\PreocupacionalTipoEstudio;

class ClientesPreocupacionalesController extends Controller
{
    
    use Preocupacionales;

	public function index()
	{
        $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)->first();
		$tipos = PreocupacionalTipoEstudio::all();
		return view('clientes.preocupacionales', compact(
			'tipos',
			'cliente'
		));

	}

    public function busqueda(Request $request)
	{

		$request->cliente_id = auth()->user()->id_cliente_relacionar;

		return $this->preocupacionalesAjax($request);
	}

	public function show($id)
	{

		$preocupacional = Preocupacional::with(['trabajador','tipo','archivos'])->where('id',$id)->first();
		$cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)->first();

		return view('clientes.preocupacionales.show',compact(
			'preocupacional',
			'cliente'
		));
	}

	public function descargar_archivo($id)
	{

		$archivo = PreocupacionalArchivo::find($id);
		$ruta = storage_path("app/preocupacionales/trabajador/{$archivo->preocupacional_id}/{$archivo->hash_archivo}");
		return download_file($ruta);

	}

	public function find($id){
		return Preocupacional::find($id);
	}

}
