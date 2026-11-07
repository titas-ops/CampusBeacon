<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';

requireRole('admin');

$professors = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'professor' ORDER BY created_at DESC");
?>
<?php include '../../includes/header.php'; ?>

<script>
function confirmDeleteProfessor(profId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete this professor account. Their notices/comments will remain unless explicitly deleted.",
        icon: 'warning',
        background: '#191B1F',
        color: '#F1F3F5',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#3B82F6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
    }).then((result) => {
        if (result.isConfirmed) {
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
        <h2 class="fw-bold text-soft-white"><i class="bi bi-person-badge"></i> Manage Professors</h2>
        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-sm text-soft-white d-flex align-items-center gap-1" style="border: 1px solid var(--border) !important; border-radius: 50px; background: transparent; color: #F1F3F5 !important;">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="../index.php" class="btn btn-sm text-soft-white d-flex align-items-center gap-1" style="border: 1px solid var(--border) !important; border-radius: 50px; background: transparent; color: #F1F3F5 !important;">
                <i class="bi bi-house"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item me-2">
            <a class="nav-link text-soft-white" href="dashboard.php">Overview</a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link active bg-primary text-white" href="professors.php">Professors</a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link text-soft-white" href="students.php">Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-soft-white" href="notices.php">Notices</a>
        </li>
    </ul>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-success" role="alert" style="background-color: rgba(34, 197, 94, 0.1) !important; border: 1px solid var(--success) !important; color: var(--success) !important;">
            Professor account removed successfully.
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center py-3 border-theme" style="background-color: var(--bg-card) !important; border-bottom: 1px solid var(--border) !important;">
            <h4 class="mb-0 text-soft-white">Professors List</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfModal">
                <i class="bi bi-plus-lg"></i> Add Professor
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mb-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Username</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Joined Date</th>
                            <th class="text-center px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($professors && $professors->num_rows > 0): ?>
                            <?php while($row = $professors->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-soft-white"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="py-3 text-muted"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="py-3 text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td class="text-center px-4 py-3">
                                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDeleteProfessor(<?php echo (int)$row['id']; ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No professors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Professor Modal -->
<div class="modal fade" id="addProfModal" tabindex="-1" aria-labelledby="addProfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-soft-white" style="background-color: var(--bg-card) !important; border: 1px solid var(--border);">
            <form action="../../server/admin_manager.php" method="POST">
                <div class="modal-header border-theme" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title text-soft-white" id="addProfModalLabel">Add New Professor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Professor Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="professor@college.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-soft-white">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Secure Password" required>
                    </div>
                </div>
                <div class="modal-footer border-theme" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 50px;">Cancel</button>
                    <button type="submit" name="add_professor" class="btn btn-primary">Create Professor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
