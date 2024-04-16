<?php

namespace App\Http\Controllers;

use App\Models\Chamados;
use Illuminate\Http\Request;
use App\Models\DetalhamentoLogChamados;
use App\Http\Resources\DetalhamentoLogResource;

class DetalhamentoLogController extends Controller
{
    
    public function index (Request $request)
    {
        return DetalhamentoLogResource::collection(DetalhamentoLogChamados::all());
    }

    public function show($chamados_id)
    {
        $detalhamentos = DetalhamentoLogChamados::where('chamados_id', $chamados_id)->get();
    
        return DetalhamentoLogResource::collection($detalhamentos);
    }

}
