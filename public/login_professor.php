<?php include '../client/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-4 fw-bold" style="color: var(--text-color) !important;">Professor Login</h3>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center mb-4" style="background-color: rgba(248, 113, 113, 0.1) !important; border: 1px solid var(--error-color) !important; color: var(--error-color) !important;">
                            <?php
                                $error = $_GET['error'];
                                if ($error === 'empty_fields') echo "Please enter both email and password.";
                                elseif ($error === 'invalid_credentials') echo "Invalid email or password.";
                                else echo "An error occurred. Please try again.";
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="../server/auth.php" method="POST" class="text-start">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-color) !important;">University Email ID</label>
                            <input type="email" name="email" class="form-control" placeholder="professor@college.edu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-color) !important;">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="professor_login" class="btn btn-view-details py-3 w-100" style="color: var(--accent-color) !important; font-weight: 600 !important;">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <small><a href="forgot_password.php" style="color: var(--text-color) !important; opacity: 0.7; text-decoration: none;">Forgot password?</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../client/footer.php'; ?>
