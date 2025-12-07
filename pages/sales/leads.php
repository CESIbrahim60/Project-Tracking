<?php
/**
 * Sales - Manage Leads
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('sales');

$page_title = t('manage_leads');
$current_user = getCurrentUser();

// Get all leads assigned to this sales person
$sql = "SELECT * FROM leads WHERE assigned_sales_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user['id']);
$stmt->execute();
$leads = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title"><?php echo t('manage_leads'); ?></h1>
            <p class="page-subtitle"><?php echo t('total_leads'); ?>: <?php echo count($leads); ?></p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addLeadModal')">
            <span>➕</span> <?php echo t('add_lead'); ?>
        </button>
    </div>
</div>

<!-- Leads Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($leads) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('lead_name'); ?></th>
                    <th><?php echo t('company_name'); ?></th>
                    <th><?php echo t('email'); ?></th>
                    <th><?php echo t('phone'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lead['lead_name']); ?></td>
                    <td><?php echo htmlspecialchars($lead['company_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($lead['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($lead['phone'] ?? 'N/A'); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $lead['status'] === 'won' ? 'success' : ($lead['status'] === 'lost' ? 'danger' : 'info'); ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $lead['status'])); ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="/maysan/pages/sales/lead-detail.php?id=<?php echo $lead['id']; ?>" class="btn btn-info btn-sm">
                                👁️ <?php echo t('view'); ?>
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="deleteLead(<?php echo $lead['id']; ?>)">
                                🗑️ <?php echo t('delete'); ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; color: var(--light-text);"><?php echo t('no_leads_found'); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Add Lead Modal -->
<div class="modal" id="addLeadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('add_lead'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="addLeadForm">
                <div class="form-group">
                    <label><?php echo t('lead_name'); ?></label>
                    <input type="text" name="lead_name" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('company_name'); ?></label>
                    <input type="text" name="company_name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('email'); ?></label>
                        <input type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('phone'); ?></label>
                        <input type="tel" name="phone">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('project_type'); ?></label>
                        <input type="text" name="project_type">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('budget_range'); ?></label>
                        <input type="text" name="budget_range">
                    </div>
                </div>
                <div class="form-group full">
                    <label><?php echo t('notes'); ?></label>
                    <textarea name="notes"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addLeadModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitAddLead()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<script>
function submitAddLead() {
    const form = document.getElementById('addLeadForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    fetch('/maysan/api/sales/add-lead.php', {
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
            closeModal('addLeadModal');
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

function deleteLead(leadId) {
    if (confirmDelete()) {
        fetch('/maysan/api/sales/delete-lead.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: leadId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
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
}
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
