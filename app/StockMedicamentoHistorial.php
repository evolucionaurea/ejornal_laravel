<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMedicamentoHistorial extends Model
{

  // Nombre de la tabla
  protected $table = 'stock_medicamentos_historial';

  // Campos habilitados para ingresar
  protected $fillable = ['id_stock_medicamentos', 'ingreso', 'suministrados', 'egreso', 'stock', 'fecha_ingreso'];


}
