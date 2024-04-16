@extends('layouts.main')

@section('title' , 'Quadro de chamados -- COMPUTEX')

@section('content')

<!-- Page header -->

<link href="./css/tabler.min.css" rel="stylesheet"/>
    <link href="./css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./css/demo.min.css" rel="stylesheet"/>
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Chamados
                </h2>
                <div class="text-secondary mt-1">Computex</div>
              </div>
              <!-- <div class="col">
                  <input type="text" class="form-control" name="search" id="search" placeholder="Buscar chamado...">
              </div> -->
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                @if(auth()->check() && auth()->user()->nivel >= 2)
                  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#CadChamadoModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Criar novo chamado
                  </a>
                  @endif
                </div>
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
    
    .table-custom-width{
        width: 880px;
    }

    .pagination {
    list-style: none;
    padding: 0;
  }

  .page-item {
    display: inline-block; /* Ou use "display: inline;" se preferir */
    margin: 0 5px; /* Espaçamento entre os elementos da paginação */
  }

  .page-link {
    text-decoration: none;
  }

    
        </style>


        <div class="page-body">
              <div class="container-xl">
                  <h3 class="mt-3">Filtrar Chamados</h3>
                  <div class="row mb-3">
                      <div class="col-md-3">
                          <label for="filterSelectTipo" class="form-label">Status:</label>
                          <select id="filterSelectTipo" class="form-select">
                              <option value="">Todos</option>
                              <option value="E">Em atendimento</option>
                              <option value="N">Não atendidos</option>
                              <option value="F">Finalizados</option>
                              <option value="D">Desativados</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="filterSelectLocal" class="form-label">Local:</label>
                          <select id="filterSelectLocal" class="form-select">
                              <option value="">Todos</option>
                              <option value="S">Suporte</option>
                              <option value="P">Programação</option>
                              <option value="F">Finalizados</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="filterSelectPrioridade" class="form-label">Prioridade:</label>
                          <select id="filterSelectPrioridade" class="form-select">
                              <option value="">Todos</option>
                              <option value="1">Urgente</option>xxx
                              <option value="5">Normal</option>
                          </select>
                      </div>
                      <!-- <div class="col-md-3">
                          <label for="filterSelectStatus" class="form-label">Status:</label>
                          <select id="filterSelectStatus" class="form-select">
                              <option value="">Todos</option>
                              <option value="1">Aberto</option>
                              <option value="0">Fechado</option>
                          </select>
                      </div> -->
                  </div>
                  
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Lista de Chamados</h3>
                  </div>
                  <div class="card-body border-bottom py-3">
                    <div class="d-flex">

                    <div class="text-secondary">
                        Exibir
                        <div class="mx-2 d-inline-block">
                          <input type="text" class="form-control form-control-sm" id="exibir-x-chamados" size="3" aria-label="Invoices count">
                        </div>
                        Chamados
                      </div>
                      
                      <div class="ms-auto text">
                        Filtrar Chamados:
                        <div class="ms-2 d-inline-block">
                          <input type="text" class="form-control form-control-sm" name="search" id="search">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="all-chamados-table" class="table card-table table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th>Nº</th>
                          <th>Data</th>
                          <th>Cliente</th>
                          <th>Contato</th>
                          <th>Titulo</th>
                          <th>Local</th>
                          <th>Tipo</th>
                          <th>Prioridade</th>
                          <th>Técnico</th>
                          @if(auth()->check() && auth()->user()->nivel >= 2)
                          <th>Ações</th>
                          @else
                          <th></th>
                          @endif
                        </tr>
                      </thead>
                      <tbody id="data-table">
                      </tbody>
                    </table>
                  </div>

                  <input type="hidden" id='lastPage'>
                  
                  <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary" id="p-exibir-container"></p>
                    <ul class="pagination m-0 ms-auto">
                        <a class="page-link"  id="link-primeira">
                          <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M11 7l-5 5l5 5"></path>
                              <path d="M17 7l-5 5l5 5"></path>
                          </svg>
                          
                        </a>
                        <a class="page-link" id="link-anterior">
                          <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                          
                        </a>
                      
                        <a class="page-link" id="link-posterior">
                           <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </a>
                        <a class="page-link" id="link-ult">
                           <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-right" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M7 7l5 5l-5 5"></path>
                              <path d="M13 7l5 5l-5 5"></path>
                            </svg>
                        </a>
                    </ul>
                  </div>  
                </div>
              </div>
            </div>

            <!-- Em algum lugar do seu HTML, insira um elemento com um atributo data para armazenar o valor de pageSize -->
            


            <!--<script src="./jquery.min.js"></script>  Inclua o jQuery se você estiver usando -->
            <input type="hidden" id="csrf-token" data-csrf="{{ csrf_token() }}">



