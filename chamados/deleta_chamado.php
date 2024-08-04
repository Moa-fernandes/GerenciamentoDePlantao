<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Deletar o chamado do banco de dados
    $query = "DELETE FROM chamados WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Chamado deletado com sucesso.";
    } else {
        echo "Erro ao deletar chamado: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    // Confirmar exclusão
    echo "<p>Tem certeza que deseja excluir este chamado?</p>";
    echo "<form method='POST' action='deleta_chamado.php?id=$id'>";
    echo "<button type='submit' class='btn btn-danger'>Deletar</button>";
    echo "<a href='lista_chamado.php' class='btn btn-secondary'>Cancelar</a>";
    echo "</form>";
}
?>
