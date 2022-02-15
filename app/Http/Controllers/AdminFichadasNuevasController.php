<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FichadaNueva;
use Carbon\Carbon;

class AdminFichadasNuevasController extends Controller
{


  public function fichadas()
  {

    $fichadas =  FichadaNueva::join('users', 'fichadas_nuevas.id_user', 'users.id')
    ->join('clientes', 'fichadas_nuevas.id_cliente', 'clientes.id')
    ->select('fichadas_nuevas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
    ->get();

    return view('admin.fichadas', compact('fichadas'));
  }


}
