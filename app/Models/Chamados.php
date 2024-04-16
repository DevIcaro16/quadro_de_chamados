<?php

namespace App\Models;

use App\Models\User;
use App\Filters\Filter;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\ChamadosImages;
use App\Filters\ChamadosFilter;
use App\Models\DetalhamentosAccordion;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\V1\ChamadosResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chamados extends Model
{
    use HasFactory;

    // Necessário informar todos os campos do form aqui:

    protected $fillable = [

        'id',
        'user_id',
        'cliente_id',
        'cliente',
        'local',
        'tipo',
        'chamados_data',
        'titulo',
        'descrição',
        'img',
        'status',
        'prioridade',
        'detalhamento',
        'nome_contato',
        'contato',
    
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }



    public function filter(Request $request){
    $queryFilter = (new ChamadosFilter)->filter($request);

    if (empty($queryFilter)) {
        return ChamadosResource::collection(Chamados::with('user')->get());
      }


    $data = Chamados::with('user');
    

    if (!empty($queryFilter['where'])) {
        foreach ($queryFilter['where'] as $value) {
            $data->where($value[0], $value[1], $value[2]);
        }
    }

    $resource = $data->where($queryFilter['where'])->get();

    return ChamadosResource::collection($resource);
}

    public function detalhamentos()
    {
        return $this->belongsToMany('App/Models/User');
    }



    public function users(){
        return $this->belongsToMany('App\Models\User');
    }


    public function clientes(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function imagens()
    {
        return $this->hasMany(ChamadosImages::class, 'chamados_id');
    }

}
