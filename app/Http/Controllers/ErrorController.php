<?php

namespace App\Http\Controllers;

use App\Error;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ErrorController extends Controller
{
    

    public function index(Request $request)
    {
        // Obtener los valores de los filtros
        $query = $request->input('query');
        $fechaDesde = trim($request->input('fecha_desde'));
        $fechaHasta = trim($request->input('fecha_hasta'));

        // Preparar la consulta
        $errores = Error::query();

        // Filtrar por búsqueda de texto
        if ($query) {
            $errores->where(function ($q) use ($query) {
                $q->where('type', 'like', "%$query%")
                  ->orWhere('message', 'like', "%$query%")
                  ->orWhere('file', 'like', "%$query%")
                  ->orWhere('line', 'like', "%$query%")
                  ->orWhere('id_user', 'like', "%$query%");
            });
        }

        // Convertir fechas de formato dd/mm/yyyy a yyyy-mm-dd
        if ($fechaDesde) {
            $fechaDesde = Carbon::createFromFormat('d/m/Y', $fechaDesde)->format('Y-m-d');
            $errores->where('created_at', '>=', Carbon::parse($fechaDesde)->startOfDay());
        }

        // Convertir fechas de formato dd/mm/yyyy a yyyy-mm-dd
        if ($fechaHasta) {
            $fechaHasta = Carbon::createFromFormat('d/m/Y', $fechaHasta)->format('Y-m-d');
            $errores->where('created_at', '<=', Carbon::parse($fechaHasta)->endOfDay());
        }

        // Ordenar por fecha de creación de forma descendente
        $errores = $errores->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.errores', compact('errores'));
    }

    public function limpiar(Request $request)
    {
        // Obtener los IDs de los últimos 50 registros
        $ids = Error::orderBy('created_at', 'desc')->limit(50)->pluck('id');
    
        // Borrar todos los registros que NO estén en esos IDs
        Error::whereNotIn('id', $ids)->delete();
    
        return redirect()->route('/admin/errores')->with('success', 'Errores limpiados correctamente.');
    }
    
    

}
