<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Contracts\Auditable;

class StockMedicamento extends Model
// class StockMedicamento extends Model implements Auditable
{

  // use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'stock_medicamentos';

  // Campos habilitados para ingresar
  protected $fillable = ['id_medicamento', 'id_user', 'id_cliente', 'ingreso', 'suministrados', 'egreso', 'fecha_ingreso', 'stock', 'motivo'];


}
