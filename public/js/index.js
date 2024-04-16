var currentFilter = ""; 


document.addEventListener('DOMContentLoaded', function() {
    var filterSelectTipo = document.getElementById('filterSelectTipo');
    var filterSelectLocal = document.getElementById('filterSelectLocal');
    var filterSelectPrioridade = document.getElementById('filterSelectPrioridade');
    var filterSelectStatus = document.getElementById('filterSelectStatus');
    //var pageSizeElement = document.getElementById('pageSizeValue');
    //var pageSize = pageSizeElement.getAttribute('data-page-size');

    // Adicione um evento de mudança a todos os três selects
    filterSelectTipo.addEventListener('change', applyFilters);
    filterSelectLocal.addEventListener('change', applyFilters);
    filterSelectPrioridade.addEventListener('change', applyFilters);
    

    var exibirChamadosInput = document.getElementById('exibir-x-chamados');
exibirChamadosInput.addEventListener('keyup', function() {

    $.ajax({

        url: './api/v1/total-chamados',
        type: 'GET',
        success: function (data) {

            var total_chamados = data.total_chamados;
            var valorInput = exibirChamadosInput.value.trim(); // Remove espaços em branco do valor

            if (valorInput === '') {
                applyFilters();
                document.getElementById('p-exibir-container').innerHTML = `Exibindo 10 de ${total_chamados} Chamados`;
            } else {
                valorInput = parseInt(valorInput); // Converte para número
                applyFilters(); // Reaplica os filtros com base no novo valor

                console.log("KEYUP");

                if (valorInput > total_chamados) {
                    document.getElementById('p-exibir-container').innerHTML = `Erro! Quantidade acima do total de Chamados`;
                } else {
                    document.getElementById('p-exibir-container').innerHTML = `Exibindo ${valorInput} de ${total_chamados} Chamados`;
                }
            }
        },
        error: function () {
            msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro: Cliente Não Encontrado!</div>";
        }
    });
});


    // Função para aplicar os filtros
    function applyFilters() {

        isSearching = true; // A pesquisa está ativa

        var selectedTipo = filterSelectTipo.value;
        var selectedLocal = filterSelectLocal.value;
        var selectedPrioridade = filterSelectPrioridade.value;
        //var selectedStatus = filterSelectStatus.value;

        var exibirChamadosValue = parseInt(exibirChamadosInput.value);

        // Construa o filtro com base nos valores selecionados
        var filter = "";

        if (selectedTipo !== "") {
            filter += "tipo[eq]=" + selectedTipo;
        }

        if (selectedLocal !== "") {
            if (filter !== "") {
                filter += "&"; // Adiciona "&" se já houver outros filtros
            }
            filter += "local[eq]=" + selectedLocal;
        }

        if (selectedPrioridade !== "") {
            if (filter !== "") {
                filter += "&"; // Adiciona "&" se já houver outros filtros
            }
            filter += "prioridade[eq]=" + selectedPrioridade;
        }
        

        

        $.ajax({

    url: './api/v1/listar-chamados?page=1',
    type: 'GET',
    success: function (data) {
        var pageSize = data.meta.per_page;

        var pageSizeToUse = exibirChamadosValue > 0 ? exibirChamadosValue : pageSize;

        fetchFilteredData(filter,userChamados,pageSizeToUse);

        console.log(pageSize);
        
    },
    error: function () {
        msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
    }
});

        //clearInterval(autoRefreshInterval);
    }

    // Função para buscar os dados da API com o filtro
    function fetchFilteredData(filter, userChamados,pageSize) {
        var baseUrl = `./api/v1/listar-chamados?size=${pageSize}`;
        var apiUrl = filter ? baseUrl + `&${filter}` : baseUrl;
    
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data);
    
                if (data && data.data && data.data.length > 0) {
                    updateTable(data.data, userChamados);
                } else {
                    showNoDataMessage();
                }
            })
            .catch(error => {
                console.error('Ocorreu um erro ao buscar os dados da API:', error);
            });
    }
    
});

let pagination = 1;

$.ajax({

    url: './api/v1/total-chamados',
    type: 'GET',
    success: function (data) {


            document.getElementById('p-exibir-container').innerHTML = `Exibindo 10 de ${data.total_chamados} Chamados`;
        

    },
    error: function () {
        msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
    }
});


