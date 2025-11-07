<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>About Us | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="about-hero">
  <div class="container">
    <h1>About Keila's Bikes</h1>
    <p>Your trusted partner for premium bicycles since 2010</p>
  </div>
</section>

<section class="about-story">
  <div class="container">
    <div class="about-layout">
      <div class="about-content">
        <h2>Our Story</h2>
        <p>
          Keila's Bikes started as a small bike shop in Marikina City with a simple mission: 
          to make quality bicycles accessible to every Filipino rider. What began as a passion 
          project has grown into one of the most trusted names in the Philippine cycling community.
        </p>
        <p>
          Over the years, we've served thousands of riders — from weekend warriors and daily commuters 
          to competitive athletes and adventure seekers. We take pride in offering not just bikes, 
          but complete cycling solutions backed by expert advice and exceptional after-sales support.
        </p>
        <p>
          Today, with our online platform, we're able to reach more riders across the Philippines, 
          bringing the same quality products and personalized service that made us a local favorite 
          to customers nationwide.
        </p>
      </div>
      <div class="about-image">
        <img src="logo.jpg" alt="Keila's Bikes" style="max-width: 300px;">
      </div>
    </div>
  </div>
</section>

<section class="about-values">
  <div class="container">
    <h2>Our Values</h2>
    <div class="values-grid">
      <div class="value-card">
        <div class="value-icon">🎯</div>
        <h3>Quality First</h3>
        <p>We source only the best bikes and components from trusted international brands to ensure durability and performance.</p>
      </div>

      <div class="value-card">
        <div class="value-icon">💡</div>
        <h3>Expert Guidance</h3>
        <p>Our team of cycling enthusiasts provides personalized recommendations to help you find the perfect bike for your needs.</p>
      </div>

      <div class="value-card">
        <div class="value-icon">🤝</div>
        <h3>Customer First</h3>
        <p>Your satisfaction is our priority. From pre-purchase consultation to after-sales support, we're here for you.</p>
      </div>

      <div class="value-card">
        <div class="value-icon">🚀</div>
        <h3>Innovation</h3>
        <p>We continuously improve our services through technology, making bike shopping easier and more convenient.</p>
      </div>
    </div>
  </div>
</section>

<section class="about-mission">
  <div class="container">
    <div class="mission-box">
      <h2>Our Mission</h2>
      <p>
        To inspire and enable more Filipinos to embrace cycling as a lifestyle — whether for fitness, 
        transportation, or adventure. We're committed to providing premium bikes, expert service, 
        and a seamless shopping experience that makes every ride better.
      </p>
    </div>
  </div>
</section>

<section class="about-cta">
  <div class="container">
    <h2>Ready to Start Your Cycling Journey?</h2>
    <p>Explore our collection and find your perfect ride today!</p>
    <a href="shop.php" class="btn btn-large">Browse Our Shop</a>
  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
