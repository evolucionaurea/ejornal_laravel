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

	public function scopeFiltrar($q, array $f = [])
    {
        $buscar = isset($f['buscar']) ? trim($f['buscar']) : null;

        return $q
            ->when(!empty($f['id_cliente']), fn($q) => $q->where('id_cliente', (int)$f['id_cliente']))
            ->when(!empty($f['id_medicamento']), fn($q) => $q->where('id_medicamento', (int)$f['id_medicamento']))
            ->when(!empty($f['fecha_desde']), fn($q) => $q->whereDate('fecha_ingreso', '>=', $f['fecha_desde']))
            ->when(!empty($f['fecha_hasta']), fn($q) => $q->whereDate('fecha_ingreso', '<=', $f['fecha_hasta']))
            ->when($buscar, function ($q) use ($buscar) {
                $q->where(function ($q) use ($buscar) {
                    $q->whereHas('medicamento', fn($m) => $m->where('nombre', 'like', "%{$buscar}%"))
                      ->orWhereHas('cliente', fn($c) => $c->withTrashed()->where('nombre', 'like', "%{$buscar}%"));
                });
            })
            // opcional: si querés ocultar clientes eliminados SOLO cuando filtran por cliente
            ->when(isset($f['solo_clientes_activos']) && $f['solo_clientes_activos'], function ($q) {
                $q->whereHas('cliente', fn($c) => $c->whereNull('deleted_at'));
            });
    }


}
