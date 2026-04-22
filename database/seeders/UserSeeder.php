<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            [
                'persona' => [
                    'nombre' => 'Cajera',
                    'apellido' => 'Cajera',
                    'sexo' => 'M',
                    'fecha_nacimiento' => '1995-05-15',
                    'CURP' => 'caje950515HDFRRR01',
                    'RFC' => 'caje950515XX1',
                    'telefono_personal' => '5555555555',
                    'celular' => '5555555555',
                ],
                'usuario' => [
                    'sucursal_id' => 2,
                    'role_id' => 10,
                    'email' => 'cajera@example.com',
                    'password' => '123456789',
                ]
            ],
            [
                'persona' => [
                    'nombre' => 'Gerente',
                    'apellido' => 'Gerente',
                    'sexo' => 'M',
                    'fecha_nacimiento' => '1995-05-15',
                    'CURP' => 'gere950515HDFRRR01',
                    'RFC' => 'gere950515XX1',
                    'telefono_personal' => '1111111111',
                    'celular' => '1111111111',
                ],
                'usuario' => [
                    'sucursal_id' => 2,
                    'role_id' => 2,
                    'email' => 'gerente@example.com',
                    'password' => '123456789',
                ]
            ],
            [
                'persona' => [
                    'nombre' => 'Verificador',
                    'apellido' => 'Verificador',
                    'sexo' => 'M',
                    'fecha_nacimiento' => '1995-05-15',
                    'CURP' => 'veri950515HDFRRR01',
                    'RFC' => 'veri950515XX1',
                    'telefono_personal' => '3333333333',
                    'celular' => '3333333333',
                ],
                'usuario' => [
                    'sucursal_id' => 2,
                    'role_id' => 6,
                    'email' => 'verificador@example.com',
                    'password' => '123456789',
                ]
            ],
            [
                'persona' => [
                    'nombre' => 'Coordinador',
                    'apellido' => 'Coordinador',
                    'sexo' => 'M',
                    'fecha_nacimiento' => '1995-05-15',
                    'CURP' => 'coor950515HDFRRR01',
                    'RFC' => 'coor950515XX1',
                    'telefono_personal' => '2222222222',
                    'celular' => '2222222222',
                ],
                'usuario' => [
                    'sucursal_id' => 2,
                    'role_id' => 4,
                    'email' => 'coordinador@example.com',
                    'password' => '123456789',
                ]
            ],
        ];

        foreach ($usersData as $data) {
            $persona = Persona::firstOrCreate(
                ['CURP' => $data['persona']['CURP']],
                $data['persona']
            );
            
            User::firstOrCreate(
                ['email' => $data['usuario']['email']],
                [
                    'persona_id' => $persona->id,
                    'sucursal_id' => $data['usuario']['sucursal_id'],
                    'role_id' => $data['usuario']['role_id'],
                    'password' => $data['usuario']['password'],
                ]
            );
        }
    }
}

