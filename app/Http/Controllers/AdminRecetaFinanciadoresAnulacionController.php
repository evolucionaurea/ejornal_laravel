<?php

namespace App\Http\Controllers;

use App\RecetaFinanciadorAnulacion;
use Illuminate\Http\Request;

class AdminRecetaFinanciadoresAnulacionController extends Controller
{
    public function index()
    {
        $financiadores = RecetaFinanciadorAnulacion::orderBy('id_financiador')->get();
        return view('admin.receta_financiadores_anulacion.index', compact('financiadores'));
    }

    public function create()
    {
        return view('admin.receta_financiadores_anulacion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_financiador' => 'required|integer|min:1',
            'nombre'         => 'required|string|max:200',
        ]);

        RecetaFinanciadorAnulacion::create($data);

        return redirect()->route('admin.receta_financiadores_anulacion.index')
            ->with('success', 'Financiador agregado correctamente.');
    }

    public function edit($id)
    {
        $financiador = RecetaFinanciadorAnulacion::findOrFail($id);
        return view('admin.receta_financiadores_anulacion.edit', compact('financiador'));
    }

    public function update(Request $request, $id)
    {
        $financiador = RecetaFinanciadorAnulacion::findOrFail($id);

        $data = $request->validate([
            'id_financiador' => 'required|integer|min:1',
            'nombre'         => 'required|string|max:200',
        ]);

        $financiador->update($data);

        return redirect()->route('admin.receta_financiadores_anulacion.index')
            ->with('success', 'Financiador actualizado correctamente.');
    }

    public function destroy($id)
    {
        RecetaFinanciadorAnulacion::findOrFail($id)->delete();

        return redirect()->route('admin.receta_financiadores_anulacion.index')
            ->with('success', 'Financiador eliminado correctamente.');
    }
}
