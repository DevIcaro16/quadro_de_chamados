<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssumirChamadoController extends Controller
{
    public function assumir($id)
    {
        try {
            // Obtenha o chamado pelo ID
            $chamado = Chamados::find($id);

            // Verifique se o chamado existe e se o usuário está autenticado
            if ($chamado && Auth::check()) {
                // Atribua o ID do usuário autenticado ao chamado
                $chamado->user_id = Auth::id();
                $chamado->save();

                return redirect()->route('meus_chamados')->with('success', 'Chamado assumido com sucesso!');
            } else {
                throw new \Exception('Não foi possível assumir o chamado.');
            }
        } catch (\Exception $e) {
            return redirect()->route('meus_chamados')->with('error', 'Erro ao assumir chamado: ' . $e->getMessage());
        }
    }
}
