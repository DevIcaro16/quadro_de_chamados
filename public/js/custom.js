$(document).ready(function() {
    // Mostre o "page loader" quando a página começar a carregar
    showPageLoader();

    // Quando a página terminar de carregar completamente, esconda o "page loader"
    $(window).on('load', function() {
        hidePageLoader();
    });
    
    // Função para mostrar o "page loader"
    function showPageLoader() {
        $('.page-center').fadeIn();
    }

    // Função para ocultar o "page loader"
    function hidePageLoader() {
        $('.page-center').fadeOut();
    }
});