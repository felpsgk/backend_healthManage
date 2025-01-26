<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verifica se o email já está cadastrado
    $checkEmailSql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($checkEmailSql);

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflito
        echo "Email já cadastrado. Tente outro email.";
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        
        if ($conn->query($sql) === TRUE) {
            http_response_code(201); // Criado com sucesso
            echo "Usuário registrado com sucesso!";
        } else {
            http_response_code(500); // Erro interno do servidor
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
} else {
    echo "trouxisse";
}
?>
