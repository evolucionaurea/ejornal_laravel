<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominaHistorial extends Model
{
  protected $table = 'nominas_historial';

	protected $fillable = ['year_month','cliente_id','cantidad'];
}
