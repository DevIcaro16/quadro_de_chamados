@extends('layouts.main')

@section('title', 'Lista dos Usuários')

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

.table-custom-width{
        width: 880px;
    }
    </style>

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
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                  <input type="search" id="search" name="search" class="form-control d-inline-block w-9 me-3" placeholder="Buscar usuário...">
                  <button href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CadUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Novo usuário
                </button>
                </div>
              </div>
            </div>
          </div>
        </div>

<!-- Page body -->
<div class="page-body">
          <div class="container-xl">
            <div class="card">
              <div class="row g-0">
                <div class="col-12 col-md-2 border-end">
                  <div class="card-body">
                    <h4 class="subheader">Opções</h4>
                    <div class="list-group list-group-transparent">
                      <!-- <a href="/perfil" class="list-group-item list-group-item-action d-flex align-items-center">Minha Conta</a> -->
                      @if(auth()->check() && auth()->user()->nivel >= 2)
                      <a href="./clientes" class="list-group-item list-group-item-action d-flex align-items-center">Ver Clientes</a>
                      @endif
                      @if(auth()->check() && auth()->user()->nivel >= 2)
                      <a href="./users" class="list-group-item list-group-item-action d-flex align-items-center">Ver Usuários</a>
                      @endif
                      <!-- <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">Connected Apps</a>
                      <a href="./settings-plan.html" class="list-group-item list-group-item-action d-flex align-items-center">Plans</a>
                      <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">Billing & Invoices</a> -->
                    </div>
                  </div>
                </div>
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card table-custom-width">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter table-mobile-md card-table">
                                            <thead>
                                                <tr>
                                                    <th>Imagem</th>
                                                    <th>Nome</th>
                                                    <th>Email</th>
                                                    <th>Senha</th>
                                                    <th>Setor</th>
                                                    <th>Ações</th>
                                                    <th class="w-1"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-table">
                                                <!-- Os dados da tabela serão preenchidos dinamicamente aqui -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
            </div>
          </div>
        </div>



<!-- Janela Modal para realizar o Cadastro : -->
<div class="modal fade" id="CadUserModal" tabindex="-1" aria-labelledby="CadUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CadUserModalLabel">Novo Cadastro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" autocomplete="off" novalidate>
                    @csrf
                    <span id="msgAlertaErro"></span>

                    <!-- Botão Adicionar Imagem e Nome -->

                            <div class="col">
                                <h3 class="form-label">Imagem de perfil:</h3>
                                <div class="d-flex align-items-center">
                                    <div class="col-auto">
                                        <div id="img-container">
                                            <img src="{{ asset('storage/img_cliente/img_padrao.png') }}" alt="" id="usuarioImage" style="max-width: 100px" class="avatar avatar-xl">
                                        </div>
                                    </div>
                                    <div class="col-auto mx-3">
                                        <div class="custom-file">
                                            <label for="userimage" class="custom-file-label btn">Escolher imagem</label>
                                            <input type="file" name="userimage" id="userimage" class="form-control" onchange="exibirNovaImagem(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                    <div class="mb-3">
                        <div class="row">
                            
                            <div class="col">
                                <label class="form-label" value="{{ __('Name') }}">Nome</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            </div>
                        </div>
                    </div>

                    <!-- Primeiro Nome -->
                    <div class="mb-3">
                      <div class="row">
                        <div class="col">
                          <label class="form-label">Primeiro Nome</label>
                          <input id="firstName" type="text" class="form-control" name="firstName" value="{{ old('firstName') }}" required autofocus autocomplete="firstName">
                        </div>

                        <!-- Último Nome -->
                        <div class="col">
                            <label class="form-label">Último Nome</label>
                            <input id="lastName" type="text" class="form-control" name="lastName" value="{{ old('lastName') }}" required autofocus autocomplete="lastName">
                        </div>
                      </div>
                    </div>

                    

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" placeholder="" type="email" name="email" :value="{{ old('email') }}" required>
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <div class="input-group input-group-flat">
                            <input id="password" type="password" class="form-control" placeholder="" type="password" name="password" required>
                        </div>
                    </div>

                    <!-- Confirmar Senha -->
                    <label class="form-label">Confirmar Senha</label>
                    <div class="input-group input-group-flat">
                        <input id="password-confirm" type="password" class="form-control" placeholder="" type="password" name="password_confirmation" required>
                        <span class="input-group-text">
                            <a href="" class="link-secondary" title="Show password" data-bs-toggle="tooltip"></a>
                        </span>
                    </div>
                    <br>
                    <!-- Área de Trabalho -->
                    <div class="mb-3">
                      <div class="row">
                        <div class="col">
                        <label class="form-label">Área de trabalho:</label>
                          <select class="form-select" name="area">
                              <option value="programação">Programação</option>
                              <option value="suporte">Suporte</option>
                          </select>
                        </div>

                        <!-- Nível de Acesso -->
                        <div class="col">
                            <label class="form-label">Nível de acesso:</label>
                            <select class="form-select" name="nivel">
                                <option value="1">Normal</option>
                                <option value="2">Administrador</option>
                            </select>
                        </div>
                      </div>  
                    </div>

                    <!-- Rodapé da Modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success" id="cad-usuario-btn" value="">{{ __('Criar conta') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="EditSenhaModal" tabindex="-1" role="dialog" aria-labelledby="EditSenhaModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditSenhaModal">Redefinir Senha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        </button>
                    </div>
                    <div class="modal-body">

                            <div class="form-label">
                                <label for="password_edit">Nova Senha</label>
                                <div>
                                <input type="password" class="form-control" id="password_edit" name="password_edit" required>
                                </div>
                            </div>
                            <div class="form-label">
                                <label for="password-confirmation">Confirmar Senha</label>
                                <div>
                                <input id="password-confirm" type="password" class="form-control"  placeholder=""  type="text" name="password_confirmation" required>
                                </div>
                            </div>  
                            <div class="modal-footer">
                            <button type="button" id="btnAtualizarSenha" class="btn btn-primary">Salvar Senha</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>


