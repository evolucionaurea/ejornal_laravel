<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Ausentismo;
use App\Cliente;
use App\NominaClienteHistorial;
use Carbon\Carbon;

class Nomina extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // Nombre de la tabla
  protected $table = 'nominas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_cliente', 'nombre', 'email', 'telefono', 'dni', 'estado', 'foto', 'hash_foto'];

  protected $casts = [
    'created_at'=>'date:d/m/Y',
    'fecha_nacimiento'=>'date:d/m/Y'
  ];

  protected $appends = ['edad','perfil_url'];


  public function ausentismos()
  {
  	return $this->hasMany(Ausentismo::class,'id_trabajador');
  }
  public function movimientos_cliente(){
    return $this->hasMany(NominaClienteHistorial::class,'nomina_id');
  }


  public function scopeWithAusentismoEstado($query)
  {
    $today = Carbon::now();
    $query->addSelect(['fecha_regreso_trabajar'=>Ausentismo::select('fecha_final')
      ->whereColumn('id_trabajador','nominas.id')
      ->where('fecha_final',null)
      ->orWhere('fecha_final','>=',$today)
    ]);
  }

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }

  public function getPhotoUrlAttribute(){
    return $this->foto ? asset('storage/nominas/fotos/'.$this->id.'/'.$this->hash_foto) : '';
  }
  public function getThumbnailUrlAttribute(){
    return $this->foto ? asset('storage/nominas/fotos/'.$this->id.'/'.$this->hash_thumbnail) : '';
  }

  public function getEdadAttribute(){
    if(!$this->fecha_nacimiento) return null;
    return $this->fecha_nacimiento->diffInYears(now());
  }

  public function getPerfilUrlAttribute()
  {
    return url('/empleados/nominas/'.$this->id);
  }


  public function caratulas()
  {
      return $this->hasMany(Caratula::class, 'id_nomina');
  }



}
