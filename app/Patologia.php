<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patologia extends Model
{
    
    // Nombre de la tabla
    protected $table = 'patologias';

    // Campos habilitados para ingresar
    protected $fillable = ['nombre'];

    public function caratulas()
    {
        return $this->hasMany(Caratula::class, 'id_patologia');
    }

}
