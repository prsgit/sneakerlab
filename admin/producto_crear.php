<?php
session_start();
require_once "../config/db.php";

/* Proteger el acceso: solo admin */
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
  <title>Crear producto</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
  
</head>

<body>
  <div class="panel-wrap">
    <div class="panel-container">

      <!-- Top bar (admin) -->
      <header class="panel-top">
        <div>
          <h1 class="panel-title">Administración</h1>
          <p class="panel-sub">Crear producto</p>
        </div>

        <a class="btn btn-ghost" href="productos.php">Volver al listado</a>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Nuevo producto</h2>
          <p class="panel-sub" style="margin: 6px 0 0;">Rellena los datos para añadir un artículo al catálogo.</p>
        </div>
      </div>

      <section class="form-card mt-3">
        <form method="post" action="producto_guardar.php" enctype="multipart/form-data">
          <div class="row g-3">

            <div class="col-12">
              <label class="form-label">Nombre:</label><br>
              <input class="form-control" type="text" name="nombre" required>
            </div>

            <div class="col-12">
              <label class="form-label">Descripción:</label><br>
              <textarea class="form-control" name="descripcion" required></textarea>
              <p class="form-help">Descripción breve y clara (materiales, estilo, detalles).</p>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Precio (€):</label><br>
              <input class="form-control" type="number" step="0.01" name="precio" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Stock:</label><br>
              <input class="form-control" type="number" name="stock" required>
            </div>

            <div class="col-12">
              <label class="form-label">Imagen (nombre de archivo):</label><br>
              <div class="file-card">
                <input class="form-control" type="file" name="imagen" accept="image/*" required>
                <p class="form-help">Usa una imagen nítida, fondo limpio si es posible. Formatos recomendados: JPG/PNG.</p>
              </div>
            </div>

            <div class="col-12 d-flex flex-column flex-sm-row gap-2 mt-1">
              <button class="btn btn-brand" type="submit">Guardar producto</button>
              <a class="btn btn-ghost" href="productos.php">Cancelar</a>
            </div>

          </div>
        </form>
      </section>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
