<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplatesController extends Controller
{

	public function tr_certificado_ausentismo(){
		return view('templates.tr-certificado-ausentismo');
	}
	public function tr_certificado_ausentismo_readonly(){
		return view('templates.tr-certificado-ausentismo-readonly');
	}

	public function form_certificado(){
		return view('templates.form-certificado');
	}

}
