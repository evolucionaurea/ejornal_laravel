<?php

use Illuminate\Database\Seeder;
use App\AgendaEstado;

class AgendaEstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      AgendaEstado::create([
      	'nombre'=>'Confirmado',
      	'descripcion'=>'El turno ha sido confirmado.',
      	'color'=>'#32a88b',
      	'referencia'=>'confirmed'
      ]);
      AgendaEstado::create([
      	'nombre'=>'Sin Confirmar',
      	'descripcion'=>'El turno no ha sido confirmado aún.',
      	'color'=>'#969035',
      	'referencia'=>'unconfirmed'
      ]);
      AgendaEstado::create([
      	'nombre'=>'Cancelado',
      	'descripcion'=>'El turno ha sido cancelado.',
      	'color'=>'#8a2c2c',
      	'referencia'=>'cancelled'
      ]);
      AgendaEstado::create([
      	'nombre'=>'Atendido',
      	'descripcion'=>'El turno ha sido brindado.',
      	'color'=>'#376d7a',
      	'referencia'=>'attended'
      ]);
      AgendaEstado::create([
      	'nombre'=>'No Asistió',
      	'descripcion'=>'El paciente no ha asistido a la consulta.',
      	'color'=>'#9561e2',
      	'referencia'=>'absent'
      ]);
      /* AgendaEstado::create([
      	'nombre'=>'Reprogramado',
      	'descripcion'=>'El turno ha sido reprogramado para otra fecha/horario.',
      	'color'=>'#6574cd',
      	'referencia'=>'rescheduled'
      ]); */

    }
}
