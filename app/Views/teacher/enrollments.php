<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Management - Teacher Panel</title>
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
            border-radius: 0;
            box-shadow: none;
            background: white;
        }
        .btn {
            border-radius: 0;
            padding: 0.5rem 1rem;
            font-weight: 400;
            transition: background 0.15s;
            border: none;
            font-size: 0.875rem;
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
        .btn-danger {
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
        .btn-danger:hover {
            background: #f3f4f6;
            color: #6b7280;
        }
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 0;
            padding: 0.5rem 0.75rem;
            transition: none;
            font-size: 0.875rem;
            color: #374151;
        }
        .form-control:focus {
            border-color: #2C5F8D;
            box-shadow: none;
            outline: none;
        }
        .table {
            border: none;
        }
        .table thead th {
            border-bottom: 1px solid #e5e7eb;
            background: white;
            font-weight: 700;
            color: #000000;
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
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            border-radius: 0;
            font-size: 0.8rem;
        }
        .badge.bg-warning {
            background: #E0E7FF !important;
            color: #3730A3;
        }
        .badge.bg-secondary {
            background: #f3f4f6 !important;
            color: #6b7280;
        }
        .no-data-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #9ca3af;
        }
        .no-data-state i {
            font-size: 3rem;
            opacity: 0.25;
            margin-bottom: 1rem;
            color: #2C5F8D;
        }
        h2, h3, h4, h5 {
            color: #1f2937;
            font-weight: 400;
        }
        .text-muted {
            color: #1f2937 !important;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #e8eaed;
            padding: 1rem 1.25rem;
        }
        .card-body {
            padding: 1.25rem;
        }
        small {
            font-weight: 700;
            color: #000000;
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
                        <a class="nav-link" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('teacher/enrollments') ?>">
                            <i class="bi bi-clipboard-check"></i> Enrollments
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-clipboard-check"></i> Enrollment Management</h2>
                <p class="text-muted mb-0">Review and approve student enrollment requests for your courses</p>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Search and Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Search Student</label>
                        <input type="text" class="form-control" id="searchStudent" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Search Course</label>
                        <input type="text" class="form-control" id="searchCourse" placeholder="Search by code or name...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Actions</label>
                        <div>
                            <button class="btn btn-primary w-100" onclick="loadPendingEnrollments()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Enrollments -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Pending Enrollment Requests</h5>
            </div>
            <div class="card-body">
                <!-- No Data Message -->
                <div id="noPendingMessage" class="no-data-state" style="display: none;">
                    <i class="bi bi-inbox"></i>
                    <h5>No Pending Enrollments</h5>
                    <p class="text-muted">All enrollment requests have been processed</p>
                </div>

                <!-- Enrollments Table -->
                <div id="enrollmentsContainer" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading enrollment requests...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let enrollmentsData = [];
        let todayStats = {
            approved: 0,
            rejected: 0,
            totalPending: 0,
            uniqueStudents: 0
        };

        // Load statistics from localStorage
        function loadStoredStats() {
            const stored = localStorage.getItem('teacher_enrollment_stats');
            if (stored) {
                todayStats = JSON.parse(stored);
                $('#approvedToday').text(todayStats.approved);
                $('#rejectedToday').text(todayStats.rejected);
                $('#totalPending').text(todayStats.totalPending);
                $('#uniqueStudents').text(todayStats.uniqueStudents);
            }
        }

        // Save statistics to localStorage
        function saveStats() {
            localStorage.setItem('teacher_enrollment_stats', JSON.stringify(todayStats));
        }

        // Load pending enrollments on page load
        $(document).ready(function() {
            loadStoredStats();
            loadPendingEnrollments();
            
            // Auto-refresh every 10 seconds
            setInterval(function() {
                loadPendingEnrollments();
            }, 10000);
        });

        // Load pending enrollments
        function loadPendingEnrollments() {
            $('#loadingSpinner').show();
            $('#enrollmentsContainer').hide();
            $('#noPendingMessage').hide();

            $.ajax({
                url: '<?= site_url('teacher/enrollments/pending') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        enrollmentsData = response.enrollments;
                        displayEnrollments(enrollmentsData);
                        updateStatistics(enrollmentsData);
                    } else {
                        showAlert('danger', response.message || 'Failed to load enrollments');
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while loading enrollments');
                },
                complete: function() {
                    $('#loadingSpinner').hide();
                }
            });
        }

        // Display enrollments in table
        function displayEnrollments(enrollments) {
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
                            <span class="badge" style="background: #E0E7FF; color: #231e63ff;">
                                <i class="bi bi-clock"></i> Pending
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-success approve-btn" 
                                        data-id="${enrollment.id}"
                                        data-student="${escapeHtml(enrollment.student_name)}"
                                        data-course="${escapeHtml(enrollment.course_code)} - ${escapeHtml(enrollment.course_name)}">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger reject-btn"
                                        data-id="${enrollment.id}"
                                        data-student="${escapeHtml(enrollment.student_name)}"
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

        // Update statistics
        function updateStatistics(enrollments) {
            const uniqueStudents = new Set(enrollments.map(e => e.user_id));
            
            // Update display
            $('#totalPending').text(enrollments.length);
            $('#uniqueStudents').text(uniqueStudents.size);
            
            // Update stored stats
            todayStats.totalPending = enrollments.length;
            todayStats.uniqueStudents = uniqueStudents.size;
            saveStats();
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

            displayEnrollments(filtered);
        });

        // Approve enrollment
        $(document).on('click', '.approve-btn', function() {
            const enrollmentId = $(this).data('id');
            const studentName = $(this).data('student');
            const courseName = $(this).data('course');

            if (confirm(`Approve enrollment for ${studentName} in ${courseName}?`)) {
                approveEnrollment(enrollmentId);
            }
        });

        function approveEnrollment(enrollmentId) {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/approve') ?>',
                type: 'POST',
                data: {
                    enrollment_id: enrollmentId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        $(`tr[data-enrollment-id="${enrollmentId}"]`).fadeOut(function() {
                            $(this).remove();
                        });
                        
                        // Update approved today count
                        todayStats.approved++;
                        $('#approvedToday').text(todayStats.approved);
                        saveStats();
                        
                        loadPendingEnrollments();
                    } else {
                        showAlert('danger', response.message || 'Failed to approve enrollment');
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while approving enrollment');
                }
            });
        }

        // Reject enrollment
        $(document).on('click', '.reject-btn', function() {
            const enrollmentId = $(this).data('id');
            const studentName = $(this).data('student');
            const courseName = $(this).data('course');

            if (confirm(`Reject enrollment for ${studentName} in ${courseName}?`)) {
                rejectEnrollment(enrollmentId);
            }
        });

        function rejectEnrollment(enrollmentId) {
            $.ajax({
                url: '<?= site_url('teacher/enrollments/reject') ?>',
                type: 'POST',
                data: {
                    enrollment_id: enrollmentId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        $(`tr[data-enrollment-id="${enrollmentId}"]`).fadeOut(function() {
                            $(this).remove();
                        });
                        
                        // Update rejected today count
                        todayStats.rejected++;
                        $('#rejectedToday').text(todayStats.rejected);
                        saveStats();
                        
                        loadPendingEnrollments();
                    } else {
                        showAlert('danger', response.message || 'Failed to reject enrollment');
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred while rejecting enrollment');
                }
            });
        }

        // Show alert message
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('#alertContainer').html(alertHtml);

            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>
