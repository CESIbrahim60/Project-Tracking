<?php
/**
 * Sales - Manage Clients
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('sales');

$page_title = t('manage_clients');

// Get all active clients
$clients = getAllClients();

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?php echo t('manage_clients'); ?></h1>
        <p class="page-subtitle"><?php echo t('total_clients'); ?>: <?php echo count($clients); ?></p>
    </div>
</div>

<!-- Clients Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    <?php if (count($clients) > 0): ?>
        <?php foreach ($clients as $client): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo htmlspecialchars($client['company_name']); ?></h3>
            </div>
            <div class="card-body">
                <p><strong><?php echo t('contact_person'); ?>:</strong> <?php echo htmlspecialchars($client['contact_person'] ?? 'N/A'); ?></p>
                <p><strong><?php echo t('email'); ?>:</strong> <?php echo htmlspecialchars($client['email'] ?? 'N/A'); ?></p>
                <p><strong><?php echo t('phone'); ?>:</strong> <?php echo htmlspecialchars($client['phone'] ?? 'N/A'); ?></p>
                <p><strong><?php echo t('city'); ?>:</strong> <?php echo htmlspecialchars($client['city'] ?? 'N/A'); ?></p>
                
                <?php if (!empty($client['notes'])): ?>
                <div style="margin-top: 1rem; padding: 0.75rem; background-color: var(--light-bg); border-radius: 4px;">
                    <strong><?php echo t('notes'); ?>:</strong>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;"><?php echo htmlspecialchars(substr($client['notes'], 0, 100)); ?>...</p>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="/maysan/pages/sales/client-detail.php?id=<?php echo $client['id']; ?>" class="btn btn-primary btn-sm">
                    👁️ <?php echo t('view_details'); ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-body" style="text-align: center; color: var(--light-text);">
            <p><?php echo t('no_clients_found'); ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
