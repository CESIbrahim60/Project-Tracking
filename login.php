<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/language.php';

$error_message = "";

if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    require_once __DIR__ . '/includes/auth.php';
    header("Location: " . getDashboardUrl($_SESSION['role']));
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username === '' || $password === '') {
        $error_message = "Username and password required";
    } else {
        $result = loginUser($username, $password);
        if ($result['success']) {
            header("Location: " . getDashboardUrl($_SESSION['role']));
            exit();
        } else {
            $error_message = $result['message'];
        }
    }
}

// Handle language switch
if (isset($_GET['lang'])) {
    setLanguage($_GET['lang']);
    header("Location: /maysan/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(getCurrentLanguage()); ?>" dir="<?= htmlspecialchars(getLanguageDirection()); ?>" class="<?= htmlspecialchars(getLanguageClass()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('login'); ?> - <?= t('company_name'); ?></title>
    <link rel="stylesheet" href="/maysan/assets/css/style.css">
    <style>
        .login-demo-info {
            background-color: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #0c4a6e;
        }
        html.ar .login-demo-info {
            border-left: none;
            border-right: 4px solid #0284c7;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <h1><?= t('company_name'); ?></h1>
            <p><?= t('company_slogan'); ?></p>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

       

        <form method="POST" class="login-form">
            <div class="form-group">
                <label><?= t('username'); ?></label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label><?= t('password'); ?></label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                <?= t('login'); ?>
            </button>
        </form>

        <div class="login-footer">
            <span><?= t('language'); ?>:</span>
            <a href="?lang=en">English</a> |
            <a href="?lang=ar">العربية</a>
        </div>
    </div>
</div>

<script src="/maysan/assets/js/main.js"></script>
</body>
</html>
