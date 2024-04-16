console.log(`${userId}`)

  // @formatter:off
  document.addEventListener("DOMContentLoaded", function () {
    let options = {
      selector: '#detalhamento',
      height: 200,
      menubar: false,
      statusbar: false,
      plugins: [
      ],
      toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat',
      content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
    }
    if (localStorage.getItem("tablerTheme") === 'dark') {
      options.skin = 'oxide-dark';
      options.content_css = 'dark';
    }
    tinyMCE.init(options);
  })
  // @formatter:on

function renderData(data) {
    const dataTable = document.getElementById('data-table');
    dataTable.innerHTML = ''; // Limpa a tabela atual

    data.forEach(item => {
        console.log("item:", item);
        
        const createdAt = new Date(item.created_at);
        const datePart = createdAt.toLocaleDateString();
        const clienteNome = item.cliente ? item.cliente.nome || 'N/A' : 'N/A';


        if (item.prioridade === 'Urgente') {
            
            var point = `<span class="badge bg-danger me-1"></span>` ;

            
        } else if (item.prioridade === 'Normal') {
            
            var point = `<span class="badge bg-success me-1"></span>` ;

        }

        console.log("Resposta da API:", data);

        $.ajax({
            url: './get-cliente-info/' + item.cliente.codigo,
            type: 'GET',
            success: function (data) {
                if (data.error) {
                    // Cliente não encontrado, você pode exibir uma mensagem de erro
                    //console.error('Cliente não encontrado');
    
                    //msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
                } else {
                    
                    console.log(data.img_cliente);
                }
            },
            error: function () {
                //msgAlertaErro.innerHTML = "<div class='alert alert-danger bg-red text-white' role='alert'>Erro:  Cliente Não Encontrado!</div>";
            }
        });

        const row = document.createElement('tr');
        row.classList.add('table-row-clickable'); // Adicione a classe clicável à linha
        row.setAttribute('data-chamado-id', item.id); // Armazena o ID do chamado na linha
        row.innerHTML = `
          <td class="text-reset">${item.id}</td>
          <td class="text-reset">${datePart}</td>
          <td class="text-reset">
          <span class="flag flag-xs flag-country-gb me-2"><img id='img-cliente-container' src="./storage/${item.cliente.img_cliente}"></span>
          ${item.cliente.codigo}-${clienteNome}</td>
          <td class="text-reset">${item.contato}</td>
          <td class="text-reset">${item.username}</td>
          <td class="text-reset">${item.local}</td>      
          <td class="text-reset"> ${item.tipo}</td>
          <td class="text-reset">${point} ${item.prioridade}</td>
          <td class="text-end">
          </td>
        `;
        dataTable.appendChild(row);
    });
}




