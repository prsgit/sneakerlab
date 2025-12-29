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
    <title>Compra confirmada</title>
</head>
<body>

<h1>Compra confirmada</h1>
<p>Tu pedido se ha registrado correctamente.</p>

<a href="catalogo.php">Volver al catálogo</a>

</body>
</html>
