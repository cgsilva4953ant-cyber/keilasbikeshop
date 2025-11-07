<?php
$servername="localhost";$username="root";$password="";$dbname="keilas_db";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){die("Connection failed: ".$conn->connect_error);}
session_start();
$msg="";
if($_SERVER['REQUEST_METHOD']=='POST'){
  $n=trim($_POST['name']);$e=trim($_POST['email']);$p=$_POST['password'];
  if($n&&$e&&$p){
    $check=$conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s",$e);
    $check->execute();
    $r=$check->get_result();
    if($r->num_rows>0){$msg="Email already used.";}
    else{
      $h=password_hash($p,PASSWORD_DEFAULT);
      $ins=$conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
      $ins->bind_param("sss",$n,$e,$h);
      $ins->execute();
      $msg="Account created! <a href='login.php'>Login</a>.";
    }
  }else{$msg="Please complete all fields.";}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sign Up | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="form-bg">
<header class="header-simple">
  <div class="logo"><a href="index.php">🚴‍♀️ Keila's Bikes</a></div>
</header>

<div class="form-card animate">
  <h2>Join the Ride</h2>
  <p class="form-sub">Create your account and start your journey with us.</p>
  <?php if($msg):?><div class="msg"><?=$msg?></div><?php endif;?>
  <form method="post">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" class="btn full">Sign Up</button>
  </form>
  <p class="switch">Already a member? <a href="login.php">Login</a></p>
</div>

<script>
document.addEventListener("DOMContentLoaded",()=>{
  document.querySelector(".animate").classList.add("show");
});
</script>
</body>
</html>
