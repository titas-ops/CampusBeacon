<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
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
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
<style>
.notice-content h1{font-size:2rem;font-weight:700;margin:.75rem 0 .5rem}
.notice-content h2{font-size:1.75rem;font-weight:700;margin:.75rem 0 .5rem}
.notice-content h3{font-size:1.5rem;font-weight:700;margin:.75rem 0 .5rem}
.notice-content h4{font-size:1.25rem;font-weight:700;margin:.75rem 0 .5rem}
.notice-content p{font-size:1rem;line-height:1.6}
</style>
<div class="row justify-content-center">
    <div class="col-md-8">
        <?php if (!$notice): ?>
            <div class="alert alert-danger">Notice not found.</div>
        <?php else: ?>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="notice-content mb-4" id="noticeContent"></div>
                    <div class="d-flex justify-content-end">
                        <div class="text-end">
                            <div>Prof: <?php echo htmlspecialchars($notice['username']); ?></div>
                            <small class="text-muted">at <?php echo date('h:i A', strtotime($notice['created_at'])); ?> on <?php echo date('d M', strtotime($notice['created_at'])); ?></small>
                        </div>
                    </div>
                    <script>document.getElementById('noticeContent').innerHTML = DOMPurify.sanitize(marked.parse(<?php echo json_encode($notice['content']); ?>));</script>
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
