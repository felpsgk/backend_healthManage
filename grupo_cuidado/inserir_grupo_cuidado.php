<?php
include '../conexao.php';

$email = $_POST['email'];
$nome_grupo = $_POST['nome_grupo'];

// Obter id_user com base no email
$sqlUserId = "SELECT id FROM usuarios WHERE lower(email) = lower('$email')";
$resultUserId = $conn->query($sqlUserId);
$id_user = null;

if ($resultUserId->num_rows > 0) {
    while($row = $resultUserId->fetch_assoc()) {
        $id_user = $row['id'];
    }
}

if ($id_user !== null) {
    $sql = "INSERT INTO grupo_cuidado (id_user, grupo_cuidado) VALUES ('$id_user', '$nome_grupo')";

    if ($conn->query($sql) === TRUE) {
        http_response_code(201); // Criado com sucesso
        echo json_encode(['message' => 'grupo de cuidado criado!']);
    } else {
        http_response_code(500); // erro
        echo json_encode(['message' => 'Erro interno do servidor ao inserir grupo de cuidado.']);
    }
} else {
    http_response_code(409); // erro
    echo json_encode(['message' => 'Usuário não encontrado.']);
}

$conn->close();
?>
