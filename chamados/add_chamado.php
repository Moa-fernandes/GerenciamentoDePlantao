<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $empresa_id = $_POST['empresa_id'];
    $tecnico_id = $_POST['tecnico_id'];
    $descricao = $_POST['descricao'];
    $observacoes = $_POST['observacoes'];

    try {
        // Inserir o chamado no banco de dados
        $query = "INSERT INTO chamados (empresa_id, tecnico_id, descricao, data_abertura, observacoes) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $empresa_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $tecnico_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $descricao, PDO::PARAM_STR);
        $stmt->bindValue(4, $observacoes, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Chamado adicionado com sucesso.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao adicionar chamado: " . implode(" ", $stmt->errorInfo()) . "</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Chamado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .form-container {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 0.2rem;
            height: calc(1.25em + .75rem + 2px);
            font-size: 0.875rem; /* Font size reduced */
        }
        .form-group {
            margin-bottom: 0.8rem;
            position: relative;
        }
        .form-group label {
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }
        .btn {
            padding: 0.3rem 0.75rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2 class="mb-3">Adicionar Chamado</h2>
        <div class="form-container">
            <form method="POST" action="add_chamado.php">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="empresa_id">Empresa:</label>
                        <select name="empresa_id" id="empresa_id" class="form-control" required>
                            <option value="">Selecione a empresa</option>
                            <?php
                            // Obter lista de empresas
                            try {
                                $result = $conn->query("SELECT id, nome FROM empresas");
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option value=''>Erro ao obter empresas: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tecnico_id">Técnico:</label>
                        <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                            <option value="">Selecione o técnico</option>
                            <?php
                            // Obter lista de técnicos
                            try {
                                $result = $conn->query("SELECT id, nome FROM usuarios");
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option value=''>Erro ao obter técnicos: " . htmlspecialchars($e->getMessage()) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="descricao">Descrição:</label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="observacoes">Observações:</label>
                        <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </form>
        </div>
    </div>

    <!-- Inclusão do JavaScript do Bootstrap e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