<script>

// Função para realizar as validações do form da janela modal da criação de users:



  function validarFormUsers() {


// Obtenha os valores dos campos
var img = document.getElementById('userimage').value;
var name = document.getElementById('name').value;
var firstName = document.getElementById('firstName').value;
var lastName = document.getElementById('lastName').value;
var email = document.getElementById('email').value;
var password = document.getElementById('password').value;
var passwordConfirm = document.getElementById('password-confirm').value;



// Verifique se os campos estão vazios
if (img === '') {
    exibirMensagemErro('Preencha o campo Imagem!');
    return false;
}

if (name === '') {
    exibirMensagemErro('Preencha o campo Nome!');
    return false;
}

if (firstName === '') {
    exibirMensagemErro('Preencha o campo Primeiro Nome!');
    return false;
}

if (lastName === '') {
    exibirMensagemErro('Preencha o campo Último Nome!');
    return false;
}
if (email === '') {
    exibirMensagemErro('Preencha o campo Email!');
    return false;
}
if (password === '') {
    exibirMensagemErro('Preencha o campo Senha!');
    return false;
}
if (passwordConfirm === '') {
    exibirMensagemErro('Preencha o campo Confirmar Senha!');
    return false;
}

if(password != passwordConfirm){
    exibirMensagemErro('As Senhas não são iguais, Preencha novamente!');
    return false;
}

// Adicione outras validações aqui, se necessário

// Se todas as validações passarem, retorne true para permitir o envio do formulário
return true;
}

// Função para exibir mensagem de erro
function exibirMensagemErro(mensagem) {
var msgAlertaErro = document.getElementById('msgAlertaErro');
msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro: " + mensagem + "</div>";
}

// Adicione um evento de escuta ao formulário para chamar a função de validação antes do envio
document.getElementById('CadUserModal').addEventListener('submit', function (event) {
if (!validarFormUsers()) {
    // Impedir o envio do formulário se a validação falhar
    event.preventDefault();
}
});


</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

function renderData(data) {
    const dataTable = document.getElementById('data-table');
    dataTable.innerHTML = ''; // Limpa a tabela atual

    data.forEach(item => {
        const row = document.createElement('tr');

        const imgCell = document.createElement('td'); // Crie a célula da imagem
        imgCell.id = 'img-container'; // Defina o ID para 'img-container'
        imgCell.innerHTML = `<img src="./storage/${item.userimage}" alt="">`; // Defina o conteúdo da célula
        row.innerHTML = `
            <td>${imgCell.innerHTML}</td>
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>
                <div class="btn-list flex-nowrap">
                <button class="btn btn-editarsenha-usuario" href="#" data-bs-toggle="modal" data-bs-target="#EditSenhaModal" data-user-id="${item.id}">
                        Alterar senha
                    </button>
                </div>
            </td>
            <td>${item.area}</td>
            <td>
                <div class="btn-list flex-nowrap">
                    <button href="#" class="btn" onclick="editUser(event, ${item.id})">
                        Editar
                    </button>
                    <button href="#" class="btn" onclick="confirmDelete('${item.id}')">
                        Excluir
                    </button>
                </div>
            </td>
        `;
        dataTable.appendChild(row);
    });

    console.log("Atualizou!");
}

function fetchDataAndRender() {
    fetch('./api/v1/users')
        .then(response => response.json())
        .then(data => {
            const reversedData = data.data.reverse(); // Inverte a ordem dos itens da API
            renderData(reversedData); // Renderiza os dados na tabela
            console.log("Atualizou!");
        })
        .catch(error => {
            console.error('Ocorreu um erro ao buscar os dados da API:', error);
        });
}

