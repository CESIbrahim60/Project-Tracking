<?php
/**
 * Admin - Manage Projects
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('admin');

$page_title = t('manage_projects');

// Get all projects with client info
$sql = "SELECT p.*, c.company_name, t.full_name as technician_name, s.full_name as sales_name 
        FROM projects p 
        LEFT JOIN clients c ON p.client_id = c.id 
        LEFT JOIN users t ON p.assigned_technician_id = t.id 
        LEFT JOIN users s ON p.assigned_sales_id = s.id 
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
$projects = $result->fetch_all(MYSQLI_ASSOC);

// Get clients and users for dropdowns
$clients = getAllClients();
$technicians = getUsersByRole('technician');
$sales_users = getUsersByRole('sales');

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title"><?php echo t('manage_projects'); ?></h1>
            <p class="page-subtitle"><?php echo t('total_projects'); ?>: <?php echo count($projects); ?></p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addProjectModal')">
            <span>➕</span> <?php echo t('add_project'); ?>
        </button>
    </div>
</div>

<!-- Projects Table -->
<div class="card">
    <div class="card-body">
        <?php if (count($projects) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo t('project_name'); ?></th>
                    <th><?php echo t('client'); ?></th>
                    <th><?php echo t('progress'); ?></th>
                    <th><?php echo t('status'); ?></th>
                    <th><?php echo t('technician'); ?></th>
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
                            <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($project['technician_name'] ?? 'Unassigned'); ?></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-info btn-sm" onclick="editProject(<?php echo $project['id']; ?>)">
                                ✏️ <?php echo t('edit'); ?>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteProject(<?php echo $project['id']; ?>)">
                                🗑️ <?php echo t('delete'); ?>
                            </button>
                        </div>
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

<!-- Add Project Modal -->
<div class="modal" id="addProjectModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('add_project'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="addProjectForm">
                <div class="form-group">
                    <label><?php echo t('project_name'); ?></label>
                    <input type="text" name="project_name" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('client'); ?></label>
                    <select name="client_id" required>
                        <option value="">-- <?php echo t('select_client'); ?> --</option>
                        <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['company_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo t('client_user'); ?></label>
                    <select name="user_id" required>
                        <option value="">-- <?php echo t('select_user'); ?> --</option>
                        <?php
                        $client_users = getUsersByRole('client'); // دالة موجودة مسبقاً
                        foreach ($client_users as $u):
                        ?>
                            <option value="<?php echo $u['id']; ?>">
                                <?php echo htmlspecialchars($u['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('project_type'); ?></label>
                        <input type="text" name="project_type">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('location'); ?></label>
                        <input type="text" name="location">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('start_date'); ?></label>
                        <input type="date" name="start_date">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('end_date'); ?></label>
                        <input type="date" name="end_date">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('budget'); ?></label>
                        <input type="number" name="budget" step="0.01">
                    </div>
                    <div class="form-group">
                        <label><?php echo t('status'); ?></label>
                        <select name="status">
                            <option value="planning"><?php echo t('planning'); ?></option>
                            <option value="in_progress"><?php echo t('in_progress'); ?></option>
                            <option value="on_hold"><?php echo t('on_hold'); ?></option>
                            <option value="completed"><?php echo t('completed'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?php echo t('assigned_technician'); ?></label>
                        <select name="assigned_technician_id">
                            <option value="">-- <?php echo t('unassigned'); ?> --</option>
                            <?php foreach ($technicians as $tech): ?>
                            <option value="<?php echo $tech['id']; ?>"><?php echo htmlspecialchars($tech['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo t('assigned_sales'); ?></label>
                        <select name="assigned_sales_id">
                            <option value="">-- <?php echo t('unassigned'); ?> --</option>
                            <?php foreach ($sales_users as $sales): ?>
                            <option value="<?php echo $sales['id']; ?>"><?php echo htmlspecialchars($sales['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group full">
                    <label><?php echo t('description'); ?></label>
                    <textarea name="description"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addProjectModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitAddProject()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<script>
function submitAddProject() {
    const form = document.getElementById('addProjectForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    fetch('/maysan/api/admin/add-project.php', {
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
            closeModal('addProjectModal');
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

function editProject(projectId) {
    alert('Edit functionality coming soon');
}

function deleteProject(projectId) {
    if (confirmDelete()) {
        fetch('/maysan/api/admin/delete-project.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: projectId })
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
