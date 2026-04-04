<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    public function listaDeRoles(){
        $roles = Role::all();

        return response()->json([
            'roles' => $roles,
        ],200);
    }
}
