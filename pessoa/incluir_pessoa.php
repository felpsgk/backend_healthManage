<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];
    $cpf = $_POST['cpf'];
    $identidade = $_POST['identidade'];
    $doc_extra = $_POST['doc_extra'];
    $doc_extra2 = $_POST['doc_extra2'];

    // Verifica se o CPF já está cadastrado
    $checkCpfSql = "SELECT * FROM pessoa WHERE cpf = '$cpf'";
    $result = $conn->query($checkCpfSql);

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflito
        echo "CPF já cadastrado.";
    } else {
        $sql = "INSERT INTO pessoa (nome, idade, sexo, cpf, identidade, doc_extra, doc_extra2) VALUES ('$nome', '$idade', '$sexo', '$cpf', '$identidade', '$doc_extra', '$doc_extra2')";
        
        if ($conn->query($sql) === TRUE) {
            http_response_code(201); // Criado com sucesso
            echo "Pessoa adicionada com sucesso!";
        } else {
            http_response_code(500); // Erro interno do servidor
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
