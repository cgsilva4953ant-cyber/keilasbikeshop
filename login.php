<?php
$servername="localhost";$username="root";$password="";$dbname="keilas_db";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){die("Connection failed: ".$conn->connect_error);}
session_start();
$msg="";
if($_SERVER['REQUEST_METHOD']=='POST'){
  $email=trim($_POST['email']);
  $pass=$_POST['password'];
  if($email && $pass){
    $stmt=$conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res=$stmt->get_result();
    if($row=$res->fetch_assoc()){
      if(password_verify($pass,$row['password'])){
        $_SESSION['user']=$row;
        header("Location: dashboard.php");exit;
      }else{$msg="Incorrect password.";}
    }else{$msg="No account found.";}
  }else{$msg="Please enter all fields.";}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="form-bg">
<header class="header-simple">
  <div class="logo"><a href="index.php">🚴‍♀️ Keila's Bikes</a></div>
</header>

<div class="form-card animate">
  <h2>Welcome Back, Rider</h2>
  <p class="form-sub">Log in to continue exploring our premium bikes.</p>
  <?php if($msg):?><div class="msg"><?=$msg?></div><?php endif;?>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" class="btn full">Login</button>
  </form>
  <p class="switch">No account? <a href="signup.php">Sign Up</a></p>
</div>

<script>
document.addEventListener("DOMContentLoaded",()=>{
  document.querySelector(".animate").classList.add("show");
});
</script>
</body>
</html>
