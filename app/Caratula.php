<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caratula extends Model
{
    
    // Nombre de la tabla
    protected $table = 'caratulas';

    // Campos habilitados para ingresar
    protected $fillable = [
        'id_nomina',
        'id_patologia', 
        'medicacion_habitual', 
        'antecedentes', 
        'alergias', 
        'peso',
        'altura',
        'imc'
    ];

    public function patologia()
    {
        return $this->belongsTo(Patologia::class, 'id_patologia');
    }

    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_nomina');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente'); 
    }


}
