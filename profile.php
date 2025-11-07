<?php
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$message = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$name || !$email) {
        $error = 'Name and email are required';
    } else {
        // Check if email is already used by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user['id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Email already in use by another account';
        } else {
            // Update basic info
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user['id']);
            $stmt->execute();

            // Handle password change
            if ($new_password) {
                if ($new_password !== $confirm_password) {
                    $error = 'New passwords do not match';
                } elseif (strlen($new_password) < 6) {
                    $error = 'Password must be at least 6 characters';
                } else {
                    // Verify current password
                    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
                    $stmt->bind_param("i", $user['id']);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc();
                    
                    if (password_verify($current_password, $result['password'])) {
                        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                        $stmt->bind_param("si", $hashed, $user['id']);
                        $stmt->execute();
                        $message = 'Profile and password updated successfully!';
                    } else {
                        $error = 'Current password is incorrect';
                    }
                }
            } else {
                $message = 'Profile updated successfully!';
            }

            // Refresh user session data
            if (!$error) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $_SESSION['user'] = $stmt->get_result()->fetch_assoc();
                $user = $_SESSION['user'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>My Profile | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="profile-section">
  <div class="container">
    <h1>My Profile</h1>

    <?php if ($message): ?>
      <div class="alert success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
      <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="profile-form">
      
      <!-- Personal Information -->
      <div class="form-section">
        <h2>Personal Information</h2>
        
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="form-group">
          <label>Email Address *</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group">
          <label>Shipping Address</label>
          <textarea name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Change Password -->
      <div class="form-section">
        <h2>Change Password</h2>
        <p class="form-hint">Leave blank if you don't want to change your password</p>
        
        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password">
          </div>

          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">
          </div>
        </div>
      </div>

      <div class="profile-actions">
        <button type="submit" class="btn btn-large">Save Changes</button>
        <a href="dashboard.php" class="btn outline">Cancel</a>
      </div>
    </form>

  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

<style>
.profile-section {
  padding: 40px 20px;
  min-height: 70vh;
}
.profile-section h1 {
  text-align: center;
  margin-bottom: 40px;
  color: var(--nav);
}
.profile-form {
  max-width: 800px;
  margin: 0 auto;
}
.form-hint {
  font-size: 0.9em;
  color: #666;
  margin-bottom: 20px;
}
.profile-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-top: 30px;
}
</style>

</body>
</html>
