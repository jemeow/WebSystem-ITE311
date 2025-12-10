<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Dashboard - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            border-left: 4px solid;
            transition: transform 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
        .stat-card-primary {
            border-left-color: #0d6efd;
        }
        .stat-card-success {
            border-left-color: #198754;
        }
        .stat-card-warning {
            border-left-color: #ffc107;
        }
        .stat-card-info {
            border-left-color: #0dcaf0;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .course-badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }
        .enrollment-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .quick-link-card {
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .quick-link-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
            color: inherit;
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
                        <a class="nav-link" href="<?= site_url('admin/dashboard') ?>">
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
                        <a class="nav-link active" href="<?= site_url('admin/enrollments/dashboard') ?>">
                            <i class="bi bi-clipboard-data"></i> Enrollment Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Manage Enrollments
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-clipboard-data"></i> Enrollment Dashboard</h2>
                <div>
                    <a href="<?= site_url('/admin/enrollments') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Manage Enrollments
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card stat-card-primary shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Total Enrollments</p>
                                    <h3 class="mb-0"><?= number_format($totalEnrollments) ?></h3>
                                </div>
                                <div class="text-primary">
                                    <i class="bi bi-clipboard-check" style="font-size: 2.5rem;"></i>
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
                                    <p class="text-muted mb-1">Active Students</p>
                                    <h3 class="mb-0"><?= number_format($totalStudents) ?></h3>
                                </div>
                                <div class="text-success">
                                    <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
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
                                    <p class="text-muted mb-1">Active Courses</p>
                                    <h3 class="mb-0"><?= number_format($totalCourses) ?></h3>
                                </div>
                                <div class="text-warning">
                                    <i class="bi bi-book-fill" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card stat-card-info shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1">Avg. per Course</p>
                                    <h3 class="mb-0"><?= $totalCourses > 0 ? number_format($totalEnrollments / $totalCourses, 1) : 0 ?></h3>
                                </div>
                                <div class="text-info">
                                    <i class="bi bi-graph-up" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <!-- Enrollment Trends Chart -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Enrollment Trends (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="enrollmentTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most Popular Courses -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-trophy-fill"></i> Most Popular Courses</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="popularCoursesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Row -->
            <div class="row g-4 mb-4">
                <!-- Recent Enrollments -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Enrollments</h5>
                        </div>
                        <div class="card-body">
                            <div class="enrollment-list">
                                <?php if(empty($recentEnrollments)): ?>
                                    <p class="text-muted text-center py-3">No enrollments yet</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach($recentEnrollments as $enrollment): ?>
                                            <div class="list-group-item px-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <i class="bi bi-person-badge text-primary"></i>
                                                            <?= esc($enrollment['student_name']) ?>
                                                        </h6>
                                                        <p class="mb-0 small text-muted">
                                                            <i class="bi bi-envelope"></i> <?= esc($enrollment['email']) ?>
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-primary">
                                                            <?= esc($enrollment['course_code']) ?>
                                                        </span>
                                                        <p class="mb-0 small text-muted mt-1">
                                                            <i class="bi bi-calendar3"></i>
                                                            <?= date('M j, Y', strtotime($enrollment['enrollment_date'])) ?>
                                                        </p>
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

                <!-- Low Enrollment Courses -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Low Enrollment</h5>
                        </div>
                        <div class="card-body">
                            <?php if(empty($lowEnrollmentCourses)): ?>
                                <p class="text-muted text-center py-3">All courses have healthy enrollment</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($lowEnrollmentCourses as $course): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= esc($course['course_code']) ?></h6>
                                                    <p class="mb-0 small text-muted"><?= esc($course['course_name']) ?></p>
                                                </div>
                                                <span class="badge bg-warning text-dark">
                                                    <?= $course['enrollment_count'] ?> students
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="bi bi-link-45deg"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body p-0">
                            <a href="<?= site_url('/admin/enrollments') ?>" class="quick-link-card">
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <i class="bi bi-plus-circle text-success me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Add Enrollment</h6>
                                        <small class="text-muted">Enroll students in courses</small>
                                    </div>
                                </div>
                            </a>
                            <a href="<?= site_url('/admin/users') ?>" class="quick-link-card">
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <i class="bi bi-people text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Manage Students</h6>
                                        <small class="text-muted">View and edit students</small>
                                    </div>
                                </div>
                            </a>
                            <a href="<?= site_url('/admin/dashboard') ?>" class="quick-link-card">
                                <div class="d-flex align-items-center p-3">
                                    <i class="bi bi-speedometer2 text-info me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-0">Main Dashboard</h6>
                                        <small class="text-muted">Return to main dashboard</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enrollment Trends Chart
        const enrollmentData = <?= json_encode($enrollmentStats) ?>;
        const dates = enrollmentData.map(item => {
            const d = new Date(item.date);
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }).reverse();
        const counts = enrollmentData.map(item => parseInt(item.count)).reverse();

        const trendCtx = document.getElementById('enrollmentTrendsChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Enrollments',
                    data: counts,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Popular Courses Chart
        const popularCourses = <?= json_encode($popularCourses) ?>;
        const courseNames = popularCourses.map(course => course.course_code);
        const courseCounts = popularCourses.map(course => parseInt(course.enrollment_count));

        const popularCtx = document.getElementById('popularCoursesChart').getContext('2d');
        new Chart(popularCtx, {
            type: 'doughnut',
            data: {
                labels: courseNames,
                datasets: [{
                    data: courseCounts,
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#0dcaf0'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
