<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Professor Login</h3>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php
                            $error = $_GET['error'];
                            if ($error === 'empty_fields') echo "Please enter both email and password.";
                            elseif ($error === 'invalid_credentials') echo "Invalid email or password.";
                            else echo "An error occurred. Please try again.";
                        ?>
                    </div>
                <?php endif; ?>

                <form action="../server/auth.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">University Email ID</label>
                        <input type="email" name="email" class="form-control" placeholder="professor@college.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="professor_login" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small><a href="forgot_password.php">Forgot password?</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
