<?php
session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';
requireRole('professor');
?>
<?php include '../../client/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Create New Notice</h3>
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Notice created successfully!</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Failed to create notice. Please try again.</div>
                <?php endif; ?>
                <form action="../../server/notices.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Notice Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter a clear title" required>
                    </div>
                <div class="mb-3">
                    <label class="form-label">Content (Markdown supported)</label>
                    <div class="row">
                        <div class="col-md-6">
                            <textarea name="content" id="mdInput" class="form-control" rows="12" placeholder="Write your notice here..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <div id="mdPreview" class="border rounded p-3" style="min-height:300px;overflow:auto;background:#fff;color:#222;"></div>
                        </div>
                    </div>
                </div>
                    <div class="mb-3">
                        <label class="form-label">Upload PDF (Optional)</label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="create_notice" class="btn btn-primary">Post Notice</button>
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
input.addEventListener('input', render);
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
<?php include '../../client/footer.php'; ?>
