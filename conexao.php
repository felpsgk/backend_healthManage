<?php
$servername = "localhost:3306"; 
$username = "felpst09_healthmanageadmin";
$password = "felipepereiramachado";
$dbname = "felpst09_healthmanage";

// Cria a Conex«ªo
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a Conex«ªo
if ($conn->connect_error) {
    die("Conex«ªo falhou: " . $conn->connect_error);
}
?>
