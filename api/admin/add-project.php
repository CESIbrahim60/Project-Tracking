<?php
/**
 * Add Project API
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

// Get input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['project_name']) || empty($input['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Project name and client are required']);
    exit();
}

// Prepare values with defaults
$client_id = (int)$input['client_id'];
$project_name = $input['project_name'];
$description = $input['description'] ?? '';
$project_type = $input['project_type'] ?? '';
$location = $input['location'] ?? '';
$start_date = $input['start_date'] ?? '';
$end_date = $input['end_date'] ?? '';
$budget = isset($input['budget']) ? (float)$input['budget'] : 0;
$status = $input['status'] ?? 'planning';
$assigned_tech = isset($input['assigned_technician_id']) ? (int)$input['assigned_technician_id'] : 0;
$assigned_sales = isset($input['assigned_sales_id']) ? (int)$input['assigned_sales_id'] : 0;

// Prepare SQL
$sql = "INSERT INTO projects (
            client_id, project_name, description, project_type, location, start_date, end_date, budget, status, assigned_technician_id, assigned_sales_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters
$stmt->bind_param(
    "issssssdsii",
    $client_id,
    $project_name,
    $description,
    $project_type,
    $location,
    $start_date,
    $end_date,
    $budget,
    $status,
    $assigned_tech,
    $assigned_sales
);

// Execute statement
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Project added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
