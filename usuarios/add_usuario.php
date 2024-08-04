<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $especialidade = $_POST['especialidade'];
    $data_contratacao = $_POST['data_contratacao'];
    $observacoes = $_POST['observacoes'];

    // Verificar se todos os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($telefone) || empty($especialidade) || empty($data_contratacao)) {
        $error_message = "Todos os campos são obrigatórios.";
    } else {
        try {
            // Inserir o usuário no banco de dados
            $query = "INSERT INTO usuarios (nome, email, telefone, especialidade, data_contratacao, observacoes) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1, $nome);
            $stmt->bindValue(2, $email);
            $stmt->bindValue(3, $telefone);
            $stmt->bindValue(4, $especialidade);
            $stmt->bindValue(5, $data_contratacao);
            $stmt->bindValue(6, $observacoes);
            
            if ($stmt->execute()) {
                $success_message = "Usuário adicionado com sucesso.";
            } else {
                $error_message = "Erro ao adicionar usuário: " . $stmt->errorInfo()[2];
            }
        } catch (PDOException $e) {
            $error_message = "Erro: " . $e->getMessage();
        }
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Usuário</title>
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
        <h2 class="mb-4">Adicionar Usuário</h2>
        <div class="form-container">
            <?php
            // Exibir mensagens de erro ou sucesso
            if (isset($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            if (isset($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            }
            ?>
            <form method="POST" action="add_usuario.php" id="formAddUsuario">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="telefone">Telefone:</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" required>
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="especialidade">Especialidade:</label>
                        <input type="text" class="form-control" id="especialidade" name="especialidade" required>
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="data_contratacao">Data de Contratação:</label>
                        <input type="date" class="form-control" id="data_contratacao" name="data_contratacao" required>
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="observacoes">Observações:</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                        <i class="fas fa-comment-alt"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formAddUsuario').on('submit', function(e) {
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
