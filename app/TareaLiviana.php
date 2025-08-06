<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TareaLivianaTipo;
use App\Nomina;
use App\Cliente;
use App\TareaLivianaDocumentacion;
use App\ComunicacionLiviana;


class TareaLiviana extends Model
{
   // Nombre de la tabla
  protected $table = 'tareas_livianas';

  // Campos habilitados para ingresar
  protected $fillable = ['id_trabajador', 'user', 'id_tipo', 'fecha_inicio', 'fecha_final', 'fecha_regreso_trabajar', 'archivo', 'hash_archivo'];


  protected $casts = [
  	'fecha_inicio'=>'date:d/m/Y',
    'fecha_final'=>'date:d/m/Y',
    'fecha_regreso_trabajar'=>'date:d/m/Y'
  ];

  protected $appends = ['trabajador_perfil_url','created_at_formatted','archivo_path','archivo_path_admin'];


  public function tipo() {
  	return $this->belongsTo(TareaLivianaTipo::class,'id_tipo');
  }
  public function trabajador(){
    return $this->belongsTo(Nomina::class,'id_trabajador')->withTrashed();
  }
  /*public function cliente(){
    return $this->hasOneThrough(Cliente::class,Nomina::class,'id','id','id_trabajador','id_cliente');
  }*/
  public function cliente(){
    return $this->belongsTo(Cliente::class,'id_cliente');
  }

  public function documentaciones(){
    return $this->hasMany(TareaLivianaDocumentacion::class,'id_tarea_liviana');
  }
  public function comunicacion(){
    return $this->hasOne(ComunicacionLiviana::class,'id_tarea_liviana');
  }


  public function getCreatedAtFormattedAttribute()
  {
    return $this->created_at->format('d/m/Y H:i:s \h\s.');
  }
  public function getTrabajadorPerfilUrlAttribute()
  {
    return url('/empleados/nominas/'.$this->id_trabajador);
  }

  public function getArchivoPathAttribute()
  {
    return url('empleados/tareas_livianas/archivo/'.$this->id);
  }
  public function getArchivoPathAdminAttribute()
  {
    return url('admin/tareas_livianas/archivo/'.$this->id);
  }

}
