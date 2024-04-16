@extends('layouts.main')

@section('title' , 'Quadro de chamados -- COMPUTEX')

@section('content')

<style>

#img-container {
   max-width: 150px; max-height: 150px; overflow: hidden;
}


</style>

<input type="hidden" id="csrf-token" data-csrf="{{ csrf_token() }}">

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
                  Meus chamados
                </h2>
                <div class="text-secondary mt-1">Computex</div>
              </div>

              <div class="page-body">
              <div class="container-x1">
              <div class="col-12">
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Lista de Chamados</h3>

                              <div class="ms-auto text">
                                  Filtrar Chamados:
                                  <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" name="search" id="search">
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
                                        <th>Técnico</th>
                                        <th>Local</th>
                                        <th>Tipo</th>
                                        <th>Prioridade</th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table">
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                            
                          </div>  
                          </div>
                      </div>
                      </div>
                      </div>
            </div>
          </div>
        </div>

<!-- Janela Modal para vizualizar informações do chamado: -->
<div class="modal fade" id="InfoChamadosModal" tabindex="-1" aria-labelledby="InfoChamadosModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title-text">Chamado:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

            <div class="mb-3">
              <div class="row">
                <div class="row align-items-center">
                  <div class="col-md-2">
                      <label for="codigo" class="form-label">Contrato:</label>
                      <input type="text" name="codigo" id="codigo_chamado" class="form-control" disabled>
                  </div>

                    <div class="col-md-4">
                      <label for="cliente" class="form-label">Cliente:</label>
                      <input type="text" name= "cliente" id="cliente_chamado" class="form-control" disabled>
                    </div>

                    <div class="col-md-5">
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

            <div class="mb-3">
              <div class="row">
                <div class="col-md-5">
                  <label for="titulo" class="form-label">Titulo:</label>
                  <input type="text" name="titulo" id="titulo_chamado" class="form-control" disabled>
                </div>

                <div class="col">
                    <label for="descrição" class="form-label">Descrição:</label>
                    <input type="text" name="descrição" id="descrição_chamado" class="form-control" disabled>
                  </div>
                </div>
            </div>

              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <div class="card-boy">
                      <label for="detalhamento" class="form-label">Detalhamento:</label>
                      <input type="hidden" name="titulo" id="titulo_descrição" class="form-control" disabled>
        
                      <input type="hidden" name="chamado_id" id="chamado_id" class="form-control" disabled>
                      <textarea rows="5" colls="33" name="conteudo" id="detalhamento" class="form-control"></textarea>
                      <br>
                        <div class="mb-3 end" style="display: flex; align-items: center;">
                        
                        <div class="col-md-6">
                          <label for="" class="form-label">Anexos:</label>
                          <div id="img-container">
                            <a href="caminho_para_sua_imagem1.jpg" target="_blank"><span class="titulo-img-container"></span></a>
                          </div>
                        </div>

                          <div class="icon-container" style="margin-left: auto;">
                            <label for="img" class="file-upload-label">
                              
                            </label>
                            <input type="file" name="img[]" id="img" multiple="multiple" class="form-control">

                            <style>
                              .file-upload-label {
                                  cursor: pointer;
                                  display: inline-flex;
                                  align-items: center;
                                  padding: 5px;
                              }

                              /* Estilize o ícone ou rótulo conforme desejado */
                              .file-upload-label svg {
                                  margin-right: 5px; /* Espaço entre o ícone e o texto (ajuste conforme necessário) */
                              }
                            </style>

                            <button type="button" class="btn btn-success btn-salvar-detalhamento" id="btnSalvarChamado">Salvar</button>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row">
                  
                </div>
           </div>

                <div class="mb-3">
                  <div class="row">
                    <div class="col">
                      <label for="detalhamentoSalvo" class="form-label">Histórico Detalhamentos:</label>
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
                <button type="button" class="btn btn-danger" id="btnDevolverChamado">Devolver Chamado</button>
                <button type="button" class="btn btn-primary" id="btnFinalizarChamado">Finalizar Atendimento</button>
                <button type="button" class="btn btn-success" id="btnReabrirChamado">Reabrir Chamado</button>
              </div>
  
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.tiny.cloud/1/5kom78lt5868zycw5rcz54qx4hw8minoomxndqad8xdhergr/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/js/tinymce.min.js" defer></script>

<script>
  const userId = @json(auth()->user()->id);
  const userName = @json(auth()->user()->name);
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{ asset('js/my_chamados.js') }}"></script>

@endsection