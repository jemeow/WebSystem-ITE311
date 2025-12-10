<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 350px;">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Login</h3>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success small py-2 mb-2 text-center"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <form action="<?= site_url('login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?= site_url('register') ?>" class="btn btn-link p-0">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if(session()->getFlashdata('error')): ?>
            alert('<?= esc(session()->getFlashdata('error')) ?>');
        <?php endif; ?>
    </script>
</body>
</html>
