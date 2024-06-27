<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ConsultaMedica;
use App\ConsultaEnfermeria;
use App\StockMedicamento;

class StockMedicamentoHistorial extends Model
{

  // Nombre de la tabla
  protected $table = 'stock_medicamentos_historial';

  // Campos habilitados para ingresar
  protected $fillable = ['id_stock_medicamentos', 'ingreso', 'suministrados', 'egreso', 'stock', 'fecha_ingreso'];


  protected $casts = [
  	'fecha_ingreso'=>'date:d/m/Y'
  ];



  public function consulta_medica(){
  	return $this->belongsTo(ConsultaMedica::class,'id_consulta_medica');
  }
  public function consulta_enfermeria(){
  	return $this->belongsTo(ConsultaEnfermeria::class,'id_consulta_enfermeria');
  }
  public function stock_medicamento(){
  	return $this->belongsTo(StockMedicamento::class,'id_stock_medicamentos');
  }


}
