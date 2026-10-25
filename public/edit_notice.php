<?php
session_start();
require_once '../database/db_connect.php';
require_once '../server/auth_helpers.php';

// Only professors can edit
requireRole('professor');

$notice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = (int)$_SESSION['user_id'];

// Verify ownership and fetch existing content
$stmt = $conn->prepare("SELECT title, content FROM notices WHERE id = ? AND professor_id = ?");
$stmt->bind_param("ii", $notice_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notice = $result->fetch_assoc();

if (!$notice) {
    header("Location: index.php?error=unauthorized");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notice'])) {
    $content = trim($_POST['content'] ?? '');
    $content = mb_substr($content, 0, 300);

    // Re-calculate title from first line if it's empty or content changed
    $firstLine = strtok($content, "\n");
    $title = trim(ltrim($firstLine, '# '));
    if ($title === '') $title = 'Untitled Notice';

    $update_stmt = $conn->prepare("UPDATE notices SET title = ?, content = ? WHERE id = ? AND professor_id = ?");
    $update_stmt->bind_param("ssii", $title, $content, $notice_id, $user_id);

    if ($update_stmt->execute()) {
        header("Location: index.php?success=notice_updated");
    } else {
        header("Location: edit_notice.php?id=$notice_id&error=update_failed");
    }
    $update_stmt->close();
    exit();
}
?>
<?php include '../client/header.php'; ?>
<div class="row justify-content-center" style="padding:0 10px;">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body" style="padding-left:0;padding-right:0;">
                <h3 class="mb-4">Edit Notice</h3>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Failed to update notice. Please try again.</div>
                <?php endif; ?>
                <form action="edit_notice.php?id=<?php echo $notice_id; ?>" method="POST">
                <div class="mb-3">
                    <div class="row" style="padding:10px 10px;">
                        <div class="col-md-6">
                            <label class="form-label">Content (Markdown supported)</label>
                            <textarea name="content" id="mdInput" class="form-control" rows="12" maxlength="300" placeholder="Edit your notice here..."><?php echo htmlspecialchars($notice['content'], ENT_QUOTES); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preview</label>
                            <div id="mdPreview" class="border rounded p-3" style="min-height:300px;overflow:auto;background:#1f2937;color:#f5f5f5;"></div>
                        </div>
                    </div>
                </div>
                    <div class="d-grid">
                        <button type="submit" name="update_notice" class="btn btn-success">Update Notice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
<script>
const input = document.getElementById('mdInput');
const preview = document.getElementById('mdPreview');
function render() {
    preview.innerHTML = DOMPurify.sanitize(marked.parse(input.value || ''));
}
input.addEventListener('input', function() {
    render();
});
input.addEventListener('keydown', function(e) {
    if (e.key === 'Tab' || e.keyCode === 9) {
        e.preventDefault();
        const start = input.selectionStart;
        const end = input.selectionEnd;
        input.value = input.value.substring(0, start) + '    ' + input.value.substring(end);
        input.selectionStart = input.selectionEnd = start + 4;
        render();
    }
});
render();
</script>
<?php include '../client/footer.php'; ?>
