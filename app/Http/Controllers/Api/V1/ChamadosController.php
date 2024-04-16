<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Chamados;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DetalhamentoLogChamados;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V1\ChamadosResource;

class ChamadosController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $page = $request->input('page', 1); // Obtém o número da página da solicitação
    $pageSize = $request->input('size', 3); // Obtém o tamanho da página da solicitação

    $query = Chamados::with('user')->orderBy('id', 'desc');

    // Verifique se há um filtro de prioridade na solicitação
    if ($request->has('prioridade')) {
        $prioridade = $request->input('prioridade');
        $query->where('prioridade', $prioridade);
    }
    if ($request->has('local')) {
        $local = $request->input('local');
        $query->where('local', $local);
    }
    if ($request->has('tipo')) {
        $tipo = $request->input('tipo');
        $query->where('tipo', $tipo);
    }
    if ($request->has('status')) {
        $status = $request->input('status');
        $query->where('status', $status);
    }

    $data = $query->paginate($pageSize, ['*'], 'page', $page);

    return ChamadosResource::collection($data);
}




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function detalhamento(Request $request, $id)
    {
        $user = auth()->user();
        
        // Obtenha os valores dos campos do formulário
        $titulo = $request->input('titulo');
        $conteudo = $request->input('conteudo');

        $chamado = Chamados::findOrFail($id);
        
        // Crie um novo registro na tabela 'chamados_detalhamentolog'
        $detalhamento = new DetalhamentoLogChamados();
        $detalhamento->chamados_id = $id;
        $detalhamento->user_id = $user->id;
        $detalhamento->titulo = $titulo;
        $detalhamento->conteudo = $conteudo;

        // $imagePaths = [];

        // if ($request->hasFile('img')) {
        
        //     foreach ($request->file('img') as $file) {

        //         $originalFileName = $file->getClientOriginalName(); 
        //         $imagePath = $file->store('chamados_images', 'public');
        //         $imagePaths[] = [
        //             'original_name' => $originalFileName,
        //             'url' => $imagePath
        //         ];

        //     }

        // }

        // $detalhamento->img = $imagePaths;

       
        $detalhamento->save();

        

        return response()->json(['message' => 'Detalhamento enviado ao banco de dados!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        $request->merge(['user_id' => $userId, 'tipo' => 'N', 'status' => 1]);

        $validatedData = $request->validate([
            'cliente_id' => 'required',
            'cliente' => 'required',
            'tipo' => 'required|max:1',
            'status' => 'required|max:1',
            'chamados_data' => 'nullable',
            'descrição' => 'required',
            'titulo' => 'required',
            'img' => 'nullable',
            'detalhamento' => 'nullable',
            'nome_contato' => 'required',
            'contato' => 'required',
            'local' => 'required|string|in:S,P',
            'prioridade' => 'required|string|in:1,5'
        ]);
        
        

        $chamado = Chamados::create([
            'cliente_id' => $validatedData['cliente_id'],
            'cliente' => $validatedData['cliente'],
            'tipo' => $validatedData['tipo'],
            'status' => $validatedData['status'],
            'descrição' => $validatedData['descrição'],
            'titulo' => $validatedData['titulo'],
            //'img' => $imagePath,
            'nome_contato' => $validatedData['nome_contato'],
            'contato' => $validatedData['contato'],
            'local' => $validatedData['local'],
            'prioridade' => $validatedData['prioridade'],
        ]);

        if ($request->hasFile('img')) {
            $imagePaths = [];
        
            foreach ($request->file('img') as $file) {
                $originalFileName = $file->getClientOriginalName(); 
                $imagePath = $file->store('chamados_images', 'public');

                $imagePaths[] = [
                    'original_name' => $originalFileName,
                    'url' => $imagePath
                ];

                $chamado->update([

                    'img' => $imagePaths

                ]);
            }
        } else {
            $imagePaths = [];
        };
    
        // Obtenha o código do cliente a partir do formulário
        $codigoCliente = $request->input('codigo');
    
        // Encontre o cliente associado ao código (supondo que você tenha um modelo Cliente)
        $cliente = Cliente::where('codigo', $codigoCliente)->first();
    
        if ($cliente) {
            // Associe o cliente ao chamado
            $chamado->clientes()->associate($cliente);
            $chamado->status = 1;
            $chamado->save();
        } else {
            return $this->error('Cliente não encontrado!', 404);
        }

        
        if($chamado){
            return redirect('/')->with('msg', 'Chamado criado com sucesso!');
        }

        return redirect('/meus_chamados')->with('msg', 'Chamado não cadastrado!');

    }

    public function getClienteInfo($codigo)
    {
        // Faça a consulta ao banco de dados usando o código do cliente
        $cliente = Cliente::where('codigo', $codigo)->first();
    
        if ($cliente) { 
            // Se o cliente for encontrado, retorne as informações em formato JSON
            return response()->json([

                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'telefone1' => $cliente->telefone1,
                'telefone2' => $cliente->telefone2,
                'codigo' => $cliente->codigo,
                'img_cliente' => $cliente->img_cliente
                
            ]);
        }
    
        // Se o cliente não for encontrado, retorne uma resposta vazia ou uma mensagem de erro
        return response()->json(['error' => 'Cliente não encontrado'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Chamados $chamados)
    {
        return new ChamadosResource($chamados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chamado = Chamados::findOrFail($id);
        return view('/chamados.edit', ['chamado' => $chamado]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function editarChamados(Request $request, Chamados $chamado)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(),[
            'titulo_edit' => 'required|string',
            'cliente_edit' => 'required|string',
            'nome_contato_edit' => 'required|string',
            'contato_edit' => 'required|string',
            'prioridade_edit' => 'required|in:1,5',
            'local_edit' => 'required|in:S,P',
            'status_edit' => 'required|in:0,1',
            'descrição_edit' => 'required|string',
            'img_edit' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails())
        {
            return $this->error('Erro', 422, $validator->errors());
        }

        $chamado->update([
            'user_id' => $request->input('user_id'),
            'titulo' => $request->input('titulo_edit'),
            'cliente' => $request->input('cliente_edit'),
            'nome_contato' => $request->input('nome_contato_edit'),
            'contato' => $request->input('contato_edit'),
            'prioridade' => $request->input('prioridade_edit'),
            'local' => $request->input('local_edit'),
            'status' => $request->input('status_edit'),
            'descrição' => $request->input('descrição_edit'),
        ]);

        // Se uma nova imagem foi fornecida, atualizar a imagem
        
        if ($request->hasFile('img_edit')) {
            // Lidar com o upload da nova imagem
            $imagePath = $request->file('img_edit')->store('chamados_images', 'public');

            // Remover a imagem antiga (se existir)
            if ($chamado->img) {
                Storage::disk('public')->delete($chamado->img);
            }

            // Atualizar o campo img_cliente
            $chamado->update(['img' => $imagePath]);
        }

        return redirect('/')->with('msg', 'Chamado editado com sucesso!');
    }

    
    public function editChamado(Request $request, Chamados $chamado)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'detalhamento' => 'required|string',
        ]);

        if (!$chamado) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        // Atualize os campos com os dados validados
        $chamado->update($validatedData);

        return response()->json(['message' => 'Chamado editado com sucesso']);

}

    public function contarChamados(){

        $totalChamados = Chamados::count();
        return response()->json(['total_chamados' => $totalChamados]);

    }


    public function getPaginatedChamados(Request $request) {
        $chamados = Chamados::paginate(1);
        return response()->json($chamados);
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $chamado = Chamados::find($id);
    
    if ($chamado) {
        $chamado->delete();
        return response()->json(['message' => 'Chamado excluído com sucesso!']);
    } else {
        return response()->json(['message' => 'Chamado não encontrado.']);
    }
}


    public function assumirChamado($id)
    {
        // Obtém o usuário autenticado
        $user = auth()->user();

        $user->pegarChamado()->attach($id);

        // Obtém o chamado pelo ID
        $chamado = Chamados::findOrFail($id);

        // Verifica se o chamado já foi assumido por outro usuário
        if ($chamado->user_id !== null) {
            return redirect('/')->with('errormsg', 'Chamado já em atendimento!');
        }

        $chamado->update([
            'tipo' => 'E',
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);
        
        $chamado->save();

        return response()->json(['message' => 'CHAMADO ASSUMIDO COM SUCESSO!']);
    }

    public function leaveChamado($id){

        $user = auth()->user();

        $user->pegarChamado()->detach($id);

        $chamado = Chamados::findOrFail($id);

        $chamado->user_id = null;
        $chamado->tipo = 'N';
        $chamado->save();

        return response()->json(['message' => 'Chamado devolvido com sucesso']);
    }
    
    public function finalizarChamado($id)
    {
        $user = auth()->user();

        $chamado = Chamados::findOrfail($id);

        $chamado->status = 0;
        $chamado->tipo = 'F';
        $chamado->solucao = $chamado->detalhamento;
        $chamado->data_solucao = now();
        $chamado->save();

        return response()->json(['message' => 'Chamado finalizado com sucesso']);
    }

    public function ReabrirChamado($id)
    {
        $user = auth()->user();

        $chamado = Chamados::findOrfail($id);

        $chamado->status = 1;
        $chamado->tipo ='E';
        $chamado->save();

        return response()->json(['message' => 'Chamado reaberto com sucesso']);
    }

    public function desabilitarChamados($id)
    {
        $user = auth()->user();

        $chamado = Chamados::findOrfail($id);

        $chamado->status = 0;
        $chamado->tipo = 'D';
        $chamado->user_id = null;
        $chamado->save();

        return redirect('/')->with('msg', 'Chamado desativado com sucesso!');
    }

    public function reabilitarChamados($id)
    {
        $user = auth()->user();

        $chamado = Chamados::findOrfail($id);

        $chamado->status = 1;
        $chamado->tipo = 'N';
        $chamado->save();

        return redirect('/')->with('msg', 'Chamado Reaberto com sucesso!');
        
    }

}