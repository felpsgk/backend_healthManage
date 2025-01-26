<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['ativo'] == 0) {
            http_response_code(403); // Usuário inativo
            echo "Usuário inativo.";
        } elseif (password_verify($senha, $user['senha'])) {
            http_response_code(200); // Login bem-sucedido
            echo "Login bem-sucedido!";
        } else {
            http_response_code(401); // Senha incorreta
            echo "Senha incorreta.";
        }
    } else {
        http_response_code(404); // Usuário não encontrado
        echo "Usuário não encontrado.";
    }

    $conn->close();
}
?>
