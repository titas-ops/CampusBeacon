<?php include 'header.php'; ?>
<div class="cb-center">
    <div class="card shadow">
        <div class="card-body text-center">
            <h3 class="mb-4">Welcome</h3>

            <div class="d-grid gap-3">
                <a href="login_professor.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-workspace"></i> Professor Login
                </a>
                <a href="login_student.php" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-person-badge"></i> Student Login
                </a>
            </div>

            <div class="mt-4">
                <small>New student? <a href="signup.php">Sign up here</a></small>
            </div>
            <div class="mt-2">
                <small><a href="forgot_password.php">Reset password</a></small>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
