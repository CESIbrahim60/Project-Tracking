<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

// تحقق من تسجيل الدخول فقط
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$current_user = getCurrentUser(); // الآن أي فني مسموح له

// التحقق من الحقول المطلوبة
if (empty($_POST['project_id']) || empty($_POST['media_type'])) {
    echo json_encode(['success' => false, 'message' => 'Project ID and media type are required']);
    exit();
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File upload failed']);
    exit();
}

$project_id = (int)$_POST['project_id'];
$media_type = $_POST['media_type'];

if (!in_array($media_type, ['image', 'video'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid media type']);
    exit();
}

// رفع الملف
$upload_result = uploadFile($_FILES['file']);
if (!$upload_result['success']) {
    echo json_encode(['success' => false, 'message' => $upload_result['message']]);
    exit();
}

$description = $_POST['description'] ?? null;

// إدخال في قاعدة البيانات
$sql = "INSERT INTO project_media (project_id, media_type, file_path, file_name, uploaded_by, description) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ississ",
    $project_id,
    $media_type,
    $upload_result['filepath'],
    $upload_result['original_name'],
    $current_user['id'],
    $description
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Media uploaded successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving media to database']);
}
