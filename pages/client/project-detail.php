<?php
/**
 * Client - Project Detail
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('client');

$current_user = getCurrentUser();
$project_id = $_GET['id'] ?? 0;

// Get client info
$sql = "SELECT * FROM clients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user['id']);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

// Get project
$project = getProjectById($project_id);

// Verify client owns this project
if (!$project || $project['client_id'] != $client['id']) {
    header('Location: /maysan/pages/client/projects.php');
    exit();
}

$page_title = htmlspecialchars($project['project_name']);

// Get project media
$media = getProjectMedia($project_id);

// Get progress updates
$updates = getProgressUpdates($project_id);

// Get feedback
$feedback = getProjectFeedback($project_id);

include __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <a href="/maysan/pages/client/projects.php" class="btn btn-outline btn-sm"><?php echo t('back'); ?></a>
    <h1 class="page-title"><?php echo htmlspecialchars($project['project_name']); ?></h1>
</div>

<!-- Project Overview -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('project_details'); ?></h2>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div>
                <strong><?php echo t('project_type'); ?>:</strong>
                <p><?php echo htmlspecialchars($project['project_type'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('location'); ?>:</strong>
                <p><?php echo htmlspecialchars($project['location'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <strong><?php echo t('status'); ?>:</strong>
                <p>
                    <span class="badge badge-<?php echo $project['status'] === 'completed' ? 'success' : ($project['status'] === 'in_progress' ? 'info' : 'warning'); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                    </span>
                </p>
            </div>
        </div>

        <div style="margin-top: 1.5rem;">
            <strong><?php echo t('progress'); ?>:</strong>
            <div class="progress" style="margin-top: 0.5rem;">
                <div class="progress-bar" style="width: <?php echo $project['progress_percentage']; ?>%">
                    <?php echo $project['progress_percentage']; ?>%
                </div>
            </div>
        </div>

        <?php if (!empty($project['description'])): ?>
        <div style="margin-top: 1.5rem;">
            <strong><?php echo t('description'); ?>:</strong>
            <p><?php echo htmlspecialchars($project['description']); ?></p>
        </div>
        <?php endif; ?>

        <div class="form-row" style="margin-top: 1.5rem;">
            <?php if (!empty($project['start_date'])): ?>
            <div>
                <strong><?php echo t('start_date'); ?>:</strong>
                <p><?php echo formatDate($project['start_date']); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($project['end_date'])): ?>
            <div>
                <strong><?php echo t('end_date'); ?>:</strong>
                <p><?php echo formatDate($project['end_date']); ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($project['budget'])): ?>
            <div>
                <strong><?php echo t('budget'); ?>:</strong>
                <p><?php echo formatCurrency($project['budget']); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Progress Updates -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('progress_updates'); ?></h2>
    </div>
    <div class="card-body">
        <?php if (count($updates) > 0): ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($updates as $update): ?>
            <div style="padding: 1rem; background-color: var(--light-bg); border-radius: 4px; border-left: 4px solid var(--primary-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong><?php echo htmlspecialchars($update['full_name'] ?? 'Technician'); ?></strong>
                    <small style="color: var(--light-text);"><?php echo formatDate($update['update_date']); ?></small>
                </div>
                <p><strong><?php echo t('progress'); ?>:</strong> <?php echo $update['progress_percentage']; ?>%</p>
                <?php if (!empty($update['report_text'])): ?>
                <p style="margin-top: 0.5rem;"><?php echo htmlspecialchars($update['report_text']); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="color: var(--light-text);"><?php echo t('no_updates'); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Project Media -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('project_media'); ?></h2>
    </div>
<div class="card-body">
        <?php if (count($media) > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
            <?php foreach ($media as $file): ?>
            <div style="border: 1px solid var(--border-color); border-radius: 4px; overflow: hidden; position: relative;">
            <?php
                // افتراضياً اسم الملف موجود في $file['file_path']
                $fileUrl = '/maysan/assets/uploads/' . basename($file['file_path']);        
                if ($file['media_type'] === 'image'): 
            ?>
            <img src="<?php echo htmlspecialchars($fileUrl); ?>" 
             alt="<?php echo htmlspecialchars($file['file_name']); ?>" 
             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;" 
             onclick="openMediaModal('<?php echo htmlspecialchars($fileUrl); ?>', 'image')">
            <?php else: ?>
                <video style="width: 100%; height: 200px; object-fit: cover; cursor:pointer;" controls
                    onclick="openMediaModal('<?php echo htmlspecialchars($fileUrl); ?>', 'video')">
                    <source src="<?php echo htmlspecialchars($fileUrl); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
            <div style="padding: 0.75rem;">
                <small style="color: var(--light-text);"><?php echo htmlspecialchars($file['full_name'] ?? 'Unknown'); ?></small>
            </div>
        </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="color: var(--light-text);"><?php echo t('no_media'); ?></p>
        <?php endif; ?>
    </div></div>

    <!-- Modal لتكبير الصور والفيديو -->
<div class="modal" id="mediaViewerModal">
    <div class="modal-content" style="max-width: 90%; max-height: 90%;">
        <span class="modal-close" onclick="closeMediaModal()" style="position:absolute; top:10px; right:20px; cursor:pointer; font-size:24px;">✕</span>
        <img id="mediaViewerImage" style="width:100%; display:none;">
        <video id="mediaViewerVideo" controls style="width:100%; display:none;">
            <source id="mediaViewerVideoSource" type="video/mp4">
        </video>
    </div>
</div>

<script>
function openMediaModal(url, type) {
    const modal = document.getElementById('mediaViewerModal');
    const img = document.getElementById('mediaViewerImage');
    const video = document.getElementById('mediaViewerVideo');
    const source = document.getElementById('mediaViewerVideoSource');

    if (type === 'image') {
        img.src = url;
        img.style.display = 'block';
        video.style.display = 'none';
    } else {
        source.src = url;
        video.load();
        video.style.display = 'block';
        img.style.display = 'none';
    }

    modal.style.display = 'flex';
}

function closeMediaModal() {
    const modal = document.getElementById('mediaViewerModal');
    const video = document.getElementById('mediaViewerVideo');
    video.pause();
    modal.style.display = 'none';
}
</script>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.8);
    justify-content: center;
    align-items: center;
}
.modal-content {
    position: relative;
}
</style>

<!-- Feedback Section -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('feedback'); ?></h2>
    </div>
    <div class="card-body">
        <button class="btn btn-primary" onclick="openModal('feedbackModal')" style="margin-bottom: 1.5rem;">
            ➕ <?php echo t('send_feedback'); ?>
        </button>

        <?php if (count($feedback) > 0): ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($feedback as $item): ?>
            <div style="padding: 1rem; background-color: var(--light-bg); border-radius: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong><?php echo htmlspecialchars($item['full_name'] ?? 'User'); ?></strong>
                    <small style="color: var(--light-text);"><?php echo formatDate($item['created_at']); ?></small>
                </div>
                <p style="margin: 0.5rem 0;"><?php echo htmlspecialchars($item['feedback_text']); ?></p>
                <span class="badge badge-info"><?php echo ucfirst($item['feedback_type']); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="color: var(--light-text);"><?php echo t('no_feedback'); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal" id="feedbackModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('send_feedback'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="feedbackForm">
                <div class="form-group">
                    <label><?php echo t('feedback_type'); ?></label>
                    <select name="feedback_type">
                        <option value="note"><?php echo t('note'); ?></option>
                        <option value="issue"><?php echo t('issue'); ?></option>
                        <option value="suggestion"><?php echo t('suggestion'); ?></option>
                        <option value="approval"><?php echo t('approval'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo t('message'); ?></label>
                    <textarea name="feedback_text" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('feedbackModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitFeedback()"><?php echo t('send'); ?></button>
        </div>
    </div>
</div>

<script>
function submitFeedback() {
    const form = document.getElementById('feedbackForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    data.project_id = <?php echo $project_id; ?>;

    fetch('/maysan/api/client/add-feedback.php', {
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
            closeModal('feedbackModal');
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
