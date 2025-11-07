<?php
require_once __DIR__ . '/db.php';
if(!isset($_SESSION['user'])){header("Location: login.php");exit;}
$user=$_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="dash animate">
  <div class="dash-card">
    <img src="images/profile.jpg" class="dash-img" alt="User">
    <h2>Hello, <?=htmlspecialchars($user['name'])?>!</h2>
    <p>Your registered email: <?=htmlspecialchars($user['email'])?></p>
    <p class="note">Check out our latest bikes and exclusive offers.</p>
    <a href="shop.php#bikes" class="btn">Browse Bikes</a>
    <a href="orders.php" class="btn outline">My Orders</a>
  </div>
</section>

<footer><p>© <?=date('Y')?> Keila's Bikes | Enjoy the Ride</p></footer>

<script>
document.addEventListener("DOMContentLoaded",()=>{
  document.querySelector(".animate").classList.add("show");
});
</script>
</body>
</html>
