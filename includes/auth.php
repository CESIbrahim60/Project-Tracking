<?php
/**
 * Authentication Functions (Stable Final Version)
 * Maysan Al-Riyidh CCTV Security Systems
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged-in user
 */
function getCurrentUser() {
    global $conn;

    if (!isLoggedIn()) return null;

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

/**
 * Check if user has any of the given roles
 */
function hasAnyRole(array $roles) {
    $user = getCurrentUser();
    return $user && in_array($user['role'], $roles);
}

/**
 * Require login (safe, avoids redirect loop)
 */
function requireLogin() {
    if (basename($_SERVER['PHP_SELF']) === "login.php") return; // don't redirect login page
    if (!isLoggedIn()) {
        header("Location: /maysan/login.php");
        exit();
    }
}

/**
 * Require a specific role
 */
function requireRole($roles) {
    requireLogin();

    // هات المستخدم الحالي
    $user = getCurrentUser();
    if (!$user) {
        header("Location: /maysan/login.php");
        exit();
    }

    // لو جالك String واحد حوله Array
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    // لو المستخدم مش من ضمن الأدوار المقبولة
    if (!in_array($user['role'], $roles)) {
        header("Location: /maysan/unauthorized.php");
        exit();
    }
}

/**
 * Require any of multiple roles
 */
function requireAnyRole(array $roles) {
    requireLogin();
    if (!hasAnyRole($roles)) {
        header("Location: /maysan/unauthorized.php");
        exit();
    }
}

/**
 * Login user
 */
function loginUser(string $username, string $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, password, role, status FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'User not found'];
    }

    $user = $result->fetch_assoc();

    if ($user['status'] !== 'active') {
        return ['success' => false, 'message' => 'User account is inactive'];
    }

    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid password'];
    }

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();

    return [
        'success' => true,
        'message' => 'Login successful',
        'role' => $user['role']
    ];
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
    return ['success' => true, 'message' => 'Logout successful'];
}

/**
 * Get dashboard URL based on role
 */
function getDashboardUrl(string $role) {
    $dashboards = [
        'admin'      => '/maysan/pages/admin/dashboard.php',
        'client'     => '/maysan/pages/client/dashboard.php',
        'technician' => '/maysan/pages/technician/dashboard.php',
        'sales'      => '/maysan/pages/sales/dashboard.php'
    ];
    return $dashboards[$role] ?? '/maysan/pages/client/dashboard.php';
}
?>
