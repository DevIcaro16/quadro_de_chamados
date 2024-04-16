<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalhamentoLogResource extends JsonResource
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
            'chamados_id' => $this->chamados_id,
            'user_id' => $this->user_id,
            'titulo' => $this->titulo,
            'conteudo' => $this->conteudo,
            'img' => $this->img,
        ];
    }
}
