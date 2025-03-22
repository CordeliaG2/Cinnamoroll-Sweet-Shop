<?php
session_start();
require_once 'config/database.php';
require_once 'config/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cinnamoroll Sweet Shop</title>
  
  <!-- Preconexión y fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
  
  <!-- Bootstrap CSS y hoja de estilos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/estilos.css">
  
</head>
<body>
  <!-- ---------- ANIMACIÓN INICIAL ---------- -->
  <div id="intro-logo" style="background: url('images/cinnamoroll.png') no-repeat center/cover;"></div>

  <div id="spinning-bg" style="background: url('images/cinnamoroll.png') no-repeat center/cover,position-absolute bottom-0 end-0;"></div>


  <!-- ---------- HEADER ---------- -->
  <header id="main-navbar" class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand">
        <img src="css/cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
        <strong class="custom-font">Cinnamoroll Sweet Shop</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a href="#" class="nav-link active">Catálogo</a></li>
          <li class="nav-item"><a href="#" class="nav-link active">Contacto</a></li>
        </ul>
        <button id="toggleMusicButton" class="btn btn-secondary">Apagar Música</button>
        <a href="logout.php" class="btn btn-danger ms-2">Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <!-- ---------- CONTENIDO PRINCIPAL ---------- -->
  <main id="contenido">
    <div class="container content">
      <?php if ($es_admin): ?>
        <div class="text-end mb-3">
          <a href="admin_productos.php" class="btn btn-warning">➕ Administrar Productos</a>
        </div>
      <?php endif; ?>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach ($resultado as $row): 
          $id = $row['id'];
          $image = "images/productos/$id/principal.png";
          if (!file_exists($image)) { $image = "images/no-photo.png"; }
        ?>
          <div class="col">
            <div class="card h-100">
              <img src="<?= $image; ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($row['nombre']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?= $row['nombre']; ?></h5>
                <p class="card-text">$<?= number_format($row['precio'], 2, '.', ','); ?></p>
                <button class="btn btn-primary btn-sm btn-hover detalles-btn" data-id="<?= $row['id']; ?>" data-token="<?= hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">Detalles</button>
                <button class="btn btn-success btn-sm btn-hover agregar-btn" data-id="<?= $row['id']; ?>" data-token="<?= hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">Agregar</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <!-- ---------- MODAL PARA DETALLES ---------- -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered container-fluid">
      <div class="modal-content" id="modalDetallesContent">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetallesLabel">Detalles del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body d-flex justify-content-center align-items-center">
          <iframe id="detallesFrame" class="img-fluid"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- ---------- AUDIO DE FONDO ---------- -->
  <audio id="backgroundMusic" autoplay loop>
    <source src="audio/tu_cancion.mp3" type="audio/mpeg">
    Tu navegador no soporta la reproducción de audio.
  </audio>

  <!-- ---------- SCRIPTS ---------- -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("📌 DOM completamente cargado.");

    // Mostrar contenido después de 3 segundos
    setTimeout(() => {
        let contenido = document.getElementById("contenido");
        let navbar = document.getElementById("main-navbar");

        if (contenido) contenido.classList.add("mostrar");
        if (navbar) {
            navbar.style.opacity = "1";
            navbar.style.transform = "translateY(0)";
        }
        document.body.style.overflow = "auto";
    }, 3000);

    // Manejo de eventos de click
    document.addEventListener("click", function (e) {
        const target = e.target;

        // Botón de detalles
        if (target.classList.contains("detalles-btn")) {
            const id = target.dataset.id;
            const token = target.dataset.token;
            let detallesFrame = document.getElementById("detallesFrame");
            let modalDetalles = document.getElementById("modalDetallesContent");

            if (detallesFrame) {
                detallesFrame.src = `detalles.php?id=${id}&token=${token}`;
            }
            if (modalDetalles) {
                modalDetalles.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 90%)`;
            }

            let modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
            modal.show();
        }

        // Botón de agregar al carrito
        else if (target.classList.contains("agregar-btn")) {
            const id = target.dataset.id;
            const token = target.dataset.token;

            fetch("agregar_carrito.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${encodeURIComponent(id)}&token=${encodeURIComponent(token)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        showNotification("¡Producto agregado al carrito!");
                        actualizarCarrito();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("❌ Error:", error);
                    alert("Error en la comunicación con el servidor.");
                });
        }

        // Botón para encender/apagar música
        else if (target.id === "toggleMusicButton") {
            const music = document.getElementById("backgroundMusic");
            if (music) {
                if (music.paused) {
                    music.play();
                    target.textContent = "Apagar Música";
                } else {
                    music.pause();
                    target.textContent = "Encender Música";
                }
            }
        }
    });

    // Interceptar clicks en botones de eliminación dentro del modal del carrito
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("eliminar-producto")) {
            event.preventDefault();
            let productId = event.target.dataset.id;

            console.log(`🗑️ Eliminando producto ID: ${productId}...`);
            fetch(`eliminarproducto.php?id=${productId}`, { method: "GET" })
                .then(response => response.text())
                .then(() => {
                    console.log("✅ Producto eliminado. Actualizando modal...");
                    actualizarContenidoModal();
                })
                .catch(error => console.error("❌ Error al eliminar producto:", error));
        }
    });

    // Función para actualizar el contenido del carrito en el modal
    function actualizarContenidoModal() {
        fetch("carrito_vista.php")
            .then(response => response.text())
            .then(data => {
                let cartItemsContainer = document.getElementById("cart-items");
                if (cartItemsContainer) {
                    cartItemsContainer.innerHTML = data;
                    actualizarBurbuja();
                }
            })
            .catch(error => console.error("❌ Error al actualizar modal:", error));
    }

    // Función para actualizar el contador del carrito
    function actualizarCarrito() {
        fetch("carrito_contador.php")
            .then(response => response.text())
            .then(data => {
                let contador = document.getElementById("contador-carrito");
                if (contador) contador.textContent = data;
            })
            .catch(error => console.error("❌ Error al actualizar carrito:", error));
    }

    // Manejo del modal del carrito
    let modalAbierto = false;
    let cartBubble = document.getElementById("cart-bubble");
    let cartCount = document.getElementById("cart-count");

    // Definir cartBubble globalmente
    window.cartBubble = document.getElementById("cart-bubble");

    if (!cartBubble) {
        console.warn("⚠️ Advertencia: #cart-bubble no encontrado. Puede que el carrito esté vacío.");
    }
    // Función para actualizar la burbuja del carrito
    function actualizarBurbuja() {
        if (modalAbierto) return; // No actualizar si el modal está abierto

        fetch("carrito_vista.php")
            .then(response => response.text())
            .then(data => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(data, "text/html");
                let filas = doc.querySelectorAll("tbody tr");

                let totalItems = filas.length;

                if (totalItems > 0) {
                    if (cartCount) cartCount.textContent = totalItems;
                    if (cartBubble) cartBubble.style.display = "flex";
                } else {
                    if (cartBubble) cartBubble.style.display = "none";
                }
            })
            .catch(error => console.error("❌ Error al actualizar la burbuja:", error));
    }

    // Llamar a la función al cargar la página y cada 5 segundos
    actualizarBurbuja();
    setInterval(actualizarBurbuja, 5000);

    // Función para mostrar notificaciones
    function showNotification(message) {
        let notification = document.getElementById("notification");
        if (!notification) return;

        notification.textContent = message;
        notification.classList.remove("hidden");
        notification.classList.add("show");

        setTimeout(() => {
            notification.classList.remove("show");
            setTimeout(() => {
                notification.classList.add("hidden");
            }, 500);
        }, 2000);
    }
});
function toggleCartModal() {
    console.log("🔄 Intentando abrir el modal...");

    var modal = new bootstrap.Modal(document.getElementById("cartModal"));
    var cartItemsContainer = document.getElementById("cart-items");

    if (!cartItemsContainer) {
        console.error("❌ ERROR: No se encontró #cart-items en el modal.");
        return;
    }

    fetch("carrito_vista.php")
        .then(response => response.text())
        .then(data => {
            console.log("📦 Contenido cargado:", data);
            cartItemsContainer.innerHTML = data;
            modal.show();

            // Verificar que cartBubble existe antes de modificarlo
            if (cartBubble) {
                cartBubble.style.display = "none"; // Ocultar burbuja al abrir el modal
            }
            window.modalAbierto = true;
        })
        .catch(error => console.error("❌ Error al cargar el carrito:", error));

    document.getElementById("cartModal").addEventListener("hidden.bs.modal", function () {
        window.modalAbierto = false;

        if (cartBubble) {
            cartBubble.style.display = "flex";
        }
    });
}

  </script>
<?php
$totalCount = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalCount += $item['quantity'];
    }
}

?>
<div id="cart-bubble" class="container-fluid" onclick="toggleCartModal()" style="display: <?= $totalCount > 0 ? 'flex' : 'none' ?>;">
  🛒 <span id="cart-count"><?= $totalCount ?></span>
</div>


<!-- Modal Bootstrap -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered container-fluid">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="cart-items">
          <!-- Aquí se cargará el carrito dinámicamente -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button onclick="window.location.href='checkout.php'" class="btn btn-primary">Finalizar Compra</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>
