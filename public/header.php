<?php
// (keep any PHP your colleague had at the very top, e.g. session_start(); and put this after it)
$themes = ['light', 'dark', 'orange'];

if (isset($_GET['theme']) && in_array($_GET['theme'], $themes, true)) {
    setcookie('cb_theme', $_GET['theme'], time() + 60 * 60 * 24 * 365, '/');
    $_COOKIE['cb_theme'] = $_GET['theme'];
}

$theme = $_COOKIE['cb_theme'] ?? 'light';
if (!in_array($theme, $themes, true)) { $theme = 'light'; }
$bsTheme = ($theme === 'dark') ? 'dark' : 'light';

if (!function_exists('themeLink')) {
    function themeLink($t) {
        $q = $_GET;
        $q['theme'] = $t;
        return '?' . htmlspecialchars(http_build_query($q));
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $bsTheme ?>" data-cb-theme="<?= $theme ?>">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBeacon - College Notice Board</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg cb-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <!-- LATER: add logo here -> <img src="logo.png" alt="Techno India logo" height="40"> -->
                <span class="cb-brand-text">CampusBeacon</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item ms-lg-3 py-2 py-lg-0">
                        <div class="cb-swatches">
                            <a href="<?= themeLink('light') ?>"  class="cb-swatch <?= $theme === 'light'  ? 'active' : '' ?>" data-theme="light"  title="White"  aria-label="White theme"></a>
                            <a href="<?= themeLink('dark') ?>"   class="cb-swatch <?= $theme === 'dark'   ? 'active' : '' ?>" data-theme="dark"   title="Dark"   aria-label="Dark theme"></a>
                            <a href="<?= themeLink('orange') ?>" class="cb-swatch <?= $theme === 'orange' ? 'active' : '' ?>" data-theme="orange" title="Orange" aria-label="Orange theme"></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container cb-main">