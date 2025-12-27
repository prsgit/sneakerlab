<?php
session_start();
require_once "../config/db.php";

/* Proteger acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Validar ID */
if (!isset($_GET["id"])) {
    header("Location: usuarios.php");
    exit;
}

$id_usuario = $_GET["id"];

/* Obtener usuario actual */
$stmt = $pdo->prepare("SELECT id_usuario, nombre, email, telefono, direccion, rol FROM usuario WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
</head>
<body>

<h1>Editar usuario</h1>

<p><a href="usuarios.php">Volver al listado</a></p>

<form method="post" action="usuario_actualizar.php">
    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required value="<?php echo htmlspecialchars($usuario['nombre']); ?>"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required value="<?php echo htmlspecialchars($usuario['email']); ?>"><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>"><br><br>

    <label>Dirección:</label><br>
    <textarea name="direccion"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea><br><br>

    <label>Rol:</label><br>
    <select name="rol" required>
        <option value="cliente" <?php echo ($usuario['rol'] === "cliente") ? "selected" : ""; ?>>Cliente</option>
        <option value="admin" <?php echo ($usuario['rol'] === "admin") ? "selected" : ""; ?>>Administrador</option>
    </select><br><br>

    <label>Nueva contraseña (Dejar en blanco para mantener la actual):</label><br>
    <input type="password" name="password"><br><br>


    <button type="submit">Guardar cambios</button>
</form>

</body>
</html>
