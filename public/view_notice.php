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
if ($notice) {
    $cstmt = $conn->prepare("SELECT c.*, u.username, u.role FROM comments c JOIN users u ON c.user_id = u.id WHERE c.notice_id = ? ORDER BY c.created_at ASC");
    $cstmt->bind_param("i", $id);
    $cstmt->execute();
    $allComments = $cstmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $cstmt->close();

    // Build tree
    $byParent = [];
    foreach ($allComments as $c) {
        $pid = $c['parent_id'] ?? null;
        $byParent[$pid][] = $c;
    }
    function renderComments($byParent, $parentId, $depth = 0) {
        if (empty($byParent[$parentId])) return;
        foreach ($byParent[$parentId] as $c) {
            $margin = $depth * 20;
            echo '<div class="mb-3 p-2 border-bottom comment-tree" data-userid="'.$c['user_id'].'" style="margin-left:'.$margin.'px;">';
            echo '<strong>'.htmlspecialchars($c['username']).' ('.htmlspecialchars($c['role']).'):</strong>';
            echo '<p class="mb-1">'.htmlspecialchars($c['comment_text']).'</p>';
            echo '<small class="text-muted">'.date('Y-m-d H:i', strtotime($c['created_at'])).'</small>';
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                echo ' <a href="#" class="reply-link small" data-id="'.$c['id'].'">Reply</a>';
                echo '<form class="reply-form mt-2" data-parent="'.$c['id'].'" style="display:none;" method="POST" action="../server/comments.php">';
                echo '<input type="hidden" name="notice_id" value="'.$GLOBALS['id'].'">';
                echo '<input type="hidden" name="parent_id" value="'.$c['id'].'">';
                echo '<textarea name="comment" class="form-control form-control-sm mb-1" rows="2" required></textarea>';
                echo '<button type="submit" class="btn btn-sm btn-primary">Reply</button>';
                echo '</form>';
            }
            echo '</div>';
            renderComments($byParent, $c['id'], $depth + 1);
        }
    }
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
.comment-tree{padding-left:1rem;border-left:3px solid #dee2e6;margin-left:1rem}
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.reply-link').forEach(function(link){
        link.addEventListener('click', function(e){
            e.preventDefault();
            var parentId = this.getAttribute('data-id');
            var form = document.querySelector('.reply-form[data-parent="'+parentId+'"]');
            if(form) form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    });
});
</script>
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
                    <h4>Comments</h4>
                    <hr>
                    <div class="comments-list mb-4" id="comments-section">
                        <?php if ($notice): ?>
                            <?php renderComments($byParent ?? [], null); ?>
                        <?php endif; ?>
                    </div>
                    <?php if (isLoggedIn() && (hasRole('student') || hasRole('professor'))): ?>
                        <form hx-post="../server/comments.php" hx-target="#comments-section" hx-swap="innerHTML" onsubmit="this.reset()">
                            <input type="hidden" name="notice_id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="3" placeholder="Add your comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted"><a href="login.php">Login</a> as a student or professor to comment.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
