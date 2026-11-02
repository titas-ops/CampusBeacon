<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBeacon - College Notice Board</title>

    <!-- Page load er age saved theme set kora (flash na hoye) -->
    <script>
        (function () {
            var saved = localStorage.getItem('cb-theme');
            var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <!-- Bootstrap + Google Fonts + style.css -->
    <?php include __DIR__ . '/../client/commonFiles.php'; ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark cb-navbar mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/CampusBeacon/public/index.php">
                <img src="/CampusBeacon/public/images/techno-india-logo.png" alt="Techno India Logo" height="40">
                <span>CampusBeacon</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="/CampusBeacon/public/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/CampusBeacon/public/login.php">Login</a></li>
                    <li class="nav-item">
                        <button type="button" id="themeToggle" class="btn btn-outline-light btn-sm">Dark / Light</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

    <script>
        document.getElementById('themeToggle').addEventListener('click', function () {
            var html = document.documentElement;
            var next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('cb-theme', next);
        });
    </script>
