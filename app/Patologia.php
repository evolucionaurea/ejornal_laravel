<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patologia extends Model
{
    
    // Nombre de la tabla
    protected $table = 'patologias';

    // Campos habilitados para ingresar
    protected $fillable = ['nombre', 'user'];

    public function caratulas()
    {
        return $this->belongsToMany(Caratula::class, 'caratula_patologia', 'id_patologia', 'id_caratula')->withTimestamps();
    }

}
