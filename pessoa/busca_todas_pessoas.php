<?php
include '../conexao.php';

$sql = "SELECT id, user_id, nome FROM pessoa";
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
