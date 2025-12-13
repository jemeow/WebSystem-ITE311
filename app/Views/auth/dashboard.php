<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= ucfirst($user['role']) ?></title>
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
            border-radius: 2px;
            box-shadow: none;
            transition: box-shadow 0.2s;
            background: white;
        }
        .stat-card {
            border-left: 3px solid #2C5F8D;
        }
        .admin-card, .teacher-card, .student-card {
            border-left-color: #2C5F8D;
        }
        .hover-shadow {
            cursor: pointer;
        }
        .hover-shadow:hover {
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .card[style*="cursor: pointer"]:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .btn {
            border-radius: 2px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: background 0.2s;
            border: none;
            font-size: 0.9rem;
        }
        .btn-primary {
            background: #2C5F8D;
            color: white;
        }
        .btn-primary:hover {
            background: #234a6d;
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
        .btn-warning {
            background: #6366F1;
            color: white;
        }
        .btn-warning:hover {
            background: #4F46E5;
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
        .btn-outline-primary {
            border: 1px solid #2C5F8D;
            color: #2C5F8D;
            background: white;
        }
        .btn-outline-primary:hover {
            background: #2C5F8D;
            color: white;
        }
        .badge {
            padding: 0.4rem 0.75rem;
            font-weight: 400;
            border-radius: 2px;
            font-size: 0.85rem;
        }
        .badge.bg-danger {
            background: #FEE2E2 !important;
            color: #991B1B;
        }
        .badge.bg-warning {
            background: #E0E7FF !important;
            color: #3730A3;
        }
        .badge.bg-success {
            background: #D1FAE5 !important;
            color: #065F46;
        }
        .alert {
            border: none;
            border-radius: 2px;
            border-left: 3px solid #2C5F8D;
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
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 2px;
            padding: 0.6rem 0.75rem;
            transition: border 0.2s;
            font-size: 0.95rem;
            color: #374151;
        }
        .form-control:focus {
            border-color: #2C5F8D;
            box-shadow: none;
            outline: none;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #e8eaed;
            padding: 1rem 1.25rem;
        }
        .card-body {
            padding: 1.25rem;
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
                        <a class="nav-link active" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <?php if($user['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/users') ?>">
                                <i class="bi bi-people"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/courses') ?>">
                                <i class="bi bi-book"></i> Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/enrollments') ?>">
                                <i class="bi bi-journals"></i> Enrollments
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if($user['role'] === 'teacher'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('teacher/courses') ?>">
                                <i class="bi bi-book"></i> My Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('teacher/enrollments') ?>">
                                <i class="bi bi-clipboard-check"></i> Enrollments
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if($user['role'] === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('student/courses') ?>">
                                <i class="bi bi-book"></i> My Courses
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php include(APPPATH . 'Views/components/notification_bell.php'); ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('profile/edit') ?>">
                            <i class="bi bi-person-circle"></i> <?= esc($user['name']) ?>
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
                        <h2>Dashboard</h2>
                    </div>
                    <div>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'warning' : 'success') ?>" class="px-3 py-2">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Role-Specific Content -->
                <?php if($user['role'] === 'admin'): ?>
                    <!-- ADMIN DASHBOARD -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card admin-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Total Users</p>
                                            <h3 class="mb-0"><?= $totalUsers ?></h3>
                                        </div>
                                        <div class="fs-1 text-danger">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card admin-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Admins</p>
                                            <h3 class="mb-0"><?= $totalAdmins ?></h3>
                                        </div>
                                        <div class="fs-1 text-danger">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card teacher-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Teachers</p>
                                            <h3 class="mb-0"><?= $totalTeachers ?></h3>
                                        </div>
                                        <div class="fs-1 text-warning">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card student-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Students</p>
                                            <h3 class="mb-0"><?= $totalStudents ?></h3>
                                        </div>
                                        <div class="fs-1 text-success">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm" style="border: 1px solid #e5e7eb;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Total Courses</p>
                                            <h3 class="mb-0"><?= $totalCourses ?></h3>
                                        </div>
                                        <div class="fs-1" style="color: #6b7280;">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm" style="border: 1px solid #e5e7eb;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Active Enrollments</p>
                                            <h3 class="mb-0"><?= isset($totalEnrollments) ? $totalEnrollments : 0 ?></h3>
                                        </div>
                                        <div class="fs-1" style="color: #6b7280;">
                                            <i class="bi bi-journal-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card shadow-sm border-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Pending Approvals</p>
                                            <h3 class="mb-0"><?= isset($pendingCount) ? $pendingCount : 0 ?></h3>
                                        </div>
                                        <div class="fs-1 text-warning">
                                            <i class="bi bi-clock-history"></i>
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

                    <!-- Quick Action Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <a href="<?= site_url('admin/users') ?>" class="text-decoration-none">
                                <div class="card hover-shadow h-100 border-primary">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people-fill fs-1 mb-3" style="color: #6b7280;"></i>
                                        <h5 class="card-title">Manage Users</h5>
                                        <p class="text-muted small">Add, edit, or remove users</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= site_url('admin/courses') ?>" class="text-decoration-none">
                                <div class="card hover-shadow h-100" style="border: 1px solid #e5e7eb;">
                                    <div class="card-body text-center">
                                        <i class="bi bi-book-fill fs-1 mb-3" style="color: #6b7280;"></i>
                                        <h5 class="card-title">Manage Courses</h5>
                                        <p class="text-muted small">Create and edit courses</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= site_url('admin/enrollments') ?>" class="text-decoration-none">
                                <div class="card hover-shadow h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="bi bi-journal-plus fs-1 text-success mb-3"></i>
                                        <h5 class="card-title">Enrollments</h5>
                                        <p class="text-muted small">Manage student enrollments</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= site_url('admin/enrollments/pending-view') ?>" class="text-decoration-none">
                                <div class="card hover-shadow h-100 border-warning">
                                    <div class="card-body text-center">
                                        <i class="bi bi-clock-history fs-1 text-warning mb-3"></i>
                                        <h5 class="card-title">
                                            Pending Approvals
                                            <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                                                <span class="badge bg-danger"><?= $pendingCount ?></span>
                                            <?php endif; ?>
                                        </h5>
                                        <p class="text-muted small">Review enrollment requests</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Users Table -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Recent Users</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recentUsers)): ?>
                                            <?php foreach($recentUsers as $recentUser): ?>
                                                <tr>
                                                    <td><?= esc($recentUser['id']) ?></td>
                                                    <td><?= esc($recentUser['name']) ?></td>
                                                    <td><?= esc($recentUser['email']) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $recentUser['role'] === 'admin' ? 'danger' : ($recentUser['role'] === 'teacher' ? 'warning' : 'success') ?>">
                                                            <?= ucfirst($recentUser['role']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('M d, Y', strtotime($recentUser['created_at'])) ?></td>
                                                    <td>
                                                        <a href="<?= site_url('admin/users') ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <?php if($recentUser['status'] === 'active'): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No users found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php elseif($user['role'] === 'teacher'): ?>
                    <!-- TEACHER DASHBOARD -->
                    
                    <!-- Pending Enrollment Notification -->
                    <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="alert-heading mb-2">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Pending Enrollment Requests
                                </h5>
                                <p class="mb-0">
                                    <?= $pendingCount ?> student<?= $pendingCount > 1 ? 's have' : ' has' ?> requested to enroll in your courses and need<?= $pendingCount > 1 ? '' : 's' ?> your approval.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-warning">
                                    <i class="bi bi-list-check"></i> Review Requests
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-card instructor-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">My Courses</p>
                                            <h3 class="mb-0"><?= $stats['courses'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-warning">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card teacher-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Total Students</p>
                                            <h3 class="mb-0"><?= $stats['students'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-warning">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card teacher-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Assignments</p>
                                            <h3 class="mb-0"><?= $stats['assignments'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-warning">
                                            <i class="bi bi-clipboard-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-warning btn-lg">
                                            <i class="bi bi-clipboard-check"></i> Manage Enrollments
                                        </a>
                                        <button class="btn btn-outline-warning">
                                            <i class="bi bi-file-earmark-plus"></i> Add Assignment
                                        </button>
                                        <button class="btn btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i> Grade Submissions
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted text-center py-4">No recent activity</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Courses Section for Teacher -->
                    <?php if(isset($teacherCourses) && !empty($teacherCourses)): ?>
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0"><i class="bi bi-book-fill"></i> My Assigned Courses</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <?php foreach($teacherCourses as $course): ?>
                                            <div class="col-md-6">
                                                <div class="card h-100" style="border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.3s;" onclick="window.location='<?= site_url('/teacher/course/' . $course['id']) ?>'">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <span class="badge bg-warning"><?= esc($course['course_code']) ?></span>
                                                            <?= esc($course['course_name']) ?>
                                                            <i class="bi bi-arrow-right-circle float-end text-warning"></i>
                                                        </h6>
                                                        <p class="card-text small text-muted mb-2"><?= esc($course['description']) ?></p>
                                                        <small class="text-muted">
                                                            <i class="bi bi-award"></i> <?= $course['credits'] ?> Credits
                                                        </small>
                                                        <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                                            <div class="mt-2 p-2" style="background: #fff3cd; border-radius: 4px;">
                                                                <small class="fw-bold text-dark">
                                                                    <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                                </small>
                                                                <br>
                                                                <small class="fw-bold text-dark">
                                                                    <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                        <small class="text-muted mt-3 d-block text-center">
                                                            <i class="bi bi-hand-index"></i> Click card to view details
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Student Enrollment Requests Section -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header" style="background: #f3f4f6; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Student Enrollment Requests</h5>
                                    <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                                        <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-sm btn-light">
                                            <i class="bi bi-arrow-right-circle"></i> View All
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($pendingEnrollments) && !empty($pendingEnrollments)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Course</th>
                                                        <th>Request Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $displayLimit = 5;
                                                    $displayEnrollments = array_slice($pendingEnrollments, 0, $displayLimit);
                                                    foreach($displayEnrollments as $enrollment): 
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <strong><?= esc($enrollment['student_name']) ?></strong>
                                                                    <br>
                                                                    <small class="text-muted"><?= esc($enrollment['email']) ?></small>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <strong><?= esc($enrollment['course_code']) ?></strong>
                                                                    <br>
                                                                    <small class="text-muted"><?= esc($enrollment['course_name']) ?></small>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <small><?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?></small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="bi bi-clock"></i> Pending
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if(count($pendingEnrollments) > $displayLimit): ?>
                                            <div class="text-center mt-3">
                                                <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-warning">
                                                    <i class="bi bi-arrow-right-circle"></i> View All <?= count($pendingEnrollments) ?> Requests
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted" style="opacity: 0.3;"></i>
                                            <p class="text-muted mt-3">No pending enrollment requests</p>
                                            <small class="text-muted">Students requesting to enroll in your courses will appear here</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Overview Section for Teacher -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header" style="background: #f3f4f6; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> System Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 hover-shadow" style="border: 1px solid #e5e7eb;">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-people fs-1 mb-2" style="color: #6b7280;"></i>
                                                        <h6 class="card-title">My Students</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-success hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-clipboard-check fs-1 text-success mb-2"></i>
                                                        <h6 class="card-title">Assignments</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-primary hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-bar-chart fs-1 mb-2" style="color: #6b7280;"></i>
                                                        <h6 class="card-title">Grades</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- STUDENT DASHBOARD -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card stat-card student-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Enrolled Courses</p>
                                            <h3 class="mb-0"><?= $stats['enrolledCourses'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-success">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card student-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Completed Lessons</p>
                                            <h3 class="mb-0"><?= $stats['completedLessons'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-success">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stat-card student-card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Pending Assignments</p>
                                            <h3 class="mb-0"><?= $stats['pendingAssignments'] ?></h3>
                                        </div>
                                        <div class="fs-1 text-success">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Courses & Available Courses -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-book-fill"></i> My Enrolled Courses</h5>
                                </div>
                                <div class="card-body" id="enrolledCoursesContainer">
                                    <?php if (!empty($enrolledCourses)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($enrolledCourses as $course): ?>
                                                <div class="list-group-item enrolled-course-item" data-course-id="<?= $course['course_id'] ?>">
                                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1"><?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?></h6>
                                                            <p class="mb-1 small text-muted"><?= esc($course['description']) ?></p>
                                                            <small class="text-muted">
                                                                <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                                                | <i class="bi bi-calendar-check"></i> Enrolled: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                                            </small>
                                                            <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                                                <div class="mt-2">
                                                                    <small class="fw-bold text-dark">
                                                                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                                        &nbsp;|&nbsp;
                                                                        <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                                    </small>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="badge bg-success">Enrolled</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5" id="noEnrolledMessage">
                                            <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No enrolled courses yet. Start learning today!</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header" style="background: #f3f4f6; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Pending Enrollments</h5>
                                </div>
                                <div class="card-body" id="pendingEnrollmentsContainer">
                                    <?php if (!empty($pendingEnrollments)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($pendingEnrollments as $course): ?>
                                                <div class="list-group-item pending-course-item" data-course-id="<?= $course['course_id'] ?>">
                                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1"><?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?></h6>
                                                            <p class="mb-1 small text-muted"><?= esc($course['description']) ?></p>
                                                            <small class="text-muted">
                                                                <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                                                | <i class="bi bi-clock"></i> Requested: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                                            </small>
                                                            <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                                                <div class="mt-2">
                                                                    <small class="fw-bold text-dark">
                                                                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                                        &nbsp;|&nbsp;
                                                                        <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                                    </small>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <span class="badge" style="background: #E0E7FF; color: #3730A3;">Pending</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5" id="noPendingMessage">
                                            <i class="bi bi-hourglass-split text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No pending enrollment requests</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header" style="background: #f3f4f6; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Available Courses</h5>
                                </div>
                                <div class="card-body" id="availableCoursesContainer">
                                    <?php if (!empty($availableCourses)): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($availableCourses as $course): ?>
                                                <div class="list-group-item available-course-item" data-course-id="<?= $course['id'] ?>">
                                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?= esc($course['course_code']) ?> - <?= esc($course['course_name']) ?></h6>
                                                            <p class="mb-1 small text-muted"><?= esc($course['description']) ?></p>
                                                            <small class="text-muted">
                                                                <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                                                | <i class="bi bi-award"></i> <?= $course['credits'] ?> Credits
                                                            </small>
                                                            <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                                                <div class="mt-2">
                                                                    <small class="fw-bold text-dark">
                                                                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                                                        &nbsp;|&nbsp;
                                                                        <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                                    </small>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <button class="btn btn-sm btn-primary enroll-btn ms-2" data-course-id="<?= $course['id'] ?>" data-course-name="<?= esc($course['course_name']) ?>">
                                                            <i class="bi bi-plus-circle"></i> Enroll
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No more courses available!</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Materials Section for Students -->
                    <?php if (!empty($enrolledCourses)): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-files"></i> Course Materials</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                $materialModel = new \App\Models\MaterialModel();
                                $materials = $materialModel->getMaterialsForEnrolledCourses(session()->get('id'));
                                ?>
                                
                                <?php if (!empty($materials)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><i class="bi bi-file-earmark"></i> File Name</th>
                                                    <th><i class="bi bi-book"></i> Course</th>
                                                    <th><i class="bi bi-calendar"></i> Uploaded</th>
                                                    <th><i class="bi bi-download"></i> Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($materials as $material): ?>
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
                                                        <td>
                                                            <span class="badge bg-primary"><?= esc($material['course_code']) ?></span>
                                                            <?= esc($material['course_name']) ?>
                                                        </td>
                                                        <td><?= date('M d, Y', strtotime($material['created_at'])) ?></td>
                                                        <td>
                                                            <a href="<?= site_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                                                <i class="bi bi-download"></i> Download
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-3">No materials available yet for your enrolled courses.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- System Overview Section for Student -->
                    <h4 class="mb-3">System Overview</h4>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card overview-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-search fs-1 mb-3" style="color: #6b7280;"></i>
                                    <h5 class="card-title">Browse Courses</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card overview-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-clipboard-check fs-1 text-warning mb-3"></i>
                                    <h5 class="card-title">Assignments</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card overview-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-graph-up fs-1 text-success mb-3"></i>
                                    <h5 class="card-title">My Grades</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card overview-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-person-circle fs-1 mb-3" style="color: #6b7280;"></i>
                                    <h5 class="card-title">Profile</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // AJAX Enrollment for Students
        <?php if($user['role'] === 'student'): ?>
        $(document).ready(function() {
            // Handle enroll button click
            $('.enroll-btn').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const courseId = button.data('course-id');
                const courseName = button.data('course-name');
                const courseItem = button.closest('.available-course-item');
                
                // Disable button and show loading
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enrolling...');
                
                // CSRF token
                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';
                
                // Send AJAX request
                $.ajax({
                    url: '<?= site_url('course/enroll') ?>',
                    type: 'POST',
                    data: {
                        course_id: courseId,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showAlert('warning', response.message);
                            
                            // Remove from available courses
                            courseItem.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Check if no more available courses
                                if ($('.available-course-item').length === 0) {
                                    $('#availableCoursesContainer').html(`
                                        <div class="text-center py-5">
                                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">No more courses available!</p>
                                        </div>
                                    `);
                                }
                            });
                            
                            // Add to pending enrollments
                            const pendingHTML = `
                                <div class="list-group-item pending-course-item" data-course-id="${courseId}">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${response.course.course_code} - ${response.course.course_name}</h6>
                                            <p class="mb-1 small text-muted">${response.course.description || ''}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> ${response.course.teacher_name || 'No teacher assigned'}
                                                | <i class="bi bi-clock"></i> Requested: Just now
                                            </small>
                                        </div>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </div>
                                </div>
                            `;
                            
                            // Remove "no pending" message if exists
                            $('#noPendingMessage').remove();
                            
                            // Add pending enrollments container if doesn't exist
                            if ($('#pendingEnrollmentsContainer .list-group').length === 0) {
                                $('#pendingEnrollmentsContainer').html('<div class="list-group list-group-flush"></div>');
                            }
                            
                            $('#pendingEnrollmentsContainer .list-group').prepend(pendingHTML);
                            
                        } else {
                            showAlert('danger', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                        }
                    },
                    error: function(xhr) {
                        let message = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showAlert('danger', message);
                        button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                    }
                });
            });
            
            // Helper function to show alerts
            function showAlert(type, message) {
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Insert at top of main content
                $('main').prepend(alertHTML);
                
                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 5000);
                
                // Scroll to top
                $('html, body').animate({ scrollTop: 0 }, 400);
            }
        });
        <?php endif; ?>

        // AJAX Enrollment for Teachers
        <?php if($user['role'] === 'teacher'): ?>
        $(document).ready(function() {
            // Handle enroll button click
            $('.enroll-btn-teacher').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const courseId = button.data('course-id');
                const courseCard = button.closest('.card[data-course-id="' + courseId + '"]');
                
                // Disable button and show loading
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enrolling...');
                
                // CSRF token
                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';
                
                // Send AJAX request
                $.ajax({
                    url: '<?= site_url('course/enroll') ?>',
                    type: 'POST',
                    data: {
                        course_id: courseId,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showAlert('warning', response.message);
                            
                            // Remove from available courses
                            courseCard.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Update count
                                const remainingCount = $('#teacher-available-courses .card').length;
                                if (remainingCount === 0) {
                                    $('#teacher-available-courses').html('<p class="text-muted"><small>No available courses</small></p>');
                                }
                            });
                            
                            // Add to pending enrollments
                            const pendingHTML = `
                                <div class="card mb-2 border-warning">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1">${response.course.course_name}</h6>
                                        <small class="text-muted d-block">${response.course.teacher_name || 'Unknown'}</small>
                                        <div class="mt-2">
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Replace "No pending" message if exists
                            const pendingContainer = $('#teacher-pending-enrollments');
                            if (pendingContainer.find('p.text-muted').length > 0) {
                                pendingContainer.html('');
                            }
                            
                            pendingContainer.append(pendingHTML);
                            
                        } else {
                            showAlert('danger', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                        }
                    },
                    error: function(xhr) {
                        let message = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showAlert('danger', message);
                        button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                    }
                });
            });
            
            // Helper function to show alerts
            function showAlert(type, message) {
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Insert at top of main content
                $('main').prepend(alertHTML);
                
                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 5000);
                
                // Scroll to top
                $('html, body').animate({ scrollTop: 0 }, 400);
            }
        });
        <?php endif; ?>

        // Auto-refresh pending enrollments for students
        <?php if($user['role'] === 'student'): ?>
        let lastPendingCount = <?= count($pendingEnrollments) ?>;
        
        function checkEnrollmentUpdates() {
            $.ajax({
                url: '<?= site_url('course/check-status') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const currentPendingCount = response.pendingCount;
                        const approvedCount = response.approvedCount;
                        
                        // If pending count decreased or approved count increased, reload page
                        if (currentPendingCount < lastPendingCount || approvedCount > 0) {
                            // Show notification before reload
                            const message = approvedCount > 0 
                                ? `Good news! ${approvedCount} enrollment${approvedCount > 1 ? 's' : ''} approved. Refreshing...`
                                : 'Enrollment status updated. Refreshing...';
                            
                            const alertHTML = `
                                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;">
                                    <i class="bi bi-check-circle-fill"></i> ${message}
                                </div>
                            `;
                            $('body').prepend(alertHTML);
                            
                            // Reload page after 1.5 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                        
                        lastPendingCount = currentPendingCount;
                    }
                },
                error: function() {
                    // Silent fail - don't disrupt user experience
                }
            });
        }
        
        // Check every 5 seconds for updates
        setInterval(checkEnrollmentUpdates, 5000);
        <?php endif; ?>

        // Auto-refresh for teacher pending approval requests
        <?php if($user['role'] === 'teacher'): ?>
        let lastTeacherPendingCount = <?= isset($pendingCount) ? $pendingCount : 0 ?>;
        let teacherFirstCheck = true;
        
        function checkTeacherPendingUpdates() {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/pending') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const currentCount = response.enrollments.length;
                        
                        // Skip reload on first check (initial page load)
                        if (teacherFirstCheck) {
                            teacherFirstCheck = false;
                            lastTeacherPendingCount = currentCount;
                            return;
                        }
                        
                        // If pending count changed, reload page
                        if (currentCount !== lastTeacherPendingCount) {
                            lastTeacherPendingCount = currentCount;
                            location.reload();
                        }
                    }
                },
                error: function() {
                    // Silent fail
                }
            });
        }
        
        // Check every 10 seconds for new enrollment requests
        setInterval(checkTeacherPendingUpdates, 10000);
        <?php endif; ?>

        // Auto-refresh for admin pending approval requests
        <?php if($user['role'] === 'admin'): ?>
        let lastAdminPendingCount = <?= isset($pendingCount) ? $pendingCount : 0 ?>;
        let adminFirstCheck = true;
        
        function checkAdminPendingUpdates() {
            $.ajax({
                url: '<?= site_url('admin/enrollments/pending') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const currentCount = response.enrollments.length;
                        
                        // Skip reload on first check (initial page load)
                        if (adminFirstCheck) {
                            adminFirstCheck = false;
                            lastAdminPendingCount = currentCount;
                            return;
                        }
                        
                        // If pending count changed, reload page
                        if (currentCount !== lastAdminPendingCount) {
                            lastAdminPendingCount = currentCount;
                            location.reload();
                        }
                    }
                },
                error: function() {
                    // Silent fail
                }
            });
        }
        
        // Check every 10 seconds for new enrollment requests
        setInterval(checkAdminPendingUpdates, 10000);
        <?php endif; ?>

        // ===== Notification System =====
        
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }
        
        /**
         * Fetch notifications from server and update UI
         */
        function fetchNotifications() {
            $.ajax({
                url: '<?= site_url('notifications') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.unread_count);
                        updateNotificationList(response.notifications);
                    }
                },
                error: function(xhr) {
                    console.error('Failed to fetch notifications:', xhr);
                }
            });
        }
        
        /**
         * Update notification badge with unread count
         */
        function updateNotificationBadge(count) {
            const badge = $('#notificationBadge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }
        
        /**
         * Update notification dropdown list
         */
        function updateNotificationList(notifications) {
            const notificationList = $('#notificationList');
            const noNotifications = $('#noNotifications');
            
            // Remove existing notification items (keep header and divider)
            notificationList.find('.notification-item').remove();
            
            if (notifications.length === 0) {
                noNotifications.show();
            } else {
                noNotifications.hide();
                
                // Add each notification to the list
                notifications.forEach(function(notification) {
                    const isUnread = notification.is_read == 0;
                    const notificationItem = $('<li>', {
                        class: 'notification-item'
                    });
                    
                    const notificationContent = $('<div>', {
                        class: 'px-3 py-2 border-bottom ' + (isUnread ? 'bg-light' : '')
                    }).html(`
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <div class="alert alert-info py-2 px-2 mb-1" role="alert">
                                    <p class="mb-0 small ${isUnread ? 'fw-bold' : ''}">${notification.message}</p>
                                </div>
                                <small class="text-muted" data-timestamp="${notification.created_at}">${formatNotificationDate(notification.created_at)}</small>
                            </div>
                            ${isUnread ? `<button type="button" class="btn btn-sm btn-outline-secondary mark-read-btn" data-id="${notification.id}">Mark as Read</button>` : ''}
                        </div>
                    `);
                    
                    notificationItem.append(notificationContent);
                    notificationList.append(notificationItem);
                });
                
                // Add divider at the end
                notificationList.append('<li><hr class="dropdown-divider"></li>');
            }
        }
        
        /**
         * Format notification date
         */
        function formatNotificationDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffSecs = Math.floor(diffMs / 1000);
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            const diffWeeks = Math.floor(diffMs / 604800000);
            const diffMonths = Math.floor(diffMs / 2592000000);
            
            if (diffSecs < 10) return 'Just now';
            if (diffSecs < 60) return diffSecs + ' seconds ago';
            if (diffMins === 1) return '1 minute ago';
            if (diffMins < 60) return diffMins + ' minutes ago';
            if (diffHours === 1) return '1 hour ago';
            if (diffHours < 24) return diffHours + ' hours ago';
            if (diffDays === 1) return 'Yesterday';
            if (diffDays < 7) return diffDays + ' days ago';
            if (diffWeeks === 1) return '1 week ago';
            if (diffWeeks < 4) return diffWeeks + ' weeks ago';
            if (diffMonths === 1) return '1 month ago';
            if (diffMonths < 12) return diffMonths + ' months ago';
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleString('en-US', options);
        }
        
        /**
         * Mark notification as read
         */
        function markNotificationAsRead(notificationId) {
            const csrfHeader = '<?= config('Security')->headerName ?>';
            const csrfCookieName = '<?= config('Security')->cookieName ?>';
            $.ajax({
                url: '<?= site_url('notifications/mark_read/') ?>' + notificationId,
                type: 'POST',
                headers: {
                    [csrfHeader]: getCookie(csrfCookieName)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Refresh notifications
                        fetchNotifications();
                    } else {
                        alert('Failed to mark notification as read');
                    }
                },
                error: function(xhr) {
                    console.error('Failed to mark notification as read:', xhr);
                    alert('An error occurred');
                }
            });
        }
        
        // Event handler for mark as read buttons (using event delegation)
        $(document).on('click', '.mark-read-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = $(this).data('id');
            markNotificationAsRead(notificationId);
        });
        
        // Fetch notifications on page load
        fetchNotifications();
        
        // Auto-refresh notifications every 60 seconds
        setInterval(fetchNotifications, 60000);

        function updateNotificationTimes() {
            $('.notification-item').each(function() {
                const timeElement = $(this).find('small.text-muted');
                const timestamp = timeElement.data('timestamp');

                if (timestamp) {
                    timeElement.text(formatNotificationDate(timestamp));
                }
            });
        }

        setInterval(updateNotificationTimes, 10000);
    </script>
</body>
</html>