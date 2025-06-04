<?php
include 'cn.php';

if (!isset($_GET['funcionId']) || !is_numeric($_GET['funcionId'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID de función no válido."]);
    exit;
}

$funcionId = $_GET['funcionId'];

$query = "SELECT fila, columna FROM asiento WHERE funcionId = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $funcionId);
$stmt->execute();
$resultado = $stmt->get_result();

$asientos = [];
while ($row = $resultado->fetch_assoc()) {
    $asientos[] = [$row['fila'], (int)$row['columna']];
}

header('Content-Type: application/json');
echo json_encode($asientos);
