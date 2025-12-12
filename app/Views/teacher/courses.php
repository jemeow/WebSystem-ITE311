<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Teacher Panel</title>
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
        .course-card {
            transition: all 0.3s;
            border-left: 4px solid #ffc107;
            cursor: pointer;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/dashboard') ?>">
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
                        <a class="nav-link active" href="<?= site_url('teacher/courses') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('teacher/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Enrollments
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('/profile/edit') ?>">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <main>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-book"></i> My Courses</h2>
                    <p class="text-muted mb-0">View and manage your assigned courses</p>
                </div>
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

            <!-- Courses Grid -->
            <div class="row g-4">
                <?php if(empty($courses)): ?>
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                <h5 class="mt-3">No Courses Assigned Yet</h5>
                                <p class="text-muted">Contact your administrator to get courses assigned to you.</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($courses as $course): ?>
                        <div class="col-md-6 col-lg-4">
                            <a href="<?= site_url('teacher/course/' . $course['id']) ?>" class="text-decoration-none text-dark">
                                <div class="card course-card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <span class="badge bg-warning text-dark"><?= esc($course['course_code']) ?></span>
                                                </h5>
                                                <h6 class="mb-2 text-dark"><?= esc($course['course_name']) ?></h6>
                                            </div>
                                            <span class="badge bg-<?= $course['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($course['status']) ?>
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-3"><?= esc($course['description']) ?></p>
                                        
                                        <!-- Schedule Info -->
                                        <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                            <div class="mb-3 p-2" style="background: #fff3cd; border-radius: 4px;">
                                                <small class="fw-bold text-dark">
                                                    <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                </small>
                                                <br>
                                                <small class="fw-bold text-dark">
                                                    <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Stats -->
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <i class="bi bi-people text-primary"></i>
                                                    <h6 class="mb-0 mt-1"><?= $course['enrollment_count'] ?></h6>
                                                    <small class="text-muted">Students</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <i class="bi bi-files text-info"></i>
                                                    <h6 class="mb-0 mt-1"><?= $course['material_count'] ?></h6>
                                                    <small class="text-muted">Materials</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-award"></i> <?= $course['credits'] ?> Credits
                                            </small>
                                            <small class="text-primary fw-bold">
                                                View Details <i class="bi bi-arrow-right"></i>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
