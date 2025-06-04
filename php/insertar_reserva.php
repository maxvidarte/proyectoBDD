<?php
include 'cn.php';
$data = json_decode(file_get_contents('php://input'), true);

$clienteId = $data['clienteId'];
$funcionId = $data['funcionId'];
$cantidad = $data['cantidad'];
$total = $data['total'];
$asientos = $data['asientos'];

if (!$clienteId || !$funcionId || !$cantidad || !$total || empty($asientos)) {
    http_response_code(400);
    exit("Datos incompletos.");
}

// Insertar la reserva
$query = "INSERT INTO reserva (clienteId, funcionId, cantidad, total) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iiid", $clienteId, $funcionId, $cantidad, $total);
$stmt->execute();

$reservaId = $stmt->insert_id;
$stmt->close();

// Insertar los asientos
$queryAsiento = "INSERT INTO asiento (id, reservaId, fila, columna) VALUES (?, ?, ?, ?)";
$stmtAsiento = $conexion->prepare($queryAsiento);

foreach ($asientos as $asiento) {
    preg_match('/^([A-J])(\d{1,2})$/', $asiento, $partes);
    if (!$partes) continue;
    $fila = $partes[1];
    $columna = intval($partes[2]);
    $idAsiento = $fila . $columna;

    $stmtAsiento->bind_param("sisi", $idAsiento, $reservaId, $fila, $columna);
    $stmtAsiento->execute();
}

$stmtAsiento->close();
echo "Reserva registrada con éxito.";
?>
