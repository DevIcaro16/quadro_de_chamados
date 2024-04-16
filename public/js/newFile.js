$(document).ready(function () {
    // Evento de mudança no select
    $("#filterSelect").change(function () {
        var selectedValue = $(this).val();
        var filter = "";

        if (selectedValue !== "") {
            filter = "filter=" + selectedValue; // Supondo que você esteja passando 'filter' como parâmetro na API
        }

        fetchFilteredData(filter);
    });



    // Função para buscar os dados da API com ou sem filtro
    function fetchFilteredData(filter) {
        var baseUrl = 'http://172.16.0.101:8000/api/v1/listar-chamados';
        var apiUrl = filter ? baseUrl + `?${filter}` : baseUrl;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {

                if (data.data.length > 0) {
                    updateTable(data.data);
                } else {
                    showNoDataMessage();
                }
            })
            .catch(error => {
                console.error('Ocorreu um erro ao buscar os dados da API:', error);
            });
    }

    // Função para preencher a tabela com os dados
    function updateTable(data) {
        const dataTable = document.getElementById('data-table');
        dataTable.innerHTML = ''; // Limpa a tabela atual

        data.reverse().forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${item.id}</td>
            <td>${valoresDataHoraListagem[valoresDataHoraListagem.length - 1]}</td>
            <td>${item.cliente}</td>
            <td>${item.detalhamento}</td>
            <td>${item.descrição}</td>
            <td>${item.user.name}</td>
            <td>${item.local}</td>
            <td>${item.tipo}</td>
            <td>${item.prioridade}</td>
        `;

            valoresDataHoraListagem.push(item.chamadosData); // Armazene a data e hora

            dataTable.appendChild(row);
        });
    }

    // Função para exibir a mensagem quando não houver dados
    function showNoDataMessage() {
        const dataTable = document.getElementById('data-table');
        dataTable.innerHTML = '<tr><td colspan="9">Não há chamados aqui!</td></tr>';
    }

});
