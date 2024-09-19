<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;
use App\Cliente;
use App\User;

class NominaClienteHistorial extends Model
{

	protected $table = 'nominas_clientes_historial';

	protected $fillable = ['nomina_id','cliente_id','user_id'];

	protected $casts = [
		'created_at'=>'datetime:d/m/Y - H:i:s'
	];


	public function trabajador()
	{
		return $this->belongsTo(Nomina::class,'nomina_id');
	}
	public function cliente()
	{
		return $this->belongsTo(Cliente::class,'cliente_id');
	}
	public function usuario()
	{
		return $this->belongsTo(User::class,'user_id');
	}
}
