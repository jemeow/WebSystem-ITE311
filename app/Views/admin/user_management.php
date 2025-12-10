<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Panel</title>
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
        .user-status-active {
            color: #198754;
        }
        .user-status-inactive {
            color: #dc3545;
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/admin/courses') ?>">
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
                        <h2><i class="bi bi-people"></i> User Management</h2>
                    </div>
                    <div>
                        <a href="<?= site_url('admin/users/create') ?>" class="btn btn-success me-2">
                            <i class="bi bi-person-plus"></i> Create User
                        </a>
                        <span class="badge bg-danger px-3 py-2">Admin</span>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter Buttons -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('admin/users') ?>" class="btn btn-<?= !isset($filter) || $filter === null ? 'primary' : 'outline-primary' ?>">
                                <i class="bi bi-people"></i> All Users
                            </a>
                            <a href="<?= site_url('admin/users?filter=active') ?>" class="btn btn-<?= isset($filter) && $filter === 'active' ? 'success' : 'outline-success' ?>">
                                <i class="bi bi-check-circle"></i> Active
                            </a>
                            <a href="<?= site_url('admin/users?filter=inactive') ?>" class="btn btn-<?= isset($filter) && $filter === 'inactive' ? 'danger' : 'outline-danger' ?>">
                                <i class="bi bi-x-circle"></i> Inactive
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <?php if(isset($filter) && $filter === 'active'): ?>
                                <i class="bi bi-check-circle text-success"></i> Active Users
                            <?php elseif(isset($filter) && $filter === 'inactive'): ?>
                                <i class="bi bi-x-circle text-danger"></i> Inactive Users
                            <?php else: ?>
                                <i class="bi bi-people"></i> All Users
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($users)): ?>
                                        <?php foreach($users as $user): ?>
                                            <tr>
                                                <td><?= esc($user['id']) ?></td>
                                                <td>
                                                    <strong><?= esc($user['name']) ?></strong>
                                                    <?php if($user['id'] == session()->get('id')): ?>
                                                        <span class="badge bg-info ms-1">You</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($user['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'warning' : 'success') ?>">
                                                        <?= ucfirst(esc($user['role'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($user['status'] === 'active'): ?>
                                                        <i class="bi bi-check-circle-fill user-status-active"></i>
                                                        <span class="text-success">Active</span>
                                                    <?php else: ?>
                                                        <i class="bi bi-x-circle-fill user-status-inactive"></i>
                                                        <span class="text-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                                <td class="text-center">
                                                    <?php if($user['id'] != session()->get('id')): ?>
                                                        <a href="<?= site_url('admin/users/edit/' . $user['id']) ?>" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Edit User">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <?php if($user['status'] === 'active'): ?>
                                                            <button class="btn btn-sm btn-warning" 
                                                                    onclick="confirmAction('deactivate', <?= $user['id'] ?>, '<?= esc($user['name']) ?>')"
                                                                    title="Deactivate User">
                                                                <i class="bi bi-pause-circle"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-success" 
                                                                    onclick="confirmAction('activate', <?= $user['id'] ?>, '<?= esc($user['name']) ?>')"
                                                                    title="Activate User">
                                                                <i class="bi bi-play-circle"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-lock-fill"></i> Cannot Edit Own Account
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No users found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

        </main>
    </div>

    <!-- Confirmation Forms (Hidden) -->
    <form id="deactivateForm" method="POST" style="display: none;">
        <?= csrf_field() ?>
    </form>
    <form id="activateForm" method="POST" style="display: none;">
        <?= csrf_field() ?>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmAction(action, userId, userName) {
            let message, formId, actionUrl;
            
            if (action === 'deactivate') {
                message = `Are you sure you want to deactivate user "${userName}"?\n\nThey will not be able to log in until reactivated.`;
                formId = 'deactivateForm';
                actionUrl = `<?= site_url('admin/users/deactivate/') ?>${userId}`;
            } else if (action === 'activate') {
                message = `Are you sure you want to activate user "${userName}"?`;
                formId = 'activateForm';
                actionUrl = `<?= site_url('admin/users/activate/') ?>${userId}`;
            }
            
            if (confirm(message)) {
                const form = document.getElementById(formId);
                form.action = actionUrl;
                form.submit();
            }
        }
    </script>
</body>
</html>
