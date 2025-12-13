<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Enrollments - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-nav {
            background: #133980;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .course-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        .course-card.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .student-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        .student-card.selected {
            border-color: #198754;
            background-color: #d1e7dd;
        }
        .enrolled-badge {
            background-color: #198754;
        }
        .not-enrolled-badge {
            background-color: #6c757d;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding-left: 40px;
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
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
                        <a class="nav-link" href="<?= site_url('teacher/courses') ?>">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('teacher/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Pending Enrollments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('teacher/manage-students') ?>">
                            <i class="bi bi-people"></i> Manage Students
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php include(APPPATH . 'Views/components/notification_bell.php'); ?>
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

    <!-- Main Content -->
    <div class="container-fluid px-4 py-4">
        <main>
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-people"></i> Manage Student Enrollments</h2>
                    <p class="text-muted mb-0">Enroll or unenroll students in your courses</p>
                </div>
            </div>

            <?php if(empty($courses)): ?>
                <!-- No Courses Message -->
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> You don't have any active courses yet.
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Left: Course Selection -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-book"></i> Select Course</h5>
                            </div>
                            <div class="card-body">
                                <div class="search-box mb-3">
                                    <i class="bi bi-search search-icon"></i>
                                    <input type="text" class="form-control" id="courseSearch" placeholder="Search courses...">
                                </div>
                                <div id="coursesList">
                                    <?php foreach($courses as $course): ?>
                                        <div class="card course-card mb-2" data-course-id="<?= $course['id'] ?>" data-course-name="<?= esc($course['course_name']) ?>" data-course-code="<?= esc($course['course_code']) ?>">
                                            <div class="card-body py-2">
                                                <h6 class="mb-1">
                                                    <span class="badge bg-primary"><?= esc($course['course_code']) ?></span>
                                                </h6>
                                                <small><?= esc($course['course_name']) ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Student Management -->
                    <div class="col-md-8">
                        <div id="noSelection" class="text-center py-5">
                            <i class="bi bi-arrow-left-circle" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 text-muted">Select a course to manage students</h5>
                        </div>

                        <div id="studentManagement" style="display: none;">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Course Information</h5>
                                </div>
                                <div class="card-body">
                                    <h5 id="selectedCourseName"></h5>
                                    <p class="mb-0 text-muted">
                                        <span class="badge bg-primary" id="selectedCourseCode"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-people"></i> Students</h5>
                                </div>
                                <div class="card-body">
                                    <div class="search-box mb-3">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" class="form-control" id="studentSearch" placeholder="Search students by name or email...">
                                    </div>
                                    <div id="studentsList" class="row">
                                        <?php foreach($students as $student): ?>
                                            <div class="col-md-6 mb-3 student-item" data-student-id="<?= $student['id'] ?>" data-search="<?= strtolower($student['name'] . ' ' . $student['email']) ?>">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1"><?= esc($student['name']) ?></h6>
                                                                <small class="text-muted"><?= esc($student['email']) ?></small>
                                                            </div>
                                                            <div>
                                                                <span class="badge not-enrolled-badge enrollment-status" data-student-id="<?= $student['id'] ?>">
                                                                    Not Enrolled
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <button class="btn btn-sm btn-success enroll-btn" data-student-id="<?= $student['id'] ?>" data-student-name="<?= esc($student['name']) ?>" style="display: none;">
                                                                <i class="bi bi-plus-circle"></i> Enroll
                                                            </button>
                                                            <button class="btn btn-sm btn-danger unenroll-btn" data-student-id="<?= $student['id'] ?>" data-student-name="<?= esc($student['name']) ?>" style="display: none;">
                                                                <i class="bi bi-x-circle"></i> Unenroll
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include(APPPATH . 'Views/components/notification_js.php'); ?>
    <script>
        let selectedCourseId = null;
        let enrolledStudentIds = [];

        $(document).ready(function() {
            // Course selection
            $('.course-card').on('click', function() {
                $('.course-card').removeClass('selected');
                $(this).addClass('selected');
                
                selectedCourseId = $(this).data('course-id');
                const courseName = $(this).data('course-name');
                const courseCode = $(this).data('course-code');
                
                $('#selectedCourseName').text(courseName);
                $('#selectedCourseCode').text(courseCode);
                
                $('#noSelection').hide();
                $('#studentManagement').show();
                
                // Load student enrollment status
                loadStudentEnrollments();
            });

            // Course search
            $('#courseSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.course-card').each(function() {
                    const courseName = $(this).data('course-name').toLowerCase();
                    const courseCode = $(this).data('course-code').toLowerCase();
                    if (courseName.includes(searchTerm) || courseCode.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Student search
            $('#studentSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.student-item').each(function() {
                    const searchText = $(this).data('search');
                    if (searchText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Enroll button
            $(document).on('click', '.enroll-btn', function() {
                const studentId = $(this).data('student-id');
                const studentName = $(this).data('student-name');
                
                if (confirm(`Enroll ${studentName} in this course?`)) {
                    enrollStudent(studentId);
                }
            });

        //need to be fixed (can't unenroll student | error message: "Unenroll endpoint not found") !! must be fixed tonight, thank you)
            // Unenroll button
            $(document).on('click', '.unenroll-btn', function() {
                const studentId = $(this).data('student-id');
                const studentName = $(this).data('student-name');
                
                if (confirm(`Unenroll ${studentName} from this course?`)) {
                    unenrollStudent(studentId);
                }
            });
        });

        function loadStudentEnrollments() {
            // Reset all buttons
            $('.enrollment-status').removeClass('enrolled-badge').addClass('not-enrolled-badge').text('Not Enrolled');
            $('.enroll-btn, .unenroll-btn').hide();
            
            // Check each student individually
            $('.student-item').each(function() {
                const studentId = $(this).find('.enrollment-status').data('student-id');
                checkStudentEnrollment(studentId);
            });
        }

        function checkStudentEnrollment(studentId) {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/get-student-enrollments') ?>',
                type: 'POST',
                data: {
                    student_id: studentId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        const isEnrolled = response.enrolled_course_ids.includes(parseInt(selectedCourseId));
                        updateStudentStatus(studentId, isEnrolled);
                    }
                }
            });
        }

        function updateStudentStatus(studentId, isEnrolled) {
            const statusBadge = $(`.enrollment-status[data-student-id="${studentId}"]`);
            const enrollBtn = $(`.enroll-btn[data-student-id="${studentId}"]`);
            const unenrollBtn = $(`.unenroll-btn[data-student-id="${studentId}"]`);
            
            if (isEnrolled) {
                statusBadge.removeClass('not-enrolled-badge').addClass('enrolled-badge').text('Enrolled');
                enrollBtn.hide();
                unenrollBtn.show();
            } else {
                statusBadge.removeClass('enrolled-badge').addClass('not-enrolled-badge').text('Not Enrolled');
                enrollBtn.show();
                unenrollBtn.hide();
            }
        }

        function enrollStudent(studentId) {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/enroll-student') ?>',
                type: 'POST',
                data: {
                    student_id: studentId,
                    course_id: selectedCourseId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        updateStudentStatus(studentId, true);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'An error occurred while enrolling student');
                }
            });
        }

        //function needs to be checked. not functionable !! must be fixed tonight, thank you
        function unenrollStudent(studentId) {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/unenroll-student') ?>',
                type: 'POST',
                data: {
                    student_id: studentId,
                    course_id: selectedCourseId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        updateStudentStatus(studentId, false);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'An error occurred while unenrolling student');
                }
            });
        }

        function showAlert(type, message) {
            const alertDiv = $('<div>', {
                class: `alert alert-${type} alert-dismissible fade show`,
                html: `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`
            });
            
            $('main').prepend(alertDiv);
            
            setTimeout(() => {
                alertDiv.fadeOut(() => alertDiv.remove());
            }, 5000);
            
            $('html, body').animate({ scrollTop: 0 }, 400);
        }
    </script>
</body>
</html>
