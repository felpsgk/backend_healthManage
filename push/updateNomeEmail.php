<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $displayName = $_POST['displayName'];
    $ativo = 1; // Valor fixo para 'ativo'

    // Verifica se o email já existe na tabela
    $checkSql = "SELECT * FROM usuarios WHERE lower(email) = lower('$email')";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Se existir, realiza a atualização
        $updateSql = "UPDATE usuarios SET nome = lower('$displayName'), ativo = '$ativo' WHERE lower(email) = lower('$email')";
        if ($conn->query($updateSql) === TRUE) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Registro atualizado com sucesso.']);
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao atualizar o registro.']);
        }
    } else {
        // Se não existir, realiza a inserção
        $insertSql = "INSERT INTO usuarios (nome, email, ativo) VALUES ('$displayName', '$email', '$ativo')";
        if ($conn->query($insertSql) === TRUE) {
            http_response_code(201); // Criado com sucesso
            echo json_encode(['message' => 'Registro inserido com sucesso.']);
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro ao inserir o registro.']);
        }
    }

    $conn->close();
}
?>
