<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Verifica que tu modelo se llame Role

class RoleSeeder extends Seeder
{
    public function run(): void
    {
       $roles = [
            ['id' => 1, 'role' => 'Gerente'],        // ID 1
            ['id' => 2, 'role' => 'Coordinador'],    // ID 2
            ['id' => 3, 'role' => 'Verificador'],    // ID 3
            ['id' => 4, 'role' => 'Distribuidora'],  // ID 4
            ['id' => 5, 'role' => 'Cajera'],         // ID 5
        ];

        foreach ($roles as $r) {
            Role::create($r);
        }
    }
}