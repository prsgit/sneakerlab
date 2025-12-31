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

   if (
    str_contains($campoLower, "password") ||
    str_contains($campoLower, "contras") ||
    $campoLower === "activo"
) {
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Usuarios</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">

</head>

<body>
  <div class="panel-wrap">
    <div class="panel-container">

      <!-- Top bar -->
      <header class="panel-top">
        <div>
          <h1 class="panel-title">Administración</h1>
          <p class="panel-sub">Gestión de usuarios</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-ghost" href="panel.php">Volver al panel</a>
          <a class="btn btn-brand" href="usuario_crear.php">+ Nuevo usuario</a>
        </div>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Usuarios</h2>
        </div>
      </div>

      <section class="admin-card mt-3">
        <div class="table-scroll">
          <table class="table table-premium table-compact align-middle mb-0">
            <thead>
              <tr>
                <?php foreach ($columnasMostrar as $col): ?>
                  <th><?php echo htmlspecialchars($col); ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($usuarios as $u): ?>
                <tr>
                  <?php foreach ($columnasMostrar as $col): ?>
                    <td><?php echo htmlspecialchars((string)($u[$col] ?? "")); ?></td>
                  <?php endforeach; ?>
                  <td>
                    <span class="action-group">
                      <a class="action-normal" href="usuario_editar.php?id=<?php echo $u['id_usuario']; ?>">Editar</a>
                      <span class="sep">|</span>
                      <a class="action-danger"
                         href="usuario_eliminar.php?id=<?php echo $u['id_usuario']; ?>"
                         onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">
                        Eliminar
                      </a>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>

          </table>
        </div>
      </section>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
