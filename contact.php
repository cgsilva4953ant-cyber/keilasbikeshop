<?php
require_once __DIR__ . '/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        $stmt = $conn->prepare("INSERT INTO inquiries (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $success = 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.';
            // Clear form
            $_POST = [];
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Contact Us | Keila's Bikes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>

<section class="contact-hero">
  <div class="container">
    <h1>Get In Touch</h1>
    <p>Have questions? We'd love to hear from you!</p>
  </div>
</section>

<section class="contact-section">
  <div class="container">
    <div class="contact-layout">
      
      <!-- Contact Form -->
      <div class="contact-form-wrapper">
        <h2>Send Us a Message</h2>
        
        <?php if ($success): ?>
          <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
          <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="contact-form">
          <div class="form-group">
            <label>Your Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" placeholder="e.g., Product Inquiry">
          </div>

          <div class="form-group">
            <label>Message *</label>
            <textarea name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-large">Send Message</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="contact-info-wrapper">
        <h2>Contact Information</h2>
        
        <div class="contact-info-item">
          <div class="contact-icon">📍</div>
          <div>
            <h3>Address</h3>
            <p>123 Bike Street, Manila City<br>Metro Manila, Philippines 1000</p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon">📞</div>
          <div>
            <h3>Phone</h3>
            <p>+63 912 345 6789<br>+63 2 8123 4567</p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon">✉️</div>
          <div>
            <h3>Email</h3>
            <p>info@keilasbikes.com<br>support@keilasbikes.com</p>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="contact-icon">🕒</div>
          <div>
            <h3>Business Hours</h3>
            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
            Saturday: 10:00 AM - 4:00 PM<br>
            Sunday: Closed</p>
          </div>
        </div>

        <div class="social-links">
          <h3>Follow Us</h3>
          <div class="social-icons">
            <a href="#" class="social-icon">Facebook</a>
            <a href="#" class="social-icon">Instagram</a>
            <a href="#" class="social-icon">Twitter</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<footer><p>© <?= date('Y') ?> Keila's Bikes | Ride Strong, Ride Smart.</p></footer>

</body>
</html>
