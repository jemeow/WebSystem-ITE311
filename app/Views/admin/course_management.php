<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Admin Panel</title>
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
            transition: all 0.3s;
            border-left: 4px solid #0d6efd;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
        }
        .course-card.inactive {
            border-left-color: #6c757d;
            opacity: 0.7;
        }
        .filter-chip {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            margin: 0.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-chip:hover {
            transform: scale(1.05);
        }
        .filter-chip.active {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.2);
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
                        <a class="nav-link" href="<?= site_url('/admin/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Manage Enrollments
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-book"></i> Course Management</h2>
                    <p class="text-muted mb-0">Create, edit, and manage all courses</p>
                </div>
                <div>
                    <a href="<?= site_url('/admin/courses/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Course
                    </a>
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

            <!-- Filters and Search -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control" id="courseSearch" placeholder="Search courses by code, name, or teacher...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center">
                                <span class="me-2 text-muted">Filter:</span>
                                <span class="filter-chip bg-primary text-white active" data-filter="all">
                                    <i class="bi bi-list"></i> All (<?= count($courses) ?>)
                                </span>
                                <span class="filter-chip bg-success text-white" data-filter="active">
                                    <i class="bi bi-check-circle"></i> Active
                                </span>
                                <span class="filter-chip bg-secondary text-white" data-filter="inactive">
                                    <i class="bi bi-x-circle"></i> Inactive
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="row g-4" id="coursesGrid">
                <?php if(empty($courses)): ?>
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                <h5 class="mt-3">No Courses Yet</h5>
                                <p class="text-muted">Start by creating your first course</p>
                                <a href="<?= site_url('/admin/courses/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create Course
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($courses as $course): ?>
                        <div class="col-md-6 col-lg-4 course-item" 
                             data-status="<?= $course['status'] ?>"
                             data-search="<?= strtolower($course['course_code'] . ' ' . $course['course_name'] . ' ' . ($course['teacher_name'] ?? '')) ?>">
                            <div class="card course-card <?= $course['status'] === 'inactive' ? 'inactive' : '' ?> h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                <span class="badge bg-primary"><?= esc($course['course_code']) ?></span>
                                            </h5>
                                            <h6 class="mb-2"><?= esc($course['course_name']) ?></h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="<?= site_url('admin/course/' . $course['id']) ?>">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?= site_url('/admin/courses/edit/' . $course['id']) ?>">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item assign-teacher" href="#" 
                                                       data-id="<?= $course['id'] ?>" 
                                                       data-course-name="<?= esc($course['course_name']) ?>"
                                                       data-current-teacher="<?= $course['teacher_id'] ?? '' ?>"
                                                       data-schedule-days="<?= esc($course['schedule_days'] ?? '') ?>"
                                                       data-schedule-start="<?= esc($course['schedule_start_time'] ?? '') ?>"
                                                       data-schedule-end="<?= esc($course['schedule_end_time'] ?? '') ?>">
                                                        <i class="bi bi-person-plus"></i> Assign Teacher
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?= site_url('/admin/course/' . $course['id']) ?>">
                                                        <i class="bi bi-cloud-upload"></i> Upload Materials
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item toggle-status" href="#" data-id="<?= $course['id'] ?>" data-status="<?= $course['status'] ?>">
                                                        <i class="bi bi-toggle-<?= $course['status'] === 'active' ? 'off' : 'on' ?>"></i> 
                                                        <?= $course['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger delete-course" href="#" data-id="<?= $course['id'] ?>" data-name="<?= esc($course['course_name']) ?>">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <p class="card-text text-muted small mb-3">
                                        <?= esc($course['description'] ?: 'No description available') ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> <?= esc($course['teacher_name'] ?? 'No teacher assigned') ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-<?= $course['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($course['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 p-2" style="background: #f3f4f6; border-radius: 4px;">
                                        <?php if (!empty($course['schedule_days']) && !empty($course['schedule_start_time'])): ?>
                                            <small class="fw-bold text-dark">
                                                <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?>
                                            </small>
                                            <br>
                                            <small class="fw-bold text-dark">
                                                <i class="bi bi-clock"></i> <?= date('g:i A', strtotime($course['schedule_start_time'])) ?> - <?= date('g:i A', strtotime($course['schedule_end_time'])) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-x"></i> No schedule set
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-award"></i> <?= $course['credits'] ?> credit<?= $course['credits'] != 1 ? 's' : '' ?>
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-people-fill"></i> <?= $course['enrollment_count'] ?> student<?= $course['enrollment_count'] != 1 ? 's' : '' ?>
                                        </small>
                                    </div>
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
                    <p class="text-muted">Try adjusting your search or filters</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Assign Teacher Modal -->
    <div class="modal fade" id="assignTeacherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Assign Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Assign a teacher to: <strong id="modalCourseName"></strong></p>
                    
                    <div class="mb-3">
                        <label for="teacherSelect" class="form-label fw-bold">Select Teacher</label>
                        <select class="form-select" id="teacherSelect">
                            <option value="">-- No Teacher (Remove Assignment) --</option>
                            <?php if(isset($teachers) && !empty($teachers)): ?>
                                <?php foreach($teachers as $teacher): ?>
                                    <option value="<?= $teacher['id'] ?>"><?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Schedule Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-calendar-week"></i> Class Schedule</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Days of the Week *</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Mon" id="dayMon" name="schedule_days">
                                            <label class="form-check-label" for="dayMon">Mon</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Tue" id="dayTue" name="schedule_days">
                                            <label class="form-check-label" for="dayTue">Tue</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Wed" id="dayWed" name="schedule_days">
                                            <label class="form-check-label" for="dayWed">Wed</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Thu" id="dayThu" name="schedule_days">
                                            <label class="form-check-label" for="dayThu">Thu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Fri" id="dayFri" name="schedule_days">
                                            <label class="form-check-label" for="dayFri">Fri</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Sat" id="daySat" name="schedule_days">
                                            <label class="form-check-label" for="daySat">Sat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Sun" id="daySun" name="schedule_days">
                                            <label class="form-check-label" for="daySun">Sun</label>
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
                            <small class="text-muted">* Required when assigning a teacher</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAssignTeacher">
                        <i class="bi bi-check-circle"></i> Assign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentFilter = 'all';
            let currentCourseId = null;
            const assignTeacherModal = new bootstrap.Modal(document.getElementById('assignTeacherModal'));

            // Search functionality
            $('#courseSearch').on('keyup', function() {
                filterCourses();
            });

            // Filter chips
            $('.filter-chip').on('click', function() {
                $('.filter-chip').removeClass('active');
                $(this).addClass('active');
                currentFilter = $(this).data('filter');
                filterCourses();
            });

            function filterCourses() {
                const searchTerm = $('#courseSearch').val().toLowerCase();
                let visibleCount = 0;

                $('.course-item').each(function() {
                    const $item = $(this);
                    const status = $item.data('status');
                    const searchData = $item.data('search');
                    
                    let matchesFilter = currentFilter === 'all' || status === currentFilter;
                    let matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
                    
                    if (matchesFilter && matchesSearch) {
                        $item.show();
                        visibleCount++;
                    } else {
                        $item.hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#noResults').show();
                } else {
                    $('#noResults').hide();
                }
            }

            // Check schedule conflicts
            function checkScheduleConflict() {
                const teacherId = $('#teacherSelect').val();
                const selectedDays = $('input[name="schedule_days"]:checked').map(function() {
                    return $(this).val();
                }).get();
                const startTime = $('#scheduleStartTime').val();
                const endTime = $('#scheduleEndTime').val();

                // Validate schedule input
                if (!teacherId || selectedDays.length === 0 || !startTime || !endTime) {
                    if (teacherId) {
                        $('#confirmAssignTeacher').prop('disabled', true);
                    }
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
                        teacher_id: teacherId,
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
            $('#teacherSelect, input[name="schedule_days"], #scheduleStartTime, #scheduleEndTime').on('change', function() {
                const teacherId = $('#teacherSelect').val();
                if (teacherId) {
                    checkScheduleConflict();
                } else {
                    $('#scheduleConflictWarning').hide();
                    $('#confirmAssignTeacher').prop('disabled', false);
                }
            });

            // Assign teacher button
            $(document).on('click', '.assign-teacher', function(e) {
                e.preventDefault();
                
                currentCourseId = $(this).data('id');
                const courseName = $(this).data('course-name');
                const currentTeacher = $(this).data('current-teacher');
                const currentScheduleDays = $(this).data('schedule-days');
                const currentStartTime = $(this).data('schedule-start');
                const currentEndTime = $(this).data('schedule-end');
                
                $('#modalCourseName').text(courseName);
                $('#teacherSelect').val(currentTeacher || '');
                
                // Reset schedule fields first
                $('input[name="schedule_days"]').prop('checked', false);
                $('#scheduleStartTime').val('');
                $('#scheduleEndTime').val('');
                
                // Load existing schedule if available
                if (currentScheduleDays) {
                    const days = currentScheduleDays.split(', ');
                    days.forEach(day => {
                        $(`input[name="schedule_days"][value="${day}"]`).prop('checked', true);
                    });
                }
                if (currentStartTime) {
                    $('#scheduleStartTime').val(currentStartTime);
                }
                if (currentEndTime) {
                    $('#scheduleEndTime').val(currentEndTime);
                }
                
                $('#scheduleConflictWarning').hide();
                $('#confirmAssignTeacher').prop('disabled', false);
                
                assignTeacherModal.show();
            });

            // Confirm assign teacher
            $('#confirmAssignTeacher').on('click', function() {
                const teacherId = $('#teacherSelect').val();
                const button = $(this);
                
                // Get schedule data
                const selectedDays = $('input[name="schedule_days"]:checked').map(function() {
                    return $(this).val();
                }).get().join(', ');
                const startTime = $('#scheduleStartTime').val();
                const endTime = $('#scheduleEndTime').val();

                console.log('Sending data:', {
                    course_id: currentCourseId,
                    teacher_id: teacherId,
                    schedule_days: selectedDays,
                    schedule_start_time: startTime,
                    schedule_end_time: endTime
                });

                // Validate if teacher is selected
                if (teacherId && (!selectedDays || !startTime || !endTime)) {
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
                        teacher_id: teacherId,
                        schedule_days: selectedDays,
                        schedule_start_time: startTime,
                        schedule_end_time: endTime,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Server response:', response);
                        if (response.success) {
                            assignTeacherModal.hide();
                            showAlert('success', response.message);
                            // Force reload to show updated schedule
                            setTimeout(() => {
                                window.location.reload(true);
                            }, 1000);
                        } else {
                            // Show error alert at top of modal
                            showAlert('danger', response.message);
                            // Keep modal open for user to adjust schedule
                            button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Assign');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to assign teacher');
                        button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Assign');
                    }
                });
            });

            // Toggle status
            $(document).on('click', '.toggle-status', function(e) {
                e.preventDefault();
                const courseId = $(this).data('id');
                const currentStatus = $(this).data('status');
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                
                if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this course?`)) {
                    return;
                }

                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/courses/toggle-status') ?>/' + courseId,
                    type: 'POST',
                    data: {
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to update course status');
                    }
                });
            });

            // Delete course
            $(document).on('click', '.delete-course', function(e) {
                e.preventDefault();
                const courseId = $(this).data('id');
                const courseName = $(this).data('name');
                
                if (!confirm(`Are you sure you want to delete "${courseName}"? This action cannot be undone.`)) {
                    return;
                }

                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('/admin/courses/delete') ?>/' + courseId,
                    type: 'POST',
                    data: {
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to delete course');
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
    <?php include(APPPATH . 'Views/components/notification_js.php'); ?>
</body>
</html>
