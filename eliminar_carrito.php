<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['usuario'])) {
    die("El usuario no está autenticado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id'])) {
    $carrito_id = $_POST['carrito_id'];

    $sql = "DELETE FROM carrito WHERE id = $carrito_id";
    if ($conn->query($sql)) {
        header('Location: carrito.php');  
        exit;
    } else {
        die("Error al eliminar el producto del carrito: " . $conn->error);
    }
}
?>
