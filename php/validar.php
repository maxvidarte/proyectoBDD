<?php

session_start();
$usuario=$_POST['usuario'];
$clave=$_POST['clave'];
include("cn.php");
$consulta="SELECT * FROM clientes WHERE email='$usuario' and contraseña='$clave'";
$resultado=mysqli_query($conexion,$consulta);

//$filas=mysqli_num_rows($resultado);

if($fila=mysqli_fetch_assoc($resultado)){
    $_SESSION['nombres']=$fila['nombres'];
    $_SESSION['clienteId']=$fila['id'];
    header("location:/../index.php");
}
else{
    echo "Error en la autenticacion";
}
mysqli_free_result($resultado);
mysqli_close($conexion);
?>