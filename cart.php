<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/cart_functions.php';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        $result = update_cart_quantity($product_id, $quantity, $conn);
        $_SESSION['cart_message'] = $result;
    } elseif (isset($_POST['remove_item'])) {
        $product_id = (int)$_POST['product_id'];
        $result = remove_from_cart($product_id);
        $_SESSION['cart_message'] = $result;
    } elseif (isset($_POST['clear_cart'])) {
        $result = clear_cart();
        $_SESSION['cart_message'] = $result;
    }
    header("Location: cart.php");
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = get_cart_total();
$count = get_cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Shopping Cart | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<?php if (isset($_SESSION['cart_message'])): ?>
  <div class="alert <?= $_SESSION['cart_message']['success'] ? 'success' : 'error' ?>">
    <?= htmlspecialchars($_SESSION['cart_message']['message']) ?>
  </div>
  <?php unset($_SESSION['cart_message']); ?>
<?php endif; ?>

<section class="cart-section">
  <div class="container">
    <h1>🛒 Shopping Cart</h1>

    <?php if (count($cart) > 0): ?>
      <div class="cart-layout">
        <!-- Cart Items -->
        <div class="cart-items">
          <?php foreach ($cart as $item): ?>
            <div class="cart-item">
              <div class="cart-item-image">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              </div>
              
              <div class="cart-item-info">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p class="cart-item-price">₱<?= number_format($item['price'], 2) ?> each</p>
              </div>

              <div class="cart-item-quantity">
                <form method="POST">
                  <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                  <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" onchange="this.form.submit()">
                  <button type="submit" name="update_quantity" class="btn-hidden">Update</button>
                </form>
              </div>

              <div class="cart-item-subtotal">
                <p>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
              </div>

              <div class="cart-item-remove">
                <form method="POST">
                  <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                  <button type="submit" name="remove_item" class="btn-remove">✕</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="cart-actions">
            <form method="POST" onsubmit="return confirm('Clear entire cart?')">
              <button type="submit" name="clear_cart" class="btn outline">Clear Cart</button>
            </form>
            <a href="shop.php" class="btn outline">Continue Shopping</a>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
          <h2>Order Summary</h2>
          
          <div class="summary-row">
            <span>Subtotal (<?= $count ?> items):</span>
            <span>₱<?= number_format($total, 2) ?></span>
          </div>
          
          <div class="summary-row">
            <span>Shipping:</span>
            <span>Calculated at checkout</span>
          </div>

          <hr>

          <div class="summary-row total">
            <span><strong>Total:</strong></span>
            <span><strong>₱<?= number_format($total, 2) ?></strong></span>
          </div>

          <?php if (isset($_SESSION['user'])): ?>
            <a href="checkout.php" class="btn btn-large full-width">Proceed to Checkout</a>
          <?php else: ?>
            <a href="login.php?redirect=checkout.php" class="btn btn-large full-width">Login to Checkout</a>
          <?php endif; ?>

          <div class="payment-icons">
            <p>We accept:</p>
            <div class="icons">
              <span>💳 Credit Card</span>
              <span>🏦 Bank Transfer</span>
              <span>💵 Cash on Delivery</span>
            </div>
          </div>
        </div>
      </div>

    <?php else: ?>
      <div class="empty-cart">
        <div class="empty-icon">🛒</div>
        <h2>Your cart is empty</h2>
        <p>Start adding some bikes to your cart!</p>
        <a href="shop.php" class="btn btn-large">Browse Our Shop</a>
      </div>
    <?php endif; ?>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
