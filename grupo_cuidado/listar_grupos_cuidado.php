<?php
include '../conexao.php';

$sql = "SELECT id, grupo_cuidado FROM grupo_cuidado";
$result = $conn->query($sql);

$grupos_cuidado = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $grupos_cuidado[] = $row;
    }
}
echo json_encode($grupos_cuidado);
$conn->close();
?>
