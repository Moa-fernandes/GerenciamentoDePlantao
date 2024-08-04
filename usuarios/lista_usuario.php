<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuários</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            margin: 2px;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2>Lista de Usuários</h2>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Especialidade</th>
                    <th>Data de Contratação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obter lista de usuários
                $query = "SELECT id, nome, email, telefone, especialidade, DATE_FORMAT(data_contratacao, '%d/%m/%Y') as data_contratacao FROM usuarios";
                $stmt = $conn->query($query);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['telefone'] . "</td>";
                    echo "<td>" . $row['especialidade'] . "</td>";
                    echo "<td>" . $row['data_contratacao'] . "</td>";
                    echo "<td>";
                    echo "<a href='edita_usuario.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Editar</a>";
                    echo "<a href='deleta_usuario.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Deletar</a>";
                    echo "</td>";
                    echo "</tr>";
                }

                $conn = null;
                ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
