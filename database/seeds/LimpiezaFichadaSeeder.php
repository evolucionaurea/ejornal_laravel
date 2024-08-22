<?php

use App\FichadaNueva;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LimpiezaFichadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fecha límite: 1ro de Julio de 2024
        $fechaLimite = Carbon::create(2024, 7, 1, 0, 0, 0);

        // Buscar registros donde "egreso" sea null y "ingreso" sea anterior a la fecha límite
        $fichadas = FichadaNueva::whereNull('egreso')
            ->where('ingreso', '<', $fechaLimite)
            ->get();

        // Actualizar cada registro
        foreach ($fichadas as $fichada) {
            $fichada->egreso = Carbon::parse($fichada->ingreso)->addHours(8);
            
            // Calcular la diferencia entre ingreso y egreso
            $f_ingreso = new \DateTime($fichada->ingreso);
            $f_egreso = new \DateTime($fichada->egreso);
            $time = $f_ingreso->diff($f_egreso);
            $tiempo_dedicado = $time->days . ' días ' . $time->format('%H horas %i minutos %s segundos');

            // Guardar el tiempo dedicado
            $fichada->tiempo_dedicado = $tiempo_dedicado;

            // Guardar los cambios en el registro
            $fichada->save();
        }
    }
}
