<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvinciaReceta extends Model
{
    
    protected $table = 'provincias_recetas';

    protected $fillable = ['nombre'];

}
