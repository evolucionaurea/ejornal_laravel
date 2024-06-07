<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreocupacionalArchivo extends Model
{

  protected $table = 'preocupacionales_archivos';

  protected $appends = ['file_path'];


  public function getFilePathAttribute()
  {
  	return url('empleados/preocupacionales/archivo/'.$this->id);
  }
}
