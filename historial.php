<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';  

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); 
    exit;
}

$usuario_id = $_SESSION['usuario'];

$sql = "SELECT h.fecha, p.nombre, h.cantidad, p.precio 
        FROM historial_compras h
        LEFT JOIN productos p ON h.producto_id = p.id
        WHERE h.usuario_id = $usuario_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Historial de Compras</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['fecha'] ?></td>
                <td>
                    <?php
                        if ($row['nombre'] !== NULL) {
                            echo htmlspecialchars($row['nombre']);
                        } else {
                            echo "Producto eliminado";
                        }
                    ?>
                </td>
                <td><?= $row['cantidad'] ?></td>
                <td>
                    <?php
                        
                        if ($row['precio'] !== NULL) {
                            echo "$" . number_format($row['precio'] * $row['cantidad'], 2);
                        } else {
                            echo "N/A"; 
                        }
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
