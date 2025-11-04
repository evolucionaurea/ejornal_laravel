<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AgendaEstado;
use App\Agenda;
use Carbon\CarbonImmutable;

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
	public function form_comunicacion(){
		return view('templates.form-comunicacion');
	}
	public function form_cambiar_fichada(){
		return view('templates.form-cambiar-fichada');
	}
	

	public function form_completar_preocupacional(){
		return view('templates.form-completar-preocupacional');
	}

}
