<?php
// Incluir o arquivo de configuração e conexão com o banco de dados
include '../config/database.php';

$id = $_GET['id'];
$query = "SELECT * FROM contatos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$contato = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $empresa_id = $_POST['empresa_id'];
    $observacoes = $_POST['observacoes'];

    // Atualizar o contato no banco de dados
    $query = "UPDATE contatos SET nome = ?, telefone = ?, email = ?, empresa_id = ?, observacoes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisi", $nome, $telefone, $email, $empresa_id, $observacoes, $id);
    if ($stmt->execute()) {
        echo "Contato atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar contato: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Contato</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h2>Editar Contato</h2>
        <form method="POST" action="edita_contato.php?id=<?php echo $id; ?>">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo $contato['nome']; ?>" required>
            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $contato['telefone']; ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $contato['email']; ?>" required>
            <label>Empresa:</label>
            <select name="empresa_id" required>
                <!-- Código PHP para listar empresas -->
                <?php
                $result = $conn->query("SELECT id, nome FROM empresas");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'" . ($contato['empresa_id'] == $row['id'] ? " selected" : "") . ">" . $row['nome'] . "</option>";
                }
                ?>
            </select>
            <label>Observações:</label>
            <textarea name="observacoes"><?php echo $contato['observacoes']; ?></textarea>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
