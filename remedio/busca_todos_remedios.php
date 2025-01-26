<?php
include '../conexao.php';

$sql = "SELECT id, nome,dosagem_caixa,tipo_dosagem FROM remedios";
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
