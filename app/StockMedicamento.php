<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Medicamento;
use App\User;
use App\Cliente;
// use OwenIt\Auditing\Contracts\Auditable;

class StockMedicamento extends Model
// class StockMedicamento extends Model implements Auditable
{

	// use \OwenIt\Auditing\Auditable;

	// Nombre de la tabla
	protected $table = 'stock_medicamentos';

	// Campos habilitados para ingresar
	protected $fillable = ['id_medicamento', 'id_user', 'id_cliente', 'ingreso', 'suministrados', 'egreso', 'fecha_ingreso', 'stock', 'motivo'];

	protected $casts = [
    'created_at'=>'date:d/m/Y - H:i \h\s.'
  ];

	public function medicamento()
	{
		return $this->belongsTo(Medicamento::class,'id_medicamento');
	}
	public function user()
	{
		return $this->belongsTo(User::class,'id_user');
	}
	public function cliente()
	{
		return $this->belongsTo(Cliente::class,'id_cliente');
	}


}
