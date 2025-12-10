<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Users - Database View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-database"></i> Users Database - Test View</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">This page shows all users in the database for testing purposes.</p>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($users)): ?>
                                <?php foreach($users as $user): ?>
                                    <tr>
                                        <td><?= esc($user['id']) ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'warning' : 'success') ?>">
                                                <?= ucfirst(esc($user['role'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($user['status'] === 'active'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></td>
                                        <td><?= date('M d, Y H:i', strtotime($user['updated_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No users found in database</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4">
                    <h5><i class="bi bi-info-circle"></i> Test Credentials:</h5>
                    <ul class="mb-0">
                        <li><strong>Admin:</strong> jesse@gmail.com / admin123</li>
                        <li><strong>Teacher:</strong> ogillee@gmail.com / teacher123</li>
                        <li><strong>Student:</strong> tally@gmail.com / student123</li>
                    </ul>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                    <a href="<?= site_url('login') ?>" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="<?= site_url('register') ?>" class="btn btn-success">
                        <i class="bi bi-person-plus"></i> Register New User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
