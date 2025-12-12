<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ITE311-ZURITA</title>
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
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            margin: 0 0.1rem;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        .header-nav .nav-link:hover,
        .header-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
        .stat-card-primary {
            border-left-color: #0d6efd;
        }
        .stat-card-danger {
            border-left-color: #e63946;
        }
        .stat-card-warning {
            border-left-color: #f77f00;
        }
        .stat-card-success {
            border-left-color: #06d6a0;
        }
        .quick-action-card {
            transition: all 0.3s;
            cursor: pointer;
        }
        .quick-action-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
        .hover-shadow {
            transition: all 0.3s;
            cursor: pointer;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">
                <i class="bi bi-mortarboard-fill"></i> ITE311-ZURITA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/users') ?>">
                            <i class="bi bi-people"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/courses') ?>">
                            <i class="bi bi-book"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/enrollments/dashboard') ?>">
                            <i class="bi bi-clipboard-data"></i> Enrollment Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Manage Enrollments
                            <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= $pendingCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
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

    <!-- Main Content -->
    <div class="container-fluid px-4 py-4">
        <main>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
                    </div>
                    <div>
                        <span class="badge bg-danger px-3 py-2 fs-6">
                            <i class="bi bi-shield-check"></i> Administrator
                        </span>
                    </div>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card stat-card-primary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Users</p>
                                        <h3 class="mb-0 fw-bold"><?= $totalUsers ?></h3>
                                        <small class="text-success">
                                            <i class="bi bi-arrow-up"></i> Active system
                                        </small>
                                    </div>
                                    <div class="fs-1 text-primary">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card stat-card-danger shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Administrators</p>
                                        <h3 class="mb-0 fw-bold"><?= $totalAdmins ?></h3>
                                        <small class="text-muted">System admins</small>
                                    </div>
                                    <div class="fs-1 text-danger">
                                        <i class="bi bi-shield-fill-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card stat-card-warning shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Teachers</p>
                                        <h3 class="mb-0 fw-bold"><?= $totalTeachers ?></h3>
                                        <small class="text-muted">Active instructors</small>
                                    </div>
                                    <div class="fs-1 text-warning">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card stat-card-success shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Students</p>
                                        <h3 class="mb-0 fw-bold"><?= $totalStudents ?></h3>
                                        <small class="text-muted">Enrolled learners</small>
                                    </div>
                                    <div class="fs-1 text-success">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Enrollments Alert Card -->
                <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                <div class="card shadow-sm border-warning mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="fs-1 text-warning me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">
                                            <span class="badge bg-warning text-dark fs-6"><?= $pendingCount ?></span>
                                            Pending Enrollment<?= $pendingCount > 1 ? 's' : '' ?> Awaiting Approval
                                        </h5>
                                        <p class="text-muted mb-0">
                                            <?= $pendingCount ?> student<?= $pendingCount > 1 ? 's have' : ' has' ?> requested to enroll in courses and need<?= $pendingCount > 1 ? '' : 's' ?> your approval.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?= site_url('admin/enrollments/pending-view') ?>" class="btn btn-warning btn-lg">
                                    <i class="bi bi-list-check"></i> Review Requests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bi bi-lightning-fill text-warning"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/users') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-people-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Manage Users</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/users/create') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-success text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-person-plus-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Add New User</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/users?filter=active') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-warning text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Active Users</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/users?filter=inactive') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-danger text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-x-circle-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Inactive Users</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrollment Actions -->
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bi bi-journal-check text-info"></i> Enrollment Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/enrollments/dashboard') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-info text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-bar-chart-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Enrollment Dashboard</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/enrollments') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-success text-white position-relative">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-journal-check fs-1 mb-2"></i>
                                                    <p class="mb-0">
                                                        Enrollment Management
                                                        <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                                                            <span class="badge bg-danger position-absolute top-0 end-0 m-2"><?= $pendingCount ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= site_url('admin/courses') ?>" class="text-decoration-none">
                                            <div class="card quick-action-card shadow-sm border-0 bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-book-fill fs-1 mb-2"></i>
                                                    <p class="mb-0">Manage Courses</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Overview Section -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> System Overview & Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="text-decoration-none">
                                            <div class="card h-100 border-primary hover-shadow">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-book fs-1 text-primary mb-2"></i>
                                                    <p class="mb-0">Manage Courses</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="text-decoration-none">
                                            <div class="card h-100 border-success hover-shadow">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-journal-text fs-1 text-success mb-2"></i>
                                                    <p class="mb-0">Content Management</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="text-decoration-none">
                                            <div class="card h-100 border-warning hover-shadow">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-gear fs-1 text-warning mb-2"></i>
                                                    <p class="mb-0">System Settings</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="text-decoration-none">
                                            <div class="card h-100 border-info hover-shadow">
                                                <div class="card-body text-center">
                                                    <i class="bi bi-bar-chart fs-1 text-info mb-2"></i>
                                                    <p class="mb-0">Reports & Analytics</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and User List -->
                <div class="row g-4 mb-4">
                    <!-- Recent Users -->
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Users</h5>
                                <a href="<?= site_url('admin/users') ?>" class="btn btn-sm btn-outline-primary">
                                    View All <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($recentUsers)): ?>
                                                <?php foreach($recentUsers as $user): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?= esc($user['name']) ?></strong>
                                                            <?php if($user['id'] == session()->get('id')): ?>
                                                                <span class="badge bg-info ms-1">You</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= esc($user['email']) ?></td>
                                                        <td>
                                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'warning' : 'success') ?>">
                                                                <?= ucfirst($user['role']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if($user['status'] === 'active'): ?>
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            <?php else: ?>
                                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><small><?= date('M d, Y', strtotime($user['created_at'])) ?></small></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No users found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="col-md-4">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> System Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Active Users</span>
                                    <span class="badge bg-success"><?= $activeUsers ?></span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?= $totalUsers > 0 ? ($activeUsers / $totalUsers * 100) : 0 ?>%"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Inactive Users</span>
                                    <span class="badge bg-danger"><?= $inactiveUsers ?></span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: <?= $totalUsers > 0 ? ($inactiveUsers / $totalUsers * 100) : 0 ?>%"></div>
                                </div>

                                <hr>

                                <div class="small text-muted">
                                    <i class="bi bi-calendar3"></i> Last login: <?= date('M d, Y H:i', strtotime(session()->get('last_login') ?? 'now')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-bell"></i> Quick Links</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="<?= site_url('admin/users?filter=active') ?>" class="list-group-item list-group-item-action">
                                        <i class="bi bi-check-circle text-success"></i> Active Users
                                    </a>
                                    <a href="<?= site_url('admin/users?filter=inactive') ?>" class="list-group-item list-group-item-action">
                                        <i class="bi bi-x-circle text-danger"></i> Inactive Users
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="bi bi-gear text-primary"></i> System Settings
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="bi bi-download text-info"></i> Export Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
