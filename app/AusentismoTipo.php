<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AusentismoTipo extends Model implements Auditable
{

  use \OwenIt\Auditing\Auditable;

  // Nombre de la tabla
  protected $table = 'ausentismo_tipo';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre', 'agrupamiento'];

}
