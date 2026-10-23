<?php
/**
 * Authentication Controller
 * Handles Login, Signup, and Logout logic.
 */

// FORCE ERROR REPORTING FOR DEBUGGING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database/db_connect.php';
require_once 'auth_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['professor_login'])) {
        handleProfessorLogin();
    } elseif (isset($_POST['student_login'])) {
        handleStudentLogin();
    } elseif (isset($_POST['signup'])) {
        handleStudentSignup();
    } else {
        header("Location: ../public/login.php?error=invalid_request");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    handleLogout();
}

/**
 * Handles Professor Login (Email + Password)
 */
function handleProfessorLogin() {
    global $conn;
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../public/login_professor.php?error=empty_fields");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ? AND role = 'professor'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            setSession($user);
            header("Location: ../public/professor/create_notice.php");
            exit();
        }
    }

    header("Location: ../public/login_professor.php?error=invalid_credentials");
    exit();
}

/**
 * Handles Student Login (Roll Number + Password)
 */
function handleStudentLogin() {
    global $conn;
    $roll_number = trim($_POST['roll_number'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($roll_number) || empty($password)) {
        header("Location: ../public/login_student.php?error=empty_fields");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE roll_number = ? AND role = 'student'");
    $stmt->bind_param("s", $roll_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            setSession($user);
            header("Location: ../public/index.php");
            exit();
        }
    }

    header("Location: ../public/login_student.php?error=invalid_credentials");
    exit();
}

/**
 * Handles Student Signup
 */
function handleStudentSignup() {
    global $conn;
    $roll_number = trim($_POST['roll_number'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($roll_number) || empty($username) || empty($email) || empty($password)) {
        header("Location: ../public/signup.php?error=empty_fields");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: ../public/signup.php?error=passwords_do_not_match");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'student';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, roll_number, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $roll_number, $role);

    if ($stmt->execute()) {
        header("Location: ../public/login_student.php?success=account_created");
    } else {
        header("Location: ../public/signup.php?error=user_already_exists");
    }
    $stmt->close();
    exit();
}

/**
 * Set session variables on successful login
 */
function setSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['logged_in'] = true;
}

/**
 * Clear session and logout user
 */
function handleLogout() {
    session_unset();
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}
?>
