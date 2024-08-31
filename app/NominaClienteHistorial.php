<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominaClienteHistorial extends Model
{

	protected $table = 'nominas_clientes_historial';

	protected $fillable = ['nomina_id','cliente_id','user_id'];
}
