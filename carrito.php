<?php
session_start();
include 'includes/db.php';
include 'includes/header.php'; 


if (!isset($_SESSION['usuario'])) {
    die("El usuario no está autenticado.");
}

$usuario_id = $_SESSION['usuario'];

$sql = "SELECT c.id, p.nombre, p.precio, c.cantidad 
        FROM carrito c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.usuario_id = $usuario_id";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Carrito de Compras</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $total = 0;
        while ($row = $result->fetch_assoc()): 
            $subtotal = $row['precio'] * $row['cantidad'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td>$<?= $row['precio'] ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td>$<?= $subtotal ?></td>
                <td>
                    <form method="POST" action="eliminar_carrito.php">
                        <input type="hidden" name="carrito_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <h3>Total: $<?= $total ?></h3>
    <form method="POST" action="pagar.php">
        <button type="submit" class="btn btn-success">Pagar</button>
    </form>
</div>
</body>
</html>
