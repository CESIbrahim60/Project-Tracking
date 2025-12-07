<?php
/**
 * Delete Client API
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
    echo json_encode(['success' => false, 'message' => 'Client ID is required']);
    exit();
}

// Delete client
$sql = "UPDATE clients SET status = 'inactive' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $input['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting client']);
}

?>
