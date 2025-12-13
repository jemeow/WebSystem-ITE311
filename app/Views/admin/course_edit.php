<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Admin Panel</title>
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
    </style>
</head>
<body>
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

    <div class="container py-4">
        <main>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1"><i class="bi bi-pencil-square"></i> Edit Course</h2>
                            <div class="text-muted small">Update course information</div>
                        </div>
                        <a href="<?= site_url('/admin/courses') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Courses
                        </a>
                    </div>

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

                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body p-4">
                            <form action="<?= site_url('/admin/courses/update/' . $course['id']) ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="course_code" name="course_code"
                                               value="<?= old('course_code', $course['course_code']) ?>" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="course_name" name="course_name"
                                               value="<?= old('course_name', $course['course_name']) ?>" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"><?= old('description', $course['description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="teacher_id" class="form-label">Assigned Teacher</label>
                                        <select class="form-select" id="teacher_id" name="teacher_id">
                                            <?php $selectedTeacherId = old('teacher_id', $course['teacher_id'] ?? ''); ?>
                                            <option value="" <?= empty($selectedTeacherId) ? 'selected' : '' ?>>-- No Teacher Assigned --</option>
                                            <?php foreach($teachers as $teacher): ?>
                                                <option value="<?= $teacher['id'] ?>" <?= (string)$selectedTeacherId === (string)$teacher['id'] ? 'selected' : '' ?>>
                                                    <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="credits" class="form-label">Credits <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="credits" name="credits" min="1" max="10"
                                               value="<?= old('credits', $course['credits']) ?>" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <?php $selectedStatus = old('status', $course['status']); ?>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" <?= $selectedStatus === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= $selectedStatus === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="schedule_days" class="form-label">Schedule Days</label>
                                        <input type="text" class="form-control" id="schedule_days" name="schedule_days"
                                               value="<?= old('schedule_days', $course['schedule_days'] ?? '') ?>"
                                               placeholder="e.g., Mon, Wed, Fri">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="schedule_start_time" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="schedule_start_time" name="schedule_start_time"
                                               value="<?= old('schedule_start_time', $course['schedule_start_time'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="schedule_end_time" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="schedule_end_time" name="schedule_end_time"
                                               value="<?= old('schedule_end_time', $course['schedule_end_time'] ?? '') ?>">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between">
                                    <a href="<?= site_url('/admin/courses') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if(isset($enrollments) && !empty($enrollments)): ?>
                        <div class="card shadow mt-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bi bi-people"></i> Enrolled Students</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Student</th>
                                                <th>Email</th>
                                                <th>Enrollment Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($enrollments as $enrollment): ?>
                                                <tr>
                                                    <td><?= esc($enrollment['name'] ?? '') ?></td>
                                                    <td><?= esc($enrollment['email'] ?? '') ?></td>
                                                    <td><?= !empty($enrollment['enrollment_date']) ? date('M d, Y', strtotime($enrollment['enrollment_date'])) : '' ?></td>
                                                    <td><?= esc($enrollment['status'] ?? '') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include(APPPATH . 'Views/components/notification_js.php'); ?>
</body>
</html>
