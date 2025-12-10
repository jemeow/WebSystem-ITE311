<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Panel</title>
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
                        <h2><i class="bi bi-pencil-square"></i> Edit User</h2>
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

                <!-- Edit Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-person-badge"></i> User Information
                                    <?php if($isOwnAccount): ?>
                                        <span class="badge bg-info ms-2">Your Account</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?= site_url('admin/users/update/' . esc($user['id'])) ?>">
                                    <?= csrf_field() ?>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="bi bi-person"></i> Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?= esc(old('name', $user['name'])) ?>" 
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
                                               value="<?= esc(old('email', $user['email'])) ?>" 
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">
                                            <i class="bi bi-shield-check"></i> Role <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" 
                                                id="role" 
                                                name="role" 
                                                <?= $isOwnAccount ? 'disabled' : '' ?>
                                                required>
                                            <option value="student" <?= old('role', $user['role']) === 'student' ? 'selected' : '' ?>>Student</option>
                                            <option value="teacher" <?= old('role', $user['role']) === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                            <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        
                                        <?php if($isOwnAccount): ?>
                                            <!-- Hidden field to preserve role when disabled -->
                                            <input type="hidden" name="role" value="<?= esc($user['role']) ?>">
                                            <div class="form-text text-warning">
                                                <i class="bi bi-info-circle"></i> You cannot change your own role for security reasons.
                                            </div>
                                        <?php else: ?>
                                            <div class="form-text">
                                                Select the user's role in the system. <strong>Changing roles requires password confirmation.</strong>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Password confirmation field (shown when role changes) -->
                                    <div class="mb-3" id="passwordConfirmSection" style="display: none;">
                                        <label for="admin_password" class="form-label">
                                            <i class="bi bi-key"></i> Confirm Your Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="admin_password" 
                                               name="admin_password" 
                                               placeholder="Enter your password to confirm role change">
                                        <div class="form-text text-info">
                                            <i class="bi bi-shield-lock"></i> For security, you must enter your password to change user roles.
                                        </div>
                                    </div>

                                    <?php if($user['role'] !== 'admin'): ?>
                                        <hr>
                                        <h5 class="mb-3">Change User Password (Optional)</h5>
                                        <p class="text-muted small">Leave blank to keep current password</p>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="new_password" 
                                                   name="new_password"
                                                   placeholder="Enter new password for user">
                                            <small class="text-muted">Minimum 6 characters</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="confirm_new_password" 
                                                   name="confirm_new_password"
                                                   placeholder="Confirm new password">
                                        </div>
                                        <hr>
                                    <?php endif; ?>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-toggle-on"></i> Status
                                        </label>
                                        <div>
                                            <?php if($user['status'] === 'active'): ?>
                                                <span class="badge bg-success fs-6">
                                                    <i class="bi bi-check-circle"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger fs-6">
                                                    <i class="bi bi-x-circle"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text">
                                            Use the Activate/Deactivate buttons on the user list to change status.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-calendar"></i> Account Created
                                        </label>
                                        <div class="text-muted">
                                            <?= date('F d, Y \a\t h:i A', strtotime($user['created_at'])) ?>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Save Changes
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

        // Track original role value
        const originalRole = '<?= esc($user['role']) ?>';
        const roleSelect = document.getElementById('role');
        const passwordSection = document.getElementById('passwordConfirmSection');
        const passwordInput = document.getElementById('admin_password');
        const isOwnAccount = <?= $isOwnAccount ? 'true' : 'false' ?>;

        // Show/hide password confirmation when role changes
        if (roleSelect && !isOwnAccount) {
            roleSelect.addEventListener('change', function() {
                if (this.value !== originalRole) {
                    passwordSection.style.display = 'block';
                    passwordInput.required = true;
                } else {
                    passwordSection.style.display = 'none';
                    passwordInput.required = false;
                    passwordInput.value = '';
                }
            });
        }

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // Check role change password requirement
            if (!isOwnAccount && roleSelect.value !== originalRole) {
                if (!passwordInput.value) {
                    e.preventDefault();
                    alert('Please enter your password to confirm the role change.');
                    passwordInput.focus();
                    return false;
                }
            }

            // Check new password fields if user is not admin
            <?php if($user['role'] !== 'admin'): ?>
                const newPassword = document.getElementById('new_password');
                const confirmPassword = document.getElementById('confirm_new_password');
                
                if (newPassword && confirmPassword) {
                    if (newPassword.value !== '' || confirmPassword.value !== '') {
                        if (newPassword.value !== confirmPassword.value) {
                            e.preventDefault();
                            alert('New passwords do not match!');
                            confirmPassword.focus();
                            return false;
                        }
                        if (newPassword.value.length < 6) {
                            e.preventDefault();
                            alert('Password must be at least 6 characters long!');
                            newPassword.focus();
                            return false;
                        }
                    }
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>
