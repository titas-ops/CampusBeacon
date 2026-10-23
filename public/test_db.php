<?php
/**
 * Temporary DB Connection Test
 * This file is used to verify that auto-initialization and connectivity are working.
 * Should be deleted after verification.
 */

try {
    require_once '../database/db_connect.php';
    if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        echo "<h1>✅ Database Connection Successful!</h1>";
        echo "<p>The database <strong>" . DB_NAME . "</strong> is connected and ready.</p>";

        // Quick check to see if the users table exists and has the admin
        $result = $conn->query("SELECT username FROM users LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "<p>✅ Default user found: <strong>" . htmlspecialchars($user['username']) . "</strong></p>";
        } else {
            echo "<p>⚠️ Connection worked, but no users found in the table.</p>";
        }
    } else {
        throw new Exception("Connection variable not found or invalid.");
    }
} catch (Exception $e) {
    echo "<h1>❌ Connection Failed</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
