<?php
/**
 * Authentication Helpers
 * Provides Role-Based Access Control (RBAC) and session verification.
 */

/**
 * Checks if the user is currently logged in.
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true;
}

/**
 * Checks if the logged-in user has a specific role.
 * @param string $role The role to check for (e.g., 'admin', 'professor', 'student').
 * @return bool
 */
function hasRole($role) {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Forces a user to have a specific role or redirects them to the login page.
 * Use this at the top of protected pages.
 * @param string $role The required role for the page.
 */
function requireRole($role) {
    if (!isLoggedIn()) {
        header("Location: ../public/login.php?error=please_login");
        exit();
    }

    if (!hasRole($role)) {
        // Redirect unauthorized users to a generic error or their own dashboard
        $currentRole = $_SESSION['role'];
        if ($currentRole === 'professor') {
            header("Location: ../public/professor/dashboard.php?error=unauthorized");
        } elseif ($currentRole === 'student') {
            header("Location: ../public/index.php?error=unauthorized");
        } else {
            header("Location: ../public/login.php?error=unauthorized");
        }
        exit();
    }
}
?>
