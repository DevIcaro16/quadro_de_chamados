@extends('layouts.main')

@section('title' , 'Lista de Clientes')

@section('content')

<style>
      /* Estilo para ocultar o campo de seleção de arquivo padrão */
      .custom-file input[type="file"] {
    display: none;
}

/* Estilo para o botão personalizado */
.custom-file-label {
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    content: 'Atualizar Imagem'; /* Adicionado o texto diretamente aqui */
}

.custom-file input[type="file"]:focus + .custom-file-label {
    border-color: black;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
    </style>

    <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Edição de Usuário ({{$user->id}})
                </h2>
                <!-- <div class="text-secondary mt-1">Usuário em edição: {{$user->name}} ID: {{$user->id}} </div> -->
              </div>
            </div>
          </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
              <div class="card-header">
                <div class="col">
                    <h3 class="card-title">Usuário em edição:</h3>
                    <div class="text-secondary mt-1"> {{$user->name}}</div>
                </div>
              </div>

              <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              <div class="card-body">
                        <div class="datagrid">
                        
                            <div class="mb-3">
                                <h3 class="form-label">Imagem do usuário:</h3>
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img id="userImage" src="{{ asset('storage/' . $user->userimage) }}" class="avatar avatar-xl"></img>
                                    </div>

                                    <div class="col-auto">
                                        <div class="md-3">
                                            <div class="custom-file">
                                                <label for="userimage" class="custom-file-label btn">Editar imagem</label>
                                                <input name="userimage" id="userimage" class="custom-file-input" type="file" onchange="exibirNovaImagem(this)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo:</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="firstName" class="form-label">Primeiro Nome:</label>
                            <input type="text" name="firstName" class="form-control" value="{{ $user->firstName }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="lastName" class="form-label">Último nome:</label>
                            <input type="text" name="lastName" class="form-control" value="{{ $user->lastName }}" required>
                        </div>

                        <div class="datagrid-item">
                            <div class="datagrid-title">

                            </div>
                            <div class="datagrid-content">

                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="area" class="form-label">Área:</label>
                            <select name="area" class="form-select" required>
                                <option value="programação" {{ $user->area === 'programação' ? 'selected' : '' }}>Programação</option>
                                <option value="suporte" {{ $user->area === 'suporte' ? 'selected' : '' }}>Suporte</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nivel" class="form-label">Nível:</label>
                            <select name="nivel" class="form-select" required>
                                <option value="1" {{ $user->nivel === 'user' ? 'selected' : '' }}>Técnico</option>
                                <option value="2" {{ $user->nivel === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                  
                    </div>

                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                
                </form>
          </div>
        </div>
    </div>
        
@endsection

<script>

    function exibirNovaImagem(input) {

    if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
        var userImage = document.getElementById('userImage');
        userImage.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
    }
    }

</script>
