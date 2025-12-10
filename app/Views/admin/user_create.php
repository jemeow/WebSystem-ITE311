<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin Panel</title>
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
                        <a class="nav-link active" href="<?= site_url('admin/users') ?>">
                            <i class="bi bi-people"></i> Users
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                        </span>
                    </li>
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
                        <h2><i class="bi bi-person-plus"></i> Create New User</h2>
                    </div>
                    <div>
                        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Create Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-person-badge"></i> User Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= site_url('admin/users/store') ?>">
                                    <?= csrf_field() ?>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="bi bi-person"></i> Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?= old('name') ?>" 
                                               placeholder="Enter full name"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope"></i> Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?= old('email') ?>" 
                                               placeholder="Enter email address"
                                               required>
                                        <div class="form-text">This will be used for login.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">
                                            <i class="bi bi-shield-check"></i> Role <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" 
                                                id="role" 
                                                name="role" 
                                                required>
                                            <option value="">Select Role</option>
                                            <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                                            <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        <div class="form-text">Select the user's role in the system.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-key"></i> Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter password"
                                               required>
                                        <div class="form-text">Minimum 6 characters.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">
                                            <i class="bi bi-key-fill"></i> Confirm Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               placeholder="Re-enter password"
                                               required>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Create User
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
    <script>
        // Show validation errors as alert
        <?php if(session()->getFlashdata('errors')): ?>
            let errorMessage = 'Validation Errors:\n\n';
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                errorMessage += '• <?= esc($error) ?>\n';
            <?php endforeach; ?>
            alert(errorMessage);
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            alert('<?= esc(session()->getFlashdata('error')) ?>');
        <?php endif; ?>

        // Password match validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });

        password.addEventListener('input', function() {
            if (confirmPassword.value) {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        });
    </script>
</body>
</html>
