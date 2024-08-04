<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

$id = $_GET['id'];
$query = "SELECT * FROM chamados WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$chamado = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $empresa_id = $_POST['empresa_id'];
    $tecnico_id = $_POST['tecnico_id'];
    $descricao = $_POST['descricao'];
    $observacoes = $_POST['observacoes'];
    $status = $_POST['status'];

    // Atualizar o chamado no banco de dados
    $query = "UPDATE chamados SET empresa_id = ?, tecnico_id = ?, descricao = ?, status = ?, observacoes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssi", $empresa_id, $tecnico_id, $descricao, $status, $observacoes, $id);
    if ($stmt->execute()) {
        echo "Chamado atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar chamado: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Chamado</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2>Editar Chamado</h2>
        <form method="POST" action="edita_chamado.php?id=<?php echo $id; ?>">
            <label>Empresa:</label>
            <select name="empresa_id" required>
                <!-- Código PHP para listar empresas -->
                <?php
                $result = $conn->query("SELECT id, nome FROM empresas");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'" . ($chamado['empresa_id'] == $row['id'] ? " selected" : "") . ">" . $row['nome'] . "</option>";
                }
                ?>
            </select>
            <label>Técnico:</label>
            <select name="tecnico_id" required>
                <!-- Código PHP para listar técnicos -->
                <?php
                $result = $conn->query("SELECT id, nome FROM usuarios");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'" . ($chamado['tecnico_id'] == $row['id'] ? " selected" : "") . ">" . $row['nome'] . "</option>";
                }
                ?>
            </select>
            <label>Descrição:</label>
            <textarea name="descricao" required><?php echo $chamado['descricao']; ?></textarea>
            <label>Status:</label>
            <select name="status" required>
                <option value="Aberto" <?php echo ($chamado['status'] == 'Aberto' ? 'selected' : ''); ?>>Aberto</option>
                <option value="Em Progresso" <?php echo ($chamado['status'] == 'Em Progresso' ? 'selected' : ''); ?>>Em Progresso</option>
                <option value="Fechado" <?php echo ($chamado['status'] == 'Fechado' ? 'selected' : ''); ?>>Fechado</option>
            </select>
            <label>Observações:</label>
            <textarea name="observacoes"><?php echo $chamado['observacoes']; ?></textarea>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
