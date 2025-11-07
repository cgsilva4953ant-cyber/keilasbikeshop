<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Keila's Bikes - Premium Bikes for Every Rider</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="hero animate">
  <div class="hero-content">
    <h1>Ride with Style & Power</h1>
    <p>Explore high-performance bikes designed for adventure, speed, and comfort.</p>
    <a href="shop.php" class="btn">Explore Bikes</a>
  </div>
  <div class="hero-img">
    <img src="bike1.jpg" alt="Bike Hero">
  </div>
</section>

<section id="bikes" class="section animate right">
  <h2 class="section-title">Featured Bikes</h2>
  <div class="bike-grid">

    <div class="bike-card animate">
      <div class="bike-image">
        <img src="bike2.jpg" alt="Mountain Bike">
        <div class="hover-text">
          <p>Frame: Aluminum Alloy 6061 T6 frame with internal
          cable routing and Toseek Butted Tube Technology.
          Brakes: Hydraulic disc brakes. <br>
          Wheels: Alloy double-wall rims with a 32-hole hub. <br> </p>
        </div>
      </div>
      <h3>Mountain Bike</h3>
      <p>Built for durability and performance on any terrain.</p>
      <span class="price">₱45,000</span>
    </div>

    <div class="bike-card animate">
      <div class="bike-image">
        <img src="bike3.jpg" alt="Road Bike">
        <div class="hover-text">
          <p>Frame: Promax PR40 aero smooth welding frame.
          Wheels: The bike has double-wall aluminum rims with <br> 
          32H hubs and Ragusa Kayden Race Tanwall tires.
          Brakes: It is equipped with alloy C-brakes.
          </p>
        </div>
      </div>
      <h3>Road Bike</h3>
      <p>Designed for a combination of speed and performance.</p>
      <span class="price">₱35,000</span>
    </div>

    <div class="bike-card animate">
      <div class="bike-image">
        <img src="bike4.jpg" alt="Mountain Bike">
        <div class="hover-text">
          <p>Frame: Aluminum 6061 T6 with internal cable routing.
          Brakes: Metroshift alloy hydraulic disc brakes. <br>
          Wheels & Tires: Double-wall alloy rims and tanwall tires</p>
        </div>
      </div>
      <h3>Mountain Bike</h3>
      <p>Suspension fork with lockout, 100mm travel.</p>
      <span class="price">₱68,000</span>
    </div>

  </div>
</section>


<section id="about" class="section about animate left">
  <h2>About Keila's Bikes</h2>
  <p>At <strong>Keila's Bikes</strong>, we merge innovation and passion for cycling. From professional racers to casual riders, our bikes are built to deliver top performance and comfort on every ride.</p>
  <img src="logo.jpg" alt="About Us Image" height="170px" width="140px">
</section>

<section id="gallery" class="section gallery animate">
  <h2>Gallery</h2>
  <div class="gallery-grid">
    <img src="gallery1.jpg" class="animate" style="transition-delay:0.1s">
    <img src="gallery2.jpg" class="animate" style="transition-delay:0.2s">
    <img src="gallery3.jpg" class="animate" style="transition-delay:0.3s">
    <img src="gallery4.jpg" class="animate" style="transition-delay:0.4s">
  </div>
</section>

<footer><p>© <?=date("Y")?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const animated = document.querySelectorAll(".animate");
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  }, {threshold:0.15});
  animated.forEach(el => observer.observe(el));
});
</script>
</body>
</html>
