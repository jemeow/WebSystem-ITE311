<?php
$this->extend('template');
$this->section('content');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><?= esc($course['course_name']) ?></h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-code-square"></i>
                        <?= esc($course['course_code']) ?>
                    </p>
                </div>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Course Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Course Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Instructor:</strong> 
                        <?php
                        // Display teacher name if available
                        if (!empty($course['teacher_id'])) {
                            echo 'Teacher ID: ' . $course['teacher_id'];
                        }
                        ?>
                    </p>
                    <p class="mb-0">
                        <strong>Status:</strong> 
                        <span class="badge bg-success">Enrolled</span>
                    </p>
                </div>
            </div>

            <!-- Course Materials -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-files"></i> Course Materials
                        <span class="badge bg-light text-dark float-end"><?= count($materials) ?> file(s)</span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(empty($materials)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">No materials available yet for this course.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                // Get file extension
                                                $filePath = $material['file_path'];
                                                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                
                                                // Determine icon and color based on file type
                                                $iconClass = 'bi-file-earmark';
                                                $iconColor = 'text-secondary';
                                                
                                                switch($ext) {
                                                    case 'pdf':
                                                        $iconClass = 'bi-file-earmark-pdf';
                                                        $iconColor = 'text-danger';
                                                        break;
                                                    case 'doc':
                                                    case 'docx':
                                                        $iconClass = 'bi-file-earmark-word';
                                                        $iconColor = 'text-primary';
                                                        break;
                                                    case 'xls':
                                                    case 'xlsx':
                                                        $iconClass = 'bi-file-earmark-spreadsheet';
                                                        $iconColor = 'text-success';
                                                        break;
                                                    case 'ppt':
                                                    case 'pptx':
                                                        $iconClass = 'bi-file-earmark-slides';
                                                        $iconColor = 'text-warning';
                                                        break;
                                                    case 'zip':
                                                    case 'rar':
                                                        $iconClass = 'bi-file-earmark-zip';
                                                        $iconColor = 'text-muted';
                                                        break;
                                                }
                                                ?>
                                                <i class="bi <?= $iconClass ?> <?= $iconColor ?>"></i>
                                                <strong><?= esc($material['file_name']) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M d, Y @ g:i A', strtotime($material['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Download this file">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Helpful Tips -->
            <div class="alert alert-info mt-4" role="alert">
                <i class="bi bi-lightbulb"></i> 
                <strong>Tip:</strong> Click the download button to save files to your device. All materials are provided by your instructor.
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
