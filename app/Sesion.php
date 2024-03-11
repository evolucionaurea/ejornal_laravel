<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{

    // Nombre de la tabla
    protected $table = 'sesiones';

    // Campos habilitados para ingresar
    protected $fillable = ['id_user', 'loggeado'];

}
