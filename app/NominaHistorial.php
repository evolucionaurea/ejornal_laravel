<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NominaHistorial extends Model
{
  protected $table = 'nominas_historial';

	protected $fillable = ['year_month','cliente_id','cantidad'];

	protected $appends = ['year_month_object','year','month'];


	public function getYearMonthObjectAttribute()
	{
		return Carbon::createFromFormat('Ymd',$this->year_month.'01');
	}
	public function getYearAttribute()
	{
		return Carbon::createFromFormat('Ymd',$this->year_month.'01')->format('Y');
	}
	public function getMonthAttribute()
	{
		$newLocale = setlocale(LC_TIME, 'Spanish');
		return Str::ucfirst(Carbon::createFromFormat('Ymd',$this->year_month.'01')->formatLocalized('%B'));
	}

}
