<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nomina;
use App\PreocupacionalArchivo;
use App\PreocupacionalTipoEstudio;
use App\Cliente;
use Carbon\Carbon;


class Preocupacional extends Model
{

	// Nombre de la tabla
	protected $table = 'preocupacionales';

	// Campos habilitados para ingresar
	protected $fillable = ['id_nomina', 'fecha', 'observaciones', 'archivo', 'hash_archivo'];

	protected $casts = [
		'fecha'=>'date:d/m/Y',
		'fecha_vencimiento'=>'date:d/m/Y',
	];
	protected $appends = ['estado_vencimiento_label','vencimiento_label','completado_label'];



	public function getEstadoVencimientoLabelAttribute()
	{
		if(is_null($this->fecha_vencimiento)) return '<span class="text-muted font-style-italic">[sin vencimiento]</span>';
		if($this->completado) return '<span class="badge badge-success">completado</span>';

		$now = Carbon::now();
		if($this->fecha_vencimiento <= $now) return '<span class="badge badge-danger">vencido</span>';
		$diff = $this->fecha_vencimiento->diffInDays($now);
		return '<span class="badge badge-secondary">vence en '.$diff.' días</span>';
	}
	public function getVencimientoLabelAttribute()
	{
		if(is_null($this->fecha_vencimiento)) return '';
		if($this->completado) return '';

		$now = Carbon::now();
		if($this->fecha_vencimiento > $now){
			$diff = $this->fecha_vencimiento->diffInDays($now);
			return '<span class="badge badge-secondary">vence en '.$diff.' días</span>';
		}
		return '<span class="badge badge-danger">vencido</span>';
	}
	public function getCompletadoLabelAttribute()
	{
		if(is_null($this->fecha_vencimiento)) return '';
		return '<span class="badge badge-'.($this->completado?'success':'danger').'">'.($this->completado?'completado':'sin completar').'</span>';
	}
	public function getCreatedAtFormattedAttribute()
	{
		return $this->created_at->format('d/m/Y H:i:s \h\s.');
	}
	public function getUpdatedAtFormattedAttribute()
	{
		return $this->updated_at->format('d/m/Y H:i:s \h\s.');
	}



	public function trabajador(){
		return $this->belongsTo(Nomina::class,'id_nomina')->withTrashed();
	}

	public function archivos()
	{
		return $this->hasMany(PreocupacionalArchivo::class);
	}

	public function tipo()
	{
		return $this->belongsTo(PreocupacionalTipoEstudio::class,'tipo_estudio_id');
	}

	public function cliente(){
		return $this->belongsTo(Cliente::class,'id_cliente');
	}


}
