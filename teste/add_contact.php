<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "Conexão falhou: " . $conn->connect_error;
    exit();
}

$nome = isset($_POST['nome']) ? $conn->real_escape_string($_POST['nome']) : '';
$telefone = isset($_POST['telefone']) ? $conn->real_escape_string($_POST['telefone']) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';

if (!empty($nome) && !empty($telefone) && !empty($email)) {
    $sql = "INSERT INTO contatos (nome, telefone, email) VALUES ('$nome', '$telefone', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo registro criado com sucesso";
    } else {
        echo "Erro: " . $conn->error;
    }
} else {
    echo "Dados do formulário não estão completos.";
}

$conn->close();
?>
