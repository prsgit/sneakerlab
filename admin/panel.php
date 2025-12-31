<?php
session_start();

/*
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel de administración</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
  
</head>

<body>
  <div class="panel-wrap">
    <div class="panel-container">

      <header class="panel-top">
        <div>
          <h1 class="panel-title">Administración SNEAKERLAB</h1>
          <p class="panel-sub">Bienvenido, administrador.</p>
        </div>

        <a class="btn btn-ghost" href="logout.php">Cerrar sesión</a>
      </header>

      <div class="mt-4">
        <div class="row g-4">
          <div class="col-12 col-md-6">
            <a class="quick-card" href="productos.php">
              <div class="quick-icon" aria-hidden="true">
                <!-- Caja / productos -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <path d="M21 8l-9-5-9 5 9 5 9-5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M3 8v10l9 5 9-5V8" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M12 13v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <h2 class="quick-title">Gestionar productos</h2>
              <p class="quick-desc">Crear, editar, eliminar y mantener el catálogo actualizado.</p>
            </a>
          </div>

          <div class="col-12 col-md-6">
            <a class="quick-card" href="usuarios.php">
              <div class="quick-icon" aria-hidden="true">
                <!-- Usuarios -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <h2 class="quick-title">Gestionar usuarios</h2>
              <p class="quick-desc">Administrar accesos, altas/bajas y control de cuentas.</p>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