<!-- Janela Modal para visualizar informações do chamado: -->
<div class="modal fade" id="InfoChamadosModal" tabindex="-1" aria-labelledby="InfoChamadosModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title-text">Chamado:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

          <div class="row">
            <div class="col-md-2">
                <label for="codigo" class="form-label">Contrato:</label>
                <input type="text" name="codigo" id="codigo_chamado" class="form-control" disabled>
              </div>

            <div class="col-md-5">
                <label for="cliente" class="form-label">Cliente:</label>
                <input type="text" name= "cliente" id="cliente_chamado" class="form-control" disabled>
              </div>
            
            <div class="col-md-5">
              <label for="nome_contato" class="form-label">Nome:</label>
              <input type="text" name="nome_contato" id="nome_contato_chamado" class="form-control" disabled>
            </div> 

          </div> 

          <br>

          <div class="row">

                  <div class="col-md-3">
                      <div class="mb-3">
                          <label for="titulo" class="form-label">Titulo:</label>
                          <input type="text" name="tituloo" id="titulo_chamado" class="form-control" disabled>
                      </div>
                  </div>

                  <div class="col-md-9">
                      <div class="mb-3">
                          <label for="descrição" class="form-label">Descrição:</label>
                          <input type="text" name="descriçãoo" id="descrição_chamado" class="form-control" disabled>
                      </div>
                  </div>

         </div>

          <div class="mb-3">
            <div class="row">
              <div class="row align-items-center">

              <div class="col-md-4">
                  <label for="telefone1" class="form-label">Telefone 01:</label>
                  <input type="text" name="telefone1" id="telefone1_chamado" class="form-control" disabled>
              </div>

              <div class="col-md-4">
                <label for="telefone2" class="form-label">Telefone 02:</label>
                <input type="text" name="telefone2" id="telefone2_chamado" class="form-control" disabled>
              </div>
                  
                <div class="col-md-3">
                    <label for="contato" class="form-label">Contato:</label>
                    <input type="text" name="contato" id="contato_chamado" class="form-control" disabled>
                </div>

                <div class="col-auto">
                  <div class="mb-3">
                    <a class="page-link" id="whatsapp_button" style="display: none;" href="" target="_blank">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp" width="40" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"></path>
                        <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"></path>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <br>

          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label for="prioridade" class="form-label">Prioridade:</label>
                <input type="text" name="prioridade" id="prioridade_chamado" class="form-control" disabled>
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label for="local" class="form-label">Local:</label>
                  <input type="text" name="local" id="local_chamado" class="form-control" disabled>
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <input type="text" name="status" id="status_chamado" class="form-control" disabled>
              </div>
            </div>
          </div>
          
           <div class="mb-3">
            <div class="row">
              <div class="col-md-6">
                <label for="" class="form-label">Anexos:</label>
                <div id="img-container">
                  <a href="caminho_para_sua_imagem1.jpg" target="_blank"><span class="titulo-img-container"></span></a>
                </div>
              </div>
            </div>
           </div>
        
           <div class="mb-3">
                  <div class="row">
                    <div class="col">
                      <label for="detalhamentoSalvo" class="form-label">Detalhamentos Salvos:</label>
                        <div class="card-body">
                          <div class="accordion" id="accordion-example">
                            <div class="accordion-item">
                              <div id="accordionsContainer"></div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
        
        <div class="modal-footer">
            @if(auth()->check() && auth()->user()->nivel >= 2)
                <!-- <button class="btn btn-warning btn-editar-chamado" href="#" data-bs-toggle="modal" data-bs-target="#EditChamadosModal">
                    Editar chamado
                </button> -->
            @endif
          <button type="button" class="btn btn-primary btn-assumir-chamado" id="btnAssumirChamado">Assumir Chamado</button>
        </div>
      </div>
    </div>
  </div>
