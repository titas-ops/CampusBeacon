<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';

// Security check: Only admins can access the dashboard
requireRole('admin');

// Get system stats
$prof_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'professor'");
$prof_count = ($prof_res && $row = $prof_res->fetch_assoc()) ? $row['total'] : 0;

$stud_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'");
$stud_count = ($stud_res && $row = $stud_res->fetch_assoc()) ? $row['total'] : 0;

$notice_res = $conn->query("SELECT COUNT(*) as total FROM notices");
$notice_count = ($notice_res && $row = $notice_res->fetch_assoc()) ? $row['total'] : 0;

// Fetch all professors
$professors = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'professor'");
?>

<?php include '../../client/header.php'; ?>

<script>
function confirmDeleteProfessor(profId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete a professor. This action cannot be undone!",
        icon: 'warning',
        background: '#1a1a1a',
        color: '#d1d5db',
        showCancelButton: true,
        confirmButtonColor: '#f87171',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a dynamic form and submit it to avoid GET request for deletion
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../server/admin_manager.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'professor_id';
            input.value = profId;
            form.appendChild(input);

            const submit = document.createElement('input');
            submit.type = 'hidden';
            submit.name = 'delete_professor';
            submit.value = '1';
            form.appendChild(submit);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-theme">
        <h2 class="fw-bold text-soft-white"><i class="bi bi-shield-lock"></i> Admin Dashboard</h2>
        <a href="../index.php" class="btn btn-sm text-soft-white" style="border: 1px solid var(--border-color) !important; border-radius: 50px; background: transparent;">
            <i class="bi bi-house"></i> Back to Home
        </a>
    </div>

    <!-- Stats Section -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-center py-4 shadow-sm bg-card">
                <div class="card-body">
                    <h5 class="text-muted mb-2">Total Professors</h5>
                    <h2 class="display-4 fw-bold text-soft-white"><?php echo $prof_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-4 shadow-sm bg-card">
                <div class="card-body">
                    <h5 class="text-muted mb-2">Total Students</h5>
                    <h2 class="display-4 fw-bold text-soft-white"><?php echo $stud_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-4 shadow-sm bg-card">
                <div class="card-body">
                    <h5 class="text-muted mb-2">Total Notices</h5>
                    <h2 class="display-4 fw-bold text-soft-white"><?php echo $notice_count; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-success" role="alert" style="background-color: rgba(74, 222, 128, 0.1) !important; border: 1px solid var(--success-color) !important;">
            <?php
                if ($_GET['success'] === 'professor_added') echo "New professor account created successfully!";
                elseif ($_GET['success'] === 'professor_deleted') echo "Professor account removed successfully.";
                else echo "Operation completed successfully!";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show text-error" role="alert" style="background-color: rgba(248, 113, 113, 0.1) !important; border: 1px solid var(--error-color) !important;">
            <?php
                if ($_GET['error'] === 'empty_fields') echo "Please fill in all required fields.";
                elseif ($_GET['error'] === 'duplicate_user') echo "A user with this email already exists.";
                elseif ($_GET['error'] === 'delete_failed') echo "Could not delete the professor.";
                else echo "An error occurred. Please try again.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Professor Management -->
    <div class="card shadow bg-card">
        <div class="card-header d-flex justify-content-between align-items-center py-3 border-theme" style="background-color: #0f0f0f !important; border-bottom: 1px solid var(--border-color) !important;">
            <h4 class="mb-0 text-soft-white">Manage Professors</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfModal">
                <i class="bi bi-plus-lg"></i> Add Professor
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-soft-white bg-card" style="background-color: var(--card-bg) !important;">
                    <thead class="border-theme" style="background-color: #0f0f0f !important; border-bottom: 1px solid var(--border-color) !important;">
                        <tr style="background-color: #0f0f0f !important;">
                            <th class="px-4 py-3 text-soft-white" style="font-weight: 600; background-color: #0f0f0f !important;">Username</th>
                            <th class="py-3 text-soft-white" style="font-weight: 600; background-color: #0f0f0f !important;">Email</th>
                            <th class="py-3 text-soft-white" style="font-weight: 600; background-color: #0f0f0f !important;">Joined Date</th>
                            <th class="text-center px-4 py-3 text-soft-white" style="font-weight: 600; background-color: #0f0f0f !important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-card">
                        <?php if ($professors->num_rows > 0): ?>
                            <?php while($row = $professors->fetch_assoc()): ?>
                                <tr class="border-theme bg-card" style="border-bottom: 1px solid var(--border-color) !important;">
                                    <td class="px-4 bg-card text-soft-white"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="bg-card text-soft-white"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="bg-card text-soft-white"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td class="text-center px-4 bg-card">
                                        <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 50px;" onclick="confirmDeleteProfessor(<?php echo $row['id']; ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr class="bg-card">
                                <td colspan="4" class="text-center py-5 text-muted bg-card">No professors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Professor Modal -->
    <div class="modal fade" id="addProfModal" tabindex="-1" aria-labelledby="addProfModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-card border-theme text-soft-white" style="border: 1px solid var(--border-color) !important;">
                <div class="modal-header border-theme" style="border-bottom: 1px solid var(--border-color) !important;">
                    <h5 class="modal-title text-soft-white" id="addProfModalLabel">Add New Professor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="../../server/admin_manager.php" method="POST" id="adminProfForm">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="adminProfPassword" class="form-control" required>
                            <div id="adminProfPasswordErrors" class="mt-2" style="font-size: 0.85rem;"></div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="add_professor" class="btn btn-primary">Create Professor Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/CampusBeacon/client/password-validation.js"></script>
    <script>
    document.getElementById('adminProfForm').addEventListener('submit', function(e) {
        const password = document.getElementById('adminProfPassword').value;
        const errorDiv = document.getElementById('adminProfPasswordErrors');
        const errors = validatePassword(password);

        if (errors.length > 0) {
            e.preventDefault();
            errorDiv.innerHTML = errors.map(err => `<div class="text-error">• ${err}</div>`).join('');
            return false;
        }
        errorDiv.innerHTML = '';
    });

    document.getElementById('adminProfPassword').addEventListener('input', function() {
        const errors = validatePassword(this.value);
        const errorDiv = document.getElementById('adminProfPasswordErrors');
        if (errors.length > 0) {
            errorDiv.innerHTML = errors.map(err => `<div class="text-error">• ${err}</div>`).join('');
        } else {
            errorDiv.innerHTML = '<div class="text-success">✓ Password is strong enough!</div>';
        }
    });
    </script>
</div>

<?php include '../../public/footer.php'; ?>
