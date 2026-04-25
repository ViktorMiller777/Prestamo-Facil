<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sucursal; // Asegúrate de que el modelo se llame Sucursal

class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        $sucursales = [
            [
                'id'        => 1,                    // ID 1
                'nombre'    => 'Prestamo_Facil',
                'municipio' => 'Gómez Palacio',
            ],
            [
                'id'        => 2,                    // ID 2
                'nombre'    => 'Prestamo_Facil',
                'municipio' => 'Lerdo',
            ],
            [
                'id'        => 3,                    // ID 3
                'nombre'    => 'Prestamo_Facil',
                'municipio' => 'Durango',
            ],
            [
                'id'        => 4,                    // ID 4
                'nombre'    => 'Prestamo_Facil',
                'municipio' => 'Torreón',
            ],
        ];

        foreach ($sucursales as $s) {
            Sucursal::create($s);
        }
    }
}