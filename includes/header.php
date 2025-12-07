<?php
/**
 * Header Component
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/language.php';

$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>" dir="<?php echo getLanguageDirection(); ?>" class="<?php echo getLanguageClass(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?><?php echo t('company_name'); ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <!-- Sidebar Toggle Button (Mobile) -->
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    ☰
                </button>

                <a href="/maysan" class="navbar-brand">
                    <span><?php echo t('company_name'); ?></span>
                </a>

                <ul class="navbar-menu">
                    <li><a href="/maysan"><?php echo t('home'); ?></a></li>
                </ul>

                <div class="navbar-right">
                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <button class="<?php echo getCurrentLanguage() === 'en' ? 'active' : ''; ?>" onclick="switchLanguage('en')">EN</button>
                        <button class="<?php echo getCurrentLanguage() === 'ar' ? 'active' : ''; ?>" onclick="switchLanguage('ar')">AR</button>
                    </div>

                    <!-- User Menu -->
                    <div class="user-menu">
                        <button class="user-menu-toggle" onclick="toggleUserMenu()">
                            <span><?php echo htmlspecialchars($current_user['full_name'] ?? 'User'); ?></span>
                            <span>▼</span>
                        </button>
                        <div class="user-menu-dropdown" id="userMenuDropdown">
                            <button onclick="logout()"><?php echo t('logout'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <?php
            $role = $current_user['role'] ?? 'client';
            
            if ($role === 'admin') {
                echo '<li><a href="/maysan/pages/admin/dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '">' . t('dashboard') . '</a></li>';
                echo '<li><a href="/maysan/pages/admin/clients.php" class="' . (basename($_SERVER['PHP_SELF']) === 'clients.php' ? 'active' : '') . '">' . t('manage_clients') . '</a></li>';
                echo '<li><a href="/maysan/pages/admin/projects.php" class="' . (basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : '') . '">' . t('manage_projects') . '</a></li>';
                echo '<li><a href="/maysan/pages/admin/users.php" class="' . (basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '') . '">' . t('manage_users') . '</a></li>';
            } elseif ($role === 'client') {
                echo '<li><a href="/maysan/pages/client/dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '">' . t('dashboard') . '</a></li>';
                echo '<li><a href="/maysan/pages/client/projects.php" class="' . (basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : '') . '">' . t('my_projects') . '</a></li>';
            } elseif ($role === 'technician') {
                echo '<li><a href="/maysan/pages/technician/dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '">' . t('dashboard') . '</a></li>';
                echo '<li><a href="/maysan/pages/technician/projects.php" class="' . (basename($_SERVER['PHP_SELF']) === 'projects.php' ? 'active' : '') . '">' . t('assigned_projects') . '</a></li>';
            } elseif ($role === 'sales') {
                echo '<li><a href="/maysan/pages/sales/dashboard.php" class="' . (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') . '">' . t('dashboard') . '</a></li>';
                echo '<li><a href="/maysan/pages/sales/leads.php" class="' . (basename($_SERVER['PHP_SELF']) === 'leads.php' ? 'active' : '') . '">' . t('manage_leads') . '</a></li>';
                echo '<li><a href="/maysan/pages/sales/clients.php" class="' . (basename($_SERVER['PHP_SELF']) === 'clients.php' ? 'active' : '') . '">' . t('manage_clients') . '</a></li>';
            }
            ?>
        </ul>
    </aside>
    <script>
            function logout() {
            // نستخدم fetch لاستدعاء الـ API أولًا
            fetch('api/logout.php', {
                method: 'GET', // أو POST لو انت محدد في الـ API
                credentials: 'same-origin' // يحافظ على الـ session
            })
            .then(() => {
                // بعد logout مباشرة نحول المستخدم لصفحة login
                window.location.href = '/maysan/login.php';
            })
            .catch(err => {
                console.error('Logout error:', err);
                alert('حدث خطأ أثناء تسجيل الخروج');
            });
        }
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");

            // لو السايدبار اتفتحت، نضيف كلاس للـ body
        if (sidebar.classList.contains("active")) {
            document.body.classList.add("sidebar-open");
            } else {
            document.body.classList.remove("sidebar-open");
        }
          }

        // إغلاق السايدبار عند الضغط على أي مكان في البودي
        document.addEventListener("click", function (event) {
            const sidebar = document.getElementById("sidebar");
            const toggleBtn = document.querySelector(".sidebar-toggle");

            // لو السايدبار مفتوحة
            if (sidebar.classList.contains("active")) {

                // لو الضغط مش على السايدبار ولا على زر الفتح
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove("active");
                    document.body.classList.remove("sidebar-open");
                }
            }
        });
    </script>

    <script src="/maysan/assets/js/main.js"></script>

    <!-- Main Content -->
    <main class="main-content">
