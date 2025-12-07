<?php
/**
 * Technician Dashboard
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('technician');

$page_title = t('technician_dashboard');
$current_user = getCurrentUser();

// Get assigned projects
$projects = getAllProjectsForTechnician();

// Calculate stats
$total_assigned = count($projects);
$in_progress = count(array_filter($projects, fn($p) => $p['status'] === 'in_progress'));
$on_hold = count(array_filter($projects, fn($p) => $p['status'] === 'on_hold'));

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title"><?php echo t('technician_dashboard'); ?></h1>
    <p class="page-subtitle"><?php echo t('welcome'); ?>, <?php echo htmlspecialchars($current_user['full_name']); ?></p>
</div>

<!-- Statistics Cards -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('assigned_projects'); ?></div>
        <div class="stat-card-value"><?php echo $total_assigned; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('in_progress'); ?></div>
        <div class="stat-card-value"><?php echo $in_progress; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('on_hold'); ?></div>
        <div class="stat-card-value"><?php echo $on_hold; ?></div>
    </div>
</div>

<!-- Assigned Projects -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('assigned_projects'); ?></h2>
        <a href="/maysan/pages/technician/projects.php" class="btn btn-outline btn-sm"><?php echo t('view_all'); ?></a>
    </div>
    <div class="card-body">
        <?php if (count($projects) > 0): ?>
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
                <?php 
                $display_projects = array_slice($projects, 0, 5);
                foreach ($display_projects as $project): 
                ?>
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
                            <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
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
