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
    
        <!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                Edição de Cliente ({{($cliente->codigo)}})
                            </h2>
                            <div class="text-secondary mt-1">Cliente: {{$cliente->nome}} </div>
                        </div>
                    </div>
                </div>
            </div><br>

            <form action="{{ route('clientes.update', $cliente->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                <div class="col">
                    <h3 class="card-title">Logo do cliente</h3>
                    <div class="d-flex align-items-center">
                        <div class="col-auto">
                            <div id="img-container">
                                <img src="/storage/{{ $cliente->img_cliente }}" alt="{{$cliente->img_cliente}}" id="clienteImage" style="max-width: 100px">
                            </div>
                        </div>
                        <div class="col-auto mx-3">
                            <div class="custom-file">
                                <label for="img_cliente_edit" class="custom-file-label btn">Editar imagem</label>
                                <input type="file" name="img_cliente_edit" class="form-control" onchange="exibirNovaImagem(this)" id="img_cliente_edit" >
                            </div>
                        </div>
                    </div>
                </div>
                
                <br>

                
                <div class="datagrid">
                        <div class="mb-3">
                            <label for="codigo_edit" class="form-label">Código:</label>
                            <input type="number" name="codigo_edit" class="form-control" value="{{ $cliente->codigo }}">
                        </div>
                        <div class="mb-3">
                            <label for="nome_edit" class="form-label">Nome do Cliente:</label>
                            <input type="text" name="nome_edit" class="form-control" value="{{ $cliente->nome }}">
                        </div>
                        <div class="mb-3">
                            <label for="cnpj_edit" class="form-label">CNPJ:</label>
                            <input type="text" name="cnpj_edit" class="form-control" value="{{ $cliente->cnpj }}">
                        </div>
                        <div class="mb-3">
                            <label for="apelido_edit" class="form-label">Apelido:</label>
                            <input type="text" name="apelido_edit" class="form-control" value="{{ $cliente->apelido }}">
                        </div>
                        <div class="mb-3">
                            <label for="fantasia_edit" class="form-label">Fantasia:</label>
                            <input type="text" name="fantasia_edit" class="form-control" value="{{ $cliente->fantasia }}">
                        </div>
                        <div class="mb-3">
                            <label for="endereco_edit" class="form-label">Endereço:</label>
                            <input type="text" name="endereco_edit" class="form-control" value="{{ $cliente->endereco }}">
                        </div>
                        <div class="mb-3">
                            <label for="bairro_edit" class="form-label">Bairro:</label>
                            <input type="text" name="bairro_edit" class="form-control" value="{{ $cliente->bairro }}">
                        </div>
                        <div class="mb-3">
                            <label for="telefone1_edit" class="form-label">Telefone 01:</label>
                            <input type="text" name="telefone1_edit" class="form-control" value="{{ $cliente->telefone1 }}">
                        </div>
                        <div class="mb-3">
                            <label for="telefone2_edit" class="form-label">Telefone 02:</label>
                            <input type="text" name="telefone2_edit" class="form-control" value="{{ $cliente->telefone2 }}">
                        </div>
                        <div class="mb-3">
                            <label for="cep_edit" class="form-label">CEP:</label>
                            <input type="text" name="cep_edit" class="form-control" value="{{ $cliente->cep }}">
                        </div>
                        <div class="mb-3">
                            <label for="cidade_edit" class="form-label">Cidade:</label>
                            <input type="text" name="cidade_edit" class="form-control" value="{{ $cliente->cidade }}">
                        </div>
                        <div class="mb-3">
                                <label for="tipo" class="form-label">UF:</label>
                                                    <select name="uf_edit" id="uf_edit" class="form-select">
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
                                                    </select><br>
                                
                                
                            </div>
                    </div>



                <div class="mb-3 float-end"> <!-- Adicione a classe text-end para alinhar o conteúdo à direita -->
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
    
            </form>
        </div>
    </div>
</div>


    <script>

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