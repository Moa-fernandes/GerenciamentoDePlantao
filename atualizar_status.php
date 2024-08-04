<?php
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chamadoId = $_POST['id'];
    $finalizar = isset($_POST['finalizar']) ? (int)$_POST['finalizar'] : 0;
    $dataRetorno = isset($_POST['data_retorno']) ? $_POST['data_retorno'] : null;

    try {
        if ($finalizar) {
            // Atualizar status para finalizado e limpar data de retorno
            $query = "UPDATE chamados SET finalizado = 1, em_espera = 0, data_fechamento = NOW(), data_retorno = NULL WHERE id = :id";
        } else {
            // Atualizar status para em espera e definir data de retorno
            $query = "UPDATE chamados SET finalizado = 0, em_espera = 1, data_retorno = :data_retorno WHERE id = :id";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $chamadoId, PDO::PARAM_INT);

        if (!$finalizar && $dataRetorno) {
            $stmt->bindParam(':data_retorno', $dataRetorno);
        }

        $stmt->execute();

        // Retornar resposta de sucesso
        echo json_encode(["status" => "success"]);
    } catch (PDOException $e) {
        // Retornar resposta de erro
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    $conn = null; // Fechar conexão
}
