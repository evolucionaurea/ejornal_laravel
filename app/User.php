<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Nombre de la tabla
    protected $table = 'users';

    // Campos habilitados para ingresar
    protected $fillable = [
       'id_rol', 'nombre', 'apellido', 'estado', 'personal_interno', 'email', 'permiso_desplegables', 'onedrive', 'cuil'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