function fetchData(pagination) {
    var baseUrl = `./api/v1/listar-chamados?page=${pagination}`;
    var apiUrl = currentFilter ? baseUrl + `&${currentFilter}` : baseUrl;

    fetch(apiUrl)
        .then(response => response.json())
        .then(responseData => {
            // Verifica se responseData.data é uma matriz
            if (responseData.data && Array.isArray(responseData.data)) {
                const data = responseData.data;

                // Mapeie os campos de array para seus valores correspondentes
                allData = data;

                if (allData.length > 0) {
                    updateTable(data);
                } else {
                    showNoDataMessage();
                }
            } else {
                console.error('A resposta da API não contém uma matriz de dados válida.');
            }
        })
        .catch(error => {
            console.error('Ocorreu um erro ao buscar os dados da API:', error);
        });

        

}


$.ajax({

    url: './api/v1/listar-chamados?page=1',
    type: 'GET',
    success: function (data) {
    
        document.getElementById('lastPage').value = data.meta.last_page;
    
    },
    error: function () {
        msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
    }
});


function changePage(newPage) {

    isSearching = true;
    pagination = newPage;
    fetchData(pagination); // Busca os dados da API com base na nova página
    updateTable(allData);

}


// Encontre os elementos de "anterior" e "próxima" no seu HTML
const linkPrimeira = document.getElementById('link-primeira');
const linkAnterior = document.getElementById('link-anterior');
const linkProxima = document.getElementById('link-posterior');
const linkUlt = document.getElementById('link-ult');

// Adicione os ouvintes de eventos
linkPrimeira.addEventListener('click', () => {
    if (pagination != 1) {
        changePage(1);
    }
});


linkAnterior.addEventListener('click', () => {
    if (pagination > 1) {
        changePage(pagination - 1);
    }

    console.log("Anterior");
});

linkProxima.addEventListener('click', () => {
    if (pagination < document.getElementById('lastPage').value) {
        changePage(pagination + 1);
    }else{
        showNoDataMessage();
    }

    console.log("QP: " + document.getElementById('lastPage').value);
});

linkUlt.addEventListener('click', () => {
    if (pagination != document.getElementById('lastPage').value ) {
        changePage(document.getElementById('lastPage').value);
    }else{
        showNoDataMessage();
    }

    console.log("QP: " + document.getElementById('lastPage').value);
});


const tipos = {
    'N': 'Não Atendidos',
    'E': 'Em Atendimento',
    // Adicione outros tipos conforme necessário
};


