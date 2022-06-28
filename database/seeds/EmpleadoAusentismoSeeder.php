<?php

use Illuminate\Database\Seeder;
use App\Nomina;
use App\AusentismoTipo;
use App\Ausentismo;
use Carbon\Carbon;

class EmpleadoAusentismoSeeder extends Seeder
{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{
			$nomina = Nomina::all();
			$tipos = AusentismoTipo::all();

			$rows = 1500;

			for($i=1; $i<=$rows; $i++){

				$rand_day = rand(0,365);

				$value = [
					'id_trabajador'=>$nomina[rand(0,count($nomina)-1)]->id,
					'id_tipo'=>$tipos[rand(0,count($tipos)-1)]->id,
					'user'=>'Rodo Empleado',
					'fecha_inicio'=>Carbon::today()->subDays($rand_day)->format('Y-m-d'),
					'fecha_final'=>Carbon::today()->subDays($rand_day-7)->format('Y-m-d'),
					'fecha_regreso_trabajar'=>Carbon::today()->subDays($rand_day-7)->format('Y-m-d'),
					'archivo'=>null,
					'hash_archivo'=>null,
					'created_at'=>date('Y-m-d H:m:s'),
					'updated_at'=>date('Y-m-d H:m:s')
				];

				Ausentismo::create($value);
			}

		}
}
