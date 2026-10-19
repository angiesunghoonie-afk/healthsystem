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

<form action="process/register_process.php" method="post" class="auth-form">
    <div class="form-logo">
        <img src="logo/icon.png" alt="logo" width="120px">
    </div>
    <h2>Create Account</h2>
    <p class="form-subtitle">Register to access the health center system</p>

    <div class="form-group">
        <label for="role">Register as</label>
        <select id="role" name="role" required onchange="togglePatientFields()">
            <option value="">Select Role</option>
            <option value="patient">Patient</option>
            <option value="visitor">Visitor</option>
        </select>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required placeholder="Enter username" autocomplete="username">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="Enter email" autocomplete="email">
    </div>

    <div class="form-group">
        <label for="contact_number">Contact Number</label>
        <input type="tel" id="contact_number" name="contact_number" required placeholder="Enter contact number" autocomplete="tel">
    </div>

    <div id="patient-fields" style="display: none;">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter first name">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter last name">
        </div>

        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="Enter middle name">
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date</label>
            <input type="date" id="birth_date" name="birth_date">
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter address">
        </div>

        <div class="form-group">
            <label for="emergency_contact">Emergency Contact</label>
            <input type="text" id="emergency_contact" name="emergency_contact" placeholder="Emergency contact name">
        </div>

        <div class="form-group">
            <label for="emergency_contact_number">Emergency Contact Number</label>
            <input type="tel" id="emergency_contact_number" name="emergency_contact_number" placeholder="Emergency contact number">
        </div>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter password" autocomplete="new-password">
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password" autocomplete="new-password">
    </div>

    <input type="submit" value="Register" class="submit-btn">

    <div class="form-footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</form>

<script>
function togglePatientFields() {
    const role = document.getElementById('role').value;
    const patientFields = document.getElementById('patient-fields');
    const patientInputs = patientFields.querySelectorAll('input, select');

    if (role === 'patient') {
        patientFields.style.display = 'block';
        patientInputs.forEach(input => input.required = true);
    } else {
        patientFields.style.display = 'none';
        patientInputs.forEach(input => input.required = false);
    }
}
</script>

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