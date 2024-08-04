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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Plantão - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
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

<div class="container mt-4">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Empresas</h5>
                    <p class="card-text">
                        <?php
                        // Contar o número de empresas
                        $query = "SELECT COUNT(*) as total FROM empresas";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $result['total'];
                        ?>
                    </p>
                    <a href="empresas/lista_empresa.php" class="btn btn-light">Ver Mais</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Usuários</h5>
                    <p class="card-text">
                        <?php
                        // Contar o número de usuários
                        $query = "SELECT COUNT(*) as total FROM usuarios";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $result['total'];
                        ?>
                    </p>
                    <a href="usuarios/lista_usuario.php" class="btn btn-light">Ver Mais</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Contatos</h5>
                    <p class="card-text">
                        <?php
                        // Contar o número de contatos
                        $query = "SELECT COUNT(*) as total FROM contatos";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $result['total'];
                        ?>
                    </p>
                    <a href="contatos/lista_contato.php" class="btn btn-light">Ver Mais</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Chamados</h5>
                    <p class="card-text">
                        <?php
                        // Contar o número de chamados
                        $query = "SELECT COUNT(*) as total FROM chamados";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $result['total'];
                        ?>
                    </p>
                    <a href="chamados/lista_chamado.php" class="btn btn-light">Ver Mais</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title">Filtrar Atividades Recentes</h5>
                    <form class="form-inline mb-4" method="GET" action="index.php">
                        <div class="form-group mr-2">
                            <label for="filtro_id" class="mr-2">ID</label>
                            <input type="text" class="form-control" id="filtro_id" name="filtro_id" value="<?= htmlspecialchars($filtroID); ?>">
                        </div>
                        <div class="form-group mr-2">
                            <label for="filtro_data" class="mr-2">Data</label>
                            <input type="date" class="form-control" id="filtro_data" name="filtro_data" value="<?= htmlspecialchars($filtroData); ?>">
                        </div>
                        <div class="form-group mr-2">
                            <label for="filtro_status" class="mr-2">Status</label>
                            <select class="form-control" id="filtro_status" name="filtro_status">
                                <option value="">Todos</option>
                                <option value="finalizado" <?= $filtroStatus == 'finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                                <option value="em_espera" <?= $filtroStatus == 'em_espera' ? 'selected' : ''; ?>>Em Espera</option>
                                <option value="pendente" <?= $filtroStatus == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>

                    <p class="card-text">Lista de atividades recentes dos Plantões.</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Data de Abertura</th>
                                <th>Técnico</th>
                                <th>Empresa</th>
                                <th>Horário de Troca</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="atividade-list">
                            <?php
                            // Construir consulta com filtros
                            $query = "SELECT c.id AS chamado_id, e.nome AS empresa_nome, u.nome AS tecnico_nome, c.descricao, c.data_abertura, c.finalizado, c.em_espera, c.data_retorno, c.data_troca
                                      FROM chamados c
                                      JOIN empresas e ON c.empresa_id = e.id
                                      JOIN usuarios u ON c.tecnico_id = u.id
                                      WHERE 1=1";
                            
                            if ($filtroID) {
                                $query .= " AND c.id = :filtroID";
                            }
                            if ($filtroData) {
                                $query .= " AND DATE(c.data_abertura) = :filtroData";
                            }
                            if ($filtroStatus) {
                                if ($filtroStatus == 'finalizado') {
                                    $query .= " AND c.finalizado = 1";
                                } elseif ($filtroStatus == 'em_espera') {
                                    $query .= " AND c.em_espera = 1";
                                } else {
                                    $query .= " AND c.finalizado = 0 AND c.em_espera = 0";
                                }
                            }
                            $query .= " ORDER BY c.data_abertura DESC LIMIT 5";

                            try {
                                $stmt = $conn->prepare($query);

                                if ($filtroID) $stmt->bindParam(':filtroID', $filtroID, PDO::PARAM_INT);
                                if ($filtroData) $stmt->bindParam(':filtroData', $filtroData, PDO::PARAM_STR);

                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $statusClass = '';
                                    $statusText = 'Pendente';

                                    if ($row['finalizado']) {
                                        $statusClass = 'status-finalizado'; // Bola verde para finalizado
                                        $statusText = 'Finalizado';
                                    } elseif ($row['em_espera']) {
                                        $statusClass = 'status-em-espera'; // Bola amarela para em espera
                                        $statusText = 'Em Espera';
                                    } else {
                                        $statusClass = 'status-pendente'; // Bola vermelha para não finalizado
                                    }

                                    $horarioTroca = isset($row['data_troca']) ? $row['data_troca'] : 'Não Definido';

                                    echo "<tr>
                                        <td>{$row['chamado_id']}</td>
                                        <td>{$row['descricao']}</td>
                                        <td>{$row['data_abertura']}</td>
                                        <td>{$row['tecnico_nome']}</td>
                                        <td>{$row['empresa_nome']}</td>
                                        <td>{$horarioTroca}</td>
                                        <td><span class='status-ball {$statusClass}'></span> {$statusText}</td>
                                        <td>
                                            <button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#statusModal' data-id='{$row['chamado_id']}' data-em-espera='{$row['em_espera']}'>Atualizar Status</button>
                                            <button class='btn btn-sm btn-secondary' data-toggle='modal' data-target='#tecnicoModal' data-id='{$row['chamado_id']}' data-tecnico='{$row['tecnico_nome']}'>Trocar Técnico</button>
                                        </td>
                                    </tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='8'>Erro ao obter chamados: " . $e->getMessage() . "</td></tr>";
                            }

                            $conn = null;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para atualização de status -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Atualizar Status do Chamado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="chamadoId">
                    <div class="form-group">
                        <label for="data_retorno">Data e Hora de Retorno</label>
                        <input type="datetime-local" class="form-control" id="data_retorno">
                    </div>
                    <div class="form-group">
                        <button type="button" id="setEmEspera" class="btn btn-warning">Mover para Espera</button>
                        <button type="button" id="finalizarChamado" class="btn btn-danger">Finalizar Chamado</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para troca de técnico -->
    <div class="modal fade" id="tecnicoModal" tabindex="-1" role="dialog" aria-labelledby="tecnicoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tecnicoModalLabel">Trocar Técnico do Chamado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="chamadoIdTecnico">
                    <div class="form-group">
                        <label for="novoTecnico">Selecione o Novo Técnico</label>
                        <select class="form-control" id="novoTecnico">
                            <!-- Populado com AJAX -->
                        </select>
                    </div>
                    <button type="button" id="trocarTecnico" class="btn btn-primary">Trocar Técnico</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar modal de status e definir o ID do chamado
            $('#statusModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que abriu o modal
                var id = button.data('id'); // Extraindo o ID do botão
                var emEspera = button.data('em-espera'); // Extraindo o status de em espera do botão

                var modal = $(this);
                modal.find('#chamadoId').val(id);
                modal.find('#data_retorno').val(emEspera ? modal.find('#data_retorno').val() : '');
            });

            // Mover para espera
            $('#setEmEspera').on('click', function () {
                var id = $('#chamadoId').val();
                var dataRetorno = $('#data_retorno').val();

                if (!dataRetorno) {
                    alert('Por favor, defina a data e hora de retorno.');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'atualizar_status.php',
                    data: {
                        id: id,
                        finalizar: 0,
                        data_retorno: dataRetorno
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            location.reload(); // Recarregar a página para refletir as mudanças
                        } else {
                            alert('Erro ao atualizar status: ' + data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Erro na requisição: ' + error);
                    }
                });
            });

            // Finalizar chamado
            $('#finalizarChamado').on('click', function () {
                var id = $('#chamadoId').val();

                $.ajax({
                    type: 'POST',
                    url: 'atualizar_status.php',
                    data: {
                        id: id,
                        finalizar: 1
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            location.reload(); // Recarregar a página para refletir as mudanças
                        } else {
                            alert('Erro ao atualizar status: ' + data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Erro na requisição: ' + error);
                    }
                });
            });

            // Mostrar modal de troca de técnico e carregar lista de técnicos
            $('#tecnicoModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que abriu o modal
                var id = button.data('id'); // Extraindo o ID do chamado
                var modal = $(this);
                modal.find('#chamadoIdTecnico').val(id);

                // Carregar lista de técnicos disponíveis
                $.ajax({
                    type: 'GET',
                    url: 'obter_tecnicos.php',
                    success: function (response) {
                        var tecnicos = JSON.parse(response);
                        var options = '';
                        tecnicos.forEach(function(tecnico) {
                            options += '<option value="' + tecnico.id + '">' + tecnico.nome + '</option>';
                        });
                        modal.find('#novoTecnico').html(options);
                    },
                    error: function (xhr, status, error) {
                        alert('Erro ao carregar lista de técnicos: ' + error);
                    }
                });
            });

            // Trocar técnico
            $('#trocarTecnico').on('click', function () {
                var chamadoId = $('#chamadoIdTecnico').val();
                var novoTecnicoId = $('#novoTecnico').val();

                $.ajax({
                    type: 'POST',
                    url: 'trocar_tecnico.php',
                    data: {
                        chamado_id: chamadoId,
                        novo_tecnico_id: novoTecnicoId
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            location.reload(); // Recarregar a página para refletir as mudanças
                        } else {
                            alert('Erro ao trocar técnico: ' + data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Erro na requisição: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
