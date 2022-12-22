<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nomina;
use App\Cliente;

class ClientesNominasController extends Controller
{

  public function index()
  {
    $cliente = Cliente::where('id', auth()->user()->id_cliente_relacionar)
    ->select('clientes.nombre')
    ->first();
    ///$nominas = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar)->get();
    return view('clientes.nominas', compact('cliente'));
  }

  public function busqueda(Request $request)
  {


    $query = Nomina::where('id_cliente', auth()->user()->id_cliente_relacionar);
    if(!is_null($request->estado)) $query->where('estado','=',(int) $request->estado);


    $query->where(function($query) use ($request) {
      $filtro = $request->search['value'].'%';
      $query->where('nombre','like',$filtro)
        ->orWhere('email','like',$filtro)
        ->orWhere('dni','like',$filtro)
        ->orWhere('telefono','like',$filtro);
    });


    if($request->order){
      $sort = $request->columns[$request->order[0]['column']]['data'];
      $dir  = $request->order[0]['dir'];
      $query->orderBy($sort,$dir);
    }


    return [
      'draw'=>$request->draw,
      'recordsTotal'=>$query->count(),
      'recordsFiltered'=>$query->count(),
      'data'=>$query->skip($request->start)->take($request->length)->get(),

      'request'=>$request->all()
    ];


  }

}
