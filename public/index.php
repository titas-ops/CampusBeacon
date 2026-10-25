<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';
$result = $conn->query("SELECT n.id, n.title, n.content, n.created_at, n.professor_id, u.username FROM notices n JOIN users u ON n.professor_id = u.id ORDER BY n.created_at DESC");
$notices = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<?php include '../client/header.php'; ?>
<style>body, html { background-color: #121212 !important; color: #f5f5f5 !important; }</style>
<div class="container mt-4">
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">Notices</h2>
        <?php if (empty($notices)): ?>
            <p class="text-muted">No notices yet.</p>
        <?php else: ?>
            <?php foreach ($notices as $n): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <p class="card-text md-excerpt" data-md="<?php echo htmlspecialchars($n['content'], ENT_QUOTES); ?>"></p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="view_notice.php?id=<?php echo (int)$n['id']; ?>" class="btn btn-primary btn-sm">View Details & Comments</a>
                            <div class="text-end">
                                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['role'] === 'professor' && (int)$n['professor_id'] === (int)$_SESSION['user_id']): ?>
                                    <div class="mb-1">
                                        <a href="edit_notice.php?id=<?php echo (int)$n['id']; ?>" class="btn btn-outline-warning btn-sm me-1">Edit</a>
                                        <a href="delete_notice.php?id=<?php echo (int)$n['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notice?')">Delete</a>
                                    </div>
                                <?php endif; ?>
                                <div>Prof: <?php echo htmlspecialchars($n['username']); ?></div>
                                <small class="text-muted">at <?php echo date('h:i A', strtotime($n['created_at'])); ?> on <?php echo date('d M', strtotime($n['created_at'])); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</div>
<?php include '../client/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
<script>
document.querySelectorAll('.md-excerpt').forEach(function(el){
    const md = el.getAttribute('data-md') || '';
    el.innerHTML = DOMPurify.sanitize(marked.parse(md));
});
</script>
