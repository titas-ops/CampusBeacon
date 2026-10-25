<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBeacon - College Notice Board</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Poppins, Roboto, Orbitron -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@300;400;500&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link href="/CampusBeacon/client/style.css?v=blowfish_final" rel="stylesheet">
    <style>
        body { background-color: #111111 !important; padding-bottom: 80px; }
        .navbar-brand {
            font-family: 'Poppins', sans-serif !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            font-size: 1.5rem !important;
        }
        .navbar-brand span {
            color: #ffffff !important;
        }
    </style>
</head>
<body data-theme="dark" style="background-color:#111111!important;color:#e0e0e0!important;padding-top:80px;padding-bottom:80px;">
    <nav class="navbar navbar-expand-lg shadow-sm mb-4 fixed-top d-flex align-items-center" style="background-color:#0f0f0f!important;border-bottom:1px solid #2d2d2d!important;padding-top:0.75rem;padding-bottom:0.75rem;min-height:56px;">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand mb-0" href="/CampusBeacon/public/index.php">
                <span>CampusBeacon</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item px-2">
                        <a class="nav-link" href="/CampusBeacon/public/index.php" style="color:#F9F6FA!important; font-weight: bold !important;">Home</a>
                    </li>
                    <?php if (($_SESSION['logged_in'] ?? false) === true && ($_SESSION['role'] ?? '') === 'professor'): ?>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="/CampusBeacon/public/professor/create_notice.php" style="color:#F9F6FA!important; font-weight: bold !important;">Create Notice</a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <li class="nav-item px-2">
                            <a class="nav-link text-danger" href="/CampusBeacon/server/auth.php?action=logout" style="font-weight: bold !important;">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item px-2">
                            <a class="nav-link" href="/CampusBeacon/public/login.php" style="font-weight: bold !important;">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