document.addEventListener('click', function (event) {
    // Verifica se o clique ocorreu em um botão de "Editar user"
    if (event.target.classList.contains('btn-editarsenha-usuario')) {
        // Obtém o ID do user associado ao botão clicado
        var userId = event.target.getAttribute('data-user-id');
    
        // Agora, você pode usar o ID para obter os detalhes do user correspondente do banco de dados
        // e exibi-los no modal, por exemplo, fazendo uma requisição AJAX
        // ou atualizando os elementos do modal diretamente com os dados já carregados na página.
        
        // Exemplo de requisição AJAX usando Fetch API:
        fetch(`./api/v1/users/${userId}`)
            .then(response => response.json())
            .then(user => {
                // Agora você pode usar os dados do user para preencher 
                preencherEditModal(user);
            })
            .catch(error => console.error('Erro ao obter detalhes do usuario:', error));
    }
    });
    
    function preencherEditModal(user) {
    console.log('Dados do usuario:', user);
    
    var userId = user.data.id;
    
    console.log(userId);
    
    document.getElementById('btnAtualizarSenha').addEventListener('click', function(){
        console.log('Editando usuario com ID:', userId);
    
        // Coletar os dados que você deseja atualizar do formulário (por exemplo, detalhamento)
        const password_edit = document.getElementById('password_edit').value;
    
        // Criar um objeto com os dados a serem atualizados
        const data = {
            password_edit: password_edit,
            // Adicione outros campos que deseja atualizar aqui
        };
    
        fetch(`/editSenha/${userId}`,{
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(data) // Enviar os dados como JSON no corpo da solicitação
        })
        .then(response => response.json())
        .then(data => {
            // Lógica após a devolução bem-sucedida, se necessário
            console.log('Senha alterada com sucesso:', data);
    
            $('#EditSenhaModal').modal('hide');
    
        })
        .catch(error => {
            console.error('Erro ao alterar Senha:', error);
            // Lógica para lidar com erros, se necessário
        });
    });
}

function editUser(event, userId) {
    event.preventDefault();
    const editUserRoute = `./users/${userId}/edit`;
    window.location.href = editUserRoute;
}

// function editSenha(event, userId) {
//     event.preventDefault();
//     const editSenhaRoute = `http://172.16.0.101:8000/users/${userId}/editsenha`;
//     window.location.href = editSenhaRoute;
// }

function confirmDelete(userId) {
    const url = `./users/${userId}`;
    const csrfToken = '{{ csrf_token() }}';
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => {
                // Lide com a resposta conforme necessário
                if (response.ok) {
                    // Se a exclusão for bem-sucedida, você pode redirecionar para outra página ou fazer outras ações
                    console.log('Usuário excluído com sucesso!');
                } else {
                    console.error('Erro ao excluir usuário:', response.statusText);
                }
            })
            .catch(error => {
                console.error('Erro na solicitação DELETE:', error);
            });
    }
}

var isSearching = false;

// Função para buscar dados periodicamente
function fetchPeriodically() {
    if (isSearching) { // Verifica se a pesquisa não está ativa
        fetchDataAndRender();
    }
}

// Inicia a busca periódica com setInterval (atualiza a cada 5 segundos)
setInterval(fetchPeriodically, 5000);

// Inicializa a renderização de dados
fetchDataAndRender();

// Função para aplicar filtro e renderizar os dados
function applyFilterAndRender() {
    isSearching = true; // A pesquisa está ativa
    var searchTerm = $('#search').val().toLowerCase(); // Obtém o valor da pesquisa em letras minúsculas

    fetch('./api/v1/users')
        .then(response => response.json())
        .then(data => {
            const filteredData = data.data.filter(function (item) {
                return (
                    (item.name && item.name.toLowerCase().includes(searchTerm)) ||
                    (item.email && item.email.toLowerCase().includes(searchTerm)) ||
                    (item.area && item.area.toLowerCase().includes(searchTerm))
                );
            });

            renderData(filteredData);

            // Verifica se a pesquisa não retornou resultados e exibe a mensagem apropriada
            if (filteredData.length === 0) {
                showNoDataMessage();
            }

            isSearching = false;
        })
        .catch(error => {
            console.error('Ocorreu um erro ao buscar os dados da API:', error);
            isSearching = false; // Certifique-se de desativar a pesquisa em caso de erro
        });
}

// Configura o evento de pesquisa quando o usuário digita no campo de pesquisa
$('#search').on('keyup', applyFilterAndRender);


// Função para exibir a mensagem de "Não há chamados!"
function showNoDataMessage() {
    const dataTable = document.getElementById('data-table');
    dataTable.innerHTML = '<tr><td colspan="9">Não há Usuários!</td></tr>';
}

function exibirNovaImagem(input) {

        

if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
        var usuarioImage = document.getElementById('usuarioImage');
        usuarioImage.src = e.target.result;
};

reader.readAsDataURL(input.files[0]);
}
}
</script>

@endsection
