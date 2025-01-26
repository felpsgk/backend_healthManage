<?php
include '../conexao.php';

$id_remedio = $_GET['id_remedio'];

$sql = "SELECT p.nome AS nome_pessoa, pr.dosagem_pessoa, pr.tipo_dosagem_pessoa 
        FROM pessoa_has_remedio pr
        JOIN pessoa p ON pr.id_pessoa = p.id
        WHERE pr.id_remedio = '$id_remedio'";
$result = $conn->query($sql);

$pessoas = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pessoas[] = $row;
    }
}

echo json_encode($pessoas);
$conn->close();
?>
