<?php
/**
 * Add Feedback API
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

// Check authentication
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['project_id']) || empty($input['feedback_text'])) {
    echo json_encode(['success' => false, 'message' => 'Project ID and feedback text are required']);
    exit();
}

$current_user = getCurrentUser();

// Insert feedback
$sql = "INSERT INTO feedback (project_id, user_id, feedback_text, feedback_type) 
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$feedback_type = $input['feedback_type'] ?? 'note';
$stmt->bind_param(
    "iiss",
    $input['project_id'],
    $current_user['id'],
    $input['feedback_text'],
    $feedback_type
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Feedback sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error sending feedback']);
}

?>
