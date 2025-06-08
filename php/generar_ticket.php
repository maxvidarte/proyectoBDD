<?php
require_once(__DIR__ . '/../tcpdf/tcpdf.php');

class PDFWithFooter extends TCPDF {
    public function Footer() {
        $this->SetY(-20);
        $this->SetFont('helvetica', '', 12);

        $html = '
        <p style="margin-top:5px; font-size:9px; text-align:justify;">
        Usted podrá hacer efectivo el ingreso con este ticket en la sala correspondiente. 
        Recuerde presentarlo antes del inicio de la función. Este ticket es válido solo para el horario indicado.
        </p>';

        $this->writeHTMLCell(0, 0, 15, '', $html, 0, 1, false, true, '');
    }
}

$pdf = new TCPDF();
include 'cn.php';

$reservaId = $_GET['reservaId'];
if (!$reservaId) exit("ID de reserva no especificado.");

// Obtener datos de la reserva y relaciones
$query = "
SELECT r.id, r.total, r.cantidad, r.funcionId, r.clienteId,
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
$stmt->close();

// Obtener asientos
$asientos = [];
$queryAsientos = "SELECT fila, columna FROM asiento WHERE reservaId = ?";
$stmtAsiento = $conexion->prepare($queryAsientos);
$stmtAsiento->bind_param("i", $reservaId);
$stmtAsiento->execute();
$resAsientos = $stmtAsiento->get_result();
while ($a = $resAsientos->fetch_assoc()) {
    $asientos[] = $a['fila'] . $a['columna'];
}
$stmtAsiento->close();

// Crear PDF
//$pdf = new \TCPDF();
$pdf = new PDFWithFooter();
$pdf->setPrintHeader(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Image('../img/titulo.png', 160, 5 , 40); 
$pdf->Ln(13);
$html = '
<h2 style="text-align:center;">Ticket de pago</h2>
<table cellpadding="5">
<tr><td><strong>cod. Reserva</strong></td><td>' . $reservaId . '</td></tr>
<tr><td><strong>Registrado por</strong></td><td>' . $data['nombres'] . ' ' . $data['apellidos'] . '</td></tr>
<tr><td><strong>Película</strong></td><td>' . $data['pelicula'] . '</td></tr>
<tr><td><strong>Sala</strong></td><td>' . $data['sala'] . '</td></tr>
<tr><td><strong>Hora</strong></td><td>' . $data['inicio'] . '</td></tr>
<tr><td><strong>Moneda</strong></td><td>SOLES</td></tr>
<tr><td><strong>Importe</strong></td><td>S/ ' . number_format($data['total'], 2) . '</td></tr>
<tr><td><strong>Asientos</strong></td><td>' . implode(', ', $asientos) . '</td></tr>
</table>';

$pdf->writeHTML($html);
$pdf->Output("ticket_reserva_$reservaId.pdf", 'I');
?>
