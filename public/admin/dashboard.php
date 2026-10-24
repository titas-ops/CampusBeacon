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

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold text-dark"><i class="bi bi-shield-lock"></i> Admin Dashboard</h2>
        <a href="../index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-house"></i> Back to Home
        </a>
    </div>

    <!-- Stats Section -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h5>Total Professors</h5>
                    <h2 class="display-4"><?php echo $prof_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h5>Total Students</h5>
                    <h2 class="display-4"><?php echo $stud_count; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h5>Total Notices</h5>
                    <h2 class="display-4"><?php echo $notice_count; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
                if ($_GET['success'] === 'professor_added') echo "New professor account created successfully!";
                elseif ($_GET['success'] === 'professor_deleted') echo "Professor account removed successfully.";
                else echo "Operation completed successfully!";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage Professors</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfModal">
                <i class="bi bi-plus-lg"></i> Add Professor
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($professors->num_rows > 0): ?>
                            <?php while($row = $professors->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td class="text-center">
                                        <form action="../../server/admin_manager.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this professor?');" style="display:inline;">
                                            <input type="hidden" name="professor_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_professor" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No professors found.</td>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProfModalLabel">Add New Professor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../server/admin_manager.php" method="POST">
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
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="add_professor" class="btn btn-primary">Create Professor Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../public/footer.php'; ?>
