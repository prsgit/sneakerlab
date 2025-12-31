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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Crear usuario</title>

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
          <p class="panel-sub">Crear usuario</p>
        </div>

        <a class="btn btn-ghost" href="usuarios.php">Volver al listado</a>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Nuevo usuario</h2>
          <p class="panel-sub" style="margin: 6px 0 0;">Completa los datos para crear una cuenta.</p>
        </div>
      </div>

      <section class="form-card mt-3">
        <form method="post" action="usuario_guardar.php">
          <div class="row g-3">

            <div class="col-12 col-md-6">
              <label class="form-label">Nombre:</label><br>
              <input class="form-control" type="text" name="nombre" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Email:</label><br>
              <input class="form-control" type="email" name="email" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Contraseña:</label><br>
              <input class="form-control" type="password" name="contrasena" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Teléfono:</label><br>
              <input class="form-control" type="text" name="telefono">
            </div>

            <div class="col-12">
              <label class="form-label">Dirección:</label><br>
              <textarea class="form-control" name="direccion"></textarea>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Tipo de usuario:</label><br>
              <select class="form-control" name="rol" required>
                <option value="cliente">Cliente</option>
                <option value="admin">Administrador</option>
              </select>
            </div>

            <div class="col-12 d-flex flex-column flex-sm-row gap-2 mt-1">
              <button class="btn btn-brand" type="submit">Crear usuario</button>
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
