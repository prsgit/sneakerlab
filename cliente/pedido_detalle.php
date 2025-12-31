<?php
session_start();
require_once "../config/db.php";

/* Solo clientes logueados */
if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

/* Validar id del pedido */
if (!isset($_GET["id"])) {
    header("Location: mis_pedidos.php");
    exit;
}

$id_pedido = $_GET["id"];

/* pedido y comprobar que es del cliente */
$stmt = $pdo->prepare("SELECT id_pedido, fecha, total, estado FROM pedido WHERE id_pedido = ? AND id_usuario = ? LIMIT 1");
$stmt->execute([$id_pedido, $id_usuario]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido no encontrado o no tienes permiso para verlo.";
    exit;
}

/* líneas del pedido + nombre del producto */
$stmtL = $pdo->prepare("
    SELECT lp.id_linea, lp.cantidad, lp.precio_unitario, p.nombre
    FROM linea_pedido lp
    INNER JOIN producto p ON p.id_producto = lp.id_producto
    WHERE lp.id_pedido = ?
");
$stmtL->execute([$id_pedido]);
$lineas = $stmtL->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detalle del pedido</title>

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

      <!-- Navbar moderno (mismo que el resto) -->
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

      <!-- Tarjeta tipo “factura” -->
      <section class="invoice-card">
        <header class="invoice-header">
          <h1 class="invoice-title">Detalle del pedido #<?php echo htmlspecialchars($pedido["id_pedido"]); ?></h1>

          <div class="invoice-meta">
            <div class="meta-item">
              <div class="meta-label">Fecha</div>
              <div class="meta-value"><?php echo htmlspecialchars($pedido["fecha"]); ?></div>
            </div>

            <div class="meta-item">
              <div class="meta-label">Estado</div>
              <div class="meta-value"><?php echo htmlspecialchars($pedido["estado"]); ?></div>
            </div>

            <div class="meta-item">
              <div class="meta-label">Total</div>
              <div class="meta-value"><?php echo htmlspecialchars(number_format((float)$pedido["total"], 2)); ?> €</div>
            </div>
          </div>
        </header>

        <div class="p-3 p-lg-4">
          <h2 class="section-title">Artículos</h2>

          <?php if (empty($lineas)): ?>
            <div class="empty-state">Este pedido no tiene líneas registradas.</div>
          <?php else: ?>
            <div class="table-scroll">
              <table class="table table-premium align-middle">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario (€)</th>
                    <th>Subtotal (€)</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($lineas as $l): ?>
                    <?php $subtotal = $l["cantidad"] * $l["precio_unitario"]; ?>
                    <tr>
                      <td class="fw-bold"><?php echo htmlspecialchars($l["nombre"]); ?></td>
                      <td><?php echo htmlspecialchars($l["cantidad"]); ?></td>
                      <td><?php echo htmlspecialchars($l["precio_unitario"]); ?></td>
                      <td class="fw-bold"><?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

          <div class="mt-3">
            <a class="back-link" href="mis_pedidos.php">Volver a mis pedidos</a>
          </div>
        </div>
      </section>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
