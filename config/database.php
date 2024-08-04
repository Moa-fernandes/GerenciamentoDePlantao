<?php
// Configurações de conexão com o banco de dados
$host = '';
$dbname = '';
$username = '';
$password = '';

try {
    // Criação da conexão usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Definir o modo de erro do PDO para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Exibir mensagem de erro se a conexão falhar
    echo "Erro de conexão: " . $e->getMessage();
    exit();
}
