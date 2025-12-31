<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Producto no válido";
    exit;
}

$id_producto = $_GET['id'];

$sql = "SELECT * FROM producto WHERE id_producto = ? AND activo = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $producto['nombre']; ?></title>

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
            <ul class="navbar-nav mx-auto gap-2 my-2 my-lg-0">
              <li class="nav-item nav-pill"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="carrito.php">Carrito</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="mis_pedidos.php">Mis pedidos</a></li>
              <li class="nav-item nav-pill"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>

            <div class="d-flex align-items-center">
              <span class="welcome-badge">
                Bienvenido<?php echo isset($_SESSION['nombre']) ? ', ' . htmlspecialchars($_SESSION['nombre']) : ''; ?>
              </span>
            </div>
          </div>

        </div>
      </nav>

      <section class="detail-card p-3 p-lg-4">
        <div class="row g-4 align-items-start">

          <!-- Imagen -->
          <div class="col-12 col-lg-6">
            <div class="detail-media">
              <?php if (!empty($producto['imagen_url'])): ?>
                <img
                  class="detail-image"
                  src="../assets/img/<?php echo $producto['imagen_url']; ?>"
                  alt="<?php echo $producto['nombre']; ?>"
                >
              <?php else: ?>
                <div class="detail-image d-grid" style="place-items:center;display:grid;">
                  <span class="text-secondary">Sin imagen</span>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Info -->
          <div class="col-12 col-lg-6">
            <h1 class="detail-title"><?php echo $producto['nombre']; ?></h1>

            <div class="price-badge">
              <strong>Precio</strong>
              <span class="price"><?php echo $producto['precio']; ?> €</span>
            </div>

            <p class="detail-desc"><?php echo $producto['descripcion']; ?></p>

            <div class="d-flex flex-column flex-sm-row gap-2">
              <form method="post" action="add_to_cart.php" class="flex-grow-1">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <button class="btn btn-brand w-100" type="submit">Añadir al carrito</button>
              </form>

              <a class="btn btn-ghost w-100" href="catalogo.php">Volver al catálogo</a>
            </div>
          </div>

        </div>
      </section>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
