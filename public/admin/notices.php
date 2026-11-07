<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';

requireRole('admin');

$notices = $conn->query("SELECT n.id, n.title, n.content, n.created_at, COALESCE(u.username, n.author_username, 'Deleted Professor') as username FROM notices n LEFT JOIN users u ON n.professor_id = u.id ORDER BY n.created_at DESC");
?>
<?php include '../../includes/header.php'; ?>

<script>
function confirmDeleteNotice(noticeId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete this notice. This action cannot be undone!",
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
            input.name = 'notice_id';
            input.value = noticeId;
            form.appendChild(input);

            const submit = document.createElement('input');
            submit.type = 'hidden';
            submit.name = 'delete_notice';
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
        <h2 class="fw-bold text-soft-white"><i class="bi bi-file-earmark-text"></i> Manage Notices</h2>
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
            <a class="nav-link text-soft-white" href="professors.php">Professors</a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link text-soft-white" href="students.php">Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-primary text-white" href="notices.php">Notices</a>
        </li>
    </ul>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-success" role="alert" style="background-color: rgba(34, 197, 94, 0.1) !important; border: 1px solid var(--success) !important; color: var(--success) !important;">
            Notice removed successfully.
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header py-3 border-theme" style="background-color: var(--bg-card) !important; border-bottom: 1px solid var(--border) !important;">
            <h4 class="mb-0 text-soft-white">All Notices</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mb-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="py-3">Posted By (Professor)</th>
                            <th class="py-3">Time Posted</th>
                            <th class="text-center px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($notices && $notices->num_rows > 0): ?>
                            <?php while($row = $notices->fetch_assoc()): ?>
                                <tr style="cursor: pointer;" onclick="window.location.href='../view_notice.php?id=<?php echo (int)$row['id']; ?>'">
                                    <td class="px-4 py-3 fw-bold text-soft-white"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="py-3 text-muted"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="py-3 text-muted"><?php echo date('h:i A, d M Y', strtotime($row['created_at'])); ?></td>
                                    <td class="text-center px-4 py-3" onclick="event.stopPropagation();">
                                        <a href="../view_notice.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-info btn-sm me-1">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDeleteNotice(<?php echo (int)$row['id']; ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No notices found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
