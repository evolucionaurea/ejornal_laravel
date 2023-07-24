<?php

use Illuminate\Database\Seeder;
use App\Ausentismo;
use App\Comunicacion;
use App\Http\Controllers\EmpleadosAusentismosController;

class RegistrosSolapadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // ELIMINAR AUSENTISMOS Y COMUNICACIONES
         $rutaEliminarAusentismos = __DIR__ . '/ausentismos_para_eliminar.php';
         if (file_exists($rutaEliminarAusentismos)) {
             require $rutaEliminarAusentismos;
 
             // La variable $ids viene del requiere anterior
             $controller = new EmpleadosAusentismosController();
             foreach ($ids as $id) {
                 $controller->destroy($id);
             }
         } else {
             echo "El archivo $rutaEliminarAusentismos no se encontró.";
         }



        // CAMBIAR FECHAS DE AUSENTISMOS
        $rutaActualizarAusentismos = __DIR__ . '/ausentismos_actualizar_info.php';
        if (file_exists($rutaActualizarAusentismos)) {
            require $rutaActualizarAusentismos;

            // La variable $data viene del requiere anterior
            foreach ($data as $valor) {
                $id = $valor['id'];
                $fecha_inicio = $valor['fecha_inicio'];
                $fecha_final = $valor['fecha_final'];
                $fecha_regreso_trabajar = $valor['fecha_regreso_trabajar'];
    
                // Buscar el ausentismo por el ID
                $ausentismo = Ausentismo::find($id);
    
                if ($ausentismo) {
                    // Actualizar las fechas
                    $ausentismo->fecha_inicio = $fecha_inicio;
                    $ausentismo->fecha_final = $fecha_final;
                    $ausentismo->fecha_regreso_trabajar = $fecha_regreso_trabajar;
                    $ausentismo->save();
                }
            }
        } else {
            echo "El archivo $rutaActualizarAusentismos no se encontró.";
        }

        
    }
}