// Função para preencher a tabela com os dados
function updateTable(data) {
    const dataTable = document.getElementById('data-table');
    dataTable.innerHTML = ''; // Limpa a tabela atual


data.forEach(item => {
    const row = document.createElement('tr');

    let cor = '';

    if (item.prioridade === 'Urgente') {
        cor = 'red';
    } else if (item.prioridade === 'Normal') {
        cor = '#00FF00';
    }

    console.log(item);

    const createdAt = new Date(item.created_at);
    const datePart = createdAt.toLocaleDateString();
    const clienteNome = item.cliente ? item.cliente.cliente || 'N/A' : 'N/A';

    if (item.prioridade === 'Urgente') {
            
        var point = `<span class="badge bg-danger me-1"></span>` ;

        
    } else if (item.prioridade === 'Normal') {
        
        var point = `<span class="badge bg-success me-1"></span>` ;

    }
    
    
            $.ajax({
                url: './get-cliente-info/' + item.cliente.codigo,
                type: 'GET',
                success: function (data) {
                    if (data.error) {
                        // Cliente não encontrado, você pode exibir uma mensagem de erro
                        //console.error('Cliente não encontrado');
        
                        msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
                    } else {
                        
                        console.log(data.img_cliente);
                    }
                },
                error: function () {
                    msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
                }
            });


            

    row.classList.add('table-row-clickable'); // Adicione a classe clicável à linha
    row.setAttribute('data-chamado-id', item.id); // Armazena o ID do chamado na linha
    row.innerHTML = `
        <td class="text-reset">${item.id}</td>
        <td class="text-reset">${datePart}</td>
        <td class="text-reset">
            <span class="flag flag-xs flag-country-gb me-2"><img id='img-cliente-container' src="./storage/${item.cliente.img_cliente}"></span>
            ${item.cliente.codigo}-${clienteNome}  
        </td>
        <td class="text-reset">${item.contato}</td>
        <td class="text-reset">${item.titulo}</td>
        <td class="text-reset">${item.local}</td>      
        <td class="text-reset">${item.tipo}</td>
        <td class="text-reset">${point} ${item.prioridade}</td>
        <td class="text-reset">${item.username !== null ? item.username : 'Nenhum'}</td>
        <td class="text-reset">
            <div style="display: inline-block;">

                    ${userNivel >= 2 ? `
                    <button class="btn" href="#" onclick="editChamados(event, ${item.id})" data-chamado-id="${item.id}" style="
                        border: none;     
                        background: none; 
                        outline: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="40" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                        <path d="M16 5l3 3"></path>
                    </svg>
                    </button> 
                ` : ''}

                ${userNivel >= 2 ? (item.tipo === 'Desativado' ?  `
                <form action="./chamados/habilitar/${item.id}" method="POST">
                    <input type="hidden" name="_token" value="${document.getElementById('csrf-token').getAttribute('data-csrf')}">
                    <a href="./chamados/habilitar/${item.id}" id="btnHabilitarChamado" class="btn btn-habilitar-chamado" data-chamado-id="${item.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-360" width="40" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M17 15.328c2.414 -.718 4 -1.94 4 -3.328c0 -2.21 -4.03 -4 -9 -4s-9 1.79 -9 4s4.03 4 9 4"></path>
                            <path d="M9 13l3 3l-3 3"></path>
                        </svg>
                    </a>
                </form> 
                ` : `
                <form action="./chamados/desabilitar/${item.id}" method="POST">
                    <input type="hidden" name="_token" value="${document.getElementById('csrf-token').getAttribute('data-csrf')}">
                    <a href="./chamados/desabilitar/${item.id}" id="btnDesabilitarChamado" class="btn btn-desabilitar-chamado" data-chamado-id="${item.id}" onclick="exibirAlertDesabilitar()" style="border: none;     
                    background: none; 
                    outline: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-x" width="40" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z"></path>
                            <path d="M9 9l6 6m0 -6l-6 6"></path>
                        </svg>
                    </a>
                </form>
                `) : ''}

            </div>
        </td>

    `;

    // Desabilita o comportamento de abrir a modal vizualizar chamados

    if (userNivel >= 2) {
        const btnHabilitar = row.querySelector('.btn-habilitar-chamado');
        if(btnHabilitar){
            btnHabilitar.addEventListener('click', function(event){
                event.stopPropagation();
            })
        }

        const btnDesabilitar = row.querySelector('.btn-desabilitar-chamado');
        if (btnDesabilitar) {
            btnDesabilitar.addEventListener('click', function(event) {
                event.stopPropagation(); // Impede a propagação do evento de clique para a linha
            });
        }
    }

    
    dataTable.appendChild(row);

});
}




// EDITAR UM CHAMADO

function editChamados(event, chamadoId) {
    event.preventDefault();
    const editChamadosRoute = `./chamados/${chamadoId}/edit`;
    window.location.href = editChamadosRoute;
}

