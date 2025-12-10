<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= ucfirst($user['role']) ?></title>
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
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0 0.2rem;
            transition: all 0.3s;
        }
        .header-nav .nav-link:hover,
        .header-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        .stat-card {
            border-left: 4px solid #457b9d;
        }
        .admin-card {
            border-left-color: #e63946;
        }
        .teacher-card {
            border-left-color: #f77f00;
        }
        .student-card {
            border-left-color: #06d6a0;
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
                    <?php if($user['role'] === 'teacher'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <?php elseif($user['role'] === 'student'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
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
                                        <button class="btn btn-warning btn-lg">
                                            <i class="bi bi-plus-circle"></i> Create New Course
                                        </button>
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

                    <!-- System Overview Section for Teacher -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> System Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-warning hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-plus-circle fs-1 text-warning mb-2"></i>
                                                        <h6 class="card-title">Create Course</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-info hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-people fs-1 text-info mb-2"></i>
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
                                                        <i class="bi bi-bar-chart fs-1 text-primary mb-2"></i>
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
                        <div class="col-md-6">
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
                        
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
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
                                            <p class="text-muted mt-3">You're enrolled in all available courses!</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Overview Section for Student -->
                    <h4 class="mb-3">System Overview</h4>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card overview-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-search fs-1 text-primary mb-3"></i>
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
                                    <i class="bi bi-person-circle fs-1 text-info mb-3"></i>
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
                            showAlert('success', response.message);
                            
                            // Remove from available courses
                            courseItem.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Check if no more available courses
                                if ($('.available-course-item').length === 0) {
                                    $('#availableCoursesContainer').html(`
                                        <div class="text-center py-5">
                                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">You're enrolled in all available courses!</p>
                                        </div>
                                    `);
                                }
                            });
                            
                            // Add to enrolled courses
                            const enrolledHTML = `
                                <div class="list-group-item enrolled-course-item" data-course-id="${courseId}">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">${response.course.course_code} - ${response.course.course_name}</h6>
                                            <p class="mb-1 small text-muted">${response.course.description || ''}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> ${response.course.teacher_name || 'No teacher assigned'}
                                                | <i class="bi bi-calendar-check"></i> Enrolled: Just now
                                            </small>
                                        </div>
                                        <span class="badge bg-success">Enrolled</span>
                                    </div>
                                </div>
                            `;
                            
                            // Remove "no enrolled" message if exists
                            $('#noEnrolledMessage').remove();
                            
                            // Add enrolled courses container if doesn't exist
                            if ($('#enrolledCoursesContainer .list-group').length === 0) {
                                $('#enrolledCoursesContainer').html('<div class="list-group list-group-flush"></div>');
                            }
                            
                            $('#enrolledCoursesContainer .list-group').prepend(enrolledHTML);
                            
                            // Update stats
                            const currentCount = parseInt($('.stat-card.student-card:first h3').text());
                            $('.stat-card.student-card:first h3').text(currentCount + 1);
                            
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
    </script>
</body>
</html>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted text-center py-4">No pending assignments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Overview Section for Student -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> System Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-success hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-search fs-1 text-success mb-2"></i>
                                                        <h6 class="card-title">Browse Courses</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-primary hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-clipboard-check fs-1 text-primary mb-2"></i>
                                                        <h6 class="card-title">Assignments</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-warning hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-trophy fs-1 text-warning mb-2"></i>
                                                        <h6 class="card-title">My Grades</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="text-decoration-none">
                                                <div class="card h-100 border-info hover-shadow">
                                                    <div class="card-body text-center">
                                                        <i class="bi bi-person fs-1 text-info mb-2"></i>
                                                        <h6 class="card-title">Profile</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
