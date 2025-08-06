<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

class webOficialController extends Controller
{

  public function index()
  {
      return view('web_oficial');
  }

}
