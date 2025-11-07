<?php
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Get user orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get order details if viewing specific order
$viewing_order = null;
$order_items = [];
if (isset($_GET['view'])) {
    $order_id = (int)$_GET['view'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user['id']);
    $stmt->execute();
    $viewing_order = $stmt->get_result()->fetch_assoc();
    
    if ($viewing_order) {
        $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

function get_status_badge($status) {
    $badges = [
        'pending' => '<span class="status-badge pending">⏳ Pending</span>',
        'processing' => '<span class="status-badge processing">📦 Processing</span>',
        'shipped' => '<span class="status-badge shipped">🚚 Shipped</span>',
        'delivered' => '<span class="status-badge delivered">✓ Delivered</span>',
        'cancelled' => '<span class="status-badge cancelled">✗ Cancelled</span>'
    ];
    return $badges[$status] ?? $status;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>My Orders | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="orders-section">
  <div class="container">
    <h1>My Orders</h1>

    <?php if (!$viewing_order): ?>
      <!-- Orders List -->
      <?php if (count($orders) > 0): ?>
        <div class="orders-list">
          <?php foreach ($orders as $order): ?>
            <div class="order-card">
              <div class="order-header">
                <div>
                  <h3>Order <?= htmlspecialchars($order['order_number']) ?></h3>
                  <p class="order-date">Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="order-status">
                  <?= get_status_badge($order['status']) ?>
                </div>
              </div>

              <div class="order-body">
                <div class="order-info">
                  <p><strong>Total Amount:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
                  <p><strong>Payment Method:</strong> <?= strtoupper($order['payment_method']) ?></p>
                  <p><strong>Shipping To:</strong> <?= htmlspecialchars($order['shipping_name']) ?></p>
                </div>
                <div class="order-actions">
                  <a href="orders.php?view=<?= $order['id'] ?>" class="btn small">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-orders">
          <div class="empty-icon">📦</div>
          <h2>No orders yet</h2>
          <p>Start shopping and place your first order!</p>
          <a href="shop.php" class="btn btn-large">Browse Shop</a>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- Order Detail View -->
      <div class="order-detail">
        <a href="orders.php" class="back-link">← Back to Orders</a>

        <div class="order-detail-header">
          <div>
            <h2>Order <?= htmlspecialchars($viewing_order['order_number']) ?></h2>
            <p>Placed on <?= date('F j, Y \a\t g:i A', strtotime($viewing_order['created_at'])) ?></p>
          </div>
          <?= get_status_badge($viewing_order['status']) ?>
        </div>

        <div class="order-detail-layout">
          <!-- Order Items -->
          <div class="order-items-detail">
            <h3>Order Items</h3>
            <?php foreach ($order_items as $item): ?>
              <div class="order-item-detail">
                <div class="item-info">
                  <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                  <p>Quantity: <?= $item['quantity'] ?> × ₱<?= number_format($item['product_price'], 2) ?></p>
                </div>
                <div class="item-subtotal">
                  ₱<?= number_format($item['subtotal'], 2) ?>
                </div>
              </div>
            <?php endforeach; ?>
            
            <div class="order-total-detail">
              <strong>Total:</strong>
              <strong>₱<?= number_format($viewing_order['total_amount'], 2) ?></strong>
            </div>
          </div>

          <!-- Shipping & Payment Info -->
          <div class="order-info-detail">
            <div class="info-section">
              <h3>Shipping Address</h3>
              <p><strong><?= htmlspecialchars($viewing_order['shipping_name']) ?></strong></p>
              <p><?= htmlspecialchars($viewing_order['shipping_email']) ?></p>
              <p><?= htmlspecialchars($viewing_order['shipping_phone']) ?></p>
              <p><?= nl2br(htmlspecialchars($viewing_order['shipping_address'])) ?></p>
            </div>

            <div class="info-section">
              <h3>Payment Information</h3>
              <p><strong>Method:</strong> <?= strtoupper($viewing_order['payment_method']) ?></p>
              <p><strong>Status:</strong> <?= ucfirst($viewing_order['payment_status']) ?></p>
            </div>

            <?php if ($viewing_order['notes']): ?>
              <div class="info-section">
                <h3>Order Notes</h3>
                <p><?= nl2br(htmlspecialchars($viewing_order['notes'])) ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
