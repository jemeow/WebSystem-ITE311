<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Register</h3>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success small py-2 mb-2 text-center"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <form action="<?= site_url('register') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?= site_url('login') ?>" class="btn btn-link p-0">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>
