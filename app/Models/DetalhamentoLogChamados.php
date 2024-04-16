<?php

namespace App\Models;

use App\Models\Chamados;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalhamentoLogChamados extends Model
{
    use HasFactory;

    protected $table = 'chamados_detalhamentolog'; // Nome da tabela intermediária

    protected $fillable = [
        'id',
        'chamados_id',
        'user_id',
        'titulo',
        'conteudo',
        'img',
    ];

}
