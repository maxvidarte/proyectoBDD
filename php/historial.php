<?php
session_start();
include 'cn.php';

if (!isset($_SESSION['clienteId'])) {
    header("Location: /login/login.html");
    exit();
}

$clienteId = $_SESSION['clienteId'];

// Consulta del historial
$query = "
    SELECT 
        r.id AS reservaId,
        p.nombre AS pelicula,
        h.inicio,
        h.final,
        f.fecha,
        r.total
    FROM reserva r
    INNER JOIN funcion f ON r.funcionId = f.id
    INNER JOIN pelicula p ON f.peliculaId = p.id
    INNER JOIN horario h ON f.horarioId = h.id
    WHERE r.clienteId = ?
    ORDER BY r.id DESC
";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINEMAFORDEVS</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/historialstyles.css">
</head>
<body>
    
       <header class="header">
        <a href="../index.php">
            <img class="header__logo" src="../img/titulo.png" alt="Logotipo"> 
        </a>
        
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace" href="../index.php"> Cartelera </a>
        <a class="navegacion__enlace" href="../nosotros.html"> Nosotros</a>
        <script src="../login/showLogin.js"></script>
    </nav>

    <table class="tabla-historial">
        <thead>
            <tr>
                <th>Código</th>
                <th>Película</th>
                <th>Horario</th>
                <th>Importe</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['reservaId'] ?></td>
                <td><?= $row['pelicula'] ?></td>
                <td><?= $row['inicio'] ?> - <?= $row['final'] ?></td>
                <td>S/ <?= number_format($row['total'], 2) ?></td>
                <td><?= $row['fecha'] ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
    </table>
</body>
<footer class="footer">
        <p class="footer__texto">CINEMA FOR DEVS - TODO LOS DERECHOS RESERVADOS 2023</p>
    </footer>
</html>
