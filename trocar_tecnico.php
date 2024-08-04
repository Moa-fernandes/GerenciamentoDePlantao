<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar os dados enviados via POST
    $chamadoId = $_POST['chamado_id'];
    $novoTecnicoId = $_POST['novo_tecnico_id'];
    $dataTroca = date('Y-m-d H:i:s'); // Data e hora da troca do técnico

    try {
        // Obter o nome da empresa associada ao chamado
        $query = "SELECT e.nome AS empresa_nome
                  FROM chamados c
                  JOIN empresas e ON c.empresa_id = e.id
                  WHERE c.id = :chamado_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':chamado_id', $chamadoId, PDO::PARAM_INT);
        $stmt->execute();
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se a empresa for encontrada, prosseguir com a troca de técnico
        if ($empresa) {
            // Atualizar o técnico responsável pelo chamado e registrar a hora da troca
            $query = "UPDATE chamados SET tecnico_id = :novo_tecnico_id, data_troca = :data_troca WHERE id = :chamado_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':novo_tecnico_id', $novoTecnicoId, PDO::PARAM_INT);
            $stmt->bindParam(':data_troca', $dataTroca, PDO::PARAM_STR);
            $stmt->bindParam(':chamado_id', $chamadoId, PDO::PARAM_INT);
            $stmt->execute();

            // Verificar se a atualização foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Técnico trocado com sucesso.",
                    "empresa_nome" => $empresa['empresa_nome'],
                    "data_troca" => $dataTroca
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Nenhuma alteração realizada."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Empresa não encontrada para o chamado fornecido."
            ]);
        }
    } catch (PDOException $e) {
        // Retornar erro em formato JSON
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    $conn = null; // Fechar conexão
} else {
    // Se não for uma solicitação POST, retornar erro
    echo json_encode(["status" => "error", "message" => "Método não permitido."]);
}
?>
