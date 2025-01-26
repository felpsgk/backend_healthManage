<?php
include '../conexao.php';

$id_pessoa = $_GET['id_pessoa'];

$sql = "SELECT r.nome AS nome_remedio, 
       pr.dosagem_pessoa, 
       pr.tipo_dosagem_pessoa, 
       TIME_FORMAT(prp.hora_dia, '%H:%i') AS hora_dia
        FROM pessoa_has_remedio pr
        JOIN remedios r ON pr.id_remedio = r.id
        JOIN pessoaRemedio_has_programacao prp ON pr.id = prp.id_pessoaRemedio
        WHERE pr.id_pessoa = '$id_pessoa'
          AND prp.hora_dia BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 6 HOUR)
        ORDER BY prp.hora_dia";
$result = $conn->query($sql);

$remedios = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $remedios[] = $row;
    }
}

echo json_encode($remedios);
$conn->close();
?>
