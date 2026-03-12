<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrarMatriculaLegacyAUsersMatriculas extends Migration
{
    public function up()
    {
        $users = DB::table('users')
            ->select(
                'id',
                'tipo_matricula',
                'matricula',
                'fecha_vencimiento',
                'archivo_matricula',
                'hash_matricula',
                'archivo_matricula_detras',
                'hash_matricula_detras'
            )
            ->get();

        foreach ($users as $u) {
            // Si no hay nada, saltar
            $tieneAlgo =
                !empty($u->tipo_matricula) ||
                !empty($u->matricula) ||
                !empty($u->fecha_vencimiento) ||
                !empty($u->hash_matricula) ||
                !empty($u->hash_matricula_detras);

            if (!$tieneAlgo) {
                continue;
            }

            $tipo = strtoupper(trim((string) $u->tipo_matricula));
            if ($tipo === '') {
                // si estaba vacío, evitamos insertar basura
                continue;
            }

            // Evitar duplicar si ya existe
            $existe = DB::table('users_matriculas')
                ->where('id_user', $u->id)
                ->where('tipo', $tipo)
                ->exists();

            if ($existe) {
                continue;
            }

            DB::table('users_matriculas')->insert([
                'id_user' => $u->id,
                'tipo' => $tipo,
                'nro' => !empty($u->matricula) ? (string)$u->matricula : null,
                'fecha_vencimiento' => !empty($u->fecha_vencimiento) ? $u->fecha_vencimiento : null,
                'archivo_frente' => !empty($u->archivo_matricula) ? $u->archivo_matricula : null,
                'hash_frente' => !empty($u->hash_matricula) ? $u->hash_matricula : null,
                'archivo_dorso' => !empty($u->archivo_matricula_detras) ? $u->archivo_matricula_detras : null,
                'hash_dorso' => !empty($u->hash_matricula_detras) ? $u->hash_matricula_detras : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        // no borramos data para evitar pérdida accidental
    }
}
