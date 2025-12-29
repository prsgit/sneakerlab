<?php
session_start();
require_once "../config/db.php";

/* acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/*
  Obtener columnas reales de la tabla usuario (para no depender de nombres exactos)
*/
$colsStmt = $pdo->query("DESCRIBE usuario");
$columnas = $colsStmt->fetchAll(PDO::FETCH_ASSOC);

/*
  Filtrar columnas que NO queremos mostrar (por seguridad)
*/
$columnasMostrar = [];
foreach ($columnas as $col) {
    $campo = $col["Field"];
    $campoLower = strtolower($campo);

    if (str_contains($campoLower, "password") || str_contains($campoLower, "contras")) {
        continue; // no mostrar
    }
    $columnasMostrar[] = $campo;
}

/* Obtener usuarios */
$usuariosStmt = $pdo->query("SELECT * FROM usuario WHERE activo = 1 ORDER BY id_usuario DESC");
$usuarios = $usuariosStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Usuarios</title>
</head>
<body>

<h1>Gestión de usuarios</h1>

<p><a href="panel.php">Volver al panel</a></p>

<p><a href="usuario_crear.php">+ Nuevo usuario</a></p>

<table border="1" cellpadding="8">
    <tr>
        <?php foreach ($columnasMostrar as $col): ?>
            <th><?php echo htmlspecialchars($col); ?></th>
        <?php endforeach; ?>
        <th>Acciones</th>
    </tr>

    <?php foreach ($usuarios as $u): ?>
        <tr>
            <?php foreach ($columnasMostrar as $col): ?>
                <td><?php echo htmlspecialchars((string)($u[$col] ?? "")); ?></td>
            <?php endforeach; ?>
            <td>
               <a href="usuario_editar.php?id=<?php echo $u['id_usuario']; ?>">Editar</a>
                |
                <a href="usuario_eliminar.php?id=<?php echo $u['id_usuario']; ?>"
                 onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">
                Eliminar
                 </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
