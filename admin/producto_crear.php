<?php
session_start();
require_once "../config/db.php";

/* Proteger el acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear producto</title>
</head>
<body>

<h1>Crear producto</h1>

<p><a href="productos.php">Volver al listado</a></p>

<form method="post" action="producto_guardar.php" enctype="multipart/form-data">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" required></textarea><br><br>

    <label>Precio (€):</label><br>
    <input type="number" step="0.01" name="precio" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Imagen (nombre de archivo):</label><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <button type="submit">Guardar producto</button>
</form>

</body>
</html>
