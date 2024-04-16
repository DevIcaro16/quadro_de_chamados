<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
{
    
    $credentials = $request->only('password');

    $loginField = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'email';
    $credentials[$loginField] = $request->input('email');

    if (Auth::attempt($credentials)) {
        $user = $request->user();

        // Verifica se o usuário já possui um session_id ativo
        if ($user->session_id !== null) {
            // Invalida a sessão existente (opcional)
            $user->session_id = null;
            $user->save();
        }

        // Gera um novo session_id
        $session_id = Str::uuid();
        $user->session_id = $session_id;
        $user->save();

        $token = $user->createToken('usuario')->plainTextToken;
        return redirect('/');
    }

    return redirect('/login')->with('errormsg', 'Credenciais Inválidas');
}




    public function register(Request $request){
        $validatedData = $request->validate([
            // 'name' => ['required', 'string', 'max:255', Rule::unique('users')],
            'name' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'userimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adicione regras para a imagem aqui
            'area' => 'required|string|in:programação,suporte',
            'nivel' => 'required|string|in:1,2'
        ]);
    
        // Lidar com o upload da imagem
        if ($request->hasFile('userimage')) {
            $imagePath = $request->file('userimage')->store('user_images', 'public');
        } else {
            $imagePath = null; // Caso o usuário não tenha enviado uma imagem
        }

          // Verificar se os dados são únicos
            $isUnique = User::where('email', $validatedData['email'])
            ->orWhere('email', $validatedData['email'])
            ->doesntExist();

        if (!$isUnique) {
        return redirect('/users')->with('errormsg', 'E-mail ou nome de usuário já cadastrado.');
        }

    
        $user = User::create([
            'name' => $validatedData['name'],
            'firstName' => $validatedData['firstName'],
            'lastName' => $validatedData['lastName'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'userimage' => $imagePath,
            'area' => $validatedData['area'],
            'nivel' => $validatedData['nivel'],
        ]);
    
        if ($user) {
            return redirect('/users')->with('msg', 'Usuário criado com sucesso!');
        }
    
        return redirect('/users')->with('errormsg', 'Registros inválidos');
    }
    

    public function logout(Request $request)
    {
        $user = Auth::user();

        $user->session_id = null;
        $user->save();

        $request->user()->tokens()->delete();

        Auth::logout();

        return redirect('/');

    }

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function showRegistrationForm()
    {
        return view('/lista-usuarios'); 
    }
}
