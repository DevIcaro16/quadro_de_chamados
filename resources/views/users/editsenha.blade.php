@extends('layouts.main')

@section('title', 'Editar Usuário')

@section('content')


<div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Redefinir Senha
                </h2>
                <div class="text-secondary mt-1">Usuário em edição: {{$user->name}}</div>
              </div>
            </div>
          </div>
    </div>

    <div class="page-body">
        
        <form action="{{ route('users.updatesenha', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="password" class="form-label">Nova Senha:</label>
                <input type="password" name="password" class="form-control">
            </div>
            <label class="form-label">Confirmar Senha</label>
              <div class="input-group input-group-flat"> 
                <input id="password-confirm" type="password" class="form-control"  placeholder=""  type="text" name="password_confirmation" required>
                <span class="input-group-text">
                  <a href="" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                  </a>
                </span>
              </div>
              <br>
              <button type="submit" class="btn btn-primary">Salvar Senha</button>
        </form>
    </div>

@endsection