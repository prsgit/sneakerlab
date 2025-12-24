<?php
session_start();
require_once "../config/db.php";

/* Proteger el acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Obtener productos de la BD */
$sql = "SELECT * FROM producto ORDER BY id_producto DESC";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Productos</title>
</head>
<body>

<h1>Gestión de productos</h1>
<p><a href="producto_crear.php">+ Nuevo producto</a></p>


<p><a href="panel.php">Volver al panel</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?php echo $producto['id_producto']; ?></td>
            <td><?php echo $producto['nombre']; ?></td>
            <td><?php echo $producto['precio']; ?> €</td>
            <td><?php echo $producto['stock']; ?></td>
            <td><?php echo $producto['imagen_url']; ?></td>
            <td>
                <a href="producto_editar.php?id=<?php echo $producto['id_producto']; ?>">Editar</a>
                |
                <a href="producto_eliminar.php?id=<?php echo $producto['id_producto']; ?>"
                    onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
                    Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
