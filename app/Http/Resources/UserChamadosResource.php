<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\ChamadosResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserChamadosResource extends JsonResource
{

    private array $tipos = ['E' => 'Em atendimento', 'N' => 'Não atendidos', 'F' => 'Finalizado'];
    private array $locais = ['S' => 'Suporte', 'P' => 'Programação'];
    private array $prioridades = ['5' => 'Normal', '1' => 'Urgente'];
    private array $statuss = ['0' => 'Fechado', '1' => 'Aberto'];

    public function toArray($request)
    {
        $status = $this->status;

        return [

                'id' => $this->id,
                'user_id' => $this->user_id,
                'username' => $this->user->name,
                'cliente_id' => $this->cliente_id,
                'descrição' => $this->descrição,
                'detalhamento' => $this->detalhamento,
                'contato' => $this->contato,

            'cliente' => [
                'nome' => $this->cliente,
                'codigo' => $this->clientes->codigo,
                'img_cliente' => $this->clientes->img_cliente
            ],

            'tipo' => $this->tipos[$this->tipo],
            'local' => $this->locais[$this->local],
            'prioridade' => $this->prioridades[$this->prioridade],
            'status' => $this->statuss[$this->status],
            'created_at' => $this->created_at
        ];
    }
}

