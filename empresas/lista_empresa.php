<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empresas</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Lista de Empresas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Contato</th>
                    <th>Data Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Obter lista de empresas
                    $query = "SELECT id, nome, endereco, contato, data_cadastro FROM empresas";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    // Use fetch(PDO::FETCH_ASSOC) para obter um array associativo
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['endereco']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contato']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_cadastro']) . "</td>";
                        echo "<td><a href='edita_empresa.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary'>Editar</a>";
                        echo " <a href='deleta_empresa.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger delete-button'>Deletar</a></td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='6'>Erro ao listar empresas: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <!-- Inclusão do JavaScript do Bootstrap e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
