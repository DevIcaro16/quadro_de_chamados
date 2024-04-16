<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ClientesResource;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    Use HttpResponses;

    public function index ()
    {

        
        return ClientesResource::collection(Cliente::all());
    }

    public function show (Cliente $cliente)
    {
        return new ClientesResource($cliente);
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', ['cliente' => $cliente]);
    }


    public function getClienteId(Cliente $cliente)
{
    return response()->json(['clienteId' => $cliente->id]);
}


    public function register_clientes(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('clientes')],
            'codigo' => 'required|string|max:255',
            'telefone1' => 'required|string|max:255',
            'telefone2' => 'required|string|max:255',
            'apelido' => 'required|string|max:255',
            'fantasia' => 'required|string|max:255',
            'cnpj' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MS,MT,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'img_cliente' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Lidar com o upload da imagem
        if ($request->hasFile('img_cliente')) {
            $imagePath = $request->file('img_cliente')->store('img_cliente', 'public');
        } else {
            $imagePath = null; // Caso o usuário não tenha enviado uma imagem
        }

        
    
          // Verificar se os dados são únicos
            $isUnique = Cliente::where('nome', $validatedData['nome'])
            ->orWhere('nome', $validatedData['nome'])
            ->doesntExist();

        if (!$isUnique) {
        return redirect('/clientes')->with('errormsg', 'Nome de cliente já cadastrado.');
        }

    
        $cliente = Cliente::create([
            'nome' => $validatedData['nome'],
            'codigo' => $validatedData['codigo'],
            'telefone1' => $validatedData['telefone1'],
            'telefone2' => $validatedData['telefone2'],
            'apelido' => $validatedData['apelido'],
            'fantasia' => $validatedData['fantasia'],
            'cnpj' => $validatedData['cnpj'],
            'endereco' => $validatedData['endereco'],
            'bairro' => $validatedData['bairro'],
            'cep' => $validatedData['cep'],
            'cidade' => $validatedData['cidade'],
            'uf' => $validatedData['uf'],
            'img_cliente' => $imagePath
        ]);

         
        if ($cliente) {
            return redirect('/clientes')->with('msg', 'Cliente criado com sucesso!');
        }
    
        return redirect('/clientes')->with('errormsg', 'Registros inválidos');
    }

    

    public function update (Request $request, Cliente $cliente)
    {
        
        $validator = Validator::make($request->all(), [
            'img_cliente' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nome_edit' => 'required', 'string', 'max:255',
            'codigo_edit' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }
    
        // Atualizar os campos do cliente
        $cliente->update([
            //'img_cliente' => $request->input('img_cliente_edit'),
            'nome' => $request->input('nome_edit'),
            'codigo' => $request->input('codigo_edit'),
            'telefone1' => $request->input('telefone1_edit'),
            'apelido' => $request->input('apelido_edit'),
            'fantasia' => $request->input('fantasia_edit'),
            'cnpj' => $request->input('cnpj_edit'),
            'endereco' => $request->input('endereco_edit'),
            'bairro' => $request->input('bairro_edit'),
            'telefone2' => $request->input('telefone2_edit'),
            'cep' => $request->input('cep_edit'),
            'cidade' => $request->input('cidade_edit'),
            'uf' => $request->input('uf_edit'),
        ]);

        if ($request->hasFile('img_cliente_edit')) {
            // Lidar com o upload da nova imagem
            $imagePath = $request->file('img_cliente_edit')->store('img_cliente', 'public');
    
            // Remover a imagem antiga (se existir)
            if ($cliente->img_cliente) {
                Storage::disk('public')->delete($cliente->img_cliente);
            }
        
            // Atualizar o campo img_cliente
            $cliente->update(['img_cliente' => $imagePath]);


        }

            return redirect('/clientes')->with('msg', 'Atualizados com sucesso');

        
        


        
    }

    public function destroy ($id)
    {
        Cliente::findOrFail($id)->delete();

        return redirect('/clientes')->with('errormsg', 'Cliente excluido com sucesso!');
    }

    public function showRegistrationForm()
    {
        return view('/lista-clientes');
    }
}