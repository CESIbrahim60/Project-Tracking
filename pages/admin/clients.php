<?php
/**
 * Admin - Manage Clients
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('admin');

$page_title = t('manage_clients');

// Get all clients
// $sql = "SELECT * FROM clients ORDER BY created_at DESC";
// $result = $conn->query($sql);
// $users = $result->fetch_all(MYSQLI_ASSOC);

$clients = getAllClients();

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title"><?php echo t('manage_clients'); ?></h1>
            <p class="page-subtitle"><?php echo t('total_clients'); ?>: <?php echo count($clients); ?></p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addClientModal')">
            <span>➕</span> <?php echo t('add_client'); ?>
        </button>
    </div>
</div>

<!-- Clients Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($clients) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('company_name'); ?></th>
                    <th><?php echo t('contact_person'); ?></th>
                    <th><?php echo t('email'); ?></th>
                    <th><?php echo t('phone'); ?></th>
                    <th><?php echo t('city'); ?></th>
                    <th><?php echo t('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($client['contact_person'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($client['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($client['phone'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($client['city'] ?? 'N/A'); ?></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-info btn-sm" onclick="editClient(<?php echo $client['id']; ?>)">
                                ✏️ <?php echo t('edit'); ?>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteClient(<?php echo $client['id']; ?>)">
                                🗑️ <?php echo t('delete'); ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; color: var(--light-text);"><?php echo t('no_clients_found'); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal" id="addClientModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('add_client'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="addClientForm">
                <div class="form-group">
                    <label><?php echo t('company_name'); ?></label>
                    <input type="text" name="company_name" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('contact_person'); ?></label>
                    <input type="text" name="contact_person">
                </div>
                <div class="form-group">
    <label>User</label>
    <select name="user_id" required>
        <option value="">-- Select User --</option>
        <?php
        $users = getUsersByRole('client'); // أو أي رول مناسب
        foreach ($users as $u):
        ?>
            <option value="<?php echo $u['id']; ?>">
                <?php echo htmlspecialchars($u['full_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
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
                        <label><?php echo t('city'); ?></label>
                        <input type="text" name="city">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('country'); ?></label>
                        <input type="text" name="country">
                    </div>
                </div>
                <div class="form-group full">
                    <label><?php echo t('address'); ?></label>
                    <textarea name="address"></textarea>
                </div>
                <div class="form-group full">
                    <label><?php echo t('notes'); ?></label>
                    <textarea name="notes"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addClientModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitAddClient()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<script>
function submitAddClient() {
    const form = document.getElementById('addClientForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    fetch('/maysan/api/admin/add-client.php', {
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
            closeModal('addClientModal');
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

function editClient(clientId) {
    // TODO: Implement edit functionality
    alert('Edit functionality coming soon');
}

function deleteClient(clientId) {
    if (confirmDelete()) {
        fetch('/maysan/api/admin/delete-client.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: clientId })
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
