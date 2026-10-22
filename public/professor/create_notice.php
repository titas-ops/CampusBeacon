<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Create New Notice</h3>
                <form action="../server/notices.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Notice Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter a clear title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content (Markdown supported)</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Write your notice here..."></textarea>
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
<?php include 'footer.php'; ?>
