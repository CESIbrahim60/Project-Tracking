<?php
/**
 * Logout API
 * Maysan Al-Riyidh CCTV Security Systems
 */

session_start();
session_destroy();

header('Location:/maysan/login.php');
exit();

?>
