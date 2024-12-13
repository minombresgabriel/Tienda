<?php
include 'includes/db.php';
include 'includes/header.php'; 


if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php'); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];
    $ruta = 'images/' . $imagen;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $sql = "INSERT INTO productos (nombre, descripcion, cantidad, precio, imagen) 
                VALUES ('$nombre', '$descripcion', $cantidad, $precio, '$ruta')";
        $conn->query($sql);
    }

    header('Location: admin.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $producto_id = $_POST['producto_id'];

    $sql_update_historial = "UPDATE historial_compras SET producto_id = NULL WHERE producto_id = $producto_id";
    if ($conn->query($sql_update_historial) === TRUE) {
        $sql_producto = "DELETE FROM productos WHERE id = $producto_id";
        if ($conn->query($sql_producto) === TRUE) {
            header('Location: admin.php');
            exit;
        } else {
            echo "Error al eliminar el producto: " . $conn->error;
        }
    } else {
        echo "Error al actualizar el historial: " . $conn->error;
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $producto_id = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $sql = "UPDATE productos SET 
            nombre = '$nombre', 
            descripcion = '$descripcion', 
            cantidad = $cantidad, 
            precio = $precio 
            WHERE id = $producto_id";
    $conn->query($sql);

    header('Location: admin.php');
    exit;
}

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Administración de Productos</h1>

    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Producto" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción" required></textarea>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" placeholder="Cantidad" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Precio" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto</label>
            <input type="file" name="imagen" id="imagen" class="form-control" required>
        </div>
        <button type="submit" name="crear" class="btn btn-success">Crear Producto</button>
    </form>

    <h2>Lista de Productos</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="POST">
                <td>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($row['nombre']) ?>" required>
                </td>
                <td>
                <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($row['descripcion']) ?>" required>
                </td>
                <td>
                    <input type="number" name="cantidad" class="form-control" value="<?= $row['cantidad'] ?>" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="precio" class="form-control" value="<?= $row['precio'] ?>" required>
                </td>
                <td>
                    <input type="hidden" name="producto_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="editar" class="btn btn-primary">Guardar</button>
                    <button type="submit" name="eliminar" class="btn btn-danger" style="margin-top: 5px;">Eliminar</button>
                </td>
            </form>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</div>
</body>
</html>
