<?php
/**
 * Add Client API
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// Check authentication and role
if (!isLoggedIn() || !hasRole('admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['company_name'])) {
    echo json_encode(['success' => false, 'message' => 'Company name is required']);
    exit();
}

// Get current user
$current_user = getCurrentUser();

// Prepare values with defaults
$user_id = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$company_name = $input['company_name'];
$contact_person = $input['contact_person'] ?? '';
$phone = $input['phone'] ?? '';
$email = $input['email'] ?? '';
$address = $input['address'] ?? '';
$city = $input['city'] ?? '';
$country = $input['country'] ?? '';
$notes = $input['notes'] ?? '';

// Prepare SQL
$sql = "INSERT INTO clients (user_id, company_name, contact_person, phone, email, address, city, country, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters
$stmt->bind_param(
    "issssssss",
    $user_id,
    $company_name,
    $contact_person,
    $phone,
    $email,
    $address,
    $city,
    $country,
    $notes
);

// Execute statement
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Client added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
