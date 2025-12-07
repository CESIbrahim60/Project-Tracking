<?php
/**
 * Technician - Assigned Projects
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('technician');

$page_title = t('assigned_projects');
$current_user = getCurrentUser();

// Get assigned projects
$projects = getAllProjectsForTechnician();


include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title"><?php echo t('assigned_projects'); ?></h1>
    <p class="page-subtitle"><?php echo t('total_projects'); ?>: <?php echo count($projects); ?></p>
</div>

<!-- Projects Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    <?php if (count($projects) > 0): ?>
        <?php foreach ($projects as $project): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo htmlspecialchars($project['project_name']); ?></h3>
            </div>
            <div class="card-body">
                <p><strong><?php echo t('client'); ?>:</strong> <?php echo htmlspecialchars($project['company_name'] ?? 'N/A'); ?></p>
                <p><strong><?php echo t('location'); ?>:</strong> <?php echo htmlspecialchars($project['location'] ?? 'N/A'); ?></p>
                <p><strong><?php echo t('status'); ?>:</strong> 
                    <span class="badge badge-<?php echo $project['status'] === 'completed' ? 'success' : ($project['status'] === 'in_progress' ? 'info' : 'warning'); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                    </span>
                </p>
                
                <div style="margin-top: 1rem;">
                    <p style="margin-bottom: 0.5rem;"><strong><?php echo t('progress'); ?>:</strong></p>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo $project['progress_percentage']; ?>%">
                            <?php echo $project['progress_percentage']; ?>%
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="/maysan/pages/technician/project-detail.php?id=<?php echo $project['id']; ?>" class="btn btn-primary btn-sm">
                    👁️ <?php echo t('view_details'); ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-body" style="text-align: center; color: var(--light-text);">
            <p><?php echo t('no_projects_found'); ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
