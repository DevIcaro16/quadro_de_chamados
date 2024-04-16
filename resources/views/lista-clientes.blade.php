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
                  Clientes
                </h2>
                <div class="text-secondary mt-1">Computex</div>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                  <input type="search" id="search" name="search" class="form-control d-inline-block w-9 me-3" placeholder="Buscar cliente...">
                  <button href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CadClienteModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Novo cliente
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
                <div class="col-30 col-md-2 border-end">
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
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter table-mobile-md card-table">
                                            <thead>
                                                <tr>
                                                    <th>Logo:</th>
                                                    <th>Código</th>
                                                    <th>Nome do Cliente</th>
                                                    <th>Telefone 01</th>
                                                    <th>Telefone 02</th>
                                                    <th class="w-1">Ações</th>
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
        <div class="modal fade" id="CadClienteModal" tabindex="-1" aria-labelledby="CadClienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CadClienteModalLabel">Novo Cadastro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('register_clientes') }}" method="POST" enctype="multipart/form-data" autocomplete="off" novalidate>
                            @csrf
                            <span id="msgAlertaErro"></span>

                            <div class="col">
                                <h3 class="form-label">Logo do cliente:</h3>
                                <div class="d-flex align-items-center">
                                    <div class="col-auto">
                                        <div id="img-container">
                                            <img src="{{ asset('storage/img_cliente/img_padrao.png') }}" alt="" id="clienteImage" style="max-width: 100px" class="avatar avatar-xl">
                                        </div>
                                    </div>
                                    <div class="col-auto mx-3">
                                        <div class="custom-file">
                                            <label for="img_cliente" class="custom-file-label btn">Escolher imagem</label>
                                            <input type="file" name="img_cliente" id="img_cliente" class="form-control" onchange="exibirNovaImagem(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <!-- <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="custom-file">
                                            <label for="img_cliente" class="custom-file-label btn">Logo do cliente</label>
                                            <input name="img_cliente" id="img_cliente" class="custom-file-input" type="file" required>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="codigo" class="form-label">Código:</label>
                                        <input type="number" name="codigo" id="codigo" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Nome:</label>
                                        <input id="nome" type="text" class="form-control" name="nome" oninput="toUppercase(this)" required autofocus autocomplete="name">
                                    </div>

                                    
                                    <script>
                                        function toUppercase(input) {
                                            input.value = input.value.toUpperCase();
                                        }
                                    </script>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="apelido" class="form-label">Apelido:</label>
                                        <input type="text" name="apelido" id="apelido" class="form-control" oninput="toUppercase(this)">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nome Fantasia:</label>
                                        <input id="fantasia" type="text" class="form-control" name="fantasia" oninput="toUppercase(this)" required autofocus autocomplete="fantasia">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">C.N.P.J:</label>
                                        <input id="cnpj" class="form-control" maxLength="14" type="text" name="cnpj" required>
                                    </div>
                                </div>
                            </div>


                            <div class="md-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">C.E.P:</label>
                                        <input id="cep" class="form-control" placeholder="" type="text" name="cep" required>
                                    </div>

                                    <script>
                                        document.addEventListener('input', function (event) {
                                        const input = event.target;

                                        if (input.id === 'cnpj') {
                                            const inputValue = input.value;
                                            

                                            if (unmaskedValue.length === 14) {
                                                const formattedValue = formatCNPJ(unmaskedValue);
                                                input.value = formattedValue;
                                            } else {
                                                input.value = inputValue;
                                            }
                                        }
                                    });

                                    function formatCNPJ(cnpj) {

                                        const formatted = cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                                        return formatted;
                                    
                                    }


                                    </script>

                                    <div class="col-md-8">
                                        <label class="form-label">Endereço:</label>
                                        <input id="endereco" class="form-control" placeholder="" type="text" name="endereco" required>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Bairro:</label>
                                        <input id="bairro" class="form-control" placeholder="" type="text" name="bairro" required>
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label">Cidade:</label>
                                        <input id="cidade" class="form-control" placeholder="" type="text" name="cidade" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tipo" class="form-label">UF:</label>
                                            <select name="uf" id="uf" class="form-control">
                                                <option value="AC">Acre</option>
                                                <option value="AL">Alagoas</option>
                                                <option value="AP">Amapá</option>
                                                <option value="AM">Amazonas</option>
                                                <option value="BA">Bahia</option>
                                                <option value="CE">Ceará</option>
                                                <option value="DF">Distrito Federal</option>
                                                <option value="ES">Espirito Santo</option>
                                                <option value="GO">Goiás</option>
                                                <option value="MA">Maranhão</option>
                                                <option value="MS">Mato Grosso do Sul</option>
                                                <option value="MT">Mato Grosso</option>
                                                <option value="MG">Minas Gerais</option>
                                                <option value="PA">Pará</option>
                                                <option value="PB">Paraíba</option>
                                                <option value="PR">Paraná</option>
                                                <option value="PE">Pernambuco</option>
                                                <option value="PI">Piauí</option>
                                                <option value="RJ">Rio de Janeiro</option>
                                                <option value="RN">Rio Grande do Norte</option>
                                                <option value="RS">Rio Grande do Sul</option>
                                                <option value="RO">Rondônia</option>
                                                <option value="RR">Roraima</option>
                                                <option value="SC">Santa Catarina</option>
                                                <option value="SP">São Paulo</option>
                                                <option value="SE">Sergipe</option>
                                                <option value="TO">Tocantins</option>
                                            </select>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label">Telefone 01:</label>
                                        <input id="telefone1" class="form-control" placeholder="" type="text" name="telefone1" required>
                                    </div>
                                    
                                    <div class="col">
                                        <label class="form-label">Telefone 02:</label>
                                        <input id="telefone2" class="form-control" placeholder="" type="text" name="telefone2" required>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('input', function (event) {
                                        const input = event.target;

                                        if (input.id === 'cnpj') {
                                            const inputValue = input.value;
                                            const unmaskedValue = inputValue.replace(/[^\d]/g, '');

                                            if (unmaskedValue.length === 14) {
                                                const formattedValue = formatCNPJ(unmaskedValue);
                                                input.value = formattedValue;
                                            } else {
                                                input.value = inputValue;
                                            }
                                        }
                                    });

                                    function formatCNPJ(cnpj) {

                                        const formatted = cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                                        return formatted;
                                    
                                    }
                            </script>
                    
                            <!-- Rodapé da Modal -->
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button> -->
                                <button type="submit" class="btn btn-success" id="cad-usuario-btn">{{ __('Cadastrar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Janela Modal para vizualizar informações dos clientes : -->
        <div class="modal fade" id="VizuClienteModal" tabindex="-1" aria-labelledby="VizuClienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="VizuClienteModal">Informações Gerais</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                            <div class="col">
                                <h3 class="form-label">Logo:</h3>
                                <div class="d-flex align-items-center">
                                    <div class="col-auto">
                                        <div id="img-container">
                                            <img alt="" id="clienteImage_vizu" style="max-width: 100px" class="avatar avatar-xl">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mx-3">
                                        <label for="codigo_vizu" class="form-label">Código:</label>
                                        <input type="number" name="codigo_viz" id="codigo_vizu" class="form-control" disabled>
                                    </div>

                                    <div class="col-md-7 mx-1">
                                        <label class="form-label">Nome:</label>
                                        <input id="nome_vizu" type="text" class="form-control" name="nome_vizu" disabled>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="codigo" class="form-label">Apelido:</label>
                                        <input type="text" name="apelido_vizu" id="apelido_vizu" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nome Fantasia:</label>
                                        <input id="fantasia_vizu" type="text" class="form-control" name="fantasia_vizu" disabled autofocus autocomplete="fantasia">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">C.N.P.J:</label>
                                        <input id="cnpj_vizu" class="form-control" maxLength="14" type="text" name="cnpj_vizu" disabled>
                                    </div>
                                </div>
                            </div>


                            <div class="md-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">C.E.P:</label>
                                        <input id="cep_vizu" class="form-control" placeholder="" type="text" name="cep_vizu" disabled>
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label">Endereço:</label>
                                        <input id="endereco_vizu" class="form-control" placeholder="" type="text" name="endereco_vizu" disabled>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Bairro:</label>
                                        <input id="bairro_vizu" class="form-control" placeholder="" type="text" name="bairro_vizu" disabled>
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label">Cidade:</label>
                                        <input id="cidade_vizu" class="form-control" placeholder="" type="text" name="cidade_vizu" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tipo" class="form-label">UF:</label>
                                        <input type="text" name="uf_vizu" id="uf_vizu" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label">Telefone 01:</label>
                                        <input id="telefone1_vizu" class="form-control" placeholder="" type="text" name="telefone1_vizu" disabled>
                                    </div>
                                    
                                    <div class="col">
                                        <label class="form-label">Telefone 02:</label>
                                        <input id="telefone2_vizu" class="form-control" placeholder="" type="text" name="telefone2_vizu" disabled>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        


        <script src="node_modules/cnpj/dist/node-cnpj.js"></script>

<script>



function validarFormClientes() {
    // Obtenha os valores dos campos
    var img_cliente = document.getElementById('img_cliente').value;
    var codigo = document.getElementById('codigo').value;
    var nome = document.getElementById('nome').value;
    var apelido = document.getElementById('apelido').value;
    var fantasia = document.getElementById('fantasia').value;
    var cnpj = document.getElementById('cnpj').value;
    var cep = document.getElementById('cep').value;
    var endereco = document.getElementById('endereco').value;
    var bairro = document.getElementById('bairro').value;
    var cidade = document.getElementById('cidade').value;
    var telefone1 = document.getElementById('telefone1').value;
    var telefone2 = document.getElementById('telefone2').value;

    // Verifique se os campos estão vazios
    if (img_cliente === '') {
        exibirMensagemErro('Preencha o campo Imagem!');
        return false;
    }

    if (codigo === '') {
        exibirMensagemErro('Preencha o campo Código!');
        return false;
    }

    if (nome === '') {
        exibirMensagemErro('Preencha o campo Nome!');
        return false;
    }

    if (apelido === '') {
        exibirMensagemErro('Preencha o campo Apelido!');
        return false;
    }

    if (fantasia === '') {
        exibirMensagemErro('Preencha o campo Nome Fantasia!');
        return false;
    }

    if (cnpj === '') {
        exibirMensagemErro('Preencha o campo CNPJ!');
        return false;
    }

    if (cep === '') {
        exibirMensagemErro('Preencha o campo CEP!');
        return false;
    }

    if (endereco === '') {
        exibirMensagemErro('Preencha o campo Endereço!');
        return false;
    }

    if (bairro === '') {
        exibirMensagemErro('Preencha o campo Bairro!');
        return false;
    }

    if (cidade === '') {
        exibirMensagemErro('Preencha o campo Cidade!');
        return false;
    }

    if (telefone1 === '') {
        exibirMensagemErro('Preencha o campo Telefone 01!');
        return false;
    }

    if (telefone2 === '') {
        exibirMensagemErro('Preencha o campo Telefone 02!');
        return false;
    }

    return true;
}


// Função para exibir mensagem de erro
function exibirMensagemErro(mensagem) {
    var msgAlertaErro = document.getElementById('msgAlertaErro');
    msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro: " + mensagem + "</div>";
}

// Adicione um evento de escuta ao formulário para chamar a função de validação antes do envio
document.getElementById('CadClienteModal').addEventListener('submit', function (event) {
    if (!validarFormClientes()) {
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
        imgCell.innerHTML = `<img class="avatar avatar-x1" src="../storage/app/public/${item.img_cliente}" alt="">`; // Defina o conteúdo da célula

        row.classList.add('table-row-clickable'); // Adicione a classe clicável à linha
        row.setAttribute('data-cliente-id', item.id); // Armazena o ID do chamado na linha
        row.innerHTML = `

            <td>${imgCell.innerHTML}</td>
            <td>${item.codigo}</td>
            <td>${item.nome}</td>
            <td>${item.telefone1}</td>
            <td>${item.telefone2}</td>
            <td>
                <div class="btn-list flex-nowrap">
                    <button href="#" class="btn btn-editar-cliente" onclick="editCliente(event, ${item.id}) ">
                        Editar
                    </button>
                    <button href="#" class="btn btn-desabilitar-cliente" onclick="confirmDelete('${item.id}')">
                        Excluir
                    </button>
                </div>
            </td>
        `;
        dataTable.appendChild(row);

        const btnDesabilitar = row.querySelector('.btn-desabilitar-cliente');
        if (btnDesabilitar) {
            btnDesabilitar.addEventListener('click', function(event) {
                event.stopPropagation(); // Impede a propagação do evento de clique para a linha
            });
        }


    });

    
    console.log("Atualizou!");
}

function fetchDataAndRender() {
    fetch('./api/v1/clientes')
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

document.getElementById('data-table').addEventListener('click', function (event) {
    const clickedRow = event.target.closest('tr.table-row-clickable');
  
    if (clickedRow) {
        // Obtém o ID do cliente associado à linha clicada
        var clienteId = clickedRow.getAttribute('data-cliente-id');

        // Exemplo de requisição AJAX usando Fetch API:
        fetch(`./api/v1/clientes/${clienteId}`)
            .then(response => response.json())
            .then(cliente => {
                // Agora você pode usar os dados do cliente para preencher o modal
                const modal = new bootstrap.Modal(document.getElementById('VizuClienteModal'));
                modal.show();
                preencherModal(cliente);
            })
            .catch(error => console.error('Erro ao obter detalhes do cliente:', error));
    }
});

function preencherModal(cliente) {
    console.log('Dados do cliente:', cliente);
    var clienteId = cliente.data.id;

    var imgElement = document.getElementById('clienteImage_vizu');
    imgElement.src = `./storage/${cliente.data.img_cliente}`;

    // PREENCHER JANELA MODAL PARA VIZUALIZAR OS CHAMADOS:

    document.getElementById('codigo_vizu').value = cliente.data.codigo;
    document.getElementById('nome_vizu').value = cliente.data.nome;
    document.getElementById('apelido_vizu').value = cliente.data.apelido;
    document.getElementById('fantasia_vizu').value = cliente.data.fantasia;
    document.getElementById('cnpj_vizu').value = cliente.data.cnpj;
    document.getElementById('cep_vizu').value = cliente.data.cep;
    document.getElementById('endereco_vizu').value = cliente.data.endereco;
    document.getElementById('bairro_vizu').value = cliente.data.bairro;
    document.getElementById('cidade_vizu').value = cliente.data.cidade;
    document.getElementById('uf_vizu').value = cliente.data.uf;
    document.getElementById('telefone1_vizu').value = cliente.data.telefone1;
    document.getElementById('telefone2_vizu').value = cliente.data.telefone2;

    
}


function editCliente(event, clienteId) {
    event.preventDefault();
    const editClienteRoute = `./clientes/${clienteId}/edit`;
    window.location.href = editClienteRoute;
}

function confirmDelete(clienteId) {
    const url = `./clientes/${clienteId}`;
    const csrfToken = '{{ csrf_token() }}';
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
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
                    console.log('Cliente excluído com sucesso!');
                } else {
                    console.error('Erro ao excluir cliente:', response.statusText);
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

    fetch('./api/v1/clientes')
        .then(response => response.json())
        .then(data => {
            const filteredData = data.data.filter(function (item) {
                return (
                    (item.nome && item.nome.toLowerCase().includes(searchTerm)) ||
                    (item.codigo && item.codigo.toLowerCase().includes(searchTerm)) ||
                    (item.contato && item.contato.toLowerCase().includes(searchTerm))
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
    dataTable.innerHTML = '<tr><td colspan="9">Não há Clientes!</td></tr>';
}

function exibirNovaImagem(input) {

        

    if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var clienteImage = document.getElementById('clienteImage');
                clienteImage.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
}


</script>

        @endsection