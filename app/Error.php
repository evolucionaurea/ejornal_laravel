<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
 
    // Nombre de la tabla
    protected $table = 'errores';
    public $timestamps = true;
    protected $fillable = ['type', 'message', 'file', 'line', 'id_user'];

}
