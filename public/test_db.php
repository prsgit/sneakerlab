<?php

require_once "../config/db.php";

$sql = "SELECT * FROM producto";
$stmt = $pdo->query($sql);

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    echo "Nombre: " . $producto['nombre'] . "<br>";
    echo "Descripción: " . $producto['descripcion'] . "<br>";
    echo "Precio: " . $producto['precio'] . " €<br>";
    echo "Stock: " . $producto['stock'] . "<br>";
    echo "<hr>";
}
