<?php
$temperatura = $_GET['ambiente'];
$humedadSuelo = $_GET['sensor'];
$fecha = date('Y-m-d H:i:s');

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'base';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO humedad (fecha, ambiente, sensor) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $fecha, $temperatura, $humedadSuelo);

if ($stmt->execute()) {
    echo "Datos guardados correctamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>