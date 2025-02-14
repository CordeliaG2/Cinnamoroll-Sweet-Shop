<?php
require 'config/database.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
$sql->execute([$nombre, $descripcion, $precio, $stock]);

header("Location: admin_productos.php");
?>
