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
		$this->belongsTo(Nomina::class);
	}
	public function cliente()
	{
		$this->belongsTo(Cliente::class);
	}
	public function usuario()
	{
		$this->belongsTo(User::class);
	}
}
