<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Chamados</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .status-ball {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #000;
        }
        .status-finalizado { background-color: #28a745; }
        .status-em-espera { background-color: #ffc107; }
        .status-pendente { background-color: #dc3545; }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Lista de Chamados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Técnico</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data Abertura</th>
                    <th>Data Fechamento</th>
                    <th>Troca de Técnico</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Obter lista de chamados
                    $query = "SELECT c.id, e.nome as empresa, u.nome as tecnico, c.descricao, c.finalizado, c.em_espera, c.data_abertura, c.data_fechamento, c.data_troca
                              FROM chamados c
                              JOIN empresas e ON c.empresa_id = e.id
                              JOIN usuarios u ON c.tecnico_id = u.id";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $statusClass = '';
                        $statusText = 'Pendente';

                        if ($row['finalizado']) {
                            $statusClass = 'status-finalizado';
                            $statusText = 'Finalizado';
                        } elseif ($row['em_espera']) {
                            $statusClass = 'status-em-espera';
                            $statusText = 'Em Espera';
                        } else {
                            $statusClass = 'status-pendente';
                        }

                        // Formatar datas
                        $dataAbertura = date("d/m/Y H:i:s", strtotime($row['data_abertura']));
                        $dataFechamento = $row['data_fechamento'] ? date("d/m/Y H:i:s", strtotime($row['data_fechamento'])) : 'N/A';
                        $dataTroca = $row['data_troca'] ? date("d/m/Y H:i:s", strtotime($row['data_troca'])) : 'N/A';

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tecnico']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                        echo "<td><span class='status-ball {$statusClass}'></span> {$statusText}</td>";
                        echo "<td>" . htmlspecialchars($dataAbertura) . "</td>";
                        echo "<td>" . htmlspecialchars($dataFechamento) . "</td>";
                        echo "<td>" . htmlspecialchars($dataTroca) . "</td>";
                        echo "<td><a href='edita_chamado.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Editar</a>";
                        echo " <a href='deleta_chamado.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm delete-button'>Deletar</a></td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='9'>Erro ao listar chamados: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }

                // Fechar a conexão com o banco de dados
                $conn = null;
                ?>
            </tbody>
        </table>
    </div>
    <!-- Inclusão do JavaScript do Bootstrap e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