document.addEventListener('click', function (event) {
    // Verifica se o clique ocorreu em um botão de "Editar chamado"
    if (event.target.classList.contains('btn-editar-chamado')) {
        // Obtém o ID do chamado associado ao botão clicado
        var chamadoId = event.target.getAttribute('data-chamado-id');
    
        // Agora, você pode usar o ID para obter os detalhes do chamado correspondente do banco de dados
        // e exibi-los no modal, por exemplo, fazendo uma requisição AJAX
        // ou atualizando os elementos do modal diretamente com os dados já carregados na página.
        
        // Exemplo de requisição AJAX usando Fetch API:
        fetch(`./api/v1/listar-chamados/${chamadoId}`)
            .then(response => response.json())
            .then(chamado => {
                // Agora você pode usar os dados do chamado para preencher 
                preencherEditModal(chamado);
            })
            .catch(error => console.error('Erro ao obter detalhes do chamado:', error));
    }
    });
    
    function preencherEditModal(chamado) {
    console.log('Dados do chamado:', chamado);
    
    var chamadoId = chamado.data.id;
    
    console.log(chamadoId);
    
    document.getElementById('btnAtualizarChamado').addEventListener('click', function(){
        console.log('Editando chamado com ID:', chamadoId);
    
        // Coletar os dados que você deseja atualizar do formulário (por exemplo, detalhamento)
        const user_id = document.getElementById('user_id').value;
        const titulo = document.getElementById('titulo').value;
        const cliente = document.getElementById('cliente_edit').value;
        const contato = document.getElementById('contato').value;
        const prioridade_edit = document.getElementById('prioridade_edit').value;
        const local_edit = document.getElementById('local_edit').value;
        const status_edit = document.getElementById('status_edit').value;
        const descrição_edit = document.getElementById('descrição_edit').value;
    
        // Criar um objeto com os dados a serem atualizados
        const data = {
            user_id: user_id,
            titulo: titulo,
            cliente_edit: cliente,
            contato: contato,
            prioridade_edit: prioridade_edit,
            local_edit: local_edit,
            status_edit: status_edit,
            descrição_edit: descrição_edit,
            // Adicione outros campos que deseja atualizar aqui
        };
    
        fetch(`./editar/${chamadoId}`,{
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
            console.log('Chamado editado com sucesso:', data);
    
            $('#EditChamadosModal').modal('hide');
    
        })
        .catch(error => {
            console.error('Erro ao editar chamado:', error);
            // Lógica para lidar com erros, se necessário
        });
    });
    
    
    // Preencha os campos do modal com os dados do chamado
    document.getElementById('EditChamadosModal').querySelector('.modal-title').innerText = 'Editando chamado: ' + chamadoId;
    document.getElementById('titulo').value = chamado.data.titulo;
    document.getElementById('codigo_edit').value = chamado.data.cliente.codigo;
    document.getElementById('cliente_edit').value = chamado.data.cliente.cliente;
    document.getElementById('contato').value = chamado.data.contato;
    document.getElementById('tecnico').value = chamado.data.username;
    document.getElementById('descrição_edit').value = chamado.data.descrição;
}

document.getElementById('data-table').addEventListener('click', function (event) {
    const clickedRow = event.target.closest('tr.table-row-clickable');
  
    if (clickedRow) {
        // Obtém o ID do chamado associado à linha clicada
        var chamadoId = clickedRow.getAttribute('data-chamado-id');

        // Exemplo de requisição AJAX usando Fetch API:
        fetch(`./api/v1/listar-chamados/${chamadoId}`)
            .then(response => response.json())
            .then(chamado => {
                // Agora você pode usar os dados do chamado para preencher o modal
                const modal = new bootstrap.Modal(document.getElementById('InfoChamadosModal'));
                modal.show();
                preencherModal(chamado);
            })
            .catch(error => console.error('Erro ao obter detalhes do chamado:', error));

            function renderDt(dt) {
                const accordionsContainer = document.getElementById('accordionsContainer');
                accordionsContainer.innerHTML = '';

                const reversedDt = dt.data.reverse();

                reversedDt.forEach(item => {
                    if (item && item.titulo) {
                        // Certifique-se de que o objeto item é definido e possui a propriedade "titulo"
                        const row = document.createElement('div');
                        row.classList.add('accordion-item');
                        row.innerHTML = `
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#detalhamento-${item.id}">
                                    ${item.titulo}
                                </button>
                            </h2>
                            <div id="detalhamento-${item.id}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    ${item.conteudo}
                                </div>
                            </div>
                        `;

                        accordionsContainer.appendChild(row);

                    } else {
                        console.log("O objeto item não possui a propriedade 'titulo' definida.");
                    }
                });
            }
            
            function fetchDtAndRender() {
                fetch(`./api/v1/listar-detalhamentos/${chamadoId}`)
                    .then(response => response.json())
                    .then(dt => {
                        
                        console.log(dt);
                        renderDt(dt); // Renderiza os dados na tabela
            
            
                        console.log("Atualizou!");
                    })
                    .catch(error => {
                        console.error('Ocorreu um erro ao buscar os dados da API:', error);
                    });
            }

            fetchDtAndRender();
    }
});

function checkContato() {
    const input = document.getElementById('contato_chamado');
    const inputValue = input.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
    const whatsappButton = document.getElementById('whatsapp_button');

    if (inputValue.length >= 11) {
        whatsappButton.style.display = 'inline'; // Exibe o link
        const whatsappLink = `https://web.whatsapp.com/send/?phone=${inputValue}`;
        whatsappButton.href = whatsappLink;
    } else {
        whatsappButton.style.display = 'none'; // Oculta o link se o comprimento for menor que 11
    }
}

