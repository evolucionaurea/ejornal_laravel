<?php

use App\ProvinciaReceta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaRecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provincias = [
            'Sin especificar',
            'Entre Rios',
            'Santiago del Estero',
            'La rioja',
            'Bs As',
            'Buenos Aires',
            'Catamarca',
            'Santa Fe',
            'San Luis',
            'Mendoza',
            'Corrientes',
            'Santa Cruz',
            'Neuquen',
            'Formosa',
            'San Juan',
            'Salta',
            'Ciudad Autonoma de Bs As',
            'Rio Negro',
            'Chaco',
            'La Pampa',
            'Tierra del Fuego',
            'Jujuy',
            'Misiones',
            'Cordoba',
            'Tucuman',
            'Chubut',
            'Nacional',
        ];

        foreach ($provincias as $nombre) {
            ProvinciaReceta::firstOrCreate(
                ['nombre' => $nombre],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

    }
}
