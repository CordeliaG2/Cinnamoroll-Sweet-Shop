<?php 
// Se incluye la configuración general y la configuración de la base de datos
require 'config/config.php';
require 'config/database.php';

// Se crea una instancia de la clase Database y se conecta a la base de datos
$db = new Database();
$con = $db->conectar();

// Se obtienen los parámetros 'id' y 'token' desde la URL, si están definidos
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Si 'id' o 'token' no están presentes, se muestra un mensaje de error y se detiene la ejecución
if ($id == '' || $token == '') {
    echo 'Error de petición';
    exit;
} else {
    // Se genera un token temporal para verificar que la petición es válida
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    // Se compara el token generado con el token recibido
    if ($token == $token_tmp) {
        // Consulta a la base de datos para verificar si el producto con el ID dado existe y está activo
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);

        // Si el producto existe, se obtienen sus detalles
        if ($sql->fetchColumn() > 0) {
            // Se realiza una consulta para obtener los datos del producto: nombre, descripción, precio y descuento
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            // Se almacenan los datos del producto en variables
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];

            // Se calcula el precio con el descuento aplicado
            $precio_desc = $precio - (($precio * $descuento) / 100);

            // Se define la ruta de la carpeta de imágenes del producto
            $dir_images = 'images/productos/' . $id . '/';
            $rutaImg = $dir_images . 'principal.png';

            // Si la imagen principal no existe, se usa una imagen por defecto
            if (!file_exists($rutaImg)) {
                $rutaImg = 'images/no-photo.png';
            }

            // Se crea un array vacío para almacenar imágenes adicionales del producto
            $imagenes = array();
            $dir = dir($dir_images);

            // Se recorre el directorio para encontrar otras imágenes (.jpg o .jpeg) y agregarlas al array
            while (($archivo = $dir->read()) != false) {
                if ($archivo != 'principal.png' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                    $imagenes[] = $dir_images . $archivo;
                }
            }
            // Se cierra el directorio
            $dir->close();
        }

    } else {
        // Si el token no coincide, se muestra un mensaje de error y se detiene la ejecución
        echo 'Error de petición';
        exit;
    }
}

// Consulta adicional para obtener todos los productos activos (activo = 1)
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalles del Producto</title>

    <!-- Fuentes y estilos de Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- Archivo de estilos personalizados -->
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
    <header>
        <style>
            .bg-sky-blue {
                background-color: #87CEEB; /* Color de fondo azul cielo */
            }

            /* Animación de oscilación para la imagen */
            @keyframes swing {
                0% { transform: rotateX(0); }
                50% { transform: rotateX(40deg); }
                100% { transform: rotateX(0); }
            }

            .custom-font {
                font-family: 'Lobster', cursive; /* Fuente Lobster */
                font-size: 24px;
                color: white;
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
        <div class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
                    <strong class="custom-font swing-text">Cinnamoroll Sweet Shop</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row">
                <!-- Columna con el carrusel de imágenes -->
                <div class="col-md-6 order-md-1">
                    <div id="carouselimages" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <!-- Imagen principal -->
                            <div class="carousel-item active">
                                <img src=" <?php echo $rutaImg ?> " class="d-block w-100">
                            </div>
                            <!-- Otras imágenes del producto -->
                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item">
                                    <img src=" <?php echo $img ?> " class="d-block w-100">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <!-- Columna con la información del producto -->
                <div class="col-md-6 order-md-1">
                    <h2> <?php echo $nombre; ?> </h2>

                    <!-- Mostrar precio con descuento -->
                    <?php if ($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?> </del> </p>
                        <h2><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                            <small class="text-success"> <?php echo $descuento; ?>% descuento </small>
                        </h2>
                    <?php } else { ?>
                        <!-- Mostrar precio sin descuento -->
                        <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                    <?php } ?>

                    <!-- Descripción del producto -->
                    <p class="lead">
                        <?php echo $descripcion; ?>
                    </p>

                    <!-- Botones para comprar o agregar al carrito -->
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button">Comprar ahora</button>
                        <button class="btn btn-outline-primary" type="button">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
