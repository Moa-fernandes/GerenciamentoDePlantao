<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include 'config/database.php';
include 'includes/navbar.php'; 

// Inicializar variáveis de filtro
$filtroData = $filtroStatus = $filtroID = '';

// Verificar se os filtros foram definidos
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $filtroData = isset($_GET['filtro_data']) ? $_GET['filtro_data'] : '';
    $filtroStatus = isset($_GET['filtro_status']) ? $_GET['filtro_status'] : '';
    $filtroID = isset($_GET['filtro_id']) ? $_GET['filtro_id'] : '';
}

// Dados para gráficos
$chamadosStatusData = [
    'finalizado' => 0,
    'em_espera' => 0,
    'pendente' => 0
];

$chamadosEmpresaData = [];

// Obter dados para os gráficos
try {
    // Obter contagem de chamados por status
    $stmt = $conn->query("SELECT finalizado, em_espera, COUNT(*) as count FROM chamados GROUP BY finalizado, em_espera");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['finalizado']) {
            $chamadosStatusData['finalizado'] += $row['count'];
        } elseif ($row['em_espera']) {
            $chamadosStatusData['em_espera'] += $row['count'];
        } else {
            $chamadosStatusData['pendente'] += $row['count'];
        }
    }

    // Obter contagem de chamados por empresa
    $stmt = $conn->query("SELECT e.nome, COUNT(*) as count FROM chamados c JOIN empresas e ON c.empresa_id = e.id GROUP BY e.nome");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chamadosEmpresaData[$row['nome']] = $row['count'];
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao obter dados: " . $e->getMessage() . "</div>";
}

// Obter lista de chamados pendentes
$chamadosPendentes = [];
try {
    $stmt = $conn->query("SELECT c.id, c.descricao, c.data_abertura, u.nome as tecnico_nome, e.nome as empresa_nome
                          FROM chamados c
                          JOIN empresas e ON c.empresa_id = e.id
                          JOIN usuarios u ON c.tecnico_id = u.id
                          WHERE c.finalizado = 0 AND c.em_espera = 0");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chamadosPendentes[] = $row;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao obter chamados pendentes: " . $e->getMessage() . "</div>";
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Plantão</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-4">
<h1 class="mb-4" style="text-align: center;">Relatório de Plantão</h1>
<br><br>

    <div id="alertPendentes" class="alert alert-warning" style="display: none;">
        Existem chamados pendentes!
    </div>

    <div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="text-center">Chamados por Status</h3>
        <canvas id="chamadosStatusChart"></canvas>
    </div>
    <div class="col-md-4">
        <h3 class="text-center">Chamados por Empresa</h3>
        <canvas id="chamadosEmpresaChart"></canvas>
    </div>
</div>

    <br><br>
    <br>
    <div class="mt-5">
        <h3>Detalhes dos Chamados Pendentes</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Data de Abertura</th>
                <th>Técnico</th>
                <th>Empresa</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($chamadosPendentes as $chamado): ?>
                <tr>
                    <td><?= htmlspecialchars($chamado['id']) ?></td>
                    <td><?= htmlspecialchars($chamado['descricao']) ?></td>
                    <td><?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($chamado['data_abertura']))) ?></td>
                    <td><?= htmlspecialchars($chamado['tecnico_nome']) ?></td>
                    <td><?= htmlspecialchars($chamado['empresa_nome']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctxStatus = document.getElementById('chamadosStatusChart').getContext('2d');
        var ctxEmpresa = document.getElementById('chamadosEmpresaChart').getContext('2d');

        // Dados para o gráfico de status
        var chamadosStatusData = {
            labels: ['Finalizado', 'Em Espera', 'Pendente'],
            datasets: [{
                label: 'Chamados por Status',
                data: [<?= $chamadosStatusData['finalizado'] ?>, <?= $chamadosStatusData['em_espera'] ?>, <?= $chamadosStatusData['pendente'] ?>],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        };

        // Dados para o gráfico de empresa
        var chamadosEmpresaData = {
            labels: <?= json_encode(array_keys($chamadosEmpresaData)) ?>,
            datasets: [{
                label: 'Chamados por Empresa',
                data: <?= json_encode(array_values($chamadosEmpresaData)) ?>,
                backgroundColor: '#007bff'
            }]
        };

        // Inicializar gráficos
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: chamadosStatusData,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' chamados';
                            }
                        }
                    }
                }
            }
        });

        new Chart(ctxEmpresa, {
            type: 'bar',
            data: chamadosEmpresaData,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' chamados';
                            }
                        }
                    }
                }
            }
        });

        // Mostrar alert para chamados pendentes
        <?php if (!empty($chamadosPendentes)): ?>
            $('#alertPendentes').show();
        <?php endif; ?>
    });
</script>
</body>
</html>
