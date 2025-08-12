<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgendaMotivo extends Model
{
    
    // Nombre de la tabla
	protected $table = 'agenda_motivos';

	// Campos habilitados para ingresar
	protected $fillable = ['nombre'];

}
