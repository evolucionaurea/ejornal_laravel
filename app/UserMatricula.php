<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMatricula extends Model
{
    // Nombre de la tabla
	protected $table = 'users_matriculas';

	// Campos habilitados para ingresar
	protected $fillable = [
        'id_user',
        'tipo',
        'nro',
        'fecha_vencimiento',
        'archivo_frente',
        'hash_frente',
        'archivo_dorso',
        'hash_dorso',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
