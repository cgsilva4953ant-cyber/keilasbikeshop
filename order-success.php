<?php
require_once __DIR__ . '/db.php';

$order_number = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Order Confirmed | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="success-section">
  <div class="container">
    <div class="success-card">
      <div class="success-icon">✓</div>
      <h1>Order Placed Successfully!</h1>
      <p class="order-number">Order Number: <strong><?= $order_number ?></strong></p>
      
      <div class="success-message">
        <p>Thank you for your order! We've received your order and will begin processing it shortly.</p>
        <p>You will receive an email confirmation at the address you provided.</p>
      </div>

      <div class="success-actions">
        <a href="orders.php" class="btn btn-large">View My Orders</a>
        <a href="shop.php" class="btn outline">Continue Shopping</a>
      </div>

      <div class="next-steps">
        <h3>What's Next?</h3>
        <ol>
          <li>We'll confirm your order and prepare it for shipment</li>
          <li>You'll receive tracking information once shipped</li>
          <li>Your order will be delivered to your specified address</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
