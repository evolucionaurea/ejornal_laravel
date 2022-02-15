<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockMedicamento;
use App\StockMedicamentoHistorial;
use Illuminate\Support\Facades\DB;

class AdminMovimientoMedicamentosController extends Controller
{

  public function index()
  {

    $stock_medicamentos = StockMedicamentoHistorial::join('stock_medicamentos', 'stock_medicamentos_historial.id_stock_medicamentos', 'stock_medicamentos.id')
    ->join('medicamentos', 'stock_medicamentos.id_medicamento', 'medicamentos.id')
    ->join('users', 'stock_medicamentos.id_user', 'users.id')
    ->join('clientes', 'stock_medicamentos.id_cliente', 'clientes.id')
    ->select('medicamentos.nombre', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'), 'stock_medicamentos_historial.ingreso', 'stock_medicamentos_historial.suministrados', 'stock_medicamentos_historial.egreso', 'stock_medicamentos_historial.stock', 'stock_medicamentos_historial.fecha_ingreso', 'stock_medicamentos_historial.motivo', 'stock_medicamentos_historial.created_at')
    ->orderBy('stock_medicamentos_historial.created_at', 'DESC')
    ->get();
    
    return view('admin.movimientos_medicamentos', compact('stock_medicamentos'));

  }


}
