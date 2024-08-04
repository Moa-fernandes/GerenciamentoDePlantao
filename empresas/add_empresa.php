<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $contato = $_POST['contato'];
    $observacoes = $_POST['observacoes'];

    // Inserir a empresa no banco de dados usando PDO
    try {
        $query = "INSERT INTO empresas (nome, endereco, contato, observacoes, data_cadastro) VALUES (:nome, :endereco, :contato, :observacoes, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
        $stmt->bindValue(':contato', $contato, PDO::PARAM_STR);
        $stmt->bindValue(':observacoes', $observacoes, PDO::PARAM_STR);
        $stmt->execute();
        echo "<div class='alert alert-success'>Empresa adicionada com sucesso.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao adicionar empresa: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Empresa</title>
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
        <h2 class="mb-4">Adicionar Empresa</h2>
        <div class="form-container">
            <form method="POST" action="add_empresa.php" id="formAddEmpresa">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="endereco">Endereço:</label>
                        <input type="text" class="form-control" id="endereco" name="endereco">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="contato">Contato:</label>
                        <input type="text" class="form-control" id="contato" name="contato">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="observacoes">Observações:</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                        <i class="fas fa-comment-alt"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formAddEmpresa').on('submit', function(e) {
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
