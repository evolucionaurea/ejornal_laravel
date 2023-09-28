<?php

use Illuminate\Database\Seeder;
use App\Nomina;

class NominasImportacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$nominas = Nomina::take(10)->get();
    	dd($nominas->toArray());
    }
}
