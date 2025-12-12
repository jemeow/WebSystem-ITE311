<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials - <?= esc($course['course_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-nav {
            background-color: #1d3557;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-nav .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff !important;
        }
        .header-nav .nav-link {
            color: rgba(255,255,255,0.8);
        }
        .header-nav .nav-link:hover {
            color: #fff;
        }
        .upload-area {
            border: 2px dashed #0d6efd;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9ff;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #0b5ed7;
            background-color: #e7f1ff;
        }
        .material-item {
            transition: all 0.3s;
        }
        .material-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/admin/dashboard') ?>">
                <i class="bi bi-mortarboard-fill"></i> ITE311-ZURITA
            </a>
            <div class="ms-auto">
                <a href="<?= site_url('/admin/courses') ?>" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Back to Courses
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Page Header -->
        <div class="mb-4">
            <h2><i class="bi bi-cloud-upload"></i> Upload Materials</h2>
            <p class="text-muted mb-0">
                Course: <strong><?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?></strong>
            </p>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Upload Form -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-arrow-up"></i> Upload New Material</h5>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('/admin/course/' . $course['id'] . '/upload') ?>
                            <div class="upload-area mb-3">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: #0d6efd;"></i>
                                <h5 class="mt-3">Choose a file to upload</h5>
                                <p class="text-muted small mb-3">Supported formats: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR</p>
                                <input type="file" class="form-control" id="material_file" name="material_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar" required>
                            </div>
                            
                            <div class="alert alert-info small">
                                <i class="bi bi-info-circle"></i> Maximum file size: 10 MB
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Upload Material
                                </button>
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Existing Materials List -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-files"></i> Uploaded Materials (<?= count($materials) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($materials)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                <h5 class="mt-3 text-muted">No materials uploaded yet</h5>
                                <p class="text-muted">Upload your first material using the form on the left.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($materials as $material): ?>
                                    <div class="list-group-item material-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                                    <?= esc($material['file_name']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> <?= date('M d, Y g:i A', strtotime($material['created_at'])) ?>
                                                </small>
                                            </div>
                                            <div class="btn-group">
                                                <a href="<?= site_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-outline-primary" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <a href="<?= site_url('/materials/delete/' . $material['id']) ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this material?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show selected filename
        document.getElementById('material_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const uploadArea = document.querySelector('.upload-area h5');
                uploadArea.textContent = 'Selected: ' + fileName;
            }
        });
    </script>
</body>
</html>
