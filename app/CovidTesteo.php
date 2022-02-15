<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CovidTesteo extends Model
{
    // Nombre de la tabla
    protected $table = 'covid_testeos';

    // Campos habilitados para ingresar
    protected $fillable = ['id_nomina', 'id_tipo', 'fecha', 'resultado', 'laboratorio'];

}
