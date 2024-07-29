<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "Conexão falhou: " . $conn->connect_error;
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "DELETE FROM contatos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Contato excluído com sucesso";
    } else {
        echo "Erro ao excluir contato: " . $conn->error;
    }
} else {
    echo "ID inválido.";
}

$conn->close();
?>
