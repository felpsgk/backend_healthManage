<?php
/*include '../conexao.php';

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


$conn->close();*/
?>
<?php
include '../conexao.php';

$id_grupo = $_POST['id_grupo'];
$id_pessoa = $_POST['id_pessoa']; // Alterado de user_id para id_pessoa

// Verifica se a relação já existe para evitar duplicatas
$check_sql = "SELECT COUNT(*) as total FROM pessoa_grupoCuidado WHERE id_pessoa = '$id_pessoa' AND id_grupoCuidado = '$id_grupo'";
$result = $conn->query($check_sql);
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
    http_response_code(409); // Conflito (registro já existe)
    echo json_encode(['message' => 'A pessoa já está neste grupo de cuidado.']);
} else {
    // Insere a nova relação
    $insert_sql = "INSERT INTO pessoa_grupoCuidado (id_pessoa, id_grupoCuidado) VALUES ('$id_pessoa', '$id_grupo')";
    
    if ($conn->query($insert_sql) === TRUE) {
        http_response_code(201); // Criado com sucesso
        echo json_encode(['message' => 'Pessoa adicionada ao grupo de cuidado com sucesso!']);
    } else {
        http_response_code(500); // Erro no servidor
        echo json_encode(['message' => 'Erro ao adicionar pessoa ao grupo de cuidado: ' . $conn->error]);
    }
}

$conn->close();
?>

