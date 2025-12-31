<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM producto WHERE activo = 1";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo de productos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
</head>

<body>
  <div class="page">
    <div class="page-container">

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-premium px-3 py-2" aria-label="Navegación principal">
        <div class="container-fluid p-0">

          <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="catalogo.php" style="color: var(--text);">
            <span class="brand-dot"></span>
            SNEAKERLAB
          </a>

          <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navCliente">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navCliente">
            <!-- Links -->
            <ul class="navbar-nav mx-auto gap-2 my-2 my-lg-0">
              <li class="nav-item nav-pill"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="carrito.php">Carrito</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="mis_pedidos.php">Mis pedidos</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>

            <!-- Bienvenido -->
            <div class="d-flex align-items-center">
              <span class="welcome-badge">
                Bienvenido<?php echo isset($_SESSION['nombre']) ? ', ' . htmlspecialchars($_SESSION['nombre']) : ''; ?>
              </span>
            </div>
          </div>

        </div>
      </nav>


      <?php if (count($productos) === 0): ?>
        <div class="empty-state">No hay productos disponibles.</div>
      <?php else: ?>

        <!-- Grid en columnas -->
        <div class="row g-4">
          <?php foreach ($productos as $producto): ?>
            <div class="col-12 col-md-6 col-lg-4">
              <article class="product-card">

                <?php if (!empty($producto['imagen_url'])): ?>
                  <img
                    class="product-image"
                    src="../assets/img/<?php echo $producto['imagen_url']; ?>"
                    alt="<?php echo $producto['nombre']; ?>"
                  >
                <?php else: ?>
                  <div class="product-image d-grid place-items-center" style="display:grid;place-items:center;">
                    <span class="text-secondary small">Sin imagen</span>
                  </div>
                <?php endif; ?>

                <div class="product-body">
                  <h2 class="product-name"><?php echo $producto['nombre']; ?></h2>

                  <div class="product-meta">
                    <span><strong>Precio</strong></span>
                    <span class="price"><?php echo $producto['precio']; ?> €</span>
                  </div>

                  <a class="btn btn-outline-brand w-100"
                     href="detalle_producto.php?id=<?php echo $producto['id_producto']; ?>">
                    Ver detalle
                  </a>
                </div>

              </article>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
