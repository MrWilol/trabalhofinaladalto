<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => "Conexão falhou: " . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM contatos WHERE id=$id");
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM contatos");
            $contacts = [];
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
            echo json_encode($contacts);
        }
        break;

    case 'POST':
        $nome = $conn->real_escape_string($_POST['nome']);
        $telefone = $conn->real_escape_string($_POST['telefone']);
        $email = $conn->real_escape_string($_POST['email']);

        if (!empty($nome) && !empty($telefone) && !empty($email)) {
            $sql = "INSERT INTO contatos (nome, telefone, email) VALUES ('$nome', '$telefone', '$email')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => "Novo contato criado com sucesso"]);
            } else {
                echo json_encode(['error' => "Erro: " . $sql . "<br>" . $conn->error]);
            }
        } else {
            echo json_encode(['error' => "Dados do formulário não estão completos."]);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = intval($_PUT['id']);
        $nome = $conn->real_escape_string($_PUT['nome']);
        $telefone = $conn->real_escape_string($_PUT['telefone']);
        $email = $conn->real_escape_string($_PUT['email']);

        if ($id > 0 && !empty($nome) && !empty($telefone) && !empty($email)) {
            $sql = "UPDATE contatos SET nome='$nome', telefone='$telefone', email='$email' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => "Contato atualizado com sucesso"]);
            } else {
                echo json_encode(['error' => "Erro ao atualizar contato: " . $conn->error]);
            }
        } else {
            echo json_encode(['error' => "Dados do formulário não estão completos ou ID inválido."]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = intval($_DELETE['id']);

        if ($id > 0) {
            $sql = "DELETE FROM contatos WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => "Contato excluído com sucesso"]);
            } else {
                echo json_encode(['error' => "Erro ao excluir contato: " . $conn->error]);
            }
        } else {
            echo json_encode(['error' => "ID inválido."]);
        }
        break;

    default:
        echo json_encode(['error' => "Método não suportado"]);
        break;
}

$conn->close();
?>
