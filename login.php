<?php
require_once __DIR__ . '/db.php';

// Get redirect URL if provided
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard.php';

$msg="";

// Show cart message if redirected from add to cart
if(isset($_SESSION['cart_message'])){
  $msg = $_SESSION['cart_message']['message'];
  unset($_SESSION['cart_message']);
}

if($_SERVER['REQUEST_METHOD']=='POST'){
  $email=trim($_POST['email']);
  $pass=$_POST['password'];
  if($email && $pass){
    $stmt=$conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res=$stmt->get_result();
    if($row=$res->fetch_assoc()){
        // First try the secure hashed flow
        if (password_verify($pass, $row['password'])) {
          // Successful login with hashed password
          $_SESSION['user'] = $row;
          header("Location: $redirect");
          exit;
        }

        // Backwards-compatibility: if the DB contains a legacy plain-text password
        // (from the temporary insecure period), convert it to a hash now.
        if ($pass === $row['password']) {
          // Re-hash and update the DB
          $newHash = password_hash($pass, PASSWORD_DEFAULT);
          if (isset($row['id'])) {
            $upd = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $upd->bind_param("si", $newHash, $row['id']);
            $upd->execute();
            // update local row so session doesn't contain old plain password
            $row['password'] = $newHash;
          }
          $_SESSION['user'] = $row;
          header("Location: $redirect");
          exit;
        }

        $msg = "Incorrect password.";
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

<?php require_once 'navbar.php'; ?>

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
