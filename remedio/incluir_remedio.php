<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = strtolower($_POST['nome']);
    $dosagem_caixa = str_replace(',', '.', $_POST['dosagem_caixa']);
    $tipo_dosagem = strtolower($_POST['tipo_dosagem']);

    // Verifica se a dosagem_caixa é um número válido
    if (!is_numeric($dosagem_caixa)) {
        http_response_code(400); // Requisição inválida
        echo "A dosagem por caixa deve ser um número.";
        exit();
    }

    // Verifica se o remédio já está cadastrado
    $checkMedicineSql = "SELECT * FROM remedios WHERE LOWER(nome) = '$nome' AND dosagem_caixa = '$dosagem_caixa' AND LOWER(tipo_dosagem) = '$tipo_dosagem'";
    $result = $conn->query($checkMedicineSql);

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflito
        echo "Remédio já cadastrado.";
    } else {
        $sql = "INSERT INTO remedios (nome, dosagem_caixa, tipo_dosagem) VALUES ('$nome', '$dosagem_caixa', '$tipo_dosagem')";
        
        if ($conn->query($sql) === TRUE) {
            http_response_code(201); // Criado com sucesso
            echo "Remédio adicionado com sucesso!";
        } else {
            http_response_code(500); // Erro interno do servidor
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
