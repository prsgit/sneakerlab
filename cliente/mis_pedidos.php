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
    <title>Mis pedidos</title>
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

<h1>Mis pedidos</h1>

<?php if (empty($pedidos)): ?>
    <p>No tienes pedidos todavía.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID Pedido</th>
            <th>Fecha</th>
            <th>Total (€)</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($pedidos as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p["id_pedido"]); ?></td>
                <td><?php echo htmlspecialchars($p["fecha"]); ?></td>
                <td><?php echo htmlspecialchars($p["total"]); ?></td>
                <td><?php echo htmlspecialchars($p["estado"]); ?></td>
                <td>
                    <a href="pedido_detalle.php?id=<?php echo $p['id_pedido']; ?>">Ver detalle</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
