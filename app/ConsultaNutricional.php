<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultaNutricional extends Model
{
    
     // Nombre de la tabla
    protected $table = 'consultas_nutricionales';

    protected $casts = [
        'fecha_atencion'=>'date:d/m/Y'
    ];
    protected $dates = ['fecha_atencion'];

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
        'medicaciones',
        'user'
    ];

    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_nomina');
    }

    // Alias para la relación nomina
    public function trabajador()
    {
        return $this->belongsTo(Nomina::class, 'id_nomina')->withTrashed();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function getCreatedAtFormattedAttribute()
    {
        if(is_null($this->created_at)) return '';
        return $this->created_at->format('d/m/Y H:i:s \h\s.');
    }

}
