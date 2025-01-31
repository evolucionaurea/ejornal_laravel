<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendaEstado extends Model
{
  // Nombre de la tabla
  protected $table = 'agenda_estados';

  // Campos habilitados para ingresar
  protected $fillable = ['nombre','referencia','descripcion','color'];
}
