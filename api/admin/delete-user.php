<?php
/**
 * Delete User API
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
if (empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit();
}

// Prevent deleting own account
$current_user = getCurrentUser();
if ($current_user['id'] == $input['id']) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
    exit();
}

// Delete user
$sql = "UPDATE users SET status = 'inactive' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $input['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting user']);
}

?>
