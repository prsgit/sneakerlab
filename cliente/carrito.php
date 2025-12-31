<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];

$productos = [];
$total = 0;

if (!empty($carrito)) {
    $ids = array_keys($carrito);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT * FROM producto WHERE id_producto IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrito de la compra</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Tema global -->
  <link rel="stylesheet" href="../assets/css/theme.css">
</head>

<body>
  <div class="page">
    <div class="page-container">

      <!-- Navbar moderno (mismo que catálogo) -->
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

      <h1 class="page-title">Carrito de la compra</h1>

      <?php if (empty($carrito)): ?>
        <div class="empty-state">El carrito está vacío.</div>
      <?php else: ?>

        <section class="cart-card">
          <div class="table-scroll">
            <table class="table table-premium align-middle">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                  <th>Acciones</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($productos as $producto): ?>
                  <?php
                      $cantidad = $carrito[$producto['id_producto']];
                      $subtotal = $producto['precio'] * $cantidad;
                      $total += $subtotal;
                  ?>
                  <tr>
                    <td class="fw-bold"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo htmlspecialchars(number_format((float)$producto['precio'], 2)); ?> €</td>
                    <td><?php echo (int)$cantidad; ?></td>
                    <td class="fw-bold"><?php echo htmlspecialchars(number_format((float)$subtotal, 2)); ?> €</td>
                    <td>
                      <a class="action-link"
                         href="eliminar_producto_carrito.php?id=<?php echo (int)$producto['id_producto']; ?>"
                         onclick="return confirm('¿Eliminar este producto del carrito?');">
                        Eliminar
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="cart-total">
            <span>Total</span>
            <span><?php echo htmlspecialchars(number_format((float)$total, 2)); ?> €</span>
          </div>
        </section>

        <div class="d-flex flex-column flex-md-row gap-2 mt-3">
          <form method="post" action="confirmar_compra.php" class="flex-grow-1">
            <button class="btn btn-brand w-100" type="submit">Confirmar compra</button>
          </form>

          <a class="btn btn-ghost w-100"
             href="vaciar_carrito.php"
             onclick="return confirm('¿Vaciar el carrito completo?');">
            Vaciar carrito
          </a>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
