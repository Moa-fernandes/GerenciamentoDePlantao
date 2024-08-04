// Espera o DOM estar completamente carregado
document.addEventListener("DOMContentLoaded", function() {
    // Função para exibir um alerta ao clicar em um botão
    document.getElementById("alertButton")?.addEventListener("click", function() {
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

    // Exemplo de uma função de filtro para uma lista de empresas
    document.getElementById("searchInput")?.addEventListener("input", function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll(".table tbody tr");

        rows.forEach(function(row) {
            let firstCell = row.getElementsByTagName("td")[0];
            if (firstCell) {
                let textValue = firstCell.textContent || firstCell.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });

    // Exemplo de uma função para expandir/colapsar detalhes em uma linha da tabela
    document.querySelectorAll(".expandable-row").forEach(function(row) {
        row.addEventListener("click", function() {
            let detailsRow = this.nextElementSibling;
            if (detailsRow && detailsRow.classList.contains("details-row")) {
                detailsRow.style.display = detailsRow.style.display === "table-row" ? "none" : "table-row";
            }
        });
    });
});
