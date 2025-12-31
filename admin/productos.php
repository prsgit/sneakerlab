<?php
session_start();
require_once "../config/db.php";

/* Proteger el acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Obtener productos de la BD */
$sql = "SELECT * FROM producto WHERE activo = 1 ORDER BY id_producto DESC";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Productos</title>

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
          <p class="panel-sub">Gestión de productos</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-ghost" href="panel.php">Volver al panel</a>
          <a class="btn btn-brand" href="producto_crear.php">+ Nuevo producto</a>
        </div>
      </header>

      <div class="admin-header">
        <div>
          <h2 class="admin-title">Productos</h2>
        </div>
      </div>

      <section class="admin-card mt-3">
        <div class="table-scroll">
          <table class="table table-premium table-compact align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($productos as $producto): ?>
                <tr>
                  <td class="mono"><?php echo $producto['id_producto']; ?></td>
                  <td class="fw-bold"><?php echo $producto['nombre']; ?></td>

                  <td class="fw-bold">
                    <?php echo htmlspecialchars(number_format((float)$producto['precio'], 2)); ?> €
                  </td>

                  <td><?php echo $producto['stock']; ?></td>

                  <!-- miniatura + texto -->
                  <td>
                    <?php if (!empty($producto['imagen_url'])): ?>
                      <span class="thumb-wrap">
                        <img
                          class="thumb"
                          src="../assets/img/<?php echo $producto['imagen_url']; ?>"
                          alt="<?php echo $producto['imagen_url']; ?>"
                        >
                        <span class="thumb-text mono"><?php echo $producto['imagen_url']; ?></span>
                      </span>
                    <?php else: ?>
                      <span class="thumb-text">—</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <span class="action-group">
                      <a class="action-normal" href="producto_editar.php?id=<?php echo $producto['id_producto']; ?>">Editar</a>
                      <span class="sep">|</span>
                      <a class="action-danger"
                         href="producto_eliminar.php?id=<?php echo $producto['id_producto']; ?>"
                         onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
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
