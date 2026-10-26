<?php include '../client/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-4 fw-bold text-soft-white">Student Login</h3>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center mb-4 text-error" style="background-color: rgba(248, 113, 113, 0.1) !important; border: 1px solid var(--error-color) !important;">
                            <?php
                                $error = $_GET['error'];
                                if ($error === 'empty_fields') echo "Please enter both roll number and password.";
                                elseif ($error === 'invalid_credentials') echo "Invalid roll number or password.";
                                else echo "An error occurred. Please try again.";
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success text-center mb-4 text-success" style="background-color: rgba(74, 222, 128, 0.1) !important; border: 1px solid var(--success-color) !important;">
                            Account created successfully! Please login.
                        </div>
                    <?php endif; ?>

                    <form action="../server/auth.php" method="POST" class="text-start">
                        <div class="mb-3">
                            <label class="form-label text-soft-white">University Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" placeholder="Roll Number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-soft-white">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="student_login" class="btn btn-view-details py-3 w-100 text-success" style="font-weight: 600 !important;">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-1 text-soft-white">Don't have an account? <a href="signup.php" class="text-accent" style="text-decoration: none;">Register here</a></p>
                        <small><a href="forgot_password.php" class="text-soft-white" style="opacity: 0.7; text-decoration: none;">Forgot password?</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../client/footer.php'; ?>
