<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChamadosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    private array $tipos = ['E' => 'Em atendimento', 'N' => 'Não atendido', 'F' => 'Finalizado', 'D' => 'Desativado'];
    private array $locais = ['S' => 'Suporte', 'P' => 'Programação'];
    private array $prioridades = ['5' => 'Normal', '1' => 'Urgente'];
    private array $statuss = ['0' => 'Fechado', '1' => 'Aberto'];   

    public function toArray($request)
    {
        $status = $this->status;

        return [

                'id' => $this->id,
                'user_id' => $this->user_id,
                'username' => $this->user->name ?? null,
                'usernivel' => $this->user->nivel ?? null,
                'chamadosData' => $status = Carbon::parse($this->chamados_data)->format('d/m/Y H:i:s'),
                'cliente_id' => $this->cliente_id ?? null,
                'titulo' => $this->titulo ?? NULL,
                'descrição' => $this->descrição ?? NULL,
                'detalhamento' => $this->detalhamento ?? NULL,
                'nome_contato' => $this->nome_contato ?? NULL,
                'contato' => $this->contato ?? NULL,
                'img' => $this->img ?? NULL,

            'cliente' => [
               'codigo' => $this->clientes->codigo, 
               'cliente' => $this->clientes->nome,
               'codigo' => $this->clientes->codigo,
               'telefone1' => $this->clientes->telefone1,
               'telefone2' => $this->clientes->telefone2,
               'img_cliente' => $this->clientes->img_cliente
            ],
            
            'tipo' => $this->tipos[$this->tipo],
            'local' => $this->locais[$this->local],
            'prioridade' => $this->prioridades[$this->prioridade],
            'status' => $this->statuss[$this->status],
            'created_at' => $this->created_at
            //'chamadosSince' => $status = Carbon::parse($this->chamados_data)->diffForHumans(),
        ];
    }
}
