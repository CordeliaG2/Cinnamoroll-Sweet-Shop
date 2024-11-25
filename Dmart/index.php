<?php 
// Incluye los archivos que contienen la configuración de la base de datos y las constantes
require 'config/database.php';
require 'config/config.php';

// Crea una nueva instancia de la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Prepara la consulta SQL para obtener productos activos (activo=1)
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute(); // Ejecuta la consulta
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC); // Almacena los resultados en un array asociativo
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cinnamoroll</title>

    <!-- Enlace a Google Fonts para la fuente "Lobster" -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

    <!-- Enlace a Bootstrap para el diseño responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- Enlace a un archivo CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body>
    <style>
        /* Define el estilo para la fuente global y elimina márgenes */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Lobster', cursive; /* Usa la fuente Lobster */
        }

        /* Fondo animado con gradiente de colores */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(300deg, #87CEEB, #6DD5ED, #5333ED); /* Degradado de azul */
            background-size: 600% 600%; /* Hace que el gradiente se mueva suavemente */
            animation: gradient-animation 10s ease infinite; /* Animación continua del fondo */
            z-index: -1; /* Coloca el fondo detrás del contenido */
        }

        /* Animación de gradiente */
        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Estilos para el contenido (texto, etc.) */
        .content {
            z-index: 1;
            position: relative;
            color: white;
            text-align: center;
            padding: 100px;
        }

        /* Estilos del header con fondo azul cielo */
        .bg-sky-blue {
            background-color: #87CEEB;
        }

        /* Animación de oscilación de la imagen */
        @keyframes swing {
            0% {
                transform: rotateX(0);
            }
            50% {
                transform: rotateX(40deg);
            }
            100% {
                transform: rotateX(0);
            }
        }

        /* Fuente personalizada para el texto del header */
        .custom-font {
            font-family: 'Lobster', cursive;
            font-size: 24px;
            color: white;
        }

        /* Aplicar la animación de oscilación a la imagen */
        .swing-image {
            animation: swing 1s ease-in-out infinite;
            height: 75px;
            transform-origin: bottom;
        }

        /* Oscilación de los botones al pasar el cursor por encima */
        @keyframes button-swing {
            0% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Aplica la animación a los botones cuando el cursor pasa sobre ellos */
        .btn:hover {
            animation: button-swing 0.5s ease-in-out infinite;
        }
    </style>

    <!-- Inicio del encabezado -->
    <header>
        <div class="navbar navbar-expand_lg navbar-dark bg-sky-blue shadow-sm">
            <div class="container">
                <!-- Logo e imagen con animación de oscilación -->
                <a href="#" class="navbar-brand">
                    <img src="cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
                    <strong class="custom-font swing-image">Cinnamoroll Sweet Shop</strong>
                </a>

                <!-- Botón para colapsar el menú en pantallas pequeñas -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú de navegación -->
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- Fin del encabezado -->

    <!-- Fondo animado (opcional) -->
    <!-- <div class="animated-background"></div> -->

    <!-- Cuerpo principal: Mostrar productos -->
    <main>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php  
                // Recorre cada producto del resultado y los muestra en una tarjeta (card)
                foreach ($resultado as $row) {
                ?>

                <div class="col">
                    <div class="card shadow-sm h-100">
                        <?php 
                        $id = $row['id'];
                        // Busca la imagen del producto, si no existe, usa una imagen por defecto
                        $image = "images/productos/" . $id . "/principal.png";
                        if (!file_exists($image)) {
                            $image = "images/no-photo.png";
                        }
                        ?>

                        <!-- Muestra la imagen del producto -->
                        <img src=" <?php echo $image; ?> " class="card-img-top img-fluid rounded d-block w-100">
                        <div class="card-body">
                            <!-- Nombre y precio del producto -->
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text">$ <?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Botones de detalles y agregar al carrito -->
                                <div class="btn-group">
                                    <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                </div>
                                <div class="btn-group">
                                    <a href="" class="btn btn-success">Agregar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

</body>
</html>
