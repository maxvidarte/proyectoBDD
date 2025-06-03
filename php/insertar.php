<?php
include("/../../cn.php");

$nombres=$_POST["nombres"];
$apellidos=$_POST["apellidos"];
$telefono=$_POST["telefono"];
$correo=$_POST["correo"];
$contraseña=$_POST["contraseña"];

$insertar ="INSERT INTO clientes(nombres,apellidos,telefono,email,contraseña) VALUES 
('$nombres', '$apellidos', '$telefono', '$correo', '$contraseña')";

$resultado=mysqli_query($conexion,$insertar);

if($resultado){
    echo"<script>alert('Se ha registrado con exito');
    window.location='/'</script>";
}
else{
    echo "<script>alert('No se pudo registrar'); window.history.go(-1); </script>";
}
?>