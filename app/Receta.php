<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receta extends Model
{
    
    use SoftDeletes;

    protected $table = 'recetas';

    protected $fillable = [
        'id_user','id_nomina','id_cliente',
        'hash_id','id_receta','estado','pdf_url',
        'payload','response'
    ];

    protected $casts = [
        'payload'  => 'array',
        'response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
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
