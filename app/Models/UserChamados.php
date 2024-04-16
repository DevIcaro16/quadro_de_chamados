<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChamados extends Model
{
    protected $table = 'chamados_user'; // Nome da tabela intermediária

    protected $fillable = [
        'user_id',    // Nome da coluna de chave estrangeira do usuário
        'chamado_id', // Nome da coluna de chave estrangeira do chamado
    ];

    
}

