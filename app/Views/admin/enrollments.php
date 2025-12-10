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
                    <h2><i class="bi bi-clipboard-check"></i> Manage Student Enrollments</h2>
                    <p class="text-muted mb-0">Add or remove student course enrollments</p>
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

            <!-- Loading Indicator -->
            <div class="loading-spinner" id="loadingSpinner">
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
                            enrolledCourseIds = response.enrolledCourseIds;
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
                $('main').prepend(alertHTML);
                setTimeout(function() {
                    $('.alert').fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 5000);
                $('html, body').animate({ scrollTop: 0 }, 400);
            }
        });
    </script>
</body>
</html>
