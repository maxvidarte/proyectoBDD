<?php
include 'php/cn.php';

$result = mysqli_query($conexion, "SELECT * FROM pelicula");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINEMAFORDEVS</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <header class="header">
        <a href="main.php">
            <img class="header__logo" src="img/titulo.png" alt="Logotipo"> 
        </a>
        
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace navegacion__enlace--activo" href="main.php"> Cartelera </a>
        <a class="navegacion__enlace" href="nosotros.html"> Nosotros</a>
        <script src="login/showLogin.js"></script>
    </nav>

    
    <main class="contenedor">
        <h1 class="cartelera-text">CARTELERA - LIMA - PERÚ</h1>
        <div class="grid">
            <?php while ($pelicula = mysqli_fetch_assoc($result)): ?>
            <div class="producto">
                <a href="producto.php?id=<?= $pelicula['id'] ?>">
                    <img class="producto__imagen" src="img/<?= $pelicula['id'] ?>.jpg" alt="<?= $pelicula['nombre'] ?>">
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="footer">
        <p class="footer__texto">CINEMA FOR DEVS - TODO LOS DERECHOS RESERVADOS 2023</p>
    </footer>
</body>
</html>