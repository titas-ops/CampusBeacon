<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';
$id = intval($_GET['id'] ?? 0);
$notice = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT n.*, u.username FROM notices n JOIN users u ON n.professor_id = u.id WHERE n.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $notice = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
$comments = [];
if ($notice) {
    $cstmt = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.student_id = u.id WHERE c.notice_id = ? ORDER BY c.created_at ASC");
    $cstmt->bind_param("i", $id);
    $cstmt->execute();
    $comments = $cstmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $cstmt->close();
}
?>
<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <?php if (!$notice): ?>
            <div class="alert alert-danger">Notice not found.</div>
        <?php else: ?>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($notice['title']); ?></h2>
                    <p class="text-muted">Posted by <?php echo htmlspecialchars($notice['username']); ?> on <?php echo date('Y-m-d H:i', strtotime($notice['created_at'])); ?></p>
                    <hr>
                    <div class="notice-content mb-4">
                        <p><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
                    </div>
                    <?php if (!empty($notice['pdf_data'])): ?>
                        <div class="alert alert-info">
                            <strong>Attached Document:</strong> <a href="stream_pdf.php?id=<?php echo $id; ?>" target="_blank">View PDF</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <h4>Student Comments</h4>
                    <hr>
                    <div class="comments-list mb-4">
                        <?php foreach ($comments as $c): ?>
                            <div class="mb-3 p-2 border-bottom">
                                <strong><?php echo htmlspecialchars($c['username']); ?>:</strong>
                                <p class="mb-1"><?php echo htmlspecialchars($c['comment_text']); ?></p>
                                <small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($c['created_at'])); ?></small>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($comments)): ?>
                            <p class="text-muted">No comments yet.</p>
                        <?php endif; ?>
                    </div>
                    <?php if (isLoggedIn() && hasRole('student')): ?>
                        <form action="../server/comments.php" method="POST">
                            <input type="hidden" name="notice_id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="3" placeholder="Add your comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted"><a href="login.php">Login</a> as a student to comment.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
