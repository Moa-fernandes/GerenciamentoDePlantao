<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include 'config/database.php';

try {
    // Consulta para obter todos os técnicos
    $query = "SELECT id, nome FROM usuarios";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Criar um array para armazenar os resultados
    $tecnicos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tecnicos[] = $row;
    }

    // Retornar os técnicos em formato JSON
    echo json_encode($tecnicos);

} catch (PDOException $e) {
    // Retornar erro em formato JSON
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn = null; // Fechar conexão
?>
