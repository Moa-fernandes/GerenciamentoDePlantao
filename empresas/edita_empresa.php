<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

$id = $_GET['id'];

// Preparar a consulta para selecionar os dados da empresa
$query = "SELECT * FROM empresas WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $contato = $_POST['contato'];
    $observacoes = $_POST['observacoes'];

    // Atualizar a empresa no banco de dados
    $query = "UPDATE empresas SET nome = :nome, endereco = :endereco, contato = :contato, observacoes = :observacoes WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
    $stmt->bindValue(':contato', $contato, PDO::PARAM_STR);
    $stmt->bindValue(':observacoes', $observacoes, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo "Empresa atualizada com sucesso.";
    } else {
        echo "Erro ao atualizar empresa.";
    }
    $stmt->closeCursor();
    $conn = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Empresa</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2>Editar Empresa</h2>
        <form method="POST" action="edita_empresa.php?id=<?php echo htmlspecialchars($id); ?>">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($empresa['nome']); ?>" required>
            <label>Endereço:</label>
            <input type="text" name="endereco" value="<?php echo htmlspecialchars($empresa['endereco']); ?>">
            <label>Contato:</label>
            <input type="text" name="contato" value="<?php echo htmlspecialchars($empresa['contato']); ?>">
            <label>Observações:</label>
            <textarea name="observacoes"><?php echo htmlspecialchars($empresa['observacoes']); ?></textarea>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
