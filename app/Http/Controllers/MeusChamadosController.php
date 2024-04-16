<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeusChamadosController extends Controller
{
    public function index()
    {
        // Buscar chamados assumidos pelo usuário autenticado
        $chamadosAssumidos = Chamados::where('user_id', Auth::id())->get();

        return view('meus_chamados', compact('chamadosAssumidos'));
    }
}
