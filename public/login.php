<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center">
                <h3 class="mb-4">Welcome</h3>
                

                <div class="d-grid gap-3">
                    <a href="login_professor.php" class="btn btn-outline-primary btn-lg py-3">
                        <i class="bi bi-person-workspace"></i> Professor Login
                    </a>
                    <a href="login_student.php" class="btn btn-outline-success btn-lg py-3">
                        <i class="bi bi-person-badge"></i> Student Login
                    </a>
                </div>

                <div class="mt-4">
                    <small><a href="forgot_password.php" class="text-decoration-none">reset passphrase</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
