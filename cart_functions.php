<?php
// Shopping cart helper functions

function add_to_cart($product_id, $quantity = 1, $conn) {
    // Validate product exists
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }
    
    if ($product['stock'] < $quantity) {
        return ['success' => false, 'message' => 'Insufficient stock'];
    }
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $new_qty = $item['quantity'] + $quantity;
            if ($new_qty > $product['stock']) {
                return ['success' => false, 'message' => 'Cannot exceed available stock'];
            }
            $item['quantity'] = $new_qty;
            $found = true;
            break;
        }
    }
    
    // Add new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }
    
    return ['success' => true, 'message' => 'Added to cart'];
}

function update_cart_quantity($product_id, $quantity, $conn) {
    if (!isset($_SESSION['cart'])) {
        return ['success' => false, 'message' => 'Cart is empty'];
    }
    
    if ($quantity <= 0) {
        return remove_from_cart($product_id);
    }
    
    // Validate stock
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result || $quantity > $result['stock']) {
        return ['success' => false, 'message' => 'Insufficient stock'];
    }
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            return ['success' => true, 'message' => 'Cart updated'];
        }
    }
    
    return ['success' => false, 'message' => 'Product not in cart'];
}

function remove_from_cart($product_id) {
    if (!isset($_SESSION['cart'])) {
        return ['success' => false, 'message' => 'Cart is empty'];
    }
    
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
        return $item['product_id'] != $product_id;
    });
    
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    
    return ['success' => true, 'message' => 'Removed from cart'];
}

function get_cart_total() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}

function get_cart_count() {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}

function clear_cart() {
    $_SESSION['cart'] = [];
    return ['success' => true, 'message' => 'Cart cleared'];
}
