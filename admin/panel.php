<?php
session_start();

/*
  Protección mínima del área admin.
  Si no hay sesión de admin activa, mandamos a login.
*/
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
</head>
<body>

<h1>Panel de administración</h1>
<p>Bienvenido, administrador.</p>

<ul>
    <li><a href="productos.php">Gestionar productos</a></li>
    <li><a href="logout.php">Cerrar sesión</a></li>
</ul>

</body>
</html>
