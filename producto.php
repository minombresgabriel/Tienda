<?php
session_start();
include 'includes/db.php';
include 'includes/header.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $usuario_id = $_SESSION['usuario'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    $sql = "SELECT * FROM carritos WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE carritos SET cantidad = cantidad + $cantidad WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
    } else {
        $sql = "INSERT INTO carritos (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, $cantidad)";
    }
    $conn->query($sql);

    header('Location: carrito.php');
    exit;
}
?>
