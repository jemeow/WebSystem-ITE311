<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($course['course_code']) ?> - Course Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-nav {
            background: #133980;
            box-shadow: none;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .header-nav .navbar-brand {
            font-size: 1.25rem;
            font-weight: 400;
            color: #fff !important;
        }
        .header-nav .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.5rem 1rem;
            border-radius: 2px;
            margin: 0 0.2rem;
            transition: background 0.2s;
            font-weight: 400;
        }
        .header-nav .nav-link:hover,
        .header-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.2);
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0;
            box-shadow: none;
            background: white;
        }
        .btn {
            border-radius: 0;
            padding: 0.5rem 1rem;
            font-weight: 400;
            transition: background 0.15s;
            border: none;
            font-size: 0.875rem;
        }
        .btn-primary {
            background: #2C5F8D;
            color: white;
        }
        .btn-primary:hover {
            background: #234a6d;
            color: white;
        }
        .btn-warning {
            background: #6366F1;
            color: white;
        }
        .btn-warning:hover {
            background: #4F46E5;
            color: white;
        }
        .btn-success {
            background: #48A868;
            color: white;
        }
        .btn-success:hover {
            background: #3d8f57;
            color: white;
        }
        .btn-danger {
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
        .btn-danger:hover {
            background: #f3f4f6;
            color: #6b7280;
        }
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 0;
            padding: 0.5rem 0.75rem;
            transition: none;
            font-size: 0.875rem;
            color: #374151;
        }
        .form-control:focus {
            border-color: #2C5F8D;
            box-shadow: none;
            outline: none;
        }
        .table {
            border: none;
        }
        .table thead th {
            border-bottom: 1px solid #e5e7eb;
            background: white;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.85rem;
            padding: 0.75rem;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #f9fafb;
        }
        .table td {
            border-top: 1px solid #f3f4f6;
            padding: 0.75rem;
            vertical-align: middle;
            color: #374151;
            font-size: 0.9rem;
        }
        h1, h2, h3, h4, h5 {
            color: #1f2937;
            font-weight: 400;
        }
        .text-muted {
            color: #6b7280 !important;
        }
        .alert {
            border: none;
            border-radius: 0;
            border-left: 3px solid #2C5F8D;
        }
        .badge {
            padding: 0.4rem 0.75rem;
            font-weight: 400;
            border-radius: 0;
            font-size: 0.85rem;
        }
        .badge.bg-warning {
            background: #FDE047 !important;
            color: #78350F;
        }
        .badge.bg-success {
            background: #D1FAE5 !important;
            color: #065F46;
        }
        .badge.bg-danger {
            background: #FEE2E2 !important;
            color: #991B1B;
        }
        .badge.bg-secondary {
            background: #E5E7EB !important;
            color: #374151;
        }
        .stat-card {
            border-left: 3px solid #2C5F8D;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('dashboard') ?>">
                <i class="bi bi-mortarboard-fill"></i> ITE311-ZURITA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">
                            <i class="bi bi-people"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/courses') ?>">
                            <i class="bi bi-book"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/enrollments') ?>">
                            <i class="bi bi-journals"></i> Enrollments
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">                    <?php include(APPPATH . 'Views/components/notification_bell.php'); ?>                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('profile/edit') ?>">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <main>
            <!-- Course Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="mb-2">
                                <a href="<?= site_url('admin/courses') ?>" class="btn btn-sm btn-outline-secondary mb-2">
                                    <i class="bi bi-arrow-left"></i> Back to Courses
                                </a>
                            </div>
                            <h2 class="mb-2">
                                <span class="badge bg-warning text-dark"><?= esc($course['course_code']) ?></span>
                                <?= esc($course['course_name']) ?>
                            </h2>
                            <p class="text-muted mb-2"><?= esc($course['description']) ?></p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-<?= $course['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($course['status']) ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-award"></i> <?= $course['credits'] ?> Credits
                                </small>
                                <?php if (!empty($course['schedule_days'])): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                    </small>
                                    <?php if (!empty($course['schedule_start_time']) && !empty($course['schedule_end_time'])): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                    </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (!empty($course['teacher_name'])): ?>
                                    <small class="text-muted">
                                        <i class="bi bi-person-badge"></i> Instructor: <?= esc($course['teacher_name']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-1 text-primary"></i>
                            <h3 class="mt-2"><?= $totalEnrolled ?></h3>
                            <p class="text-muted mb-0">Enrolled Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-files fs-1 text-info"></i>
                            <h3 class="mt-2"><?= count($materials) ?></h3>
                            <p class="text-muted mb-0">Course Materials</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload New Material -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload New Material</h5>
                </div>
                <div class="card-body">
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
                    
                    <div id="uploadStatus"></div>
                    
                    <div id="progressContainer" style="display: none;" class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small"><strong>Upload Progress</strong></span>
                            <span id="progressPercent" class="small text-muted">0%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div id="progressBar" class="progress-bar progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <form id="uploadForm">
                        <div class="mb-3">
                            <label for="material_file" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="material_file" name="material_file" required>
                            <small class="text-muted d-block mt-1">
                                PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR (Max 100MB)
                            </small>
                        </div>
                        <button type="button" class="btn btn-warning" id="uploadBtn">
                            <i class="bi bi-upload"></i> Upload Material
                        </button>
                    </form>
                    
                    <script>
                    document.getElementById('uploadBtn').addEventListener('click', function() {
                        const file = document.getElementById('material_file').files[0];
                        if (!file) {
                            alert('Please select a file first');
                            return;
                        }
                        
                        // Validate file size (100MB)
                        if (file.size > 102400000) {
                            document.getElementById('uploadStatus').innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> File is too large. Maximum size is 100MB.</div>';
                            return;
                        }
                        
                        const formData = new FormData();
                        formData.append('material_file', file);
                        
                        const statusDiv = document.getElementById('uploadStatus');
                        const progressContainer = document.getElementById('progressContainer');
                        const progressBar = document.getElementById('progressBar');
                        const progressPercent = document.getElementById('progressPercent');
                        
                        statusDiv.innerHTML = '';
                        progressContainer.style.display = 'block';
                        progressBar.style.width = '0%';
                        progressPercent.textContent = '0%';
                        
                        // Create XMLHttpRequest for upload tracking
                        const xhr = new XMLHttpRequest();
                        
                        // Track upload progress
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percentComplete = (e.loaded / e.total) * 100;
                                progressBar.style.width = percentComplete + '%';
                                progressPercent.textContent = Math.round(percentComplete) + '%';
                            }
                        });
                        
                        // Handle completion
                        xhr.addEventListener('load', function() {
                            progressContainer.style.display = 'none';
                            
                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        statusDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' + response.message + '</div>';
                                        document.getElementById('material_file').value = '';
                                        setTimeout(() => location.reload(), 1500);
                                    } else {
                                        statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' + response.message + '</div>';
                                    }
                                } catch (e) {
                                    statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Invalid server response: ' + xhr.responseText.substring(0, 100) + '</div>';
                                }
                            } else {
                                statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Upload failed with status: ' + xhr.status + '</div>';
                            }
                        });
                        
                        // Handle errors
                        xhr.addEventListener('error', function() {
                            progressContainer.style.display = 'none';
                            statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Network error during upload</div>';
                        });
                        
                        xhr.addEventListener('abort', function() {
                            progressContainer.style.display = 'none';
                            statusDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Upload was cancelled</div>';
                        });
                        
                        // Send request
                        xhr.open('POST', '<?= base_url("admin/course/" . $course["id"]) ?>');
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.send(formData);
                    });
                    </script>
                </div>
            </div>

            <!-- Course Materials -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-files"></i> Course Materials (<?= count($materials) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($materials)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">No materials uploaded yet. Use the form above to upload your first material.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Uploaded Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($materials as $material): ?>
                                        <?php 
                                        // Get file extension and determine icon
                                        $filePath = $material['file_path'];
                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        
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
                                        <tr>
                                            <td>
                                                <i class="bi <?= $iconClass ?> <?= $iconColor ?>"></i>
                                                <?= esc($material['file_name']) ?>
                                            </td>
                                            <td><?= date('M d, Y g:i A', strtotime($material['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= site_url('materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                    <a href="<?= site_url('materials/delete/' . $material['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this material?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Enrolled Students -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-people-fill"></i> Enrolled Students (<?= $totalEnrolled ?>)</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $approvedEnrollments = array_filter($enrollments, function($e) { 
                        return $e['status'] === 'approved'; 
                    });
                    ?>
                    <?php if(empty($approvedEnrollments)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 text-muted">No students enrolled yet</h5>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Enrollment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($approvedEnrollments as $enrollment): ?>
                                        <tr>
                                            <td><i class="bi bi-person-circle text-success"></i> <?= esc($enrollment['student_name']) ?></td>
                                            <td><?= esc($enrollment['student_email']) ?></td>
                                            <td><?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include(APPPATH . 'Views/components/notification_js.php'); ?>
</body>
</html>
