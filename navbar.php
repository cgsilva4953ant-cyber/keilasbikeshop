<?php
// Enhanced navbar for Keila's Bike Shop
// Include this in all pages with: require_once 'navbar.php';

// Calculate cart count from session
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? (int)$item['quantity'] : 0;
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
  <div class="nav">
    <div class="logo">
      <a href="index.php">🚴‍♀️ Keila's Bikes</a>
    </div>
    
    <button class="mobile-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
    
    <nav class="main-nav" id="mainNav">
      <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a>
      
      <!-- Shop Dropdown -->
      <div class="dropdown">
        <a href="shop.php" class="<?= $current_page === 'shop.php' ? 'active' : '' ?>">
          Shop <span class="dropdown-arrow">▼</span>
        </a>
        <div class="dropdown-menu">
          <a href="shop.php">All Bikes</a>
          <a href="shop.php?category=mountain">Mountain Bikes</a>
          <a href="shop.php?category=road">Road Bikes</a>
          <a href="shop.php?category=hybrid">Hybrid Bikes</a>
          <a href="shop.php?category=electric">Electric Bikes</a>
        </div>
      </div>
      
      <a href="about.php" class="<?= $current_page === 'about.php' || $current_page === 'about (1).php' ? 'active' : '' ?>">About</a>
      <a href="contact.php" class="<?= $current_page === 'contact.php' || $current_page === 'contact (1).php' ? 'active' : '' ?>">Contact</a>
      
      <!-- Cart Icon with Counter -->
      <a href="cart.php" class="cart-link <?= $current_page === 'cart.php' ? 'active' : '' ?>">
        🛒 Cart
        <?php if ($cart_count > 0): ?>
          <span class="cart-badge"><?= $cart_count ?></span>
        <?php endif; ?>
      </a>
      
      <!-- User Menu -->
      <?php if (isset($_SESSION['user'])): ?>
        <div class="dropdown user-dropdown">
          <a href="#" class="user-link">
            👤 <?= htmlspecialchars($_SESSION['user']['name']) ?> <span class="dropdown-arrow">▼</span>
          </a>
          <div class="dropdown-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">My Orders</a>
            <a href="profile.php">Profile</a>
          </div>
        </div>
        <a href="logout.php" class="btn small logout-btn">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn small <?= $current_page === 'login.php' ? 'active' : '' ?>">Login</a>
        <a href="signup.php" class="btn small outline <?= $current_page === 'signup.php' ? 'active' : '' ?>">Sign Up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<script>
// Enhanced dropdown and mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('mobileMenuToggle');
  const nav = document.getElementById('mainNav');
  
  // Mobile menu toggle
  if (toggle && nav) {
    toggle.addEventListener('click', function() {
      nav.classList.toggle('active');
      toggle.classList.toggle('active');
    });
  }
  
  // Enhanced dropdown behavior
  const dropdowns = document.querySelectorAll('.dropdown');
  
  dropdowns.forEach(dropdown => {
    const menu = dropdown.querySelector('.dropdown-menu');
    let timeoutId;
    
    // Show dropdown on hover
    dropdown.addEventListener('mouseenter', function() {
      clearTimeout(timeoutId);
      dropdown.classList.add('show');
    });
    
    // Hide dropdown with delay on mouse leave
    dropdown.addEventListener('mouseleave', function() {
      timeoutId = setTimeout(() => {
        dropdown.classList.remove('show');
      }, 300); // 300ms delay
    });
    
    // Keep dropdown open when hovering over menu items
    if (menu) {
      menu.addEventListener('mouseenter', function() {
        clearTimeout(timeoutId);
      });
      
      menu.addEventListener('mouseleave', function() {
        timeoutId = setTimeout(() => {
          dropdown.classList.remove('show');
        }, 300);
      });
    }
  });
  
  // Close mobile menu and dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.nav')) {
      nav.classList.remove('active');
      toggle.classList.remove('active');
      
      // Close all dropdowns
      dropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    }
  });
  
  // Close dropdowns when clicking inside nav but outside dropdown
  nav.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      dropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    }
  });
});
</script>
