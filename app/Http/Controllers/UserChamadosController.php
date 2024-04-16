<?php

namespace App\Http\Controllers;

use App\Models\UserChamados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserChamadosResource;
use App\Models\User; // Importe o modelo User

class UserChamadosController extends Controller
{
    public function getUserChamados($userId)
    {

            
        // Encontre o usuário pelo ID
        $user = User::findOrFail($userId);

        // Obtenha os chamados associados a este usuário (supondo que exista um relacionamento na model User)
        $userChamados = $user->chamados;

        // Retorne os IDs dos chamados como uma resposta JSON
        return UserChamadosResource::collection($userChamados);
    }
}
    
