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
    <title>Detalle del pedido</title>
</head>
<body>

<nav style="margin-bottom:20px; padding:10px; border-bottom:1px solid #ccc;">
    <strong>Bienvenido<?php echo isset($_SESSION['nombre']) ? ', ' . htmlspecialchars($_SESSION['nombre']) : ''; ?></strong>
    |
    <a href="catalogo.php">Catálogo</a>
    |
    <a href="carrito.php">Carrito</a>
    |
    <a href="mis_pedidos.php">Mis pedidos</a>
    |
    <a href="logout.php">Cerrar sesión</a>
</nav>

<h1>Detalle del pedido #<?php echo htmlspecialchars($pedido["id_pedido"]); ?></h1>

<p><strong>Fecha:</strong> <?php echo htmlspecialchars($pedido["fecha"]); ?></p>
<p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido["estado"]); ?></p>
<p><strong>Total:</strong> <?php echo htmlspecialchars($pedido["total"]); ?> €</p>

<h2>Artículos</h2>

<?php if (empty($lineas)): ?>
    <p>Este pedido no tiene líneas registradas.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unitario (€)</th>
            <th>Subtotal (€)</th>
        </tr>

        <?php foreach ($lineas as $l): ?>
            <?php $subtotal = $l["cantidad"] * $l["precio_unitario"]; ?>
            <tr>
                <td><?php echo htmlspecialchars($l["nombre"]); ?></td>
                <td><?php echo htmlspecialchars($l["cantidad"]); ?></td>
                <td><?php echo htmlspecialchars($l["precio_unitario"]); ?></td>
                <td><?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<p><a href="mis_pedidos.php">← Volver a mis pedidos</a></p>

</body>
</html>
