<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/cart_functions.php';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        $_SESSION['cart_message'] = [
            'success' => false,
            'message' => 'Please login first to add items to cart'
        ];
        header("Location: login.php?redirect=product.php?id=" . (int)$_POST['product_id']);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $result = add_to_cart($product_id, $quantity, $conn);
    $_SESSION['cart_message'] = $result;
    header("Location: product.php?id=$product_id");
    exit;
}

// Get product
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: shop.php");
    exit;
}

// Get related products (same category, exclude current)
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND is_active = 1 LIMIT 4");
$stmt->bind_param("si", $product['category'], $id);
$stmt->execute();
$related = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$specs = $product['specs'] ? explode("\n", $product['specs']) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= htmlspecialchars($product['name']) ?> | Keila's Bikes</title>
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

<section class="product-detail">
  <div class="container">
    <div class="breadcrumb">
      <a href="index.php">Home</a> / 
      <a href="shop.php">Shop</a> / 
      <a href="shop.php?category=<?= $product['category'] ?>"><?= ucfirst($product['category']) ?></a> / 
      <span><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="product-layout">
      <!-- Product Image -->
      <div class="product-main-image">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <?php if ($product['featured']): ?>
          <span class="badge-featured">⭐ Featured</span>
        <?php endif; ?>
      </div>

      <!-- Product Info -->
      <div class="product-main-info">
        <span class="product-category"><?= ucfirst($product['category']) ?> Bike</span>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        
        <div class="product-price-large">₱<?= number_format($product['price'], 2) ?></div>
        
        <div class="product-stock">
          <?php if ($product['stock'] > 0): ?>
            <span class="in-stock">✓ In Stock (<?= $product['stock'] ?> available)</span>
          <?php else: ?>
            <span class="out-of-stock">✗ Out of Stock</span>
          <?php endif; ?>
        </div>

        <div class="product-description">
          <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

        <!-- Add to Cart Form -->
        <?php if ($product['stock'] > 0): ?>
          <form method="POST" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="quantity-selector">
              <label>Quantity:</label>
              <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" required>
            </div>
            <button type="submit" name="add_to_cart" class="btn btn-large">🛒 Add to Cart</button>
          </form>
        <?php else: ?>
          <button class="btn btn-large" disabled>Out of Stock</button>
        <?php endif; ?>

        <!-- Specifications -->
        <?php if (count($specs) > 0): ?>
          <div class="product-specs">
            <h3>Specifications</h3>
            <ul>
              <?php foreach ($specs as $spec): ?>
                <?php if (trim($spec)): ?>
                  <li><?= htmlspecialchars($spec) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Related Products -->
    <?php if (count($related) > 0): ?>
      <div class="related-products">
        <h2>You May Also Like</h2>
        <div class="products-grid">
          <?php foreach ($related as $rel): ?>
            <div class="product-card">
              <div class="product-image">
                <a href="product.php?id=<?= $rel['id'] ?>">
                  <img src="<?= htmlspecialchars($rel['image']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>">
                </a>
              </div>
              <div class="product-info">
                <span class="product-category"><?= ucfirst($rel['category']) ?></span>
                <h3><a href="product.php?id=<?= $rel['id'] ?>"><?= htmlspecialchars($rel['name']) ?></a></h3>
                <div class="product-footer">
                  <span class="product-price">₱<?= number_format($rel['price'], 2) ?></span>
                  <a href="product.php?id=<?= $rel['id'] ?>" class="btn small">View</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
