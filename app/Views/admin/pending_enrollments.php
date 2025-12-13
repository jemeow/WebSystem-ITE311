<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Enrollment Approvals - Admin Panel</title>
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
            border-radius: 2px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: background 0.2s;
            border: none;
            font-size: 0.9rem;
        }
        .btn-primary, .btn-outline-primary {
            background: #2C5F8D;
            color: white;
        }
        .btn-primary:hover, .btn-outline-primary:hover {
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
        .btn-danger, .btn-warning {
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
        .btn-danger:hover, .btn-warning:hover {
            background: #f3f4f6;
            color: #6b7280;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
            color: #6b7280;
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
            padding: 0.4rem 0.75rem;
            font-weight: 500;
            border-radius: 2px;
            font-size: 0.85rem;
        }
        .badge-pending {
            background: #E0E7FF;
            color: #3730A3;
        }
        .badge.bg-secondary {
            background: #f3f4f6 !important;
            color: #6b7280;
        }
        .modal-content {
            border: 1px solid #e8eaed;
            border-radius: 2px;
        }
        .modal-header {
            border-bottom: 1px solid #e8eaed;
            background: white;
        }
        .modal-header.bg-success {
            background: #2C5F8D !important;
        }
        .modal-header.bg-danger {
            background: #ffffff !important;
            color: #1f2937 !important;
            border-bottom: 1px solid #e5e7eb;
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
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                        <a class="nav-link" href="<?= site_url('admin/courses') ?>">
                            <i class="bi bi-book"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin/enrollments') ?>">
                            <i class="bi bi-journals"></i> Enrollments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('admin/enrollments/pending-view') ?>">
                            <i class="bi bi-clock-history"></i> Pending Approvals
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php include(APPPATH . 'Views/components/notification_bell.php'); ?>
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
                    <h2><i class="bi bi-clock-history"></i> Pending Enrollment Approvals</h2>
                    <p class="text-muted">Review and approve student enrollment requests</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" id="refreshBtn">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer"></div>

            <!-- Filter and Search -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Search Student</label>
                            <input type="text" class="form-control" id="searchStudent" placeholder="Search by student name or email...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Search Course</label>
                            <input type="text" class="form-control" id="searchCourse" placeholder="Search by course code or name...">
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
        </main>
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
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle"></i> This action cannot be undone. The student will need to submit a new enrollment request.
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentEnrollmentId = null;
            let enrollmentsData = [];
            const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            
            let todayStats = {
                approved: 0,
                rejected: 0,
                totalPending: 0,
                uniqueStudents: 0
            };
            
            // Load statistics from localStorage
            function loadStoredStats() {
                const stored = localStorage.getItem('admin_enrollment_stats');
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
                localStorage.setItem('admin_enrollment_stats', JSON.stringify(todayStats));
            }
            
            // Load stored stats on page load
            loadStoredStats();

            // Load pending enrollments
            function loadPendingEnrollments() {
                $('#loadingSpinner').show();
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
                        } else {
                            showAlert('danger', response.message || 'Failed to load enrollments');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Error loading pending enrollments');
                        $('#loadingSpinner').hide();
                        $('#noPendingMessage').show();
                    }
                });
            }

            // Render enrollments table
            function renderEnrollments(enrollments) {
                $('#loadingSpinner').hide();
                
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
                                <span class="badge badge-pending">
                                    <i class="bi bi-clock"></i> Pending
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons btn-group" role="group">
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

                renderEnrollments(filtered);
            });

            // Approve button click
            $(document).on('click', '.approve-btn', function() {
                currentEnrollmentId = $(this).data('id');
                $('#approveStudentName').text($(this).data('student'));
                $('#approveStudentEmail').text($(this).data('email'));
                $('#approveCourse').text($(this).data('course'));
                approveModal.show();
            });

            // Confirm approve
            $('#confirmApprove').on('click', function() {
                const button = $(this);
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
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            approveModal.hide();
                            $(`tr[data-enrollment-id="${currentEnrollmentId}"]`).fadeOut(400, function() {
                                $(this).remove();
                                loadPendingEnrollments();
                            });
                            updateApprovedCount();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to approve enrollment');
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Approve');
                    }
                });
            });

            // Reject button click
            $(document).on('click', '.reject-btn', function() {
                currentEnrollmentId = $(this).data('id');
                $('#rejectStudentName').text($(this).data('student'));
                $('#rejectStudentEmail').text($(this).data('email'));
                $('#rejectCourse').text($(this).data('course'));
                rejectModal.show();
            });

            // Confirm reject
            $('#confirmReject').on('click', function() {
                const button = $(this);
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
                    success: function(response) {
                        if (response.success) {
                            showAlert('warning', response.message);
                            rejectModal.hide();
                            $(`tr[data-enrollment-id="${currentEnrollmentId}"]`).fadeOut(400, function() {
                                $(this).remove();
                                loadPendingEnrollments();
                            });
                            updateRejectedCount();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to reject enrollment');
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class="bi bi-x-circle"></i> Reject');
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

            // Update approved count
            function updateApprovedCount() {
                todayStats.approved++;
                $('#approvedToday').text(todayStats.approved);
                saveStats();
            }

            // Update rejected count
            function updateRejectedCount() {
                todayStats.rejected++;
                $('#rejectedToday').text(todayStats.rejected);
                saveStats();
            }

            // Show alert
            function showAlert(type, message) {
                const alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill'}"></i>
                        ${message}
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

            // Escape HTML
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

            // Initial load
            loadPendingEnrollments();

            // Auto-refresh every 10 seconds
            setInterval(function() {
                if (!$('.modal.show').length) {
                    loadPendingEnrollments();
                }
            }, 10000);
        });
    </script>
    <?php include(APPPATH . 'Views/components/notification_js.php'); ?>
</body>
</html>
