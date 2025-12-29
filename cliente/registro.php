<?php
session_start();

/* Si ya está logueado como cliente, no registrarse */
if (!empty($_SESSION["cliente"]) && !empty($_SESSION["id_usuario"])) {
    header("Location: catalogo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h1>Registro de cliente</h1>

<form method="post" action="registro_guardar.php">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono"><br><br>

    <label>Dirección:</label><br>
    <textarea name="direccion"></textarea><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Crear cuenta</button>
</form>

<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>

</body>
</html>
