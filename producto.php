<?php
include 'php/cn.php';

// Verifica si se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de película no válido.");
}

$id = $_GET['id'];

// Consulta los datos de la película
$query = "SELECT * FROM pelicula WHERE id = $id";
$resultado = mysqli_query($conexion, $query);
$pelicula = mysqli_fetch_assoc($resultado);

if (!$pelicula) {
    die("Película no encontrada.");
}


//////////////////

$query_fhorario = "
    SELECT horario.id AS id_horario, horario.inicio, horario.final
    FROM funcion
    INNER JOIN horario ON funcion.horarioId = horario.id
    WHERE funcion.peliculaId = '$id'
";
$result_fhorario = mysqli_query($conexion, $query_fhorario);
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

    <nav class="navegacion producto">
        
        <a class="navegacion__enlace " href="main.php"> Cartelera </a>
        <a class="navegacion__enlace " href="nosotros.html"> Nosotros</a>
        <script src="login/showLogin.js"></script>
    </nav>

    <main class="contenedor">
        <div class="pelicula">
            <div class="sipnosis_pelicula">
                
                  
                <div class="info_adicional">
                   <img class="pelicula_imagen" src="img/<?= $pelicula['id'] ?>.jpg" alt="Imagen del Producto">
                <div class="texto_info">
                <h3>
                    <span id="sipnosis_titulo"><?= $pelicula['nombre'] ?></span>
                    <small>
                        <span id="sipnosis_titulo_ingles"><?= $pelicula['nombre'] ?></span>
                    </small>
                </h3>

                <div class="etiquetas_pelicula">
                    <span class="etiqueta clasificacion"><?= $pelicula['clasificacion'] ?></span>
                    <span class="etiqueta duracion"><?= $pelicula['duracion'] ?> min</span>
                    <span class="etiqueta genero"><?= $pelicula['genero'] ?></span>
                </div>
            </div>
            
            </div>
                <h3 id="tt_sipnosis">Sipnosis</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit dolor sit amet 
                    volutpat dapibus. Morbi quis velit massa. Integer rutrum magna non lacus cursus 
                    interdum. Maecenas ut pretium dolor, porta sodales nunc. Cras enim risus, bibendum 
                    sollicitudin tortor quis, congue aliquet libero. Curabitur feugiat turpis eros. 
                    Vivamus maximus semper sem, eu accumsan justo rhoncus id
                </p>
                
            </div>
        
        
        
            <div class="mapa_asientos">
                
                

            <div class="contenedor_asientos">
                    <div id="pantalla"></div>
               
                <div id="mapa_asientos"></div>

                <ul class="ver_caso">
                    <li>
                        <div class="asiento"></div>
                        <small>Disponible</small>
                    </li>
                    <li>
                        <div class="asiento seleccionado"></div>
                        <small>Seleccionado</small>
                    </li>
                    <li>
                        <div class="asiento ocupado"></div>
                        <small>Ocupado</small>
                    </li>
                </ul>
            </div>
            <div class="listo_para_reservar">                        
                <p class="alerta_asientos">
                    Tu has seleccionado <span id="count">0</span> asientos
                </p>
                <button class="btn_reservar">Reservar</button>
            </div>

            <div class="horario">
                    <form class="formulario">
                        <select class="formulario_horario" name="horarioId">
                            <option disabled selected>--Seleccionar Horario--</option>
                            <?php while ($horario = mysqli_fetch_assoc($result_fhorario)): ?>
                                <option value="<?= $horario['id_horario'] ?>">
                                    <?= $horario['inicio'] ?> - <?= $horario['final'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
            </div>
        </div>
    </div>
    </main>

    <footer class="footer">
        <p class="footer__texto">CINEMA FOR DEVS - TODO LOS DERECHOS RESERVADOS 2023</p>
    </footer>
    <script src="mapadeasientos.js"></script>
</body>
</html>