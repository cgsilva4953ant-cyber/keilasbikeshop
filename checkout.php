<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/cart_functions.php';

// Require login
if (!isset($_SESSION['user'])) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}

// Check cart not empty
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (count($cart) === 0) {
    header("Location: cart.php");
    exit;
}

$total = get_cart_total();
$shipping_fee = 200; // Flat rate for now
$grand_total = $total + $shipping_fee;

$user = $_SESSION['user'];
$error = '';
$success = '';

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['shipping_name']);
    $email = trim($_POST['shipping_email']);
    $phone = trim($_POST['shipping_phone']);
    $address = trim($_POST['shipping_address']);
    $payment = $_POST['payment_method'];
    $notes = trim($_POST['notes']);

    if (!$name || !$email || !$phone || !$address) {
        $error = 'Please fill in all required fields';
    } else {
        // Generate order number
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_amount, shipping_name, shipping_email, shipping_phone, shipping_address, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isdssssss", $user['id'], $order_number, $grand_total, $name, $email, $phone, $address, $payment, $notes);
            $stmt->execute();
            $order_id = $conn->insert_id;
            
            // Insert order items and update stock
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            
            foreach ($cart as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $stmt_item->bind_param("iisdid", $order_id, $item['product_id'], $item['name'], $item['price'], $item['quantity'], $subtotal);
                $stmt_item->execute();
                
                $stmt_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt_stock->execute();
            }
            
            $conn->commit();
            
            // Clear cart
            clear_cart();
            
            // Redirect to success page
            header("Location: order-success.php?order=$order_number");
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Order placement failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Checkout | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="checkout-section">
  <div class="container">
    <h1>Checkout</h1>

    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="checkout-form">
      <div class="checkout-layout">
        
        <!-- Shipping Information -->
        <div class="checkout-main">
          <div class="form-section">
            <h2>Shipping Information</h2>
            
            <div class="form-group">
              <label>Full Name *</label>
              <input type="text" name="shipping_name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="shipping_email" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>

              <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" name="shipping_phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label>Shipping Address *</label>
              <textarea name="shipping_address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
              <label>Order Notes (Optional)</label>
              <textarea name="notes" rows="2" placeholder="Special instructions or delivery notes"></textarea>
            </div>
          </div>

          <div class="form-section">
            <h2>Payment Method</h2>
            
            <div class="payment-options">
              <label class="payment-option">
                <input type="radio" name="payment_method" value="cod" checked>
                <div class="payment-info">
                  <strong>💵 Cash on Delivery</strong>
                  <p>Pay when you receive your order</p>
                </div>
              </label>

              <label class="payment-option">
                <input type="radio" name="payment_method" value="bank">
                <div class="payment-info">
                  <strong>🏦 Bank Transfer</strong>
                  <p>Transfer to our bank account (details will be sent)</p>
                </div>
              </label>

              <label class="payment-option">
                <input type="radio" name="payment_method" value="gcash">
                <div class="payment-info">
                  <strong>📱 GCash</strong>
                  <p>Pay via GCash mobile wallet</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="checkout-sidebar">
          <div class="order-summary">
            <h2>Order Summary</h2>
            
            <div class="order-items">
              <?php foreach ($cart as $item): ?>
                <div class="order-item">
                  <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                  <div class="order-item-info">
                    <p><?= htmlspecialchars($item['name']) ?></p>
                    <p class="order-item-qty">Qty: <?= $item['quantity'] ?></p>
                  </div>
                  <p class="order-item-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="order-totals">
              <div class="total-row">
                <span>Subtotal:</span>
                <span>₱<?= number_format($total, 2) ?></span>
              </div>
              <div class="total-row">
                <span>Shipping:</span>
                <span>₱<?= number_format($shipping_fee, 2) ?></span>
              </div>
              <hr>
              <div class="total-row grand-total">
                <span><strong>Grand Total:</strong></span>
                <span><strong>₱<?= number_format($grand_total, 2) ?></strong></span>
              </div>
            </div>

            <button type="submit" name="place_order" class="btn btn-large full-width">Place Order</button>
            
            <a href="cart.php" class="back-link">← Back to Cart</a>
          </div>
        </div>

      </div>
    </form>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
