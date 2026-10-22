<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Student Login</h3>
                <form action="../server/auth.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">University Roll Number</label>
                        <input type="text" name="roll_number" class="form-control" placeholder="Roll Number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="student_login" class="btn btn-success">Login</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-1">Don't have an account? <a href="signup.php">Register here</a></p>
                    <small><a href="forgot_password.php">Forgot password?</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
