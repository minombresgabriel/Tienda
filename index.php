<?php
include 'includes/db.php';
include 'includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }

    $usuario_id = $_SESSION['usuario'];
    $producto_id = $_POST['producto_id'];

    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES ($usuario_id, $producto_id, 1)";
    $conn->query($sql);
}

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tienda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Productos Disponibles</h1>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?= $row['imagen'] ?>" class="card-img-top" alt="<?= $row['nombre'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['nombre'] ?></h5>
                        <p class="card-text"><?= $row['descripcion'] ?></p>
                        <p class="card-text"><strong>Precio: </strong>$<?= $row['precio'] ?></p>
                        <form method="POST">
                            <input type="hidden" name="producto_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
