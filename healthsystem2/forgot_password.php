<?php
session_start();
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/header.php'; ?>
</head>
<body>

<div class="bg-slider">
    <div class="slide active"><img src="logo/3.png" alt="Slide 1"></div>
    <div class="slide"><img src="logo/4.png" alt="Slide 2"></div>
    <div class="slide"><img src="logo/5.png" alt="Slide 3"></div>
    <div class="slide"><img src="logo/6.png" alt="Slide 4"></div>
    <div class="bg-overlay"></div>
</div>

<?php include 'components/navbar.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<form action="process/forgot_password_process.php" method="post" class="auth-form">
    <div class="form-logo">
        <img src="logo/icon.png" alt="logo" width="120px">
    </div>
    <h2>Reset Password</h2>
    <p class="form-subtitle">Enter your email to receive password reset instructions</p>
    
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email" autocomplete="email">
    </div>
    
    <input type="submit" value="Send Reset Link" class="submit-btn">
    
    <div class="form-footer">
        <p>Remember your password? <a href="login.php">Login here</a></p>
    </div>
</form>

<script>
    const slides = document.querySelectorAll('.bg-slider .slide');
    let currentSlide = 0;

    function nextSlide() {
        if (slides.length <= 1) return;

        const previousSlide = slides[currentSlide];
        previousSlide.classList.remove('active');
        previousSlide.classList.add('exit');

        currentSlide = (currentSlide + 1) % slides.length;
        const incomingSlide = slides[currentSlide];
        incomingSlide.classList.remove('exit');
        incomingSlide.classList.add('active');

        setTimeout(() => {
            previousSlide.classList.remove('exit');
        }, 1200);
    }

    setInterval(nextSlide, 4000);
</script>

<?php include 'components/footer.php'; ?>

</body>
</html>