function fetchDataAndRender() {
    fetch(`./user-chamados/${userId}`)
        .then(response => response.json())
        .then(data => {
            const reversedData = data.data.reverse(); // Inverte a ordem dos itens da API

            allData = reversedData;
            
            renderData(allData); // Renderiza os dados na tabela


            console.log("Atualizou!");
        })
        .catch(error => {
            console.error('Ocorreu um erro ao buscar os dados da API:', error);
        });
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

const infoChamadosModal = document.getElementById('InfoChamadosModal');

    // Adiciona um ouvinte de eventos para o evento hidden.bs.modal
    infoChamadosModal.addEventListener('hidden.bs.modal', function () {
    // Recarrega a página
    window.location.reload();
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

    // Condições/Validações dos botões
    var btnSalvarChamado = document.getElementById('btnSalvarChamado');
    var detalhamentoInput = document.getElementById('detalhamento');
    var btnDevolverChamado = document.getElementById('btnDevolverChamado');
    var btnReabrirChamado = document.getElementById('btnReabrirChamado');
    var btnFinalizarChamado = document.getElementById('btnFinalizarChamado');

    
    btnSalvarChamado.addEventListener('click', function () {
        btnDevolverChamado.style.display = 'inline-block';
        btnFinalizarChamado.style.display = 'inline-block';
    });

    if (chamado.data.tipo === 'Finalizado') {
        btnReabrirChamado.style.display = 'inline-block';
        btnFinalizarChamado.style.display = 'none';
    } else {
        btnReabrirChamado.style.display = 'none';
        btnFinalizarChamado.style.display = 'inline-block';
    }

    if(chamado.data.status === 'Fechado'){
        btnDevolverChamado.style.display = 'none';
    } else{
        btnDevolverChamado.style.display = 'inline-block';
    }

  var chamadoId = chamado.data.id;


    document.getElementById('btnSalvarChamado').addEventListener('click', function(){
        console.log('Salvar chamado com ID:', chamadoId);

        fetch(`./chamados/salvarDt/${chamadoId}`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                titulo: document.getElementById('titulo_descrição').value,
                conteudo: tinyMCE.get('detalhamento').getContent(),
                
                
        })
        })
        .then(response => response.json())
        .then(data => {
            // Lógica após a devolução bem-sucedida, se necessário
            console.log('Detalhamento salvo com sucesso:', data);

            // $('#InfoChamadosModal').modal('hide');
            // window.location.href = "/meus_chamados";

        })
        .catch(error => {
            console.error('Erro ao enviar detalhamento:', error);
            // Lógica para lidar com erros, se necessário
        });
    
        // Coletar os dados que você deseja atualizar do formulário (por exemplo, detalhamento)
        const detalhamento = tinyMCE.get('detalhamento').getContent();
    
        // Criar um objeto com os dados a serem atualizados
        const data = {
            detalhamento: detalhamento
        };
    
        fetch(`./edit/${chamadoId}`,{
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
            console.log('Chamado Editado com sucesso:', data);

            $('#InfoChamadosModal').modal('hide');
    
        })
        .catch(error => {
            console.error('Erro ao editar chamado:', error);
            // Lógica para lidar com erros, se necessário
        });

    });
    

    document.getElementById('btnFinalizarChamado').addEventListener('click', function(){
        console.log('Finalizar chamado com ID:', chamadoId);

        fetch(`./chamados/finalizar/${chamadoId}`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            // Lógica após a devolução bem-sucedida, se necessário
            console.log('Chamado finalizado com sucesso:', data);

            $('#InfoChamadosModal').modal('hide');

        })
        .catch(error => {
            console.error('Erro ao finalizar chamado:', error);
            // Lógica para lidar com erros, se necessário
        });
    });

     document.getElementById('btnDevolverChamado').addEventListener('click', function() {
   
        console.log('Devolver chamado com ID:', chamadoId);
        
        fetch(`./chamados/leave/${chamadoId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            // Lógica após a devolução bem-sucedida, se necessário
            console.log('Chamado devolvido com sucesso:', data);

            $('#InfoChamadosModal').modal('hide');

            window.location.href = './';

        })
        .catch(error => {
            console.error('Erro ao devolver chamado:', error);
            // Lógica para lidar com erros, se necessário
        });
    });

    function inserirDados() {
        const dataHoraAtual = new Date();
        const dataFormatada = dataHoraAtual.toLocaleDateString();
        const horaFormatada = dataHoraAtual.toLocaleTimeString();
    
        var textoInserido = `Data: ${dataFormatada} - ${horaFormatada} | Técnico: ${userName}`;
        return textoInserido;
    }

    var textoInserido = inserirDados();

  // Preencha os campos do modal com os dados do chamado
  document.getElementById('InfoChamadosModal').querySelector('.modal-title').innerText = 'Chamado ' + chamadoId;
  document.getElementById('img-container').innerHTML = `<img src="./storage/${chamado.data.img}" alt="">`;
  document.getElementById('titulo_chamado').value = chamado.data.titulo;
  document.getElementById('titulo_descrição').value = textoInserido;
  //document.getElementById('chamado_id').value = chamadoId;
  document.getElementById('descrição_chamado').value = chamado.data.descrição;
  document.getElementById('cliente_chamado').value = chamado.data.cliente.cliente;
  document.getElementById('codigo_chamado').value = chamado.data.cliente.codigo;
  document.getElementById('contato_chamado').value = chamado.data.contato;
  document.getElementById('detalhamento').value = chamado.data.detalhamento;

  // Carregue o conteúdo do banco de dados no editor TinyMCE

    var conteudoDoBancoDeDados = chamado.data.detalhamento;

    // Suponha que você tenha o conteúdo do banco de dados em uma variável chamada "conteudoDoBancoDeDados"
    if (conteudoDoBancoDeDados) {
    // Configure o conteúdo do editor TinyMCE
    tinyMCE.get('detalhamento').setContent(conteudoDoBancoDeDados);
    };

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



var isSearching = false;

// Função para buscar dados periodicamente
function fetchPeriodically() {
    if (!isSearching) { // Verifica se a pesquisa não está ativa
        fetchDataAndRender();
            
    }
}

// Inicia a busca periódica com setInterval (atualiza a cada 5 segundos)

setInterval(fetchPeriodically, 5000); // 5000 milissegundos = 5 segundos

fetchDataAndRender();

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
               (item.cliente.codigo && item.cliente.codigo.toLowerCase().includes(searchTerm)) ||
               (item.cliente.nome && item.cliente.nome.toLowerCase().includes(searchTerm)) ||
               (item.local && item.local.toLowerCase().includes(searchTerm)) ||
               (item.prioridade && item.prioridade.toLowerCase().includes(searchTerm)) ||
               (item.tipo && item.tipo.toLowerCase().includes(searchTerm));

    });

    //console.log(filteredData);

    // Atualiza a tabela com os dados filtrados
     renderData(filteredData);

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


$('#search').on('keyup', applyFilterAndRender);

// Inicializa a renderização de dados
fetchDataAndRender();