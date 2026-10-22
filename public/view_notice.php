<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Notice Details Section -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <h2 class="card-title">Sample Notice: Semester Exams</h2>
                <p class="text-muted">Posted by Prof. Smith on 2026-10-20</p>
                <hr>
                <div class="notice-content mb-4">
                    <!-- Markdown rendered content would go here -->
                    <p>This is a sample markdown notice. The exams will start from next Monday. Please check your hall tickets.</p>
                </div>

                <!-- PDF View Link Placeholder -->
                <div class="alert alert-info">
                    <strong>Attached Document:</strong> <a href="stream_pdf.php?id=1" target="_blank">semester_exams.pdf</a>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card shadow">
            <div class="card-body">
                <h4>Student Comments</h4>
                <hr>
                <div class="comments-list mb-4">
                    <div class="mb-3 p-2 border-bottom">
                        <strong>Student A:</strong>
                        <p class="mb-1">Will the hall tickets be available online?</p>
                        <small class="text-muted">2026-10-21 10:00 AM</small>
                    </div>
                    <div class="mb-3 p-2 border-bottom">
                        <strong>Student B:</strong>
                        <p class="mb-1">Thank you for the update, Professor!</p>
                        <small class="text-muted">2026-10-21 11:30 AM</small>
                    </div>
                </div>

                <!-- Comment Form -->
                <form action="../server/comments.php" method="POST">
                    <input type="hidden" name="notice_id" value="1">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Add your comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
