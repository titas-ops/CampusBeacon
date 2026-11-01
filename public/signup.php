<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow bg-card">
            <div class="card-body">
                <h3 class="text-center mb-4 text-soft-white">Student Registration</h3>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center text-error" style="background-color: rgba(248, 113, 113, 0.1) !important; border: 1px solid var(--error-color) !important;">
                        <?php
                            $error = $_GET['error'];
                            if ($error === 'empty_fields') echo "Please fill in all fields.";
                            elseif ($error === 'passwords_do_not_match') echo "Passwords do not match!";
                            elseif ($error === 'user_already_exists') echo "Roll number or email already registered.";
                            else echo "An error occurred. Please try again.";
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success text-center text-success" style="background-color: rgba(74, 222, 128, 0.1) !important; border: 1px solid var(--success-color) !important;">
                        Account created successfully! <a href="login_student.php" class="text-accent">Login here</a>
                    </div>
                <?php endif; ?>

                <form action="../server/auth.php" method="POST" id="signupForm">
                    <div class="mb-3">
                        <label class="form-label text-soft-white">University Roll Number</label>
                        <input type="text" name="roll_number" class="form-control" placeholder="e.g. 2023CS101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Full Name</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="student@college.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Create a strong password" required>
                        <div id="passwordErrors" class="mt-2" style="font-size: 0.85rem;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Repeat your password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="signup" class="btn btn-success">Sign Up</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small class="text-soft-white">Already have an account? <a href="login.php" class="text-accent" style="text-decoration: none;">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/CampusBeacon/client/password-validation.js"></script>
<script>
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const errorDiv = document.getElementById('passwordErrors');

    const errors = validatePassword(password);

    if (errors.length > 0) {
        e.preventDefault();
        errorDiv.innerHTML = errors.map(err => `<div class="text-error">• ${err}</div>`).join('');
        return false;
    }

    if (password !== confirm) {
        e.preventDefault();
        errorDiv.innerHTML = `<div class="text-error">• Passwords do not match!</div>`;
        return false;
    }

    errorDiv.innerHTML = '';
});

document.getElementById('password').addEventListener('input', function() {
    const errors = validatePassword(this.value);
    const errorDiv = document.getElementById('passwordErrors');
    if (errors.length > 0) {
        errorDiv,innerHTML = errors.map(err => `<div class="text-error">• ${err}</div>`).join('');
    } else {
        errorDiv.innerHTML = '<div class="text-success">✓ Password is strong enough!</div>';
    }
});
</script>
<?php include '../includes/footer.php'; ?>
