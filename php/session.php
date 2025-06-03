<?php
session_start();

if (isset($_SESSION['nombres'])) {
    echo json_encode([
        'logueado' => true,
        'nombres' => $_SESSION['nombres']
    ]);
} else {
    echo json_encode(['logueado' => false]);
}
?>