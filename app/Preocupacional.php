<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;

class Preocupacional extends Model
{

  // Nombre de la tabla
  protected $table = 'preocupacionales';

  // Campos habilitados para ingresar
  protected $fillable = ['id_nomina', 'fecha', 'observaciones', 'archivo', 'hash_archivo'];


  public function trabajador(){
    return $this->belongsTo(Nomina::class,'id_nomina');
  }


}
