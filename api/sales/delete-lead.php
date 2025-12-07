<?php
/**
 * Delete Lead API
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

// Check authentication
if (!isLoggedIn() || !hasRole('sales')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Lead ID is required']);
    exit();
}

// Delete lead
$sql = "DELETE FROM leads WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $input['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Lead deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting lead']);
}

?>
