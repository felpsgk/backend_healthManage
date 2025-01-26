<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_remedio = $_POST['id_remedio'];
    $emailGoogle = $_POST['emailGoogle'];
    $id_pessoa = $_POST['id_pessoa'];
    $dosagem_pessoa = $_POST['dosagem_pessoa'];
    $tipo_dosagem_pessoa = strtolower($_POST['tipo_dosagem_pessoa']); // Converte para minúsculas
    $hora_dia = $_POST['hora_dia'];
    $frequencia = $_POST['frequencia'];

    $user_id = '';
    $sqlUserId = "SELECT * FROM `usuarios` where lower(email) = lower('$emailGoogle')";
    
    $resultUserId = $conn->query($sqlUserId);
    if ($resultUserId->num_rows > 0) {
        while($row = $resultUserId->fetch_assoc()) {
            $user_id = $row['id'];
        }
    }
    
    // Verifica se o remédio com a mesma dosagem e tipo de dosagem já está atribuído à pessoa
    $checkSql = "SELECT * FROM pessoa_has_remedio WHERE id_remedio = '$id_remedio' AND id_pessoa = '$id_pessoa' AND dosagem_pessoa = '$dosagem_pessoa' AND LOWER(tipo_dosagem_pessoa) = '$tipo_dosagem_pessoa'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflito
        echo json_encode(['message' => 'Esse remédio com essa dosagem e tipo de dosagem já está atribuído a esta pessoa.']);
    } else {
        $sql = "INSERT INTO pessoa_has_remedio (id_remedio, id_pessoa, dosagem_pessoa, tipo_dosagem_pessoa) VALUES ('$id_remedio', '$id_pessoa', '$dosagem_pessoa', '$tipo_dosagem_pessoa')";
        
        if ($conn->query($sql) === TRUE) {
            $id_pessoaRemedio = $conn->insert_id;
            $sqlProgramacao = "INSERT INTO pessoaRemedio_has_programacao (id_pessoaRemedio, user_id, hora_dia, frequencia) VALUES ('$id_pessoaRemedio', '$user_id', '$hora_dia', '$frequencia')";
            if ($conn->query($sqlProgramacao) === TRUE) {
                http_response_code(201); // Criado com sucesso
                echo json_encode(['message' => 'Remédio atribuído à pessoa com sucesso!']);
            } else {
                http_response_code(500); // Erro interno do servidor ao inserir a programação
                echo json_encode(['message' => 'Erro interno do servidor ao inserir a programação.']);
            }
        } else {
            http_response_code(500); // Erro interno do servidor
            echo json_encode(['message' => 'Erro interno do servidor ao atribuir remédio.']);
        }
    }

    $conn->close();
}
?>