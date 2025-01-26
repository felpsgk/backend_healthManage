<?php
include '../conexao.php';

$id_grupo = $_POST['id_grupo'];
$id_user = $_POST['user_id'];

$sql = "UPDATE usuarios SET id_grupoCuidado = '$id_grupo' WHERE id = '$id_user'";

if ($conn->query($sql) === TRUE) {
    http_response_code(201); // Criado com sucesso
    echo json_encode(['message' => 'Pessoa adicionada ao grupo de cuidado com sucesso!']);
} else {
    http_response_code(500); // erro
    echo json_encode(['message' => 'Erro ao adicionar pessoa ao grupo de cuidado: ' . $conn->error]);
}


$conn->close();
?>
