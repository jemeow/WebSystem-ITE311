<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Management - Admin Panel</title>
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
        .enrollment-card {
            border-left: 3px solid #0d6efd;
            transition: all 0.3s;
        }
        .enrollment-card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            transform: translateX(5px);
        }
        .course-card {
            border-left: 3px solid #198754;
            transition: all 0.3s;
            cursor: pointer;
        }
        .course-card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .student-selector {
            position: sticky;
            top: 20px;
        }
        .search-box {
            position: relative;
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .search-box input {
            padding-left: 40px;
        }
        .filter-chip {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            margin: 0.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            transition: all 0.3s;
            cursor: pointer;
        }
        .filter-chip:hover {
            transform: scale(1.05);
        }
        .enrollment-count {
            background-color: #0d6efd;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .no-data-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
        .no-data-state i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
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
                        <a class="nav-link" href="<?= site_url('/admin/users') ?>">
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
                        <a class="nav-link active" href="<?= site_url('admin/enrollments') ?>">
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
                <div>
                    <h2><i class="bi bi-clipboard-check"></i> Enrollment Management</h2>
                    <p class="text-muted mb-0">Manage student enrollments and review pending requests</p>
                </div>
                <a href="<?= site_url('/admin/enrollments/dashboard') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard-data"></i> View Dashboard
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Alert Container for AJAX messages -->
            <div id="alertContainer"></div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="enrollmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-panel" type="button" role="tab">
                        <i class="bi bi-person-plus"></i> Add/Remove Enrollments
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-panel" type="button" role="tab">
                        <i class="bi bi-clock-history"></i> Pending Approvals
                        <span class="badge bg-danger rounded-pill" id="pendingBadge" style="display: none;">0</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="enrollmentTabContent">
                <!-- Manage Enrollments Panel -->
                <div class="tab-pane fade show active" id="manage-panel" role="tabpanel">
                    <div class="row g-4">
                <!-- Student Selection Panel -->
                <div class="col-lg-4">
                    <div class="card shadow-sm student-selector">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Select Student</h5>
                        </div>
                        <div class="card-body">
                            <!-- Search Box -->
                            <div class="search-box mb-3">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control" id="studentSearch" placeholder="Search students...">
                            </div>
                            
                            <!-- Student Dropdown -->
                            <div class="mb-3">
                                <label for="studentSelect" class="form-label fw-bold">Choose Student:</label>
                                <select class="form-select form-select-lg" id="studentSelect" size="8">
                                    <option value="">-- Select a Student --</option>
                                    <?php foreach($students as $student): ?>
                                        <option value="<?= $student['id'] ?>" 
                                                data-name="<?= esc($student['name']) ?>"
                                                data-email="<?= esc($student['email']) ?>">
                                            <?= esc($student['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Selected Student Info -->
                            <div id="studentInfo" style="display: none;">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-person-badge-fill me-2" style="font-size: 2rem;"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" id="studentName"></h6>
                                            <p class="mb-1 small" id="studentEmail"></p>
                                            <div class="mt-2">
                                                <span class="enrollment-count" id="enrollmentCount">0 courses</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between text-muted small">
                                    <span><i class="bi bi-people"></i> Total Students:</span>
                                    <strong><?= count($students) ?></strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mt-2">
                                    <span><i class="bi bi-book"></i> Total Courses:</span>
                                    <strong><?= count($courses) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrollment Management Panel -->
                <div class="col-lg-8">
                    <div class="row g-3">
                        <!-- Current Enrollments -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bi bi-book-fill"></i> Current Enrollments</h5>
                                        <span class="badge bg-light text-dark" id="enrolledCount">0</span>
                                    </div>
                                </div>
                                <div class="card-body" id="enrollmentsSection">
                                    <div class="no-data-state">
                                        <i class="bi bi-arrow-left-circle"></i>
                                        <p class="mb-0">Select a student to view their enrollments</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Courses -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bi bi-plus-circle-fill"></i> Available Courses</h5>
                                        <span class="badge bg-light text-dark" id="availableCount">0</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Course Search/Filter -->
                                    <div class="search-box mb-3">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" class="form-control" id="courseSearch" placeholder="Search available courses...">
                                    </div>
                                    
                                    <div id="availableCoursesSection">
                                        <div class="no-data-state">
                                            <i class="bi bi-arrow-up-circle"></i>
                                            <p class="mb-0">Select a student first</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Pending Approvals Panel -->
            <div class="tab-pane fade" id="pending-panel" role="tabpanel">
                <!-- Filter and Search -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Search Student</label>
                                <input type="text" class="form-control" id="searchStudent" placeholder="Search by student name or email...">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Search Course</label>
                                <input type="text" class="form-control" id="searchCourse" placeholder="Search by course code or name...">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary w-100" id="refreshBtn">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Enrollments List -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Pending Enrollment Requests</h5>
                    </div>
                    <div class="card-body">
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Loading pending enrollments...</p>
                        </div>

                        <!-- Enrollments Container -->
                        <div id="enrollmentsContainer" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="enrollmentsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Student</th>
                                            <th>Course</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="enrollmentsTableBody">
                                        <!-- Populated via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- No Pending Message -->
                        <div id="noPendingMessage" class="text-center py-5" style="display: none;">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">All Caught Up!</h4>
                            <p class="text-muted">No pending enrollment requests at the moment.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Confirmation Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-check-circle"></i> Approve Enrollment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to approve this enrollment?</p>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Student:</strong> <span id="approveStudentName"></span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="approveStudentEmail"></span></p>
                                    <p class="mb-0"><strong>Course:</strong> <span id="approveCourse"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="confirmApprove">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Confirmation Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="bi bi-x-circle"></i> Reject Enrollment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject and remove this enrollment request?</p>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Student:</strong> <span id="rejectStudentName"></span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="rejectStudentEmail"></span></p>
                                    <p class="mb-0"><strong>Course:</strong> <span id="rejectCourse"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmReject">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="loading-spinner" id="loadingSpinner" style="display:none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading enrollments...</p>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentStudentId = null;
        let currentStudentName = '';
        let currentStudentEmail = '';
        let enrolledCourseIds = [];
        const allCourses = <?= json_encode($courses) ?>;
        let filteredCourses = [...allCourses];

        $(document).ready(function() {
            // Student search functionality
            $('#studentSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#studentSelect option').each(function() {
                    const text = $(this).text().toLowerCase();
                    const value = $(this).val();
                    if (value === '' || text.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Course search functionality
            $('#courseSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                filterAndDisplayCourses(searchTerm);
            });

            function filterAndDisplayCourses(searchTerm = '') {
                filteredCourses = allCourses.filter(course => {
                    if (enrolledCourseIds.includes(parseInt(course.id))) return false;
                    if (searchTerm === '') return true;
                    const matches = course.course_code.toLowerCase().includes(searchTerm) ||
                                  course.course_name.toLowerCase().includes(searchTerm) ||
                                  (course.description && course.description.toLowerCase().includes(searchTerm));
                    return matches;
                });
                displayAvailableCourses();
            }

            // Handle student selection
            $('#studentSelect').on('change', function() {
                currentStudentId = $(this).val();
                const selectedOption = $(this).find('option:selected');
                currentStudentName = selectedOption.data('name');
                currentStudentEmail = selectedOption.data('email');
                
                if (currentStudentId) {
                    $('#studentInfo').show();
                    $('#studentName').text(currentStudentName);
                    $('#studentEmail').html('<i class="bi bi-envelope"></i> ' + currentStudentEmail);
                    $('#courseSearch').val('');
                    loadStudentEnrollments();
                } else {
                    resetInterface();
                }
            });

            function resetInterface() {
                $('#studentInfo').hide();
                $('#enrollmentsSection').html(`
                    <div class="no-data-state">
                        <i class="bi bi-arrow-left-circle"></i>
                        <p class="mb-0">Select a student to view their enrollments</p>
                    </div>
                `);
                $('#availableCoursesSection').html(`
                    <div class="no-data-state">
                        <i class="bi bi-arrow-up-circle"></i>
                        <p class="mb-0">Select a student first</p>
                    </div>
                `);
                $('#enrolledCount').text('0');
                $('#availableCount').text('0');
                $('#enrollmentCount').text('0 courses');
            }

            // Load student enrollments
            function loadStudentEnrollments() {
                $('#loadingSpinner').show();
                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/enrollments/get-student-enrollments') ?>',
                    type: 'POST',
                    data: {
                        student_id: currentStudentId,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#loadingSpinner').hide();
                        if (response.success) {
                            enrolledCourseIds = response.enrolledCourseIds.map(id => parseInt(id));
                            displayEnrollments(response.enrollments);
                            filterAndDisplayCourses($('#courseSearch').val().toLowerCase());
                            
                            // Update counts
                            $('#enrollmentCount').text(response.enrollments.length + ' course' + (response.enrollments.length !== 1 ? 's' : ''));
                            $('#enrolledCount').text(response.enrollments.length);
                        }
                    },
                    error: function() {
                        $('#loadingSpinner').hide();
                        showAlert('danger', 'Failed to load enrollments');
                    }
                });
            }

            // Display enrollments
            function displayEnrollments(enrollments) {
                if (enrollments.length === 0) {
                    $('#enrollmentsSection').html(`
                        <div class="no-data-state">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0">No enrollments yet</p>
                            <small class="text-muted">Student has not enrolled in any courses</small>
                        </div>
                    `);
                    return;
                }

                let html = '<div class="row g-3">';
                enrollments.forEach(function(enrollment) {
                    html += `
                        <div class="col-md-6">
                            <div class="card enrollment-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">
                                            <span class="badge bg-primary">${enrollment.course_code}</span>
                                        </h6>
                                        <button class="btn btn-sm btn-danger unenroll-btn" 
                                                data-course-id="${enrollment.course_id}" 
                                                data-course-name="${enrollment.course_name}"
                                                title="Unenroll student">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                    <h6 class="card-title">${enrollment.course_name}</h6>
                                    <p class="card-text small text-muted mb-2">${enrollment.description || 'No description'}</p>
                                    <div class="d-flex justify-content-between align-items-center small text-muted">
                                        <span><i class="bi bi-person"></i> ${enrollment.teacher_name || 'No teacher'}</span>
                                        <span><i class="bi bi-calendar-check"></i> ${new Date(enrollment.enrollment_date).toLocaleDateString()}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#enrollmentsSection').html(html);
            }

            // Display available courses
            function displayAvailableCourses() {
                const availableCourses = filteredCourses;
                $('#availableCount').text(availableCourses.length);
                
                if (availableCourses.length === 0) {
                    const message = $('#courseSearch').val() ? 'No courses match your search' : 'All courses enrolled!';
                    $('#availableCoursesSection').html(`
                        <div class="no-data-state">
                            <i class="bi bi-${$('#courseSearch').val() ? 'search' : 'check-circle'}"></i>
                            <p class="mb-0">${message}</p>
                        </div>
                    `);
                    return;
                }

                let html = '<div class="row g-3">';
                availableCourses.forEach(function(course) {
                    html += `
                        <div class="col-md-6">
                            <div class="card course-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-success">${course.course_code}</span>
                                        <button class="btn btn-sm btn-success enroll-btn" 
                                                data-course-id="${course.id}" 
                                                data-course-name="${course.course_name}"
                                                title="Enroll student">
                                            <i class="bi bi-plus-circle"></i> Enroll
                                        </button>
                                    </div>
                                    <h6 class="card-title">${course.course_name}</h6>
                                    <p class="card-text small text-muted">${course.description || 'No description'}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-award"></i> ${course.credits} credit${course.credits !== '1' ? 's' : ''}
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#availableCoursesSection').html(html);
            }

            // Handle enroll button
            $(document).on('click', '.enroll-btn', function() {
                const button = $(this);
                const courseId = button.data('course-id');
                const courseName = button.data('course-name');
                
                if (!confirm(`Enroll ${currentStudentName} in ${courseName}?`)) {
                    return;
                }

                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/enrollments/enroll-student') ?>',
                    type: 'POST',
                    data: {
                        student_id: currentStudentId,
                        course_id: courseId,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            loadStudentEnrollments();
                        } else {
                            showAlert('danger', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Failed to enroll student';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showAlert('danger', message);
                        button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Enroll');
                    }
                });
            });

            // Handle unenroll button
            $(document).on('click', '.unenroll-btn', function() {
                const button = $(this);
                const courseId = button.data('course-id');
                const courseName = button.data('course-name');
                
                if (!confirm(`Unenroll ${currentStudentName} from ${courseName}?`)) {
                    return;
                }

                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/enrollments/unenroll-student') ?>',
                    type: 'POST',
                    data: {
                        student_id: currentStudentId,
                        course_id: courseId,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            loadStudentEnrollments();
                        } else {
                            showAlert('danger', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-x-circle"></i>');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to unenroll student');
                        button.prop('disabled', false).html('<i class="bi bi-x-circle"></i>');
                    }
                });
            });

            // Show alert function
            function showAlert(type, message) {
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').html(alertHTML);
                setTimeout(function() {
                    $('.alert').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 5000);
                $('html, body').animate({ scrollTop: 0 }, 400);
            }
        });

        // Pending Approvals JavaScript
        let enrollmentsData = [];
        let currentEnrollmentId = null;
        const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

        // Load pending enrollments when tab is shown
        $('#pending-tab').on('shown.bs.tab', function() {
            loadPendingEnrollments();
        });

        // Load on page load to get count
        $(document).ready(function() {
            loadPendingEnrollmentsCount();
        });

        function loadPendingEnrollmentsCount() {
            $.ajax({
                url: '<?= site_url('admin/enrollments/pending') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.enrollments.length > 0) {
                        $('#pendingBadge').text(response.enrollments.length).show();
                    } else {
                        $('#pendingBadge').hide();
                    }
                }
            });
        }

        function loadPendingEnrollments() {
            $('#pending-panel #loadingSpinner').show();
            $('#enrollmentsContainer').hide();
            $('#noPendingMessage').hide();

            $.ajax({
                url: '<?= site_url('admin/enrollments/pending') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        enrollmentsData = response.enrollments;
                        renderEnrollments(enrollmentsData);
                        updateStatistics(enrollmentsData);
                        if (response.enrollments.length > 0) {
                            $('#pendingBadge').text(response.enrollments.length).show();
                        } else {
                            $('#pendingBadge').hide();
                        }
                    } else {
                        $('#pending-panel #loadingSpinner').hide();
                        $('#noPendingMessage').show();
                    }
                },
                error: function() {
                    $('#pending-panel #loadingSpinner').hide();
                    showAlert('danger', 'Failed to load pending enrollments');
                }
            });
        }

        function renderEnrollments(enrollments) {
            $('#pending-panel #loadingSpinner').hide();
            
            if (enrollments.length === 0) {
                $('#noPendingMessage').show();
                $('#enrollmentsContainer').hide();
                return;
            }

            $('#noPendingMessage').hide();
            $('#enrollmentsContainer').show();

            let html = '';
            enrollments.forEach(function(enrollment) {
                const requestDate = new Date(enrollment.enrollment_date);
                const formattedDate = requestDate.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                html += `
                    <tr data-enrollment-id="${enrollment.id}">
                        <td><span class="badge bg-secondary">#${enrollment.id}</span></td>
                        <td>
                            <div>
                                <strong>${escapeHtml(enrollment.student_name)}</strong>
                                <br>
                                <small class="text-muted">${escapeHtml(enrollment.email)}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>${escapeHtml(enrollment.course_code)}</strong>
                                <br>
                                <small class="text-muted">${escapeHtml(enrollment.course_name)}</small>
                            </div>
                        </td>
                        <td>
                            <small>${formattedDate}</small>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock"></i> Pending
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-success approve-btn" 
                                        data-id="${enrollment.id}"
                                        data-student="${escapeHtml(enrollment.student_name)}"
                                        data-email="${escapeHtml(enrollment.email)}"
                                        data-course="${escapeHtml(enrollment.course_code)} - ${escapeHtml(enrollment.course_name)}">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn"
                                        data-id="${enrollment.id}"
                                        data-student="${escapeHtml(enrollment.student_name)}"
                                        data-email="${escapeHtml(enrollment.email)}"
                                        data-course="${escapeHtml(enrollment.course_code)} - ${escapeHtml(enrollment.course_name)}">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#enrollmentsTableBody').html(html);
        }

        function updateStatistics(enrollments) {
            $('#totalPending').text(enrollments.length);
            const uniqueStudents = new Set(enrollments.map(e => e.user_id));
            $('#uniqueStudents').text(uniqueStudents.size);
        }

        // Search functionality
        $('#searchStudent, #searchCourse').on('keyup', function() {
            const studentQuery = $('#searchStudent').val().toLowerCase();
            const courseQuery = $('#searchCourse').val().toLowerCase();

            const filtered = enrollmentsData.filter(function(enrollment) {
                const studentMatch = enrollment.student_name.toLowerCase().includes(studentQuery) ||
                                   enrollment.email.toLowerCase().includes(studentQuery);
                const courseMatch = enrollment.course_code.toLowerCase().includes(courseQuery) ||
                                  enrollment.course_name.toLowerCase().includes(courseQuery);
                
                return studentMatch && courseMatch;
            });

            renderEnrollments(filtered);
        });

        // Approve button click - Prevent double-click
        $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            
            // Prevent multiple clicks
            if ($(this).prop('disabled')) {
                return false;
            }
            
            currentEnrollmentId = $(this).data('id');
            $('#approveStudentName').text($(this).data('student'));
            $('#approveStudentEmail').text($(this).data('email'));
            $('#approveCourse').text($(this).data('course'));
            approveModal.show();
        });

        // Confirm approve with duplicate prevention
        $('#confirmApprove').on('click', function() {
            const button = $(this);
            
            // Prevent double submission
            if (button.prop('disabled')) {
                return false;
            }
            
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Approving...');

            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';

            $.ajax({
                url: '<?= site_url('admin/enrollments/approve') ?>',
                type: 'POST',
                data: {
                    enrollment_id: currentEnrollmentId,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                timeout: 10000, // 10 second timeout
                success: function(response) {
                    if (response.success) {
                        // Show success notification with animation
                        showNotification('success', response.message);
                        
                        // Disable action buttons in the row to prevent duplicate actions
                        const row = $(`tr[data-enrollment-id="${currentEnrollmentId}"]`);
                        row.find('.approve-btn, .reject-btn').prop('disabled', true);
                        
                        // Add success highlight animation
                        row.addClass('table-success');
                        
                        // Remove the row with smooth animation
                        setTimeout(() => {
                            row.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Update enrollmentsData array
                                enrollmentsData = enrollmentsData.filter(e => e.id != currentEnrollmentId);
                                
                                // Update counts
                                $('#totalPending').text(enrollmentsData.length);
                                const uniqueStudents = new Set(enrollmentsData.map(e => e.user_id));
                                $('#uniqueStudents').text(uniqueStudents.size);
                                
                                // Update badge
                                if (enrollmentsData.length > 0) {
                                    $('#pendingBadge').text(enrollmentsData.length).show();
                                } else {
                                    $('#pendingBadge').hide();
                                    // Show no pending message if table is empty
                                    if ($('#enrollmentsTableBody tr').length === 0) {
                                        $('#enrollmentsContainer').hide();
                                        $('#noPendingMessage').show();
                                    }
                                }
                            });
                        }, 500);
                        
                        updateApprovedCount();
                        
                        // Reset button and close modal
                        button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Approve');
                        approveModal.hide();
                    } else {
                        showNotification('danger', response.message);
                        button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Approve');
                    }
                },
                error: function(xhr, status, error) {
                    let message = 'Failed to approve enrollment. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (status === 'timeout') {
                        message = 'Request timed out. Please check your connection.';
                    }
                    showNotification('danger', message);
                    button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Approve');
                    approveModal.hide();
                }
            });
        });

        // Reject button click - Prevent double-click
        $(document).on('click', '.reject-btn', function(e) {
            e.preventDefault();
            
            // Prevent multiple clicks
            if ($(this).prop('disabled')) {
                return false;
            }
            
            currentEnrollmentId = $(this).data('id');
            $('#rejectStudentName').text($(this).data('student'));
            $('#rejectStudentEmail').text($(this).data('email'));
            $('#rejectCourse').text($(this).data('course'));
            rejectModal.show();
        });

        // Confirm reject with duplicate prevention
        $('#confirmReject').on('click', function() {
            const button = $(this);
            
            // Prevent double submission
            if (button.prop('disabled')) {
                return false;
            }
            
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Rejecting...');

            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';

            $.ajax({
                url: '<?= site_url('admin/enrollments/reject') ?>',
                type: 'POST',
                data: {
                    enrollment_id: currentEnrollmentId,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                timeout: 10000, // 10 second timeout
                success: function(response) {
                    if (response.success) {
                        // Show warning notification with animation
                        showNotification('warning', response.message);
                        
                        // Disable action buttons in the row to prevent duplicate actions
                        const row = $(`tr[data-enrollment-id="${currentEnrollmentId}"]`);
                        row.find('.approve-btn, .reject-btn').prop('disabled', true);
                        
                        // Add danger highlight animation
                        row.addClass('table-danger');
                        
                        // Remove the row with smooth animation
                        setTimeout(() => {
                            row.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Update enrollmentsData array
                                enrollmentsData = enrollmentsData.filter(e => e.id != currentEnrollmentId);
                                
                                // Update counts
                                $('#totalPending').text(enrollmentsData.length);
                                const uniqueStudents = new Set(enrollmentsData.map(e => e.user_id));
                                $('#uniqueStudents').text(uniqueStudents.size);
                                
                                // Update badge
                                if (enrollmentsData.length > 0) {
                                    $('#pendingBadge').text(enrollmentsData.length).show();
                                } else {
                                    $('#pendingBadge').hide();
                                    // Show no pending message if table is empty
                                    if ($('#enrollmentsTableBody tr').length === 0) {
                                        $('#enrollmentsContainer').hide();
                                        $('#noPendingMessage').show();
                                    }
                                }
                            });
                        }, 500);
                        
                        updateRejectedCount();
                        
                        // Reset button and close modal
                        button.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Reject');
                        rejectModal.hide();
                    } else {
                        showNotification('danger', response.message);
                        button.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Reject');
                    }
                },
                error: function(xhr, status, error) {
                    let message = 'Failed to reject enrollment. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (status === 'timeout') {
                        message = 'Request timed out. Please check your connection.';
                    }
                    showNotification('danger', message);
                    button.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Reject');
                    rejectModal.hide();
                }
            });
        });

        // Refresh button
        $('#refreshBtn').on('click', function() {
            const icon = $(this).find('i');
            icon.addClass('fa-spin');
            loadPendingEnrollments();
            setTimeout(() => icon.removeClass('fa-spin'), 1000);
        });

        // Update counts
        function updateApprovedCount() {
            const current = parseInt($('#approvedToday').text());
            $('#approvedToday').text(current + 1);
        }

        function updateRejectedCount() {
            const current = parseInt($('#rejectedToday').text());
            $('#rejectedToday').text(current + 1);
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Enhanced notification function with animations
        function showNotification(type, message) {
            // Remove any existing notifications
            $('.toast-notification').remove();
            
            // Icon mapping
            const icons = {
                'success': 'bi-check-circle-fill',
                'danger': 'bi-exclamation-triangle-fill',
                'warning': 'bi-exclamation-circle-fill',
                'info': 'bi-info-circle-fill'
            };
            
            // Color mapping
            const colors = {
                'success': 'success',
                'danger': 'danger',
                'warning': 'warning',
                'info': 'info'
            };
            
            const icon = icons[type] || icons['info'];
            const color = colors[type] || colors['info'];
            
            // Create toast notification
            const toastHTML = `
                <div class="toast-notification position-fixed top-0 end-0 m-3" style="z-index: 9999; min-width: 300px; animation: slideInRight 0.3s ease-out;">
                    <div class="alert alert-${color} alert-dismissible fade show shadow-lg border-0" role="alert" style="margin: 0;">
                        <div class="d-flex align-items-center">
                            <i class="bi ${icon} fs-4 me-2"></i>
                            <div class="flex-grow-1">${message}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(toastHTML);
            
            // Auto dismiss after 4 seconds
            setTimeout(function() {
                $('.toast-notification').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 4000);
        }

        // Auto-refresh every 30 seconds
        setInterval(function() {
            if ($('#pending-panel').hasClass('active') && !$('.modal.show').length) {
                loadPendingEnrollmentsCount();
            }
        }, 30000);

        // History JavaScript
        let historyData = [];
        let filteredHistoryData = [];

        // Load history when tab is shown
        $('#history-tab').on('shown.bs.tab', function() {
            loadHistory();
        });

        function loadHistory() {
            $('#historyLoading').show();
            $('#historyNoData').hide();
            $('#historyTableContainer').hide();

            $.ajax({
                url: '<?= site_url('admin/enrollments/history') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.history) {
                        historyData = response.history;
                        filteredHistoryData = historyData;
                        
                        // Update statistics
                        $('#totalApproved').text(response.stats.total_approved);
                        $('#totalRejected').text(response.stats.total_rejected);
                        $('#todayApproved').text(response.stats.today_approved);
                        $('#todayRejected').text(response.stats.today_rejected);
                        
                        renderHistory(filteredHistoryData);
                    }
                },
                error: function() {
                    $('#historyLoading').hide();
                    $('#historyNoData').show();
                }
            });
        }

        function renderHistory(data) {
            $('#historyLoading').hide();
            
            if (!data || data.length === 0) {
                $('#historyNoData').show();
                $('#historyTableContainer').hide();
                $('#historyCount').text('0 Records');
                return;
            }

            $('#historyNoData').hide();
            $('#historyTableContainer').show();
            $('#historyCount').text(data.length + ' Record' + (data.length !== 1 ? 's' : ''));

            let html = '';
            data.forEach(function(record) {
                const date = new Date(record.created_at);
                const dateStr = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const timeStr = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                
                const actionBadge = record.action === 'approved' 
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>'
                    : '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>';

                html += `
                    <tr>
                        <td>
                            <i class="bi bi-calendar3 text-muted me-1"></i> ${dateStr}<br>
                            <small class="text-muted"><i class="bi bi-clock text-muted me-1"></i> ${timeStr}</small>
                        </td>
                        <td>${actionBadge}</td>
                        <td><i class="bi bi-person-fill text-primary me-1"></i> ${escapeHtml(record.student_name)}</td>
                        <td>${escapeHtml(record.course_name)}</td>
                        <td><code>${escapeHtml(record.course_code)}</code></td>
                        <td><i class="bi bi-shield-fill-check text-success me-1"></i> ${escapeHtml(record.admin_name)}</td>
                    </tr>
                `;
            });

            $('#historyTableBody').html(html);
        }

        // Apply filters
        $('#applyFiltersBtn').on('click', function() {
            const action = $('#filterAction').val();
            const dateFrom = $('#filterDateFrom').val();
            const dateTo = $('#filterDateTo').val();
            const search = $('#filterSearch').val().toLowerCase();

            filteredHistoryData = historyData.filter(function(record) {
                // Filter by action
                if (action && record.action !== action) return false;
                
                // Filter by date range
                if (dateFrom && new Date(record.created_at) < new Date(dateFrom)) return false;
                if (dateTo && new Date(record.created_at) > new Date(dateTo + ' 23:59:59')) return false;
                
                // Filter by search
                if (search) {
                    const searchText = (
                        record.student_name + ' ' +
                        record.course_name + ' ' +
                        record.course_code + ' ' +
                        record.admin_name
                    ).toLowerCase();
                    if (!searchText.includes(search)) return false;
                }
                
                return true;
            });

            renderHistory(filteredHistoryData);
        });

        // Reset filters
        $('#resetFiltersBtn').on('click', function() {
            $('#filterAction').val('');
            $('#filterDateFrom').val('');
            $('#filterDateTo').val('');
            $('#filterSearch').val('');
            filteredHistoryData = historyData;
            renderHistory(filteredHistoryData);
        });

        // Export to CSV
        $('#exportCSVBtn').on('click', function() {
            if (!filteredHistoryData || filteredHistoryData.length === 0) {
                showNotification('warning', 'No data to export');
                return;
            }

            let csv = 'Date,Time,Action,Student,Course,Course Code,Processed By\n';
            
            filteredHistoryData.forEach(function(record) {
                const date = new Date(record.created_at);
                const dateStr = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const timeStr = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                
                csv += `"${dateStr}","${timeStr}","${record.action}","${record.student_name}","${record.course_name}","${record.course_code}","${record.admin_name}"\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `enrollment_history_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showNotification('success', 'History exported successfully');
        });
    </script>
    
    <!-- Add animation styles -->
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .table-success {
            animation: pulse 0.5s ease-in-out;
            background-color: #d1e7dd !important;
        }
        
        .table-danger {
            animation: pulse 0.5s ease-in-out;
            background-color: #f8d7da !important;
        }
        
        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
</body>
</html>
