<?php
/**
 * Admin - Manage Users
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('admin');

$page_title = t('manage_users');

// Get all users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title"><?php echo t('manage_users'); ?></h1>
            <p class="page-subtitle"><?php echo t('total_users'); ?>: <?php echo count($users); ?></p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addUserModal')">
            <span>➕</span> <?php echo t('add_user'); ?>
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($users) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('username'); ?></th>
                    <th><?php echo t('full_name'); ?></th>
                    <th><?php echo t('email'); ?></th>
                    <th><?php echo t('role'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="badge badge-primary">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-info btn-sm" onclick="editUser(<?php echo $user['id']; ?>)">
                                ✏️ <?php echo t('edit'); ?>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                🗑️ <?php echo t('delete'); ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; color: var(--light-text);"><?php echo t('no_users_found'); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal" id="addUserModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('add_user'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="addUserForm">
                <div class="form-group">
                    <label><?php echo t('username'); ?></label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('full_name'); ?></label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('email'); ?></label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo t('phone'); ?></label>
                        <input type="tel" name="phone">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('password'); ?></label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo t('confirm_password'); ?></label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo t('role'); ?></label>
                    <select name="role" required>
                        <option value="client"><?php echo t('client'); ?></option>
                        <option value="technician"><?php echo t('technician'); ?></option>
                        <option value="sales"><?php echo t('sales'); ?></option>
                        <option value="admin"><?php echo t('admin'); ?></option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addUserModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitAddUser()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<script>
function submitAddUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    // Validate passwords match
    if (data.password !== data.confirm_password) {
        showAlert('Passwords do not match', 'danger');
        return;
    }

    fetch('/maysan/api/admin/add-user.php', {
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
            closeModal('addUserModal');
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

function editUser(userId) {
    alert('Edit functionality coming soon');
}

function deleteUser(userId) {
    if (confirmDelete()) {
        fetch('/maysan/api/admin/delete-user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: userId })
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
