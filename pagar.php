<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['usuario'])) {
    die("El usuario no está autenticado.");
}

$usuario_id = $_SESSION['usuario'];

$sql = "SELECT * FROM carrito WHERE usuario_id = $usuario_id";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}

if ($result->num_rows === 0) {
    die("No tienes productos en el carrito.");
}

while ($row = $result->fetch_assoc()) {
    $producto_id = $row['producto_id'];
    $cantidad = $row['cantidad'];

    $sql_historial = "INSERT INTO historial_compras (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, $cantidad)";
    if (!$conn->query($sql_historial)) {
        die("Error al registrar la compra: " . $conn->error);
    }

    $sql_stock = "UPDATE productos SET cantidad = cantidad - $cantidad WHERE id = $producto_id";
    if (!$conn->query($sql_stock)) {
        die("Error al descontar stock: " . $conn->error);
    }
}

$sql_vaciar_carrito = "DELETE FROM carrito WHERE usuario_id = $usuario_id";
if (!$conn->query($sql_vaciar_carrito)) {
    die("Error al vaciar el carrito: " . $conn->error);
}

header('Location: historial.php');
exit;
?>
