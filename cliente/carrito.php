<?php
session_start();
require_once "../config/db.php";

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
    <title>Carrito de la compra</title>
</head>
<body>

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
        </tr>

        <?php foreach ($productos as $producto): ?>
            <?php
                $cantidad = $carrito[$producto['id_producto']];
                $subtotal = $producto['precio'] * $cantidad;
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['precio']; ?> €</td>
                <td><?php echo $cantidad; ?></td>
                <td><?php echo $subtotal; ?> €</td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?php echo $total; ?> €</strong></td>
        </tr>
    </table>
    <form method="post" action="confirmar_compra.php">
        <button type="submit">Confirmar compra</button>
    </form>

<?php endif; ?>

</body>
</html>
