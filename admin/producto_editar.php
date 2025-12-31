<?php
session_start();
require_once "../config/db.php";

/* Proteger acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Validar ID */
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$id_producto = $_GET['id'];

/* Obtener datos actuales del producto */
$sql = "SELECT * FROM producto WHERE id_producto = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar producto</title>

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
          <p class="panel-sub">Editar producto</p>
        </div>

        <a class="btn btn-ghost" href="productos.php">Volver al listado</a>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Editar producto</h2>
          <p class="panel-sub" style="margin: 6px 0 0;">Actualiza la información del artículo.</p>
        </div>
      </div>

      <section class="form-card mt-3">
        <form method="post" action="producto_actualizar.php" enctype="multipart/form-data">
          <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Nombre:</label><br>
              <input class="form-control" type="text" name="nombre" required value="<?php echo $producto['nombre']; ?>">
            </div>

            <div class="col-12">
              <label class="form-label">Descripción:</label><br>
              <textarea class="form-control" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Precio (€):</label><br>
              <input class="form-control" type="number" step="0.01" name="precio" required value="<?php echo $producto['precio']; ?>">
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Stock:</label><br>
              <input class="form-control" type="number" name="stock" required value="<?php echo $producto['stock']; ?>">
            </div>

            <!-- Imagen actual -->
            <div class="col-12">
              <div class="current-image">
                <div>
                  <div class="label">Imagen actual</div>
                  <div class="value mono"><?php echo $producto['imagen_url']; ?></div>
                </div>

                <?php if (!empty($producto['imagen_url'])): ?>
                  <img
                    class="thumb"
                    src="../assets/img/<?php echo $producto['imagen_url']; ?>"
                    alt="<?php echo $producto['imagen_url']; ?>"
                  >
                <?php else: ?>
                  <span class="thumb-text">—</span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Nueva imagen -->
            <div class="col-12">
              <label class="form-label">Nueva imagen (opcional):</label><br>
              <div class="file-card">
                <input class="form-control" type="file" name="imagen" accept="image/*">
                <p class="form-help">Si no seleccionas nada, se mantiene la imagen actual.</p>
              </div>
            </div>

            <div class="col-12 d-flex flex-column flex-sm-row gap-2 mt-1">
              <button class="btn btn-brand" type="submit">Guardar cambios</button>
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

