<?php
/**
 * Technician - Project Detail
 * Maysan Al-Riyidh CCTV Security Systems
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/language.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole(['technician', 'admin']);

$current_user = getCurrentUser();
$project_id = $_GET['id'] ?? 0;

// Get project
$project = getProjectById($project_id);


// Verify technician is assigned to this project

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
    <a href="/maysan/pages/technician/projects.php" class="btn btn-outline btn-sm"><?php echo t('back'); ?></a>
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
                <strong><?php echo t('client'); ?>:</strong>
                <p><?php echo htmlspecialchars($project['company_name'] ?? 'N/A'); ?></p>
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
    </div>
</div>

<!-- Progress Updates -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php echo t('progress_updates'); ?></h2>
        <button class="btn btn-primary" onclick="openModal('updateModal')">
            ➕ <?php echo t('add_progress'); ?>
        </button>
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
        <button class="btn btn-primary" onclick="openModal('mediaModal')">
            ➕ <?php echo t('upload_media'); ?>
        </button>
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
    </div>
</div>

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

<!-- Progress Update Modal -->
<div class="modal" id="updateModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('add_progress'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="updateForm">
                <div class="form-group">
                    <label><?php echo t('progress_percentage'); ?></label>
                    <input type="number" name="progress_percentage" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('report'); ?></label>
                    <textarea name="report_text" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('updateModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitUpdate()"><?php echo t('save'); ?></button>
        </div>
    </div>
</div>

<!-- Media Upload Modal -->
<div class="modal" id="mediaModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?php echo t('upload_media'); ?></h2>
            <button class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <form id="mediaForm">
                <div class="form-group">
                    <label><?php echo t('media_type'); ?></label>
                    <select name="media_type" required>
                        <option value="image"><?php echo t('image'); ?></option>
                        <option value="video"><?php echo t('video'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo t('file'); ?></label>
                    <input type="file" name="file" required>
                </div>
                <div class="form-group">
                    <label><?php echo t('description'); ?></label>
                    <textarea name="description"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('mediaModal')"><?php echo t('cancel'); ?></button>
            <button class="btn btn-primary" onclick="submitMedia()"><?php echo t('upload'); ?></button>
        </div>
    </div>
</div>

<script>
function submitUpdate() {
    const form = document.getElementById('updateForm');
    const formData = new FormData(form);
    formData.append('project_id', <?php echo $project_id; ?>);

    fetch('/maysan/api/technician/add-progress.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeModal('updateModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showAlert('An error occurred', 'danger');
    });
}
function submitMedia() {
    const form = document.getElementById('mediaForm');
    const formData = new FormData(form);
    formData.append('project_id', <?php echo $project_id; ?>);

    fetch('/maysan/api/technician/upload-media.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text(); // اقرأ الاستجابة كنص
        try {
            const data = JSON.parse(text); // حاول تحويل النص إلى JSON
            if (data.success) {
                showAlert(data.message, 'success');
                closeModal('mediaModal');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message, 'danger');
            }
        } catch (e) {
            console.error('Invalid JSON:', text);
            showAlert('Server returned an invalid response', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'danger');
    });
}

</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
