<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
                    <?php if(session()->get('role') === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/users') ?>">
                                <i class="bi bi-people"></i> Users
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(session()->get('role') === 'instructor' || session()->get('role') === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-book"></i> My Courses
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="<?= site_url('profile/edit') ?>"><i class="bi bi-pencil"></i> Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <?php if(session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show">
                                        <?= session()->getFlashdata('success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="<?= site_url('profile/update') ?>" method="POST" id="profileForm">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?= old('name', $user['name']) ?>" 
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?= old('email', $user['email']) ?>" 
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="role" 
                                               value="<?= ucfirst($user['role']) ?>" 
                                               disabled>
                                        <small class="text-muted">Role cannot be changed</small>
                                    </div>

                                    <hr>

                                    <h5 class="mb-3">Change Password (Optional)</h5>
                                    <p class="text-muted small">Leave blank to keep current password</p>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password">
                                        <small class="text-muted">Minimum 6 characters</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password">
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
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

        // Password matching validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== '' || confirmPassword !== '') {
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
            }
        });
    </script>
</body>
</html>
