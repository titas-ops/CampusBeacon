<?php
session_start();
require_once '../../server/auth_helpers.php';
require_once '../../database/db_connect.php';
requireRole('professor');
?>
<?php include '../../client/header.php'; ?>
<div class="container-fluid" style="height: calc(100vh - 160px); padding: 20px 0;">
    <div class="row justify-content-center align-items-center" style="height: 100%;">
        <div class="col-auto" style="width: 70%;">
            <div class="card shadow" style="height: 100%; display: flex; flex-direction: column;">
                <div class="card-body p-4 d-flex flex-column">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success" style="background-color: rgba(74, 222, 128, 0.1) !important; border: 1px solid var(--success-color) !important; color: var(--success-color) !important;">Notice created successfully!</div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger" style="background-color: rgba(248, 113, 113, 0.1) !important; border: 1px solid var(--error-color) !important; color: var(--error-color) !important;">Failed to create notice. Please try again.</div>
                    <?php endif; ?>

                    <form action="../../server/notices.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                        <div class="mb-4 flex-grow-1">
                            <div class="row h-100">
                                <div class="col-md-6 d-flex flex-column">
                                    <label class="form-label" style="color: var(--text-color) !important;">Content (Markdown supported)</label>
                                    <textarea name="content" id="mdInput" class="form-control flex-grow-1" style="height: 100% !important; min-height: 400px !important;" maxlength="300" placeholder="Write your notice here..." required></textarea>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label class="form-label" style="color: var(--text-color) !important;">Preview</label>
                                    <div id="mdPreview" class="border rounded p-3 flex-grow-1" style="height: 100% !important; min-height: 400px !important; overflow:auto; background: var(--card-bg) !important; color: var(--text-color) !important; border-color: var(--border-color) !important;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mb-0">
                            <div class="col-md-8">
                                <label class="form-label" style="color: var(--text-color) !important;">Upload PDF (Optional)</label>
                                <div class="d-flex align-items-center">
                                    <input type="file" name="pdf_file" id="pdfUpload" class="form-control form-control-sm" accept="application/pdf" style="width: auto; max-width: 300px;">
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="submit" name="create_notice" class="btn btn-view-details px-4" style="font-weight: 600 !important;">Post</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    const lines = input.value.split('\n');
    if (lines.length > 0 && lines[0].trim() !== '' && !lines[0].trim().startsWith('#')) {
        const pos = input.selectionStart;
        lines[0] = '# ' + lines[0];
        input.value = lines.join('\n');
        input.selectionStart = input.selectionEnd = pos + 2;
    }
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

// PDF Validation
document.getElementById('pdfUpload').addEventListener('change', function() {
    const file = this.files[0];
    if (file && file.type !== 'application/pdf') {
        Swal.fire({
            icon: 'error',
            title: 'Invalid file type',
            text: 'Please upload a PDF file only.',
            background: '#1a1a1a',
            color: '#d1d5db',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'OK',
            customClass: {
                title: 'swal-title',
                popup: 'swal-popup'
            }
        });
        this.value = ''; // Reset the input
    }
});
</script>
<?php include '../../client/footer.php'; ?>
