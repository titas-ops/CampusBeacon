<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';

requireRole('admin');

// Get system stats
$prof_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'professor'");
$prof_count = ($prof_res && $row = $prof_res->fetch_assoc()) ? $row['total'] : 0;

$stud_res = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'");
$stud_count = ($stud_res && $row = $stud_res->fetch_assoc()) ? $row['total'] : 0;

$notice_res = $conn->query("SELECT COUNT(*) as total FROM notices");
$notice_count = ($notice_res && $row = $notice_res->fetch_assoc()) ? $row['total'] : 0;

// Fetch recent professors & notices for overview
$professors = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'professor' ORDER BY created_at DESC LIMIT 5");
$notices = $conn->query("SELECT n.id, n.title, n.created_at, COALESCE(u.username, n.author_username, 'Deleted Professor') as username FROM notices n LEFT JOIN users u ON n.professor_id = u.id ORDER BY n.created_at DESC LIMIT 5");
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-theme">
        <h2 class="fw-bold text-soft-white"><i class="bi bi-shield-lock"></i> Admin Dashboard</h2>
        <a href="../index.php" class="btn btn-sm text-soft-white d-flex align-items-center gap-1" style="border: 1px solid var(--border-color) !important; border-radius: 50px; background: transparent; color: #f5f5f5 !important;">
            <i class="bi bi-house" style="color: #f5f5f5 !important;"></i> <span style="color: #f5f5f5 !important;">Back to Home</span>
        </a>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item me-2">
            <a class="nav-link active bg-primary text-white" href="dashboard.php">Overview</a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link text-soft-white" href="professors.php">Professors</a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link text-soft-white" href="students.php">Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-soft-white" href="notices.php">Notices</a>
        </li>
    </ul>

    <!-- Stats Section (Clickable Cards) -->
    <div class="row mb-5">
        <div class="col-md-4">
            <a href="professors.php" class="text-decoration-none">
                <div class="card text-center py-4 shadow-sm bg-card h-100 border-theme" style="transition: transform 0.2s; cursor: pointer;">
                    <div class="card-body">
                        <h5 class="text-muted mb-2"><i class="bi bi-person-badge"></i> Professors</h5>
                        <h2 class="display-4 fw-bold text-soft-white"><?php echo $prof_count; ?></h2>
                        <small class="text-accent">Manage Professors &rarr;</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="students.php" class="text-decoration-none">
                <div class="card text-center py-4 shadow-sm bg-card h-100 border-theme" style="transition: transform 0.2s; cursor: pointer;">
                    <div class="card-body">
                        <h5 class="text-muted mb-2"><i class="bi bi-people"></i> Students</h5>
                        <h2 class="display-4 fw-bold text-soft-white"><?php echo $stud_count; ?></h2>
                        <small class="text-success">Manage Students &rarr;</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="notices.php" class="text-decoration-none">
                <div class="card text-center py-4 shadow-sm bg-card h-100 border-theme" style="transition: transform 0.2s; cursor: pointer;">
                    <div class="card-body">
                        <h5 class="text-muted mb-2"><i class="bi bi-file-earmark-text"></i> Notices</h5>
                        <h2 class="display-4 fw-bold text-soft-white"><?php echo $notice_count; ?></h2>
                        <small class="text-warning">Manage Notices &rarr;</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Overview Sections -->
    <div class="row">
        <!-- Recent Professors -->
        <div class="col-md-6 mb-4">
            <div class="card shadow bg-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3 border-theme" style="background-color: #0f0f0f !important;">
                    <h5 class="mb-0 text-soft-white">Recent Professors</h5>
                    <a href="professors.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush bg-card">
                        <?php if ($professors && $professors->num_rows > 0): ?>
                            <?php while($p = $professors->fetch_assoc()): ?>
                                <li class="list-group-item bg-card text-soft-white border-theme d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="color: #111111 !important;"><?php echo htmlspecialchars($p['username']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($p['email']); ?></small>
                                    </div>
                                    <small class="text-muted"><?php echo date('M d', strtotime($p['created_at'])); ?></small>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item bg-card text-muted text-center py-3">No professors found.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Notices -->
        <div class="col-md-6 mb-4">
            <div class="card shadow bg-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3 border-theme" style="background-color: #0f0f0f !important;">
                    <h5 class="mb-0 text-soft-white">Recent Notices</h5>
                    <a href="notices.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush bg-card">
                        <?php if ($notices && $notices->num_rows > 0): ?>
                            <?php while($n = $notices->fetch_assoc()): ?>
                                <li class="list-group-item bg-card text-soft-white border-theme d-flex justify-content-between align-items-center py-3" style="cursor: pointer;" onclick="window.location.href='../view_notice.php?id=<?php echo (int)$n['id']; ?>'">
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="color: #111111 !important;"><?php echo htmlspecialchars($n['title']); ?></h6>
                                        <small class="text-muted">By: <?php echo htmlspecialchars($n['username']); ?></small>
                                    </div>
                                    <small class="text-muted"><?php echo date('h:i A, M d', strtotime($n['created_at'])); ?></small>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item bg-card text-muted text-center py-3">No notices found.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
