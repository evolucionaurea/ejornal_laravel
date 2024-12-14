<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class EdicionFichada extends Model
{

    // Nombre de la tabla
  protected $table = 'ediciones_fichadas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_user', 'old_ingreso', 'old_egreso', 'new_ingreso', 'new_egreso', 'ip', 'dispositivo'];

  protected $appends = ['old_ingreso_formatted','old_egreso_formatted','new_ingreso_formatted','new_egreso_formatted'];

  public function user(){
  	return $this->belongsTo(User::class, 'id_user');
  }
  public function fichada(){
    return $this->belongsTo(FichadaNueva::class, 'id_fichada');
  }

  public function getOldIngresoFormattedAttribute()
  {
  	return $this->dateFormatted(Carbon::parse($this->old_ingreso));
  }
  public function getOldEgresoFormattedAttribute()
  {
  	return $this->dateFormatted(Carbon::parse($this->old_egreso));
  }
  public function getNewIngresoFormattedAttribute()
  {
  	return $this->dateFormatted(Carbon::parse($this->new_ingreso));

  }
  public function getNewEgresoFormattedAttribute()
  {
  	return $this->dateFormatted(Carbon::parse($this->new_egreso));
  }
  public function dateFormatted($date){
  	return mb_convert_case($date->translatedFormat('l'),MB_CASE_TITLE,'UTF-8') . ', '. $date->format('d/m/Y') . ' - ' . $date->format('H:i:s') . ' hs.';
  }
  public function getCreatedAtFormattedAttribute(){
    return $this->created_at->format('d/m/Y H:i:s \h\s.');
  }

}
