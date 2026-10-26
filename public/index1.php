<?php include 'header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Notices</h2>

        <!-- Hardcoded Placeholder for a Notice Card -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Sample Notice: Semester Exams</h5>
                <p class="card-subtitle mb-2 text-muted">Posted by Prof. Smith on 2026-10-20</p>
                <p class="card-text">This is a sample markdown notice. The exams will start from next Monday. Please check your hall tickets.</p>
                <a href="view_notice.php?id=1" class="btn btn-primary">View Details &amp; Comments</a>
            </div>
        </div>

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Sample Notice: Holiday Announcement</h5>
                <p class="card-subtitle mb-2 text-muted">Posted by Prof. Johnson on 2026-10-21</p>
                <p class="card-text">Please find the attached PDF for the revised holiday calendar for the current semester.</p>
                <a href="view_notice.php?id=2" class="btn btn-primary">View PDF &amp; Details</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
