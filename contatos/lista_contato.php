<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Contatos</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h2>Lista de Contatos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Empresa</th>
                    <th>Data de Adição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Obter lista de contatos
                    $query = "SELECT c.id, c.nome, c.telefone, c.email, e.nome as empresa, c.data_adicao
                              FROM contatos c
                              JOIN empresas e ON c.empresa_id = e.id";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_adicao']) . "</td>";
                        echo "<td><a href='edita_contato.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary'>Editar</a>";
                        echo " <a href='deleta_contato.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger delete-button'>Deletar</a></td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='7'>Erro ao listar contatos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }

                // Fechar a conexão com o banco de dados
                $conn = null;
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
