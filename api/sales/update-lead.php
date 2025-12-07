<?php
/**
 * Update Lead API
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

// Update lead
$sql = "UPDATE leads SET lead_name = ?, company_name = ?, email = ?, phone = ?, project_type = ?, budget_range = ?, status = ?, notes = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssi",
    $input['lead_name'],
    $input['company_name'] ?? null,
    $input['email'] ?? null,
    $input['phone'] ?? null,
    $input['project_type'] ?? null,
    $input['budget_range'] ?? null,
    $input['status'] ?? 'new',
    $input['notes'] ?? null,
    $input['id']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Lead updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating lead']);
}

?>
