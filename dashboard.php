<?php
$servername="localhost";$username="root";$password="";$dbname="keilas_db";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){die("Connection failed: ".$conn->connect_error);}
session_start();
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
<header>
  <div class="nav">
    <div class="logo"><a href="index.php">🚴‍♀️ Keila's Bikes</a></div>
    <nav>
      <a href="index.php">Home</a>
      <a href="logout.php" class="btn small">Logout</a>
    </nav>
  </div>
</header>

<section class="dash animate">
  <div class="dash-card">
    <img src="images/profile.jpg" class="dash-img" alt="User">
    <h2>Hello, <?=htmlspecialchars($user['name'])?>!</h2>
    <p>Your registered email: <?=htmlspecialchars($user['email'])?></p>
    <p class="note">Check out our latest bikes and exclusive offers.</p>
    <a href="index.php#bikes" class="btn">Browse Bikes</a>
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
