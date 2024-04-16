@extends('layouts.main')

        @section('title' , 'Meu Perfil')

        @section('content')

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Usuários
                </h2>
                <div class="text-secondary mt-1">Computex</div>
              </div>
            </div>
          </div>
        </div>

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

    <script src="./js/demo-theme.js"></script>    
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="card">
              <div class="row g-0">
                <div class="col-12 col-md-3 border-end">
                  <div class="card-body">
                    <h4 class="subheader">Opções</h4>
                    <div class="list-group list-group-transparent">
                      <a href="./perfil" class="list-group-item list-group-item-action d-flex align-items-center active">Minha Conta</a>
                      <!-- @if(auth()->check() && auth()->user()->nivel >= 2)
                      <a href="/users" class="list-group-item list-group-item-action d-flex align-items-center">Ver Usuários</a>
                      @endif
                      @if(auth()->check() && auth()->user()->nivel >= 2)
                      <a href="/clientes" class="list-group-item list-group-item-action d-flex align-items-center">Ver Clientes</a>
                      @endif -->
                      <!-- <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">Connected Apps</a>
                      <a href="./settings-plan.html" class="list-group-item list-group-item-action d-flex align-items-center">Plans</a>
                      <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">Billing & Invoices</a> -->
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-9 d-flex flex-column">
                  <div class="card-body">
                    <form action="{{ route('users.updateindividual') }}" method="POST" enctype="multipart/form-data">

                    @csrf 
                    @method('PUT')

                    <h2 class="mb-4">Meu Perfil</h2>
                    <h3 class="card-title">Imagem de perfil</h3>
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <img id="userImage" src="{{ asset('../storage/app/public/' . Auth::user()->userimage) }}" class="avatar avatar-xl"></img>
                      </div>
                      <div class="col-auto">
                        <div class="custom-file">
                        <label for="userimage" class="custom-file-label btn">Editar imagem</label>
                        <input name="userimage" id="userimage" class="custom-file-input" type="file" onchange="exibirNovaImagem(this)">
                        </div>
                      </div>
                      <!-- <div class="col-auto">
                        <button type="submit" name="remove_image" class="btn btn-ghost-danger">
                          Remover imagem
                      </button>
                    </div> -->
                    </div>
                    <h3 class="card-title mt-4">Informações Gerais</h3>
                    <div class="row g-3">
                      <div class="col-md">
                        <div class="form-label">Nome</div>
                        <input type="text" id="nameInput" name="name" class="form-control" value="{{ auth()->user()->name }}" disabled>
                      </div>
                      <div class="col-md">
                        <div class="form-label">Primeiro Nome</div>
                        <input type="text" id="firstNameInput" class="form-control" name="firstName" value="{{ auth()->user()->firstName }}" disabled>
                      </div>
                      <div class="col-md">
                        <div class="form-label">Último nome</div>
                        <input type="text" class="form-control" name="lastName" id="lastNameInput"
			       value="{{ auth()->user()->lastName }}" disabled>
                      </div>
                    </div>
                    <h3 class="card-title mt-4">Email</h3>
                    <p class="card-subtitle"></p>
                    <div>
                      <div class="row g-2">
                        <div class="col-auto">
                          <input type="text" id="emailInput" name="email" class="form-control w-auto" value="{{ auth()->user()->email }}" disabled>
                        </div>
                        <!-- <div class="col-auto"><a href="#" class="btn">
                            Change
                          </a></div> -->
                      </div>
                    </div>
                    <h3 class="card-title mt-4">Senha</h3>
                    <p class="card-subtitle">Sua senha pode ser redefinida clicando no botão abaixo</p>
                    <div>
                      <button type="button" class="btn" data-toggle="modal" data-target="#redefinirSenhaModal">
                        Redefinir Senha
                      </button>
                    </div>
                    <br>
                    <div class="card-footer bg-transparent mt-auto">
                    <div class="btn-list justify-content-end">
                      <a id="AtualizarBtn" class="btn" onclick="habilitarCampos()">
                        Atualizar Informações
                      </a>
                      <a type="button" id="cancelarBtn" class="btn btn-danger" onclick="desabilitarCampos()" style="display: none;">
                        Cancelar
                      </a>
                      <input type="submit" value="Salvar" id="salvarBtn" class="btn btn-primary" style="display: none;">
                    </div>
                  </div>
                    </form>
                  </div>
                </div> 
              </div>
            </div>
          </div>
        </div>

        

        <!-- Modal -->
        <div class="modal fade" id="redefinirSenhaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Redefinir Senha</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('users.redefinirsenha') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-label">
                                <label for="password">Nova Senha</label>
                                <div>
                                <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="form-label">
                                <label for="password-confirm">Confirmar Senha</label>
                                <div>
                                <input id="password-confirm" type="password" class="form-control"  placeholder=""  type="text" name="password_confirmation" required>
                                </div>
                            </div>  
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Salvar Senha</button>
                            </div>
                          </form>
                    </div>
                </div>
            </div>
        </div>

@endsection

<script>

function desabilitarCampos(){
        document.getElementById('nameInput').setAttribute('disabled', 'true');
        document.getElementById('firstNameInput').setAttribute('disabled', 'true');
        document.getElementById('lastNameInput').setAttribute('disabled', 'true');
        document.getElementById('emailInput').setAttribute('disabled', 'true');

        document.getElementById('cancelarBtn').style.display = 'none';
        document.getElementById('salvarBtn').style.display = 'none';
        document.getElementById('AtualizarBtn').style.display = 'block';

        var userImage = document.getElementById('userImage');
        var userimageInput = document.getElementById('userimage');
        var cancelarBtn = document.getElementById('cancelarBtn');

        // Reverter a exibição da imagem para a imagem anterior
        userImage.src = "{{ asset('storage/' . Auth::user()->userimage) }}";

        userimageInput.value = "";

        cancelarBtn.style.display = 'none';
}

function habilitarCampos() {
        // Seleciona os inputs pelos IDs e remove o atributo 'disabled'
        document.getElementById('nameInput').removeAttribute('disabled');
        document.getElementById('firstNameInput').removeAttribute('disabled');
        document.getElementById('lastNameInput').removeAttribute('disabled');
        document.getElementById('emailInput').removeAttribute('disabled');

        document.getElementById('cancelarBtn').style.display = 'block';
        document.getElementById('salvarBtn').style.display = 'block';
        document.getElementById('AtualizarBtn').style.display = 'none';
    }

    function exibirNovaImagem(input) {

        document.getElementById('nameInput').removeAttribute('disabled');
        document.getElementById('firstNameInput').removeAttribute('disabled');
        document.getElementById('lastNameInput').removeAttribute('disabled');
        document.getElementById('emailInput').removeAttribute('disabled');

        document.getElementById('cancelarBtn').style.display = 'block';
        document.getElementById('salvarBtn').style.display = 'block';
        document.getElementById('AtualizarBtn').style.display = 'none';

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