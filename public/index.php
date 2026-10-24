<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';
$result = $conn->query("SELECT n.id, n.title, n.content, n.created_at, u.username FROM notices n JOIN users u ON n.professor_id = u.id ORDER BY n.created_at DESC");
$notices = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<?php include '../client/header.php'; ?>
<div class="container mt-4">
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Notices</h2>
        <?php if (empty($notices)): ?>
            <p class="text-muted">No notices yet.</p>
        <?php else: ?>
            <?php foreach ($notices as $n): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($n['title']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Posted by <?php echo htmlspecialchars($n['username']); ?> on <?php echo date('Y-m-d', strtotime($n['created_at'])); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars(mb_substr($n['content'], 0, 200)); ?></p>
                        <a href="view_notice.php?id=<?php echo (int)$n['id']; ?>" class="btn btn-primary btn-sm">View Details & Comments</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</div>
<?php include '../client/footer.php'; ?>
