<?php
/**
 * Language Switch API
 * Maysan Al-Riyidh CCTV Security Systems
 */

session_start();

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['language']) && in_array($input['language'], ['en', 'ar'])) {
    $_SESSION['language'] = $input['language'];
    echo json_encode(['success' => true, 'message' => 'Language switched successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid language']);
}

?>