</div>



  <!-- Janela Modal para criar chamados -->
  <div class="modal fade" id="CadChamadoModal" tabindex="-1" aria-labelledby="CadUsuarioModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="CadUsuarioModal">Novo Chamado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('create') }}" method="POST" id="cad-usuario-form" enctype="multipart/form-data">
            @csrf
            <span id="msgAlertaErro"></span>

            <div class="mb-3">
              <div class="row">
              <div class="col-md-7">
                  <label for="titulo" class="form-label">Titulo:</label>
                  <input type="text" name="titulo" id="titulo" class="form-control">
              </div>

              <div class="col-md-5">
                  <label for="img" class="form-label">Anexos:</label>
                  <input type="file" name="img[]" id="img" multiple="multiple" class="form-control">
                </div>
              </div>
            </div>

            <div class="mb-3">
              <div class="row">

                <div class="col-md-2">
                  <label for="codigo" class="form-label">Contrato:</label>
                  <input type="number" name="codigo" id="codigo" class="form-control">
                </div>

                <div class="col-md-5">
                  <label for="cliente" class="form-label">Cliente:</label>
                  <input type="text" name= "cliente" id="cliente" class="form-control">
                  <input type="hidden" name= "cliente_id" id="cliente_id" class="form-control">
                </div>

                <div class="col">
                  <label for="nome_contato" class="form-label">Nome:</label>
                  <input type="text" name="nome_contato" id="nome_contato" class="form-control">
                </div>

              </div>
            </div>

            <div class="mb-3">
              <div class="row">

                <div class="col-md-4">
                  <label for="telefone1" class="form-label">Telefone 1:</label>
                  <input type="text" name="telefone1" id="telefone1" class="form-control">
                </div>

                <div class="col-md-4">
                  <label for="telefone2" class="form-label">Telefone 2:</label>
                  <input type="text" name="telefone2" id="telefone2" class="form-control">
                </div>

                <div class="col">
                  <label for="contato" class="form-label">Número:</label>
                  <input type="text" name="contato" id="contato" class="form-control">
                </div>

                <script>
                  
                   document.addEventListener('input', function (event) {
                  const input = event.target;

                  if (input.id === 'contato') {
                      const inputValue = input.value;
                      const unmaskedValue = inputValue.replace(/[^\d]/g, ''); // Remove caracteres não numéricos

                      if (unmaskedValue.length >= 10) {
                          const formattedValue = formatPhoneNumber(unmaskedValue); // Formata o número
                          input.value = formattedValue;
                      } else {
                          input.value = inputValue; // Mantém o texto não formatado se for muito curto para ser um número de telefone
                      }
                  }
              });

              function formatPhoneNumber(phoneNumber) {
                  const formatted = phoneNumber.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
                  return formatted;
              }

                </script>

              </div>
            </div>

            <div class="mb-3">
              <div class="row">
              <div class="col-md-6">
                  <label for="prioridade" class="form-label">Prioridade:</label>
                  <select name="prioridade" id="prioridade" class="form-control">
                    <option value="5">Normal</option>
                    <option value="1">Urgente</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="local" class="form-label">Local:</label>
                  <select name="local" id="local" class="form-control">
                    <option value="S">Suporte</option>
                    <option value="P">Programação</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <div class="row">
                <div class="col">
                    <label for="descrição" class="form-label">Descrição:</label>
                    <textarea rows="5" cols="33" name="descrição" id="descrição" class="form-control"></textarea>
                </div>
              </div>
            </div>

              <!-- <div class="col-md-6">
                <div class="mb-3">
                  <label for="status" class="form-label">Status:</label>
                  <select name="status" id="status" class="form-control">
                    <option value="1">Aberto</option>
                    <option value="0">Fechado</option>
                  </select>
                </div> -->
              

            <div class="modal-footer">
              <input type="submit" class="btn btn-success" id="cad-usuario-btn" value="Criar novo chamado">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



<!-- Em sua view Blade do Laravel -->
<script>
    const userId = @json(auth()->user()->id);
    console.log(userId);

    const userNivel = @json(auth()->user()->nivel);
    console.log(userNivel);

    
</script>


<!-- Linkamento Jquery:  -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Linkamento JS do public: -->

<script src="{{ asset('js/index.js') }}"></script>



  
</script>

        @endsection