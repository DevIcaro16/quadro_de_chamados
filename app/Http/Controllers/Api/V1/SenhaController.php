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

class SenhaController extends Controller
{
    use HttpResponses;

    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function ShowRedefinirForm()
    {
        return view('/users');
    }

    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('users.editsenha', ['user' => $user]);
}

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return $this->error('Validation failed', 422, $validator->errors());
    }

    $user->update([
        'password' => $request->input('password'),
    ]);

    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->input('password'))]);
    }

    return redirect('/users')->with('success', 'Senha redefinida com sucesso');
}

public function editSenhaUser(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'password_edit' => 'nullable|string|min:6|',
    ]);

    if ($validator->fails()) {
        return $this->error('Validation failed', 422, $validator->errors());
    }

    if ($request->filled('password_edit')) {
        $user->update(['password' => Hash::make($request->input('password_edit'))]);
    }

    return response()->json(['message' => 'Senha redefinida com sucesso!']);
}

}
