<?php

namespace App\Models;

use App\Models\Chamados;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'id',
        'codigo',
        'nome',
        'telefone1',
        'apelido',
        'fantasia',
        'cnpj',
        'endereco',
        'bairro',
        'telefone2',
        'cep',
        'cidade',
        'uf',
        'img_cliente'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function chamados()
    {
        return $this->hasMany(Chamados::class, 'cliente_id');
    }

}
