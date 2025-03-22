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
                if (cartBubble) cartBubble.style.display = "none"; // Ocultar burbuja al abrir el modal
                modalAbierto = true;
            })
            .catch(error => console.error("❌ Error al cargar el carrito:", error));

        // Detectar cuando el modal se cierra
        document.getElementById("cartModal").addEventListener("hidden.bs.modal", function () {
            modalAbierto = false;
            if (cartBubble) cartBubble.style.display = "flex";
        });
    }