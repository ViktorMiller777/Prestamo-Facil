<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorias;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['categoria' => 'Cobre', 'porcentaje_comision' => 3.00],
            ['categoria' => 'Plata', 'porcentaje_comision' => 6.00],
            ['categoria' => 'Oro', 'porcentaje_comision' => 10.00],
        ];
    }
}
