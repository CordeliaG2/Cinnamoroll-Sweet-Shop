<?php
session_start();

// Verifica si el usuario tiene el rol de administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'config/database.php';
$db = new Database();
$con = $db->conectar();

// Si se envía el formulario, procesa el registro del producto

// Procesar el registro del producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $precio = $_POST['precio'];
    $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : 0;
    $stock = $_POST['stock'];

    // Inserta el producto en la base de datos
    $sql = $con->prepare("INSERT INTO productos (nombre, descripcion, precio, descuento, stock, activo) VALUES (?, ?, ?, ?, ?, 1)");
    $result = $sql->execute([$nombre, $descripcion, $precio, $descuento, $stock]);

    if ($result) {
        // Obtener el ID del producto insertado
        $id_producto = $con->lastInsertId();

        if (!$id_producto) {
            // Método alternativo si lastInsertId falla
            $sql = $con->prepare("SELECT LAST_INSERT_ID()");
            $sql->execute();
            $id_producto = $sql->fetchColumn();
        }

        echo "ID del producto insertado: " . $id_producto;

        // Crear un directorio para el producto
        $ruta_producto = "images/productos/$id_producto/";
        if (!file_exists($ruta_producto)) {
            mkdir($ruta_producto, 0777, true);
        }

        // Subir la imagen principal
        $img_principal = $ruta_producto . 'principal.png';
        move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $img_principal);

        // Subir imágenes secundarias
        foreach ($_FILES['imagenes_secundarias']['tmp_name'] as $key => $tmp_name) {
            $ext = pathinfo($_FILES['imagenes_secundarias']['name'][$key], PATHINFO_EXTENSION);
            $filename = $ruta_producto . 'secundaria_' . uniqid() . '.' . $ext;
            move_uploaded_file($tmp_name, $filename);
        }

        // Actualizar la base de datos con la imagen principal
        $sql = $con->prepare("UPDATE productos SET imagen = ? WHERE id = ?");
        $sql->execute([$img_principal, $id_producto]);

    } else {
        $errorInfo = $sql->errorInfo();
        echo "Error en la inserción: " . $errorInfo[2];
    }
}

// Obtener productos para mostrar
$sql = $con->prepare("SELECT * FROM productos WHERE activo = 1");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <style>
        .bg-sky-blue {
                background-color: #87CEEB; /* Color de fondo azul cielo */
            }

        body {
            background-color: #f8f9fa;
            font-family: 'Lobster', cursive;
        }
        .container {
            margin-top: 50px;
        }
        .custom-font {
            font-family: 'Lobster', cursive; /* Fuente Lobster */
            font-size: 24px;
            color: white;
        }
        @keyframes swing {
            0% { transform: rotateX(0); }
            50% { transform: rotateX(40deg); }
            100% { transform: rotateX(0); }
        }
        .swing-image {
            animation: swing 1s ease-in-out infinite;
            height: 75px;
            transform-origin: bottom;
        }

        /* Animación de oscilación para el texto */
        @keyframes text-swing {
            0% { transform: translateY(0); }
            50% { transform: translateY(-40px); }
            100% { transform: translateY(0); }
        }

        /* Aplicar animación de oscilación al texto */
        .swing-text {
            animation: text-swing 1.5s ease-in-out infinite;
        }

    </style>
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
        <div class="container">
            <!-- <a href="index.php" class="navbar-brand">
                <img src="cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
                <strong class="custom-font swing-text">Cinnamoroll Sweet Shop</strong>
            </a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>-->
        </div>
    </header>

    <div class="container">
        <h1 class="text-center mb-4">Administrar Productos</h1>

        <!-- Formulario para registrar productos -->
        <form method="POST" enctype="multipart/form-data" class="mb-5">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="descuento" class="form-label">Descuento (%)</label>
                <input type="number" class="form-control" id="descuento" name="descuento" step="1" min="0">
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="mb-3">
                <label for="imagen_principal" class="form-label">Imagen Principal</label>
                <input type="file" class="form-control" id="imagen_principal" name="imagen_principal" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="imagenes_secundarias" class="form-label">Imágenes Secundarias</label>
                <input type="file" class="form-control" id="imagenes_secundarias" name="imagenes_secundarias[]" accept="image/*" multiple>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrar Producto</button>
        </form>

        <!-- Tabla de productos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo $producto['descuento']; ?>%</td>
                        <td><?php echo isset($producto['stock']) ? $producto['stock'] : 'Sin definir'; ?></td>
                        <td>
                            <?php 
                                $imagen = isset($producto['imagen']) && file_exists($producto['imagen']) ? $producto['imagen'] : 'images/no-photo.png'; 
                            ?>
                            <img src="<?php echo $imagen; ?>" alt="Imagen" style="width: 100px; height: auto;">
                        </td>
                        <td>
                            <form method="POST" action="eliminar_producto.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
