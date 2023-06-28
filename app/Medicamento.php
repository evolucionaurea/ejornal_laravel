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

  protected $casts = [
    'created_at'=>'date:d/m/Y - H:i \h\s.'
  ];



  public function stock_medicamento()
  {
  	return $this->hasMany(StockMedicamento::class,'id_medicamento');
  }
  public function total_suministrados()
  {
  	return $this->hasMany(StockMedicamento::class,'id_medicamento')->sum('suministrados');
  }


}