function preencherModal(chamado) {
    console.log('Dados do chamado:', chamado);
    var chamadoId = chamado.data.id;

    var btnAssumirChamado = document.getElementById('btnAssumirChamado');

    // ASSUMIR UM CHAMADO

    if (chamado.data.user_id === null) {
        btnAssumirChamado.style.display = 'inline-block';
    } else {
        btnAssumirChamado.style.display = 'none';
    }

    document.getElementById('btnAssumirChamado').addEventListener('click', function(){
        console.log('Assumindo chamado com ID:', chamadoId);

        fetch(`./chamados/assumir/${chamadoId}`,{
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            // Lógica após a devolução bem-sucedida, se necessário
            console.log('Chamado assumido com sucesso:', data);

            $('#InfoChamadosModal').modal('hide');
            window.location.href = "./meus_chamados";

        })
        .catch(error => {
            console.error('Erro ao assumir chamado:', error);
            // Lógica para lidar com erros, se necessário
        });
    });

    // PREENCHER JANELA MODAL PARA VIZUALIZAR OS CHAMADOS:

    document.getElementById('InfoChamadosModal').querySelector('.modal-title').innerText = 'Chamado ' + chamadoId;
    
    document.getElementById('titulo_chamado').value = chamado.data.titulo;
    document.getElementById('descrição_chamado').value = chamado.data.descrição;
    document.getElementById('cliente_chamado').value = chamado.data.cliente.cliente;
    document.getElementById('codigo_chamado').value = chamado.data.cliente.codigo;
    document.getElementById('nome_contato_chamado').value = chamado.data.nome_contato;
    document.getElementById('contato_chamado').value = chamado.data.contato;
    document.getElementById('telefone1_chamado').value = chamado.data.cliente.telefone1;
    document.getElementById('telefone2_chamado').value = chamado.data.cliente.telefone2;
    document.getElementById('prioridade_chamado').value = chamado.data.prioridade;
    document.getElementById('local_chamado').value = chamado.data.local;
    document.getElementById('status_chamado').value = chamado.data.status;

    checkContato();
    
    var imgContainer = document.getElementById('img-container');
    var imageInfoText = chamado.data.img; // Suponha que esta variável contenha a string JSON
    
    imgContainer.innerHTML = '';
    
    try {
        var imageInfoList = JSON.parse(imageInfoText);
    
        if (Array.isArray(imageInfoList)) {
            imageInfoList.forEach(function(imageInfo) {
                var originalFileName = imageInfo.original_name;
                var url = imageInfo.url;
    
                var link = document.createElement('a');
                link.href = './storage/' + url;
                link.target = '_blank';
    
                var span = document.createElement('span');
                span.className = 'titulo-img-container';
                span.innerText = originalFileName;
    
                link.appendChild(span);
                imgContainer.appendChild(link);
    
                var lineBreak = document.createElement('br');
                imgContainer.appendChild(lineBreak);
            });
        } else {
            // Lida com o caso em que imageInfoList não é um array
        }
    } catch (error) {
        // Lida com erros de análise JSON
    }
    
        // Adicione um evento de clique ao link para abrir a imagem
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Impede o link de seguir o href normal
            window.open(link.getAttribute('href'));
        });
    
}

// EXCLUIR CHAMADO

function exibirAlertDesabilitar(){

    alert("Deseja Desabilitar Este Chamado?");

    window.location.href = document.getElementById('btnDesabilitarChamado').getAttribute('href');

}

function confirmDelete(chamadoId) {

    const url = `./api/v1/listar-chamados/${chamadoId}`;
    if (confirm('Tem certeza que deseja excluir este chamado?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => {
                // Lide com a resposta conforme necessário
                if (response.ok) {
                    // Se a exclusão for bem-sucedida, você pode redirecionar para outra página ou fazer outras ações
                    console.log('Chamado excluído com sucesso!');
                } else {
                    console.error('Erro ao excluir chamado:', response.statusText);
                    alert('Erro ao excluir chamado: Chamado Em atendimento');
                }
            })
            .catch(error => {
                console.error('Erro na solicitação DELETE:', error);
            });
    }

}


var userChamados = [];

var isSearching = false;

// Função para buscar dados periodicamente
function fetchPeriodically() {
if (!isSearching) { // Verifica se a pesquisa não está ativa
    fetchData(1);
}
}

// Inicia a busca periódica com setInterval (atualiza a cada 5 segundos)

setInterval(fetchPeriodically, 5000); // 5000 milissegundos = 5 segundos

