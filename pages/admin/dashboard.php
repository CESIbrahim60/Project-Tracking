<?php
/**
 * Admin Dashboard
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('admin');

$page_title = t('admin_dashboard');
$stats = getDashboardStats();

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title"><?php echo t('admin_dashboard'); ?></h1>
    <p class="page-subtitle"><?php echo t('welcome'); ?>, <?php echo htmlspecialchars($current_user['full_name']); ?></p>
</div>

<!-- Statistics Cards -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('total_clients'); ?></div>
        <div class="stat-card-value"><?php echo $stats['total_clients']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('total_projects'); ?></div>
        <div class="stat-card-value"><?php echo $stats['total_projects']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('active_projects'); ?></div>
        <div class="stat-card-value"><?php echo $stats['active_projects']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('completed_projects'); ?></div>
        <div class="stat-card-value"><?php echo $stats['completed_projects']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('total_users'); ?></div>
        <div class="stat-card-value"><?php echo $stats['total_users']; ?></div>
    </div>

</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('quick_actions'); ?></h2>
    </div>
    <div class="card-body">
        <div class="dashboard-grid">
            <a href="/maysan/pages/admin/clients.php" class="btn btn-primary">
                <span>➕</span> <?php echo t('add_client'); ?>
            </a>
            <a href="/maysan/pages/admin/projects.php" class="btn btn-secondary">
                <span>➕</span> <?php echo t('add_project'); ?>
            </a>
            <a href="/maysan/pages/admin/users.php" class="btn btn-info">
                <span>➕</span> <?php echo t('add_user'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Recent Projects -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('recent_projects'); ?></h2>
        <a href="/maysan/pages/admin/projects.php" class="btn btn-outline btn-sm"><?php echo t('view_all'); ?></a>
    </div>
    <div class="card-body">
        <?php
        $projects = getAllProjects();
        if (count($projects) > 0):
            $projects = array_slice($projects, 0, 5);
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('project_name'); ?></th>
                    <th><?php echo t('client'); ?></th>
                    <th><?php echo t('progress'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('actions'); ?></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                    <td><?php echo htmlspecialchars($project['company_name'] ?? 'N/A'); ?></td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo $project['progress_percentage']; ?>%">
                                <?php echo $project['progress_percentage']; ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $project['status'] === 'completed' ? 'success' : ($project['status'] === 'in_progress' ? 'info' : 'warning'); ?>">
                            <?php echo ucfirst($project['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="/maysan/pages/technician/project-detail.php?id=<?php echo $project['id']; ?>" class="btn btn-info btn-sm">
                            👁️ <?php echo t('view'); ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; color: var(--light-text);"><?php echo t('no_projects_found'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
