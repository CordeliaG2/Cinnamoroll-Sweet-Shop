<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $db = new Database();
    $con = $db->conectar();

    // Desactivar el producto (eliminación lógica)
    $sql = $con->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
    $sql->execute([$id]);

    header("Location: admin_productos.php");
    exit;
}
?>
