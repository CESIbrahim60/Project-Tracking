<?php
/**
 * Utility Functions
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Get file extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Check if file is image
 */
function isImage($filename) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    return in_array(getFileExtension($filename), $allowed);
}

/**
 * Check if file is video
 */
function isVideo($filename) {
    $allowed = ['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv'];
    return in_array(getFileExtension($filename), $allowed);
}

/**
 * Upload file to server
 */
function uploadFile($file, $upload_dir = '/assets/uploads/') {
    global $conn;
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    
    // Validate file size (max 50MB)
    if ($file_size > 52428800) {
        return ['success' => false, 'message' => 'File size exceeds 50MB limit'];
    }
    
    // Generate unique filename
    $new_filename = time() . '_' . generateToken(8) . '.' . getFileExtension($filename);
    
    // Create upload directory if it doesn't exist
    $upload_path = __DIR__ . '/..' . $upload_dir;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }
    
    // Move uploaded file
    $full_path = $upload_path . $new_filename;
    if (!move_uploaded_file($tmp_name, $full_path)) {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
    
    return [
        'success' => true,
        'message' => 'File uploaded successfully',
        'filename' => $new_filename,
        'filepath' => $upload_dir . $new_filename,
        'original_name' => $filename
    ];
}

/**
 * Delete file from server
 */
function deleteFile($filepath) {
    $full_path = __DIR__ . '/..' . $filepath;
    
    if (file_exists($full_path)) {
        if (unlink($full_path)) {
            return ['success' => true, 'message' => 'File deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete file'];
        }
    }
    
    return ['success' => false, 'message' => 'File not found'];
}

/**
 * Format date
 */
function formatDate($date, $format = 'Y-m-d') {
    if (empty($date)) {
        return '';
    }
    
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'SAR') {
    return number_format($amount, 2) . ' ' . $currency;
}

/**
 * Get client by ID
 */
function getClientById($client_id) {
    global $conn;
    
    $sql = "SELECT c.*, u.full_name, u.email, u.phone FROM clients c 
            LEFT JOIN users u ON c.user_id = u.id WHERE c.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Get project by ID
 */
function getProjectById($project_id) {
    global $conn;
    
    $sql = "SELECT p.*, c.company_name, 
            t.full_name as technician_name, 
            s.full_name as sales_name 
            FROM projects p 
            LEFT JOIN clients c ON p.client_id = c.id 
            LEFT JOIN users t ON p.assigned_technician_id = t.id 
            LEFT JOIN users s ON p.assigned_sales_id = s.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}


/**
 * Get user by ID
 */
function getUserById($user_id) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Get all clients
 */
function getAllClients() {
    global $conn;
    
    $sql = "SELECT c.*, u.full_name, u.email FROM clients c 
            LEFT JOIN users u ON c.user_id = u.id 
            WHERE c.status = 'active' ORDER BY c.created_at DESC";
    $result = $conn->query($sql);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all projects
 */
function getAllProjects() {
    global $conn;
    
    $sql = "SELECT p.*, c.company_name FROM projects p 
            LEFT JOIN clients c ON p.client_id = c.id 
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all users by role
 */
function getUsersByRole($role) {
    global $conn;
    
    $sql = "SELECT * FROM users WHERE role = ? AND status = 'active' ORDER BY full_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}


/**
 * Get project media
 */
function getProjectMedia($project_id) {
    global $conn;
    
    $sql = "SELECT pm.*, u.full_name FROM project_media pm 
            LEFT JOIN users u ON pm.uploaded_by = u.id 
            WHERE pm.project_id = ? ORDER BY pm.uploaded_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get progress updates for project
 */
function getProgressUpdates($project_id) {
    global $conn;
    
    $sql = "SELECT pu.*, u.full_name FROM progress_updates pu 
            LEFT JOIN users u ON pu.technician_id = u.id 
            WHERE pu.project_id = ? ORDER BY pu.update_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get feedback for project
 */
function getProjectFeedback($project_id) {
    global $conn;
    
    $sql = "SELECT f.*, u.full_name FROM feedback f 
            LEFT JOIN users u ON f.user_id = u.id 
            WHERE f.project_id = ? ORDER BY f.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get dashboard statistics
 */
function getDashboardStats() {
    global $conn;
    
    $stats = [];
    
    // Total clients
    $result = $conn->query("SELECT COUNT(*) as count FROM clients WHERE status = 'active'");
    $stats['total_clients'] = $result->fetch_assoc()['count'];
    
    // Total projects
    $result = $conn->query("SELECT COUNT(*) as count FROM projects");
    $stats['total_projects'] = $result->fetch_assoc()['count'];
    
    // Active projects
    $result = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status = 'in_progress'");
    $stats['active_projects'] = $result->fetch_assoc()['count'];
    
    // Completed projects
    $result = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status = 'completed'");
    $stats['completed_projects'] = $result->fetch_assoc()['count'];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
    $stats['total_users'] = $result->fetch_assoc()['count'];
    
    // Total leads
    $result = $conn->query("SELECT COUNT(*) as count FROM leads WHERE status != 'lost'");
    $stats['total_leads'] = $result->fetch_assoc()['count'];
    
    return $stats;
}

/**
 * Convert JSON response
 */
function jsonResponse($success, $message, $data = []) {
    return json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
}
function getAllProjectsForTechnician() {
    global $conn;

    $sql = "
        SELECT 
            p.id,
            p.project_name,
            p.location,
            p.status,
            p.progress_percentage,
            c.company_name
        FROM projects p
        LEFT JOIN clients c ON p.client_id = c.id
        WHERE p.status != 'cancelled'
        ORDER BY p.created_at DESC
    ";

    $result = $conn->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

?>
