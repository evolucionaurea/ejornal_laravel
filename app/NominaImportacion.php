<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominaImportacion extends Model
{

	protected $table = 'nominas_importaciones';

	protected $fillable = ['total', 'nuevos', 'existentes', 'actualizados', 'borrados', 'year_month', 'filename', 'user_id', 'cliente_id'];

}
