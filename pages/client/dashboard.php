<?php
/**
 * Client Dashboard
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('client');

$page_title = t('client_dashboard');
$current_user = getCurrentUser();

// Get client info
$sql = "SELECT * FROM clients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user['id']);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

// Get client's projects
$sql = "SELECT p.*, c.company_name FROM projects p 
        LEFT JOIN clients c ON p.client_id = c.id 
        WHERE p.client_id = ? ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client['id']);
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate stats
$total_projects = count($projects);
$active_projects = count(array_filter($projects, fn($p) => $p['status'] === 'in_progress'));
$completed_projects = count(array_filter($projects, fn($p) => $p['status'] === 'completed'));

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title"><?php echo t('client_dashboard'); ?></h1>
    <p class="page-subtitle"><?php echo t('welcome'); ?>, <?php echo htmlspecialchars($client['company_name'] ?? $current_user['full_name']); ?></p>
</div>

<!-- Statistics Cards -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('total_projects'); ?></div>
        <div class="stat-card-value"><?php echo $total_projects; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('active_projects'); ?></div>
        <div class="stat-card-value"><?php echo $active_projects; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label"><?php echo t('completed_projects'); ?></div>
        <div class="stat-card-value"><?php echo $completed_projects; ?></div>
    </div>
</div>

<!-- My Projects -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('my_projects'); ?></h2>
        <a href="/maysan/pages/client/projects.php" class="btn btn-outline btn-sm"><?php echo t('view_all'); ?></a>
    </div>
    <div class="card-body">
        <?php if (count($projects) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('project_name'); ?></th>
                    <th><?php echo t('progress'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('start_date'); ?></th>
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
                    <td><?php echo formatDate($project['start_date']); ?></td>
                    <td>
                        <a href="/maysan/pages/client/project-detail.php?id=<?php echo $project['id']; ?>" class="btn btn-info btn-sm">
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
