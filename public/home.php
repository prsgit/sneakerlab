<?php
session_start();

/* Si ya está logueado como cliente */
if (!empty($_SESSION["cliente"]) && !empty($_SESSION["id_usuario"])) {
    header("Location: ../cliente/catalogo.php");
    exit;
}

/* Si ya está logueado como admin */
if (!empty($_SESSION["admin"])) {
    header("Location: ../admin/panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SNEAKERLAB</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">

</head>

<body>
  <main class="auth-wrap">
    <section class="auth-card" aria-label="Página de entrada">

      <div class="auth-header">
        <span class="auth-badge">
          <span style="width:8px;height:8px;border-radius:50%;background:var(--brand);display:inline-block;"></span>
          SNEAKERLAB
        </span>

        <h1 class="auth-title">Bienvenido a SNEAKERLAB</h1>
      </div>

      <div class="auth-body">
        <div class="d-grid gap-2">
          <a class="btn btn-brand" href="../cliente/login.php">
            Entrar como cliente
          </a>

          <a class="btn btn-ghost" href="../admin/login.php">
            Entrar como admin
          </a>
        </div>
      </div>

      <div class="auth-footer">
        <span style="color: var(--muted);">
          SNEAKERLAB · Acceso inicial
        </span>
      </div>

    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
