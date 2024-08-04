<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $empresa_id = $_POST['empresa_id'];
    $observacoes = $_POST['observacoes'];
    $data_adicao = date('Y-m-d H:i:s'); // Data atual para data_adicao

    try {
        // Inserir o contato no banco de dados
        $query = "INSERT INTO contatos (nome, email, telefone, empresa_id, observacoes, data_adicao) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $telefone);
        $stmt->bindValue(4, $empresa_id);
        $stmt->bindValue(5, $observacoes);
        $stmt->bindValue(6, $data_adicao);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Contato adicionado com sucesso.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao adicionar contato: " . implode(" ", $stmt->errorInfo()) . "</div>";
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
    <title>Adicionar Contato</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <style>
        body {
            background-color: #f5f5f5; /* Fundo cinza pastel */
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border: 1px solid #ced4da; /* Borda padrão */
            border-radius: 0.25rem;
            height: calc(1.5em + .75rem + 2px); /* Diminuir altura */
        }
        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }
        .form-group i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Adicionar Contato</h2>
        <div class="form-container">
            <form method="POST" action="add_contato.php" id="formAddContato">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="telefone">Telefone:</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" required>
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="empresa_id">Empresa:</label>
                        <select class="form-control" id="empresa_id" name="empresa_id" required>
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
                </div>
                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                    <i class="fas fa-comment-alt"></i>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </form>
        </div>
    </div>

    <!-- Inclusão do JavaScript do Bootstrap e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formAddContato').on('submit', function(e) {
                var nome = $('#nome').val();
                if (nome === '') {
                    e.preventDefault();
                    alert('O campo Nome é obrigatório.');
                }
            });
        });
    </script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
