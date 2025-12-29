<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];

/* echo "<pre>";
print_r($_SESSION["carrito"] ?? []);
echo "</pre>";
 */

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
    <title>Carrito de la compra</title>
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


<h1>Carrito de la compra</h1>

<?php if (empty($carrito)): ?>
    <p>El carrito está vacío.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($productos as $producto): ?>
            <?php
                $cantidad = $carrito[$producto['id_producto']];
                $subtotal = $producto['precio'] * $cantidad;
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars(number_format((float)$producto['precio'], 2)); ?> €</td>
                <td><?php echo (int)$cantidad; ?></td>
                <td><?php echo htmlspecialchars(number_format((float)$subtotal, 2)); ?> €</td>
                <td>
                    <a href="eliminar_producto_carrito.php?id=<?php echo (int)$producto['id_producto']; ?>"
                        onclick="return confirm('¿Eliminar este producto del carrito?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?php echo htmlspecialchars(number_format((float)$total, 2)); ?> €</strong></td>
        </tr>
    </table>
    <form method="post" action="confirmar_compra.php">
        <button type="submit">Confirmar compra</button>
    </form>
    <p>
        <a href="vaciar_carrito.php" onclick="return confirm('¿Vaciar el carrito completo?');">
         Vaciar carrito
        </a>
    </p>

<?php endif; ?>

</body>
</html>
