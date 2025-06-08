<?php
include 'cn.php';

$reservaId = $_GET['reservaId'];
if (!$reservaId) {
    http_response_code(400);
    echo json_encode(["error" => "ID no proporcionado"]);
    exit;
}

$query = "
SELECT r.id, r.total, r.cantidad, r.funcionId,
       c.nombres, c.apellidos,
       f.fecha, f.horarioId, h.inicio, h.final,
       p.nombre AS pelicula, s.nombre AS sala
FROM reserva r
JOIN clientes c ON r.clienteId = c.id
JOIN funcion f ON r.funcionId = f.id
JOIN pelicula p ON f.peliculaId = p.id
JOIN sala s ON f.salaId = s.id
JOIN horario h ON f.horarioId = h.id
WHERE r.id = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $reservaId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo json_encode(["error" => "Reserva no encontrada."]);
    exit;
}

$stmt->close();

$asientos = [];
$queryAsientos = "SELECT fila, columna FROM asiento WHERE reservaId = ?";
$stmtAsientos = $conexion->prepare($queryAsientos);
$stmtAsientos->bind_param("i", $reservaId);
$stmtAsientos->execute();
$resAsientos = $stmtAsientos->get_result();
while ($a = $resAsientos->fetch_assoc()) {
    $asientos[] = $a['fila'] . $a['columna'];
}

echo json_encode([
    "id" => $data['id'],
    "nombre" => $data['nombres'] . " " . $data['apellidos'],
    "pelicula" => $data['pelicula'],
    "sala" => $data['sala'],
    //"fecha" => $data['fecha'], no es necesario
    "horario" => $data['inicio'],
    "asientos" => $asientos,
    "total" => $data['total']
]);
?>
