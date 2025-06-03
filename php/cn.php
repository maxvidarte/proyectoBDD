<?php
$host = 'mysql-e31d350-unmsm-8f0b.l.aivencloud.com';
$port = 26519;
$user = 'avnadmin';
$password = 'AVNS_2MMOBozurrJQne47dxb';
$dbname = 'appcine';

$conexion =mysqli_connect( $host, $user, $password, $dbname, $port);
mysqli_set_charset($conexion,"utf8");
?>