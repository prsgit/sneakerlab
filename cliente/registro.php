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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/theme.css">
</head>

<body>
  <main class="auth-wrap">
    <section class="auth-card">
      <div class="auth-header">
        <span class="auth-badge">SNEAKERLAB</span>
        <h1 class="auth-title">Crea tu cuenta</h1>
      </div>

      <div class="auth-body">
        <form method="post" action="registro_guardar.php">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="nombre">Nombre</label>
              <input class="form-control" type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
            </div>

            <div class="col-12">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" type="email" id="email" name="email" required placeholder="tuemail@ejemplo.com">
            </div>

            <div class="col-12">
              <label class="form-label" for="telefono">Teléfono</label>
              <input class="form-control" type="text" id="telefono" name="telefono" placeholder="Opcional">
            </div>

            <div class="col-12">
              <label class="form-label" for="direccion">Dirección</label>
              <textarea class="form-control" id="direccion" name="direccion" placeholder="Calle, número, ciudad..."></textarea>
            </div>

            <div class="col-12">
              <label class="form-label" for="password">Contraseña</label>
              <input class="form-control" type="password" id="password" name="password" required placeholder="••••••••">
              <div class="form-text" style="color: var(--muted);">
                Usa una contraseña segura (mínimo 8 caracteres)
              </div>
            </div>

            <div class="col-12">
              <button class="btn btn-brand w-100" type="submit">Crear cuenta</button>
            </div>
          </div>
        </form>
      </div>

      <div class="auth-footer">
        ¿Ya tienes cuenta?
        <a href="login.php">Inicia sesión</a>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
