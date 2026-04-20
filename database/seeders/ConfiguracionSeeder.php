<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            [
                'clave' => 'puntos',
                'valor' => '2',
                'descripcion' => 'canjea tus puntos por dinero',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'recargos',
                'valor' => '300',
                'descripcion' => 'multa por pagos a destiempo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
