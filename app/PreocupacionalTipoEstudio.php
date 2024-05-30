<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreocupacionalTipoEstudio extends Model
{
		protected $table = 'preocupacionales_tipos_estudio';

		protected $fillable = ['name'];
}
