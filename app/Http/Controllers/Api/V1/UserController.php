<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponses;

class UserController extends Controller
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
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', ['user' => $user]);
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
        'area' => 'required|string|in:programação,suporte',
        'nivel' => 'required|string|in:1,2',
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
        'area' => $request->input('area'),
        'nivel' => $request->input('nivel'),
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

    return redirect('/users')->with('msg', 'Atualizados com sucesso');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect('/users')->with('errormsg', 'usuário excluido com sucesso!');
    }
}
