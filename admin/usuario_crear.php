<?php
session_start();
require_once "../config/db.php";

/* Proteger acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuario</title>
</head>
<body>

<h1>Crear usuario</h1>

<p><a href="usuarios.php">Volver al listado</a></p>

<form method="post" action="usuario_guardar.php">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="contrasena" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono"><br><br>

    <label>Dirección:</label><br>
    <textarea name="direccion"></textarea><br><br>

    <label>Tipo de usuario:</label><br>
    <select name="rol" required>
        <option value="cliente">Cliente</option>
        <option value="admin">Administrador</option>
    </select><br><br>

    <button type="submit">Crear usuario</button>
</form>

</body>
</html>
