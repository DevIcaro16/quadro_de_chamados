<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'codigo' => $this->codigo,
            'telefone1' => $this->telefone1,
            'apelido' => $this->apelido,
            'fantasia' => $this->fantasia,
            'cnpj' => $this->cnpj,
            'endereco' => $this->endereco,
            'bairro' => $this->bairro,
            'telefone2' => $this->telefone2,
            'cep' => $this->cep,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'img_cliente' => $this->img_cliente ?? null
        ];
    }
}
