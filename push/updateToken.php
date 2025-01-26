<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailGoogle = $_POST['emailGoogle'];
    $token = $_POST['tokenFcm'];

    // Verifica se o email já existe
    $checkSql = "SELECT * FROM usuarios WHERE email = '$emailGoogle'";
    
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Atualiza o tokenFcm se o email já existir
        $updateSql = "UPDATE usuarios SET tokenFcm = '$token' WHERE email = '$emailGoogle'";
        if ($conn->query($updateSql) === TRUE) {
            http_response_code(200); // Atualizado com sucesso
            echo json_encode(['message' => 'Token atualizado com sucesso.']);
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao atualizar o token.']);
        }
    } else {
        // Insere um novo registro
        $insertSql = "INSERT INTO usuarios (email, tokenFcm, ativo) VALUES ('$emailGoogle', '$token', 1)";
        if ($conn->query($insertSql) === TRUE) {
            http_response_code(201); // Criado com sucesso
            echo json_encode(['message' => 'Usuário criado com sucesso.']);
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao criar usuário.']);
        }
    }

    $conn->close();
}
?>
