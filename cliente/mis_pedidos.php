<?php
session_start();
require_once "../config/db.php";

/* Solo clientes logueados */
if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

/* Obtener pedidos del cliente */
$stmt = $pdo->prepare("SELECT id_pedido, fecha, total, estado FROM pedido WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->execute([$id_usuario]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mis pedidos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
</head>

<body>
  <div class="page">
    <div class="page-container">

      <!-- Navbar moderno -->
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

      <h1 class="page-title">Mis pedidos</h1>

      <?php if (empty($pedidos)): ?>
        <div class="empty-state">No tienes pedidos todavía.</div>
      <?php else: ?>

        <section class="orders-card">
          <div class="table-scroll">
            <table class="table table-premium align-middle">
              <thead>
                <tr>
                  <th>ID Pedido</th>
                  <th>Fecha</th>
                  <th>Total (€)</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($pedidos as $p): ?>
                  <tr>
                    <td class="fw-bold"><?php echo htmlspecialchars($p["id_pedido"]); ?></td>
                    <td><?php echo htmlspecialchars($p["fecha"]); ?></td>
                    <td class="fw-bold"><?php echo htmlspecialchars(number_format((float)$p["total"], 2)); ?></td>
                    <td>
                      <span class="badge-status">
                        <span class="dot"></span>
                        <?php echo htmlspecialchars($p["estado"]); ?>
                      </span>
                    </td>
                    <td>
                      <a class="action-link" href="pedido_detalle.php?id=<?php echo $p['id_pedido']; ?>">
                        Ver detalle
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
          </div>
        </section>

      <?php endif; ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
