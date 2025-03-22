<?php
session_start();
echo array_sum($_SESSION['cart']);
?>

<a class="navbar-brand" href="carrito_vista.php">
    🛒 Carrito (<span id="contador-carrito"><?php echo array_sum($_SESSION['cart']); ?></span>)
</a>
