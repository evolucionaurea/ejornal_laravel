<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaNutricional extends Model
{
    
     // Nombre de la tabla
    protected $table = 'consultas_nutricionales';

    // Campos habilitados para ingresar
    protected $fillable = [
        'id_nomina',
        'tipo', 
        'objetivos', 
        'gustos_alimentarios', 
        'comidas_diarias',
        'descanso',
        'intolerancias_digestivas',
        'alergias_alimentarias',
        'fecha_atencion',
        'act_fisica',
        'circunferencia_cintura',
        'porcent_masa_grasa',
        'porcent_masa_muscular',
        'transito_intestinal',
        'evolucion',
        'prox_cita',
        'medicaciones'
    ];

    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_nomina');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

}
