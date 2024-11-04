<?php

namespace App\Http\Controllers;

use App\EdicionFichada;
use Illuminate\Http\Request;

class AdminEdicionFichadaController extends Controller
{
    public function index()
    {
        $ediciones = EdicionFichada::with('user')->get();
        return view('admin.reportes.ediciones_fichadas', compact('ediciones'));
    }
}
