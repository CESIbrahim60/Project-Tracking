<?php
/**
 * Add User API
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

// Check authentication and role
if (!isLoggedIn() || !hasRole('admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['username']) || empty($input['email']) || empty($input['password']) || empty($input['full_name']) || empty($input['role'])) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    exit();
}

// Check if username already exists
$sql = "SELECT id FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $input['username'], $input['email']);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
    exit();
}

// Hash password
$hashed_password = password_hash($input['password'], PASSWORD_BCRYPT);

// Insert user
// Insert user
$sql = "INSERT INTO users (username, email, password, full_name, phone, role, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'active')";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

$phone = $input['phone'] ?? '';
$stmt->bind_param(
    "ssssss",
    $input['username'],
    $input['email'],
    $hashed_password,
    $input['full_name'],
    $phone,
    $input['role']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

?>
