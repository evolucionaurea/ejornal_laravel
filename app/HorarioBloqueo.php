<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorarioBloqueo extends Model
{
    
    protected $table = 'horario_bloqueos';
    protected $fillable = ['user_id','dia_semana','hora_inicio','hora_fin', 'cliente_id'];
    
    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }
    public function cliente()
    {
      return $this->belongsTo(Cliente::class,'cliente_id');
    }
}
