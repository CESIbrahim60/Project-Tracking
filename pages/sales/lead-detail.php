<?php
/**
 * Sales - Lead Detail
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('sales');

$current_user = getCurrentUser();
$lead_id = $_GET['id'] ?? 0;

// Get lead
$sql = "SELECT * FROM leads WHERE id = ? AND assigned_sales_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $lead_id, $current_user['id']);
$stmt->execute();
$lead = $stmt->get_result()->fetch_assoc();

if (!$lead) {
    header('Location: /maysan/pages/sales/leads.php');
    exit();
}

$page_title = htmlspecialchars($lead['lead_name']);

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <a href="/maysan/pages/sales/leads.php" class="btn btn-outline btn-sm"><?php echo t('back'); ?></a>
    <h1 class="page-title"><?php echo htmlspecialchars($lead['lead_name']); ?></h1>
</div>

<!-- Lead Details -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('lead_details'); ?></h2>
        <button class="btn btn-primary" onclick="openModal('updateLeadModal')">
            ✏️ <?php echo t('edit'); ?>
        </button>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div>
                <strong><?php echo t('lead_name'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['lead_name']); ?></p>
            </div>
            <div>
                <strong><?php echo t('company_name'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['company_name'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('status'); ?>:</strong>
                <p>
                    <span class="badge badge-<?php echo $lead['status'] === 'won' ? 'success' : ($lead['status'] === 'lost' ? 'danger' : 'info'); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $lead['status'])); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="form-row" style="margin-top: 1.5rem;">
            <div>
                <strong><?php echo t('email'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['email'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('phone'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['phone'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <div class="form-row" style="margin-top: 1.5rem;">
            <div>
                <strong><?php echo t('project_type'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['project_type'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('budget_range'); ?>:</strong>
                <p><?php echo htmlspecialchars($lead['budget_range'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <?php if (!empty($lead['notes'])): ?>
        <div style="margin-top: 1.5rem;">
            <strong><?php echo t('notes'); ?>:</strong>
            <p><?php echo htmlspecialchars($lead['notes']); ?></p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <small style="color: var(--light-text);">
                <strong><?php echo t('created'); ?>:</strong> <?php echo formatDate($lead['created_at']); ?><br>
                <strong><?php echo t('updated'); ?>:</strong> <?php echo formatDate($lead['updated_at']); ?>
            </small>
        </div>
    </div>
</div>

<!-- Update Lead Modal -->
<div class="modal" id="updateLeadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('edit_lead'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="updateLeadForm">
                <div class="form-group">
                    <label><?php echo t('lead_name'); ?></label>
                    <input type="text" name="lead_name" value="<?php echo htmlspecialchars($lead['lead_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('company_name'); ?></label>
                    <input type="text" name="company_name" value="<?php echo htmlspecialchars($lead['company_name'] ?? ''); ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('email'); ?></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($lead['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('phone'); ?></label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($lead['phone'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('project_type'); ?></label>
                        <input type="text" name="project_type" value="<?php echo htmlspecialchars($lead['project_type'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('budget_range'); ?></label>
                        <input type="text" name="budget_range" value="<?php echo htmlspecialchars($lead['budget_range'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo t('status'); ?></label>
                    <select name="status">
                        <option value="new" <?php echo $lead['status'] === 'new' ? 'selected' : ''; ?>><?php echo t('new'); ?></option>
                        <option value="contacted" <?php echo $lead['status'] === 'contacted' ? 'selected' : ''; ?>><?php echo t('contacted'); ?></option>
                        <option value="qualified" <?php echo $lead['status'] === 'qualified' ? 'selected' : ''; ?>><?php echo t('qualified'); ?></option>
                        <option value="proposal_sent" <?php echo $lead['status'] === 'proposal_sent' ? 'selected' : ''; ?>><?php echo t('proposal_sent'); ?></option>
                        <option value="negotiation" <?php echo $lead['status'] === 'negotiation' ? 'selected' : ''; ?>><?php echo t('negotiation'); ?></option>
                        <option value="won" <?php echo $lead['status'] === 'won' ? 'selected' : ''; ?>><?php echo t('won'); ?></option>
                        <option value="lost" <?php echo $lead['status'] === 'lost' ? 'selected' : ''; ?>><?php echo t('lost'); ?></option>
                    </select>
                </div>
                <div class="form-group full">
                    <label><?php echo t('notes'); ?></label>
                    <textarea name="notes"><?php echo htmlspecialchars($lead['notes'] ?? ''); ?></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('updateLeadModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitUpdateLead()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<script>
function submitUpdateLead() {
    const form = document.getElementById('updateLeadForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    data.id = <?php echo $lead_id; ?>;

    fetch('/maysan/api/sales/update-lead.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeModal('updateLeadModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
