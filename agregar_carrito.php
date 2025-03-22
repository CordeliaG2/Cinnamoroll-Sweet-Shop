<?php
session_start();
require 'config/config.php'; // Aquí debes definir KEY_TOKEN

// Inicializa el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $token = isset($_POST['token']) ? $_POST['token'] : '';

    if ($id == '' || $token == '') {
        echo json_encode(['ok' => false, 'message' => 'Error de petición']);
        exit;
    }

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = ['quantity' => 1];
        }
        echo json_encode(['ok' => true, 'totalCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))]);
    } else {
        echo json_encode(['ok' => false, 'message' => 'Token no válido']);
    }
}
?>
