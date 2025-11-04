<?php

namespace App\Http\Controllers;

use App\Receta;
use Illuminate\Http\Request;

class AdminRecetasController extends Controller
{
    
    public function index()
    {
        $recetas = Receta::all();
        return view('admin.recetas', compact('recetas'));
    }
}