//var autoRefreshInterval = setInterval(fetchData, 5000); // Atualizar a cada 60 segundos

fetchData(1);

var allData = [];

function applyFilterAndRender() {

isSearching = true; // A pesquisa está ativa
var searchTerm = $('#search').val().toLowerCase(); // Obtém o valor da pesquisa em letras minúsculas

// Filtra os dados com base no termo de pesquisa
var filteredData = allData.filter(function (item) {
    
    

    const createdAt = new Date(item.created_at).toLocaleString();

    return (item.descrição && item.descrição.toLowerCase().includes(searchTerm)) ||
            (createdAt && createdAt.toLowerCase().includes(searchTerm)) ||
           (item.contato && item.contato.toLowerCase().includes(searchTerm)) ||
        //    (item.cliente.codigo && item.cliete.codigo.toLowerCase().includes(searchTerm)) ||
           (item.cliente.cliente && item.cliente.cliente.toLowerCase().includes(searchTerm)) ||
           (item.local && item.local.toLowerCase().includes(searchTerm)) ||
           (item.prioridade && item.prioridade.toLowerCase().includes(searchTerm)) ||
           (item.tipo && item.tipo.toLowerCase().includes(searchTerm));

});

console.log(allData);

// Atualiza a tabela com os dados filtrados
updateTable(filteredData);

// Verifica se a pesquisa não retornou resultados e exibe a mensagem apropriada
if (filteredData.length === 0) {
    showNoDataMessage();
}

}

// Função para exibir a mensagem de "Não há chamados!"
function showNoDataMessage() {
const dataTable = document.getElementById('data-table');
dataTable.innerHTML = '<tr><td colspan="9">Não há chamados!</td></tr>';
}



// Em seu arquivo index.js

// Suponha que você tenha uma função para obter os chamados associados ao usuário
function getUserChamados(userId) {
return fetch(`./api/v1/user-chamados/${userId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao buscar chamados do usuário');
        }
        return response.json();
    })
    .then(data => data.chamados)
    .catch(error => {
        throw error;
    });
}



// window.onload = function() {
//     getUserChamados(userId)
//         .then(userChamados => {
//             // Agora que você tem a lista de IDs de chamados associados ao usuário,
//             // você pode usá-la como necessário.
//             console.log(userChamados); // Faça algo com a lista de chamados, por exemplo, passe-a para a função updateTable
//         })
//         .catch(error => {
//             console.error("Ocorreu um erro ao buscar os chamados do usuário:", error);
//         });
// };

$('#search').on('keyup', applyFilterAndRender);


$('#codigo').on('change', function () {
var codigoCliente = $(this).val();

$.ajax({
    url: './get-cliente-info/' + codigoCliente,
    type: 'GET',
    success: function (data) {
        if (data.error) {
            // Cliente não encontrado, você pode exibir uma mensagem de erro
            //console.error('Cliente não encontrado');

            msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
        } else {
            // Preencha os campos "cliente", "contato" e "cliente_id" com os dados do cliente
            $('#cliente').val(data.nome);
            $('#cliente_id').val(data.id); // Inclua o campo "id" do cliente
            $('#telefone1').val(data.telefone1);
            $('#telefone2').val(data.telefone2);
            $('.flag.flag-xs.flag-country-gb.me-2').html(`<img id='img-cliente-container' src="./storage/img_cliente/${data.img_cliente}" alt="img_cliente_${data.nome}">`);
            console.log(data.img_cliente);
        }
    },
    error: function () {
        msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
    }
});
});


// Função para realizar as validações do form da janela modal de criação de chamados:

function validarFormularioChamado() {
// Obtenha os valores dos campos

var descricao = document.getElementById('descrição').value;
var codigo = document.getElementById('codigo').value;
var cliente = document.getElementById('cliente').value;


// Verifique se os campos estão vazios
if (descricao === '') {
 exibirMensagemErro('Preencha o campo Descrição!');
 return false;
}

if (codigo === '') {
 exibirMensagemErro('Preencha o campo Contrato!');
 return false;
}

if (cliente === '') {
 exibirMensagemErro('Preencha o campo Cliente!');
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
document.getElementById('cad-usuario-form').addEventListener('submit', function (e) {
if (!validarFormularioChamado()) {
 // Impedir o envio do formulário se a validação falhar
 e.preventDefault();

 console.log("sdhuqifw");  
}

});



