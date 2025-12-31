<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/*Comprueba que el carrito existe y no está vacío */
if (empty($_SESSION['carrito'])) {
    echo "El carrito está vacío. No se puede confirmar la compra.";
    exit;
}

$carrito = $_SESSION['carrito'];


$id_usuario = (int)$_SESSION["id_usuario"];


$total_pedido = 0;

foreach ($carrito as $id_producto => $cantidad) {

    $stmtProducto = $pdo->prepare(
        "SELECT precio FROM producto WHERE id_producto = ?"
    );
    $stmtProducto->execute([$id_producto]);
    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Producto no encontrado.";
        exit;
    }

    $total_pedido += $producto['precio'] * $cantidad;
}

/* transacción */
$pdo->beginTransaction();

try {

    /* Crear pedido */
    $sqlPedido = "
        INSERT INTO pedido (id_usuario, fecha, total, estado)
        VALUES (?, NOW(), ?, 'pendiente')
    ";
    $stmtPedido = $pdo->prepare($sqlPedido);
    $stmtPedido->execute([$id_usuario, $total_pedido]);

    $id_pedido = $pdo->lastInsertId();

    /*inserción de líneas de pedido */
    $sqlLinea = "
        INSERT INTO linea_pedido (id_pedido, id_producto, cantidad, precio_unitario)
        VALUES (?, ?, ?, ?)
    ";
    $stmtLinea = $pdo->prepare($sqlLinea);

    /*Insertar cada línea */
    foreach ($carrito as $id_producto => $cantidad) {

        $stmtProducto = $pdo->prepare(
            "SELECT precio FROM producto WHERE id_producto = ?"
        );
        $stmtProducto->execute([$id_producto]);
        $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }

        $precio_unitario = $producto['precio'];

        $stmtLinea->execute([
            $id_pedido,
            $id_producto,
            $cantidad,
            $precio_unitario
        ]);
    }

    /*Vaciar carrito */
    unset($_SESSION['carrito']);

    /*Confirmar transacción */
    $pdo->commit();

    echo "Pedido confirmado correctamente.";

} catch (Exception $e) {

    $pdo->rollBack();
    echo "Error al confirmar la compra: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Compra confirmada</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">
  
</head>

<body>
  <main class="auth-wrap">
    <section class="success-card">
      <div class="success-icon" aria-hidden="true">
        <!-- Check en SVG  -->
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <h1 class="success-title">Compra confirmada</h1>
      <p class="success-text">Tu pedido se ha registrado correctamente.</p>

      <a class="btn btn-brand" href="catalogo.php">Volver al catálogo</a>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

