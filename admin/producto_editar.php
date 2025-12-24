<?php
session_start();
require_once "../config/db.php";

/* Proteger acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Validar ID */
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$id_producto = $_GET['id'];

/* Obtener datos actuales del producto */
$sql = "SELECT * FROM producto WHERE id_producto = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
</head>
<body>

<h1>Editar producto</h1>

<p><a href="productos.php">Volver al listado</a></p>

<form method="post" action="producto_actualizar.php" enctype="multipart/form-data">
    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required value="<?php echo $producto['nombre']; ?>"><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br><br>

    <label>Precio (€):</label><br>
    <input type="number" step="0.01" name="precio" required value="<?php echo $producto['precio']; ?>"><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required value="<?php echo $producto['stock']; ?>"><br><br>

    <p>Imagen actual: <?php echo $producto['imagen_url']; ?></p>

    <label>Nueva imagen (opcional):</label><br>
    <input type="file" name="imagen" accept="image/*"><br><br>

    <button type="submit">Guardar cambios</button>
</form>

</body>
</html>
