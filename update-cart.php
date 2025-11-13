<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'] ?? null;
    $change = $_POST['change'] ?? 0;
    
    if ($index !== null && isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] += $change;
        
        // Remove item if quantity is 0 or less
        if ($_SESSION['cart'][$index]['quantity'] <= 0) {
            array_splice($_SESSION['cart'], $index, 1);
        }
    }
}

echo json_encode(['success' => true]);
?>
