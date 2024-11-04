<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdicionFichada extends Model
{
    
    // Nombre de la tabla
  protected $table = 'ediciones_fichadas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_user', 'old_ingreso', 'old_egreso', 'new_ingreso', 'new_egreso', 'ip', 'dispositivo'];

  public function user(){
  	return $this->belongsTo(User::class, 'id_user');
  }

}
