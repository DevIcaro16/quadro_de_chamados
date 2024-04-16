@extends('layouts.main')

@section('title', 'Editar Chamado')

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
                  Edição de chamados
                </h2>
                <!-- <div class="text-secondary mt-1">Usuário em edição: {{$chamado->name}} ID: {{$chamado->id}} </div> -->
              </div>
            </div>
          </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
              <div class="card-header">
                <div class="col">
                    <h3 class="card-title">Chamado em edição:</h3>
                    <div class="text-secondary mt-1">ID: {{$chamado->id}}  {{$chamado->titulo}}</div>
                </div>
              </div>

              <form action="{{ route('chamados.update', $chamado->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              <div class="card-body">

              <div class="mb-3">
                    <div class="col-md-30">
                    <label for="titulo_edit" class="form-label">Titulo do chamado:</label>
                    <input type="text" name="titulo_edit" class="form-control" value="{{ $chamado->titulo }}" required>
                    </div>
                </div>

                <div class="datagrid">
        

                <div class="mb-3">
                    <label for="codigo_edit" class="form-label">Código:</label>
                    <input type="text" name="codigo_edit" class="form-control" value="{{ $chamado->clientes->codigo }}" required>
                </div>

                <div class="mb-3">
                    <label for="cliente_edit" class="form-label">Cliente:</label>
                    <input type="text" name="cliente_edit" class="form-control" value="{{ $chamado->cliente }}" required>
                </div>

                <div class="mb-3">
                    <label for="contato_edit" class="form-label">Contato:</label>
                    <input type="text" name="contato_edit" class="form-control" value="{{ $chamado->contato }}" required>
                </div>

                <div class="mb-3">
                    <label for="nome_contato_edit" class="form-label">Nome:</label>
                    <input type="text" name="nome_contato_edit" class="form-control" value="{{ $chamado->nome_contato }}">
                </div>

                <div class="datagrid-item">
                    <div class="datagrid-title">

                    </div>
                    <div class="datagrid-content">

                    </div>
                </div>


                 <div class="mb-3">
                    <label for="prioridade_edit" class="form-label">Prioridade:</label>
                    <select name="prioridade_edit" class="form-select" required>
                        <option value="1" {{ $chamado->prioridade === '1' ? 'selected' : '' }}>Urgente</option>
                        <option value="5" {{ $chamado->prioridade === '5' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="local_edit" class="form-label">Local:</label>
                    <select name="local_edit" class="form-select" required>
                        <option value="S" {{ $chamado->local === 'S' ? 'selected' : '' }}>Suporte</option>
                        <option value="P" {{ $chamado->local === 'P' ? 'selected' : '' }}>Programação</option>
                    </select>
                </div> 

                <div class="mb-3">
                    <label for="status_edit" class="form-label">Status:</label>
                    <select name="status_edit" class="form-select" required>
                        <option value="1" {{ $chamado->status === '1' ? 'selected' : '' }}>Aberto</option>
                        <option value="0" {{ $chamado->status === '0' ? 'selected' : '' }}>Fechado</option>
                    </select>
                </div>

                <div class="datagrid-item">
                    <div class="datagrid-title">

                    </div>
                    <div class="datagrid-content">

                    </div>
                </div>

              </div>

              <div class="mb-3">
                    <label for="descrição_edit" class="form-label">Descrição:</label>
                    <input type="text" name="descrição_edit" class="form-control" value="{{ $chamado->descrição }}" required>
                </div>

              <div class="mb-3">
                        <h3 class="form-label">Anexos do chamado:</h3>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img id="chamadoImage" src="{{ asset('storage/' . $chamado->img) }}" class="avatar avatar-xl"></img>
                            </div>

                            <div class="col-auto">
                                <div class="md-3">
                                    <div class="custom-file">
                                        <label for="img_edit" class="custom-file-label btn">Editar anexo</label>
                                        <input name="img_edit" id="img_edit" class="custom-file-input" type="file" onchange="exibirNovaImagem(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
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
        var chamadoImage = document.getElementById('chamadoImage');
        chamadoImage.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
    }
    }

</script>
