<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

$id = $_GET['id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $especialidade = $_POST['especialidade'];
    $data_contratacao = $_POST['data_contratacao'];
    $observacoes = $_POST['observacoes'];

    // Atualizar o usuário no banco de dados
    $query = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, especialidade = ?, data_contratacao = ?, observacoes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $nome, $email, $telefone, $especialidade, $data_contratacao, $observacoes, $id);
    if ($stmt->execute()) {
        echo "Usuário atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar usuário: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuário</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2>Editar Usuário</h2>
        <form method="POST" action="edita_usuario.php?id=<?php echo $id; ?>">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo $usuario['nome']; ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $usuario['telefone']; ?>" required>
            <label>Especialidade:</label>
            <input type="text" name="especialidade" value="<?php echo $usuario['especialidade']; ?>" required>
            <label>Data de Contratação:</label>
            <input type="date" name="data_contratacao" value="<?php echo $usuario['data_contratacao']; ?>" required>
            <label>Observações:</label>
            <textarea name="observacoes"><?php echo $usuario['observacoes']; ?></textarea>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
