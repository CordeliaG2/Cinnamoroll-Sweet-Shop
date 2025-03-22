<?php
session_start();
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '') {
    echo 'Error de petición';
    exit;
}

// Consulta para obtener los detalles del producto
$sql = $con->prepare("SELECT nombre, precio FROM productos WHERE id=? AND activo=1");
$sql->execute([$id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo 'Producto no encontrado';
    exit;
}

$nombre = $row['nombre'];
$precio = $row['precio'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <p>Producto: <?php echo $nombre; ?></p>
        <p>Precio: <?php echo MONEDA . number_format($precio, 2, '.', ','); ?></p>
        <!-- Aquí puedes agregar el formulario de pago o redirigir a una pasarela de pago -->
        <form action="procesar_pago.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="precio" value="<?php echo $precio; ?>">
            <button type="submit" class="btn btn-primary">Pagar</button>
        </form>
    </div>
</body>
</html>