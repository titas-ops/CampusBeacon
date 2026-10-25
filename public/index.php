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
        <h2 class="mb-4" style="color: var(--text-color) !important;">Notices</h2>
        <?php if (empty($notices)): ?>
            <p class="text-muted">No notices yet.</p>
        <?php else: ?>
            <?php foreach ($notices as $index => $n): ?>
                <div class="card mb-3 shadow-sm" onclick="window.location.href='view_notice.php?id=<?php echo (int)$n['id']; ?>'">
                    <div class="card-body">
                        <p class="card-text md-excerpt" data-md="<?php echo htmlspecialchars($n['content'], ENT_QUOTES); ?>"></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="view_notice.php?id=<?php echo (int)$n['id']; ?>#comments" class="btn btn-view-details btn-sm" onclick="event.stopPropagation();">Comments</a>
                            <div class="text-end">
                                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                    <div class="mb-1">
                                        <?php
                                        // A professor can only edit/delete their own latest notice.
                                        // We only show these options if the notice is the absolute latest in the system AND belongs to them.
                                        $isLatestAndOwner = ($_SESSION['role'] === 'professor' && (int)$n['professor_id'] === (int)$_SESSION['user_id'] && $index === 0);
                                        ?>
                                        <?php if ($isLatestAndOwner): ?>
                                            <a href="edit_notice.php?id=<?php echo (int)$n['id']; ?>" class="btn btn-outline-warning btn-sm me-1" onclick="event.stopPropagation();">Edit</a>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['role'] === 'admin' || $isLatestAndOwner): ?>
                                            <button class="btn btn-outline-danger btn-sm" onclick="event.stopPropagation(); confirmDelete(<?php echo (int)$n['id']; ?>)">Delete</button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="color: var(--text-color) !important;">Prof: <?php echo htmlspecialchars($n['username']); ?></div>
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
<script>
function confirmDelete(noticeId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        background: '#1a1a1a',
        color: '#d1d5db',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#f87171',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_notice.php?id=' + noticeId;
        }
    })
}
</script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
<script>
document.querySelectorAll('.md-excerpt').forEach(function(el){
    const md = el.getAttribute('data-md') || '';
    el.innerHTML = DOMPurify.sanitize(marked.parse(md));
});
</script>
