
<?php
/**
 * Home Page
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/includes/language.php';

//session_start();

// If logged in, redirect to dashboard
$role = $_SESSION['role'] ?? null;
if ($role && isset($dashboards[$role])) {
    header('Location: ' . $dashboards[$role]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>" dir="<?php echo getLanguageDirection(); ?>" class="<?php echo getLanguageClass(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('company_name'); ?> - <?php echo t('company_slogan'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 20px;
            text-align: center;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: var(--light-text);
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="index.php" class="navbar-brand">
                    <span><?php echo t('company_name'); ?></span>
                </a>

                <div class="navbar-right">
                    
                    <a href="/maysan/login.php" class="btn btn-primary"><?php echo t('login'); ?></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1><?php echo t('company_name'); ?></h1>
        <p><?php echo t('company_slogan'); ?></p>
        <div class="hero-buttons">
            <a href="login.php" class="btn btn-outline" style="background: white; color: var(--primary-color); border-color: white;">
                <?php echo t('login'); ?>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <div class="container">
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">📹</div>
                <h3><?php echo t('project_management'); ?></h3>
                <p><?php echo t('manage_projects_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3><?php echo t('client_management'); ?></h3>
                <p><?php echo t('manage_clients_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3><?php echo t('progress_tracking'); ?></h3>
                <p><?php echo t('track_progress_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3><?php echo t('lead_management'); ?></h3>
                <p><?php echo t('manage_leads_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3><?php echo t('secure_access'); ?></h3>
                <p><?php echo t('secure_access_desc'); ?></p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3><?php echo t('multilingual'); ?></h3>
                <p><?php echo t('multilingual_desc'); ?></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: white; border-top: 1px solid var(--border-color); padding: 2rem 0; margin-top: 3rem; text-align: center; color: var(--light-text);">
        <div class="container">
            <p>&copy; 2025 <?php echo t('company_name'); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
