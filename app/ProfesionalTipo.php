<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfesionalTipo extends Model
{
	protected $table = 'profesionales_tipos';

	protected $fillable = [
		'nombre',
	];
}
