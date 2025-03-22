<?php
session_start();
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_POST['id']) ? $_POST['id'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';

if ($id == '' || $precio == '') {
    echo 'Error de petición';
    exit;
}

// Aquí puedes agregar la lógica para procesar el pago
// Por ejemplo, puedes insertar un registro en la base de datos de pedidos

$sql = $con->prepare("INSERT INTO pedidos (producto_id, precio) VALUES (?, ?)");
$sql->execute([$id, $precio]);

echo 'Pago procesado con éxito';
?>