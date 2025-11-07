<?php
require_once __DIR__ . '/db.php';

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'featured';

// Build query
$where = ["is_active = 1"];
$params = [];
$types = '';

if ($category) {
    $where[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}

if ($search) {
    $where[] = "(name LIKE ? OR description LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ss';
}

$whereClause = implode(' AND ', $where);

// Sorting
$orderBy = match($sort) {
    'price_low' => 'price ASC',
    'price_high' => 'price DESC',
    'name' => 'name ASC',
    'newest' => 'created_at DESC',
    default => 'featured DESC, created_at DESC'
};

$query = "SELECT * FROM products WHERE $whereClause ORDER BY $orderBy";
$stmt = $conn->prepare($query);

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$pageTitle = $category ? ucfirst($category) . ' Bikes' : 'All Bikes';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= $pageTitle ?> | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="shop-hero">
  <div class="container">
    <h1><?= $pageTitle ?></h1>
    <p>Find your perfect ride from our premium collection</p>
  </div>
</section>

<section class="shop-content">
  <div class="container">
    
    <!-- Filters and Search -->
    <div class="shop-controls">
      <div class="search-box">
        <form method="GET" action="shop.php">
          <input type="text" name="search" placeholder="Search bikes..." value="<?= htmlspecialchars($search) ?>">
          <?php if ($category): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
          <?php endif; ?>
          <button type="submit">🔍</button>
        </form>
      </div>
      
      <div class="sort-box">
        <form method="GET" action="shop.php" id="sortForm">
          <?php if ($category): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
          <?php endif; ?>
          <?php if ($search): ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
          <?php endif; ?>
          <select name="sort" onchange="document.getElementById('sortForm').submit()">
            <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
          </select>
        </form>
      </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="category-pills">
      <a href="shop.php" class="pill <?= !$category ? 'active' : '' ?>">All Bikes</a>
      <a href="shop.php?category=mountain" class="pill <?= $category === 'mountain' ? 'active' : '' ?>">Mountain</a>
      <a href="shop.php?category=road" class="pill <?= $category === 'road' ? 'active' : '' ?>">Road</a>
      <a href="shop.php?category=hybrid" class="pill <?= $category === 'hybrid' ? 'active' : '' ?>">Hybrid</a>
      <a href="shop.php?category=electric" class="pill <?= $category === 'electric' ? 'active' : '' ?>">Electric</a>
      <a href="shop.php?category=kids" class="pill <?= $category === 'kids' ? 'active' : '' ?>">Kids</a>
      <a href="shop.php?category=bmx" class="pill <?= $category === 'bmx' ? 'active' : '' ?>">BMX</a>
    </div>

    <!-- Products Grid -->
    <?php if (count($products) > 0): ?>
      <div class="products-grid">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <?php if ($product['featured']): ?>
              <span class="badge-featured">⭐ Featured</span>
            <?php endif; ?>
            <?php if ($product['stock'] <= 0): ?>
              <span class="badge-stock">Out of Stock</span>
            <?php endif; ?>
            
            <div class="product-image">
              <a href="product.php?id=<?= $product['id'] ?>">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
              </a>
            </div>
            
            <div class="product-info">
              <span class="product-category"><?= ucfirst($product['category']) ?></span>
              <h3><a href="product.php?id=<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a></h3>
              <p class="product-description"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
              <div class="product-footer">
                <span class="product-price">₱<?= number_format($product['price'], 2) ?></span>
                <?php if ($product['stock'] > 0): ?>
                  <a href="product.php?id=<?= $product['id'] ?>" class="btn small">View Details</a>
                <?php else: ?>
                  <button class="btn small" disabled>Out of Stock</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-results">
        <h3>No bikes found</h3>
        <p>Try adjusting your search or filters</p>
        <a href="shop.php" class="btn">View All Bikes</a>
      </div>
    <?php endif; ?>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
