<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - Admin Panel</title>
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
    <div class="container py-4">
        <main>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-plus-circle"></i> Create New Course</h2>
                        <a href="<?= site_url('/admin/courses') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Courses
                        </a>
                    </div>

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
                            <form action="<?= site_url('/admin/courses/store') ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="course_code" name="course_code" 
                                           value="<?= old('course_code') ?>" required
                                           placeholder="e.g., CS101, MATH201">
                                    <small class="text-muted">Unique identifier for the course (3-20 characters)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="course_name" name="course_name" 
                                           value="<?= old('course_name') ?>" required
                                           placeholder="e.g., Introduction to Programming">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                              placeholder="Enter course description..."><?= old('description') ?></textarea>
                                    <small class="text-muted">Optional course description (max 1000 characters)</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="teacher_id" class="form-label">Assigned Teacher</label>
                                        <select class="form-select" id="teacher_id" name="teacher_id">
                                            <option value="">-- No Teacher Assigned --</option>
                                            <?php foreach($teachers as $teacher): ?>
                                                <option value="<?= $teacher['id'] ?>" <?= old('teacher_id') == $teacher['id'] ? 'selected' : '' ?>>
                                                    <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Optional teacher assignment</small>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="credits" class="form-label">Credits <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="credits" name="credits" 
                                               value="<?= old('credits', '3') ?>" min="1" max="10" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= site_url('/admin/courses') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Create Course
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
