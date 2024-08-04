document.addEventListener("DOMContentLoaded", function() {
    // Funções e scripts personalizados aqui

    // Exemplo de função para exibir um alerta ao clicar em um botão
    document.getElementById("alertButton").addEventListener("click", function() {
        alert("Botão clicado!");
    });

    // Função para confirmar a exclusão de um item
    document.querySelectorAll(".delete-button").forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (!confirm("Tem certeza que deseja excluir este item?")) {
                event.preventDefault();
            }
        });
    });
});
