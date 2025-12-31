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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar usuario</title>

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
          <p class="panel-sub">Editar usuario</p>
        </div>

        <a class="btn btn-ghost" href="usuarios.php">Volver al listado</a>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Editar usuario</h2>
          <p class="panel-sub" style="margin: 6px 0 0;">Actualiza la información de la cuenta.</p>
        </div>
      </div>

      <section class="form-card mt-3">
        <form method="post" action="usuario_actualizar.php">
          <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Nombre:</label><br>
              <input class="form-control" type="text" name="nombre" required value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Email:</label><br>
              <input class="form-control" type="email" name="email" required value="<?php echo htmlspecialchars($usuario['email']); ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Teléfono:</label><br>
              <input class="form-control" type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
            </div>

            <div class="col-12">
              <label class="form-label">Dirección:</label><br>
              <textarea class="form-control" name="direccion"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Rol:</label><br>
              <select class="form-control" name="rol" required>
                <option value="cliente" <?php echo ($usuario['rol'] === "cliente") ? "selected" : ""; ?>>Cliente</option>
                <option value="admin" <?php echo ($usuario['rol'] === "admin") ? "selected" : ""; ?>>Administrador</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Nueva contraseña:</label><br>
              <input class="form-control" type="password" name="contrasena">
              <p class="form-help">Déjalo vacío si no quieres cambiar la contraseña.</p>
            </div>

            <div class="col-12 d-flex flex-column flex-sm-row gap-2 mt-1">
              <button class="btn btn-brand" type="submit">Guardar cambios</button>
              <a class="btn btn-ghost" href="usuarios.php">Cancelar</a>
            </div>
          </div>
        </form>
      </section>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
