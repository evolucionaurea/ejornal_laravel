<?php

namespace App\Http\Controllers;

use App\Preocupacional;
use App\PreocupacionalTipoEstudio;
use Illuminate\Http\Request;

class EmpleadosPreocupacionalesTipoController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
			'name' => 'required'
		]);

        $tipo = new PreocupacionalTipoEstudio();
        $tipo->name = $request->name;
        $tipo->save();
        return back()->with('success', 'Creado con exito');
    }

    public function destroy($id)
    {

        $tipo = Preocupacional::where('tipo_estudio_id', $id)->count();
        if ($tipo > 0) {
            return back()->with('error', 'No puede eliminar este tipo de estudio porque existen estudios cargados con este tipo de estudio');
        }else{
            $tipo = PreocupacionalTipoEstudio::find($id);
            $tipo->delete();
            return back()->with('success', 'Eliminado con exito');
        }

    }
}
