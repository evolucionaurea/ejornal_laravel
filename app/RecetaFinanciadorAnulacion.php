<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecetaFinanciadorAnulacion extends Model
{
    protected $table = 'receta_financiadores_anulacion';

    protected $fillable = ['id_financiador', 'nombre'];

    /**
     * Devuelve un array con los id_financiador que permiten anulación.
     */
    public static function idsPermitidos(): array
    {
        return static::pluck('id_financiador')->map(fn($v) => (string) $v)->all();
    }
}
