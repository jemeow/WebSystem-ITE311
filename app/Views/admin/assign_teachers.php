<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Teachers - Admin Panel</title>
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
        .course-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s;
        }
        .course-card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .course-card.has-teacher {
            border-left-color: #198754;
        }
        .course-card.no-teacher {
            border-left-color: #ffc107;
        }
        .teacher-badge {
            font-size: 0.875rem;
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
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark header-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/admin/dashboard') ?>">
                <i class="bi bi-mortarboard-fill"></i> ITE311-ZURITA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/users') ?>">
                            <i class="bi bi-people"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('/admin/courses') ?>">
                            <i class="bi bi-book"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/enrollments/dashboard') ?>">
                            <i class="bi bi-clipboard-data"></i> Enrollment Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Manage Enrollments
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

    <!-- Main Content -->
    <div class="container-fluid px-4 py-4">
        <main>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-person-workspace"></i> Assign Teachers to Courses</h2>
                    <p class="text-muted mb-0">Manage teacher assignments for all courses</p>
                </div>
                <a href="<?= site_url('/admin/courses') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Courses
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Search and Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control" id="courseSearch" placeholder="Search courses...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h5 class="mb-0"><?= count($courses) ?></h5>
                                    <small class="text-muted">Total Courses</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="mb-0 text-success"><?= count(array_filter($courses, fn($c) => !empty($c['teacher_id']))) ?></h5>
                                    <small class="text-muted">With Teachers</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="mb-0 text-warning"><?= count(array_filter($courses, fn($c) => empty($c['teacher_id']))) ?></h5>
                                    <small class="text-muted">Unassigned</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses List -->
            <div class="row g-4" id="coursesGrid">
                <?php if(empty($courses)): ?>
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                <h5 class="mt-3">No Courses Yet</h5>
                                <p class="text-muted">Create courses first before assigning teachers</p>
                                <a href="<?= site_url('/admin/courses/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create Course
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($courses as $course): ?>
                        <div class="col-md-6 col-lg-4 course-item" 
                             data-search="<?= strtolower($course['course_code'] . ' ' . $course['course_name']) ?>">
                            <div class="card course-card <?= empty($course['teacher_id']) ? 'no-teacher' : 'has-teacher' ?> h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-primary"><?= esc($course['course_code']) ?></span>
                                            <h6 class="mt-2 mb-1"><?= esc($course['course_name']) ?></h6>
                                            <small class="text-muted">
                                                <i class="bi bi-award"></i> <?= $course['credits'] ?> credit<?= $course['credits'] != 1 ? 's' : '' ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?= $course['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($course['status']) ?>
                                        </span>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">
                                            <i class="bi bi-person-circle"></i> Assigned Teacher
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <?php if(empty($course['teacher_id'])): ?>
                                                <span class="text-warning teacher-badge">
                                                    <i class="bi bi-exclamation-triangle"></i> No teacher assigned
                                                </span>
                                            <?php else: ?>
                                                <span class="text-success teacher-badge">
                                                    <i class="bi bi-check-circle-fill"></i> <?= esc($course['teacher_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                        <div class="mb-3 p-2" style="background: #f3f4f6; border-radius: 4px;">
                                            <label class="form-label small text-muted mb-1">
                                                <i class="bi bi-calendar-week"></i> Schedule
                                            </label>
                                            <div>
                                                <small class="fw-bold text-dark">
                                                    <?= esc($course['schedule_days']) ?>
                                                </small>
                                                <br>
                                                <small class="fw-bold text-dark">
                                                    <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <button class="btn btn-primary btn-sm w-100 assign-teacher-btn" 
                                            data-course-id="<?= $course['id'] ?>"
                                            data-course-name="<?= esc($course['course_name']) ?>"
                                            data-course-code="<?= esc($course['course_code']) ?>"
                                            data-current-teacher="<?= $course['teacher_id'] ?? '' ?>">
                                        <i class="bi bi-person-plus"></i> 
                                        <?= empty($course['teacher_id']) ? 'Assign Teacher' : 'Change Teacher' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="noResults" class="card shadow-sm mt-4" style="display: none;">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3">No Courses Found</h5>
                    <p class="text-muted">Try adjusting your search</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Assign Teacher Modal -->
    <div class="modal fade" id="assignTeacherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus"></i> Assign Teacher
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong><i class="bi bi-book"></i> Course:</strong> <span id="modalCourseCode"></span> - <span id="modalCourseName"></span>
                    </div>

                    <div class="mb-3">
                        <label for="teacherSearchInput" class="form-label">Search Teachers</label>
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" class="form-control" id="teacherSearchInput" placeholder="Search by name or email...">
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="alert alert-primary">
                        <h6 class="mb-3"><i class="bi bi-calendar-week"></i> Class Schedule</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="scheduleDays" class="form-label fw-bold">Days of the Week *</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Mon" id="dayMon" name="schedule_days">
                                        <label class="form-check-label" for="dayMon">Monday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Tue" id="dayTue" name="schedule_days">
                                        <label class="form-check-label" for="dayTue">Tuesday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Wed" id="dayWed" name="schedule_days">
                                        <label class="form-check-label" for="dayWed">Wednesday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Thu" id="dayThu" name="schedule_days">
                                        <label class="form-check-label" for="dayThu">Thursday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Fri" id="dayFri" name="schedule_days">
                                        <label class="form-check-label" for="dayFri">Friday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Sat" id="daySat" name="schedule_days">
                                        <label class="form-check-label" for="daySat">Saturday</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Sun" id="daySun" name="schedule_days">
                                        <label class="form-check-label" for="daySun">Sunday</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="scheduleStartTime" class="form-label fw-bold">Start Time *</label>
                                <input type="time" class="form-control" id="scheduleStartTime" required>
                            </div>
                            <div class="col-md-6">
                                <label for="scheduleEndTime" class="form-label fw-bold">End Time *</label>
                                <input type="time" class="form-control" id="scheduleEndTime" required>
                            </div>
                        </div>
                        <small class="text-muted">* Required fields when assigning a teacher</small>
                    </div>

                    <!-- Schedule Conflict Warning -->
                    <div id="scheduleConflictWarning" class="alert alert-danger" style="display: none;">
                        <h6><i class="bi bi-exclamation-triangle"></i> Schedule Conflict Detected!</h6>
                        <p class="mb-0" id="conflictMessage"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Teacher</label>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                            <div class="list-group list-group-flush" id="teachersList">
                                <button type="button" class="list-group-item list-group-item-action teacher-option" data-teacher-id="">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-x-circle text-danger me-2"></i>
                                        <div>
                                            <h6 class="mb-0">No Teacher (Remove Assignment)</h6>
                                            <small class="text-muted">Leave course without a teacher</small>
                                        </div>
                                    </div>
                                </button>
                                <?php foreach($teachers as $teacher): ?>
                                    <button type="button" class="list-group-item list-group-item-action teacher-option" 
                                            data-teacher-id="<?= $teacher['id'] ?>"
                                            data-teacher-name="<?= esc($teacher['name']) ?>"
                                            data-search="<?= strtolower($teacher['name'] . ' ' . $teacher['email']) ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">
                                                    <i class="bi bi-person-circle text-primary"></i> <?= esc($teacher['name']) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope"></i> <?= esc($teacher['email']) ?>
                                                </small>
                                            </div>
                                            <i class="bi bi-check-circle text-success" style="display: none;"></i>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="noTeachersFound" class="alert alert-warning" style="display: none;">
                        <i class="bi bi-search"></i> No teachers match your search
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmAssignTeacher" disabled>
                        <i class="bi bi-check-circle"></i> Assign Teacher
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentCourseId = null;
            let selectedTeacherId = null;
            const assignTeacherModal = new bootstrap.Modal(document.getElementById('assignTeacherModal'));

            // Course search
            $('#courseSearch').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.course-item').each(function() {
                    const searchData = $(this).data('search');
                    if (searchTerm === '' || searchData.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#noResults').show();
                } else {
                    $('#noResults').hide();
                }
            });

            // Open assign teacher modal
            $(document).on('click', '.assign-teacher-btn', function() {
                currentCourseId = $(this).data('course-id');
                const courseName = $(this).data('course-name');
                const courseCode = $(this).data('course-code');
                const currentTeacher = $(this).data('current-teacher');

                $('#modalCourseCode').text(courseCode);
                $('#modalCourseName').text(courseName);
                $('#teacherSearchInput').val('');
                
                // Reset selection
                $('.teacher-option').removeClass('active');
                $('.teacher-option i.bi-check-circle').hide();
                selectedTeacherId = null;
                $('#confirmAssignTeacher').prop('disabled', true);

                // Highlight current teacher if exists
                if (currentTeacher) {
                    $(`.teacher-option[data-teacher-id="${currentTeacher}"]`).addClass('active')
                        .find('i.bi-check-circle').show();
                }

                $('.teacher-option').show();
                $('#noTeachersFound').hide();
                assignTeacherModal.show();
            });

            // Teacher search in modal
            $('#teacherSearchInput').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.teacher-option').each(function() {
                    const searchData = $(this).data('search');
                    if (!searchData || searchTerm === '' || searchData.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#noTeachersFound').show();
                } else {
                    $('#noTeachersFound').hide();
                }
            });

            // Select teacher
            $(document).on('click', '.teacher-option', function() {
                $('.teacher-option').removeClass('active');
                $('.teacher-option i.bi-check-circle').hide();
                
                $(this).addClass('active');
                $(this).find('i.bi-check-circle').show();
                
                selectedTeacherId = $(this).data('teacher-id') || null;
                
                // Check schedule conflicts if teacher selected
                if (selectedTeacherId) {
                    checkScheduleConflict();
                } else {
                    $('#scheduleConflictWarning').hide();
                    $('#confirmAssignTeacher').prop('disabled', false);
                }
            });

            // Check schedule conflicts
            function checkScheduleConflict() {
                const selectedDays = $('input[name="schedule_days"]:checked').map(function() {
                    return $(this).val();
                }).get();
                const startTime = $('#scheduleStartTime').val();
                const endTime = $('#scheduleEndTime').val();

                // Validate schedule input
                if (selectedDays.length === 0 || !startTime || !endTime) {
                    $('#confirmAssignTeacher').prop('disabled', true);
                    return;
                }

                // Validate time range
                if (startTime >= endTime) {
                    $('#scheduleConflictWarning').show();
                    $('#conflictMessage').text('End time must be after start time.');
                    $('#confirmAssignTeacher').prop('disabled', true);
                    return;
                }

                // Check for conflicts via AJAX
                $.ajax({
                    url: '<?= site_url("/admin/courses/check-schedule-conflict") ?>',
                    type: 'POST',
                    data: {
                        teacher_id: selectedTeacherId,
                        course_id: currentCourseId,
                        schedule_days: selectedDays.join(','),
                        start_time: startTime,
                        end_time: endTime,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.conflict) {
                            $('#scheduleConflictWarning').show();
                            $('#conflictMessage').html(response.message);
                            $('#confirmAssignTeacher').prop('disabled', true);
                        } else {
                            $('#scheduleConflictWarning').hide();
                            $('#confirmAssignTeacher').prop('disabled', false);
                        }
                    },
                    error: function() {
                        $('#scheduleConflictWarning').hide();
                        $('#confirmAssignTeacher').prop('disabled', false);
                    }
                });
            }

            // Trigger conflict check when schedule changes
            $('input[name="schedule_days"], #scheduleStartTime, #scheduleEndTime').on('change', function() {
                if (selectedTeacherId) {
                    checkScheduleConflict();
                }
            });

            // Confirm assignment
            $('#confirmAssignTeacher').on('click', function() {
                const button = $(this);
                
                // Get schedule data
                const selectedDays = $('input[name="schedule_days"]:checked').map(function() {
                    return $(this).val();
                }).get().join(',');
                const startTime = $('#scheduleStartTime').val();
                const endTime = $('#scheduleEndTime').val();

                // Validate if teacher is selected
                if (selectedTeacherId && (!selectedDays || !startTime || !endTime)) {
                    showAlert('danger', 'Please fill in all schedule fields when assigning a teacher.');
                    return;
                }

                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Assigning...');

                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/courses/assign-teacher') ?>',
                    type: 'POST',
                    data: {
                        course_id: currentCourseId,
                        teacher_id: selectedTeacherId,
                        schedule_days: selectedDays,
                        schedule_start_time: startTime,
                        schedule_end_time: endTime,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            assignTeacherModal.hide();
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('danger', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Assign Teacher');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to assign teacher');
                        button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Assign Teacher');
                    }
                });
            });

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
