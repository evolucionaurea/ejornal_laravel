<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StockMedicamento;

class Medicamento extends Model
{

  // Nombre de la tabla
  protected $table = 'medicamentos';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre'];


  public function stock_medicamento()
  {
  	return $this->hasMany(StockMedicamento::class,'id_medicamento');
  }
  public function total_suministrados()
  {
  	return $this->hasMany(StockMedicamento::class,'id_medicamento')->sum('suministrados');
  }


}
