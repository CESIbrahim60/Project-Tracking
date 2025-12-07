<?php
/**
 * Sales - Client Detail
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('sales');

$client_id = $_GET['id'] ?? 0;

// Get client
$client = getClientById($client_id);

if (!$client) {
    header('Location: /maysan/pages/sales/clients.php');
    exit();
}

$page_title = htmlspecialchars($client['company_name']);

// Get client's projects
$sql = "SELECT * FROM projects WHERE client_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <a href="/maysan/pages/sales/clients.php" class="btn btn-outline btn-sm"><?php echo t('back'); ?></a>
    <h1 class="page-title"><?php echo htmlspecialchars($client['company_name']); ?></h1>
</div>

<!-- Client Details -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('client_details'); ?></h2>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div>
                <strong><?php echo t('company_name'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['company_name']); ?></p>
            </div>
            <div>
                <strong><?php echo t('contact_person'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['contact_person'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('status'); ?>:</strong>
                <p>
                    <span class="badge badge-<?php echo $client['status'] === 'active' ? 'success' : 'danger'; ?>">
                        <?php echo ucfirst($client['status']); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="form-row" style="margin-top: 1.5rem;">
            <div>
                <strong><?php echo t('email'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['email'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('phone'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['phone'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <div class="form-row" style="margin-top: 1.5rem;">
            <div>
                <strong><?php echo t('city'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['city'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('country'); ?>:</strong>
                <p><?php echo htmlspecialchars($client['country'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <?php if (!empty($client['address'])): ?>
        <div style="margin-top: 1.5rem;">
            <strong><?php echo t('address'); ?>:</strong>
            <p><?php echo htmlspecialchars($client['address']); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($client['notes'])): ?>
        <div style="margin-top: 1.5rem;">
            <strong><?php echo t('notes'); ?>:</strong>
            <p><?php echo htmlspecialchars($client['notes']); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Client Projects -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('projects'); ?></h2>
    </div>
    <div class="card-body">
        <?php if (count($projects) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('project_name'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('progress'); ?></th>
                    <th><?php echo t('start_date'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $project['status'] === 'completed' ? 'success' : ($project['status'] === 'in_progress' ? 'info' : 'warning'); ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                        </span>
                    </td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo $project['progress_percentage']; ?>%">
                                <?php echo $project['progress_percentage']; ?>%
                            </div>
                        </div>
                    </td>
                    <td><?php echo formatDate($project['start_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="color: var(--light-text);"><?php echo t('no_projects_found'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
