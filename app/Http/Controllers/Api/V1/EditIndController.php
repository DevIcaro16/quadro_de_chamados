<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditIndController extends Controller
{
    use HttpResponses;

    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
{
    $user = Auth::user();
    return view('perfil', ['user' => $user]);
}   


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
{
    $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            Rule::unique('users')->ignore($user->id),
        ],
        'password' => 'nullable|string|min:6|confirmed',
        'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return $this->error('Validation failed', 422, $validator->errors());
    }

    // Atualizar os campos do usuário
    $user->update([
        'name' => $request->input('name'),
        'firstName' => $request->input('firstName'),
        'lastName' => $request->input('lastName'),
        'email' => $request->input('email'),
    ]);

    // Se uma nova imagem foi fornecida, atualizar a imagem
    if ($request->hasFile('userimage')) {
        // Lidar com o upload da nova imagem
        $imagePath = $request->file('userimage')->store('user_images', 'public');

        // Remover a imagem antiga (se existir)
        if ($user->userimage) {
            Storage::disk('public')->delete($user->userimage);
        }

        // Atualizar o campo userimage
        $user->update(['userimage' => $imagePath]);
    }

    return redirect('/perfil')->with('success', 'Atualizados com sucesso');
}

public function removeImage()
    {
        $user = Auth::user();

        // Remova a imagem existente (se existir)
        if ($user->userimage) {
            // Lide com a lógica de remoção da imagem do armazenamento aqui
            Storage::disk('public')->delete($user->userimage);

            // Atualize o campo userimage no banco de dados
            $user->update(['userimage' => null]);
        }

        // Redirecione de volta à página de perfil (ou para onde você preferir)
        return redirect()->route('users.editindividual')->with('success', 'Imagem removida com sucesso');
    }

    public function redefinirSenha(Request $request)
    {
        
        $user = Auth::user();

    $validator = Validator::make($request->all(), [
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return $this->error('Validation failed', 422, $validator->errors());
    }

   
    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->input('password'))]);
    }

 
    return view('/perfil')->with('success', 'Senha redefinida com sucesso');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
