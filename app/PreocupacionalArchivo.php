<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreocupacionalArchivo extends Model
{

  protected $table = 'preocupacionales_archivos';

  protected $appends = ['file_path','file_path_admin', 'file_path_cliente'];


  public function getFilePathAttribute()
  {
		return url('empleados/preocupacionales/archivo/'.$this->id);
  }

  public function getFilePathAdminAttribute()
  {
		return url('admin/preocupacionales/archivo/'.$this->id);
  }

  public function getFilePathClienteAttribute()
  {
		return url('clientes/preocupacionales/archivo/'.$this->id);
  }

}
