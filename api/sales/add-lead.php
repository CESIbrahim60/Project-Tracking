<?php
/**
 * Add Lead API
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
if (empty($input['lead_name'])) {
    echo json_encode(['success' => false, 'message' => 'Lead name is required']);
    exit();
}

$current_user = getCurrentUser();

// Insert lead
$sql = "INSERT INTO leads (lead_name, company_name, email, phone, project_type, budget_range, assigned_sales_id, notes, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new')";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssss",
    $input['lead_name'],
    $input['company_name'] ?? null,
    $input['email'] ?? null,
    $input['phone'] ?? null,
    $input['project_type'] ?? null,
    $input['budget_range'] ?? null,
    $current_user['id'],
    $input['notes'] ?? null
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Lead added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding lead']);
}

?>
