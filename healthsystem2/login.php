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

<form action="process/login_process.php" method="post" class="auth-form">
    <div class="form-logo">
        <img src="logo/icon.png" alt="logo" width="120px">
    </div>
    <h2>Welcome Back</h2>
    <p class="form-subtitle">Login to access the health center system</p>
    
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required placeholder="Enter username" autocomplete="username">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter password" autocomplete="current-password">
    </div>
    
    <div class="form-links">
        <a href="forgot_password.php">Forgot Password?</a>
    </div>
    
    <input type="submit" value="Login" class="submit-btn">
    
    <div class="social-login">
        <div class="social-divider">
            <span>or continue with</span>
        </div>
        <button type="button" class="social-btn social-btn-facebook">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Continue with Facebook
        </button>
        <button type="button" class="social-btn social-btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continue with Google
        </button>
    </div>
    
    <div class="form-footer">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
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


</body>
</html>
