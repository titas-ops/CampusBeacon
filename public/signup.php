<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Student Registration</h3>
                <form action="../server/auth.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">University Roll Number</label>
                        <input type="text" name="roll_number" class="form-control" placeholder="e.g. 2023CS101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="student@college.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a strong password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat your password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="signup" class="btn btn-success">Sign Up</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small>Already have an account? <a href="login.php">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
