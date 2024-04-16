<?php

namespace App\Models;

use App\Models\Chamados;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChamadosImages extends Model
{
    use HasFactory;

    protected $fillable = [

        'id',
        'user_id',  
        'chamados_id',
        'images'

        
    ];


    public function chamados()
    {
        return $this->belongsTo(Chamados::class, 'chamado_id');
    }

}
