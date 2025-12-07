<?php
/**
 * Add Progress Update API
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

$current_user = getCurrentUser();

// Accept both JSON or FormData
$input = json_decode(file_get_contents('php://input'), true);

// If JSON is empty, fallback to POST (FormData)
if (!$input) {
    $input = $_POST;
}

// Validate required fields
$project_id = $input['project_id'] ?? null;
$progress = $input['progress_percentage'] ?? null;

if (empty($project_id) || $progress === null) {
    echo json_encode(['success' => false, 'message' => 'Project ID and progress percentage are required']);
    exit();
}

// Validate progress percentage range
$progress = (int)$progress;
if ($progress < 0 || $progress > 100) {
    echo json_encode(['success' => false, 'message' => 'Progress percentage must be between 0 and 100']);
    exit();
}

$report_text = $input['report_text'] ?? null;

// Insert progress update
$sql = "INSERT INTO progress_updates (project_id, technician_id, progress_percentage, report_text) 
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $project_id, $current_user['id'], $progress, $report_text);

if ($stmt->execute()) {
    // Update project progress
    $update_sql = "UPDATE projects SET progress_percentage = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $progress, $project_id);
    $update_stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Progress update added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding progress update']);
}
?>
