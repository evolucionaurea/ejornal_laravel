<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cliente;
use App\Grupo;

class ClienteGrupo extends Model
{

  protected $table = 'cliente_grupo';
  protected $fillable = ['id_cliente', 'id_grupo'];

  public function cliente()
  {
  	return $this->belongsTo(Cliente::class, 'id_cliente');
  }
  public function grupo()
  {
  	return $this->belongsTo(Grupo::class, 'id_grupo');
  }

}
