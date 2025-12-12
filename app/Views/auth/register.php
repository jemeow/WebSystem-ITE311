<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ITE311-ZURITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            max-width: 400px;
            width: 100%;
        }
        .register-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0;
            overflow: hidden;
        }
        .register-header {
            background: #133980;
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .register-header i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .register-header h4 {
            margin: 0;
            font-weight: 400;
            letter-spacing: 0.5px;
            font-size: 1.5rem;
        }
        .register-body {
            padding: 3rem 2.5rem;
        }
        .form-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 0;
            padding: 0.65rem;
            transition: none;
            font-size: 0.875rem;
            color: #374151;
        }
        .form-control:focus {
            border-color: #2C5F8D;
            box-shadow: none;
            outline: none;
        }
        .btn-register {
            background: #2C5F8D;
            border: none;
            border-radius: 0;
            padding: 0.65rem;
            color: white;
            font-weight: 400;
            transition: background 0.15s;
            width: 100%;
        }
        .btn-register:hover {
            background: #234a6d;
            color: white;
        }
        .alert {
            border: none;
            border-radius: 2px;
            font-size: 0.9rem;
        }
        a {
            color: #2C5F8D;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        small {
            color: #5f6368;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container mx-auto">
            <div class="register-card">
                <!-- Header -->
                <div class="register-header">
                    <i class="bi bi-person-plus-fill fs-1 mb-2"></i>
                    <h4 class="mb-1">Create Account</h4>
                    <small>Join ITE311-ZURITA LMS</small>
                </div>

                <!-- Body -->
                <div class="register-body">
                    <!-- Success Message -->
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Error Messages -->
                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form action="<?= site_url('register') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirm" class="form-control" placeholder="Confirm password" required>
                        </div>

                        <button type="submit" class="btn btn-register w-100 mb-3">Register</button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center">
                        <small class="text-muted">Already have an account? 
                            <a href="<?= site_url('login') ?>" class="text-decoration-none">Login</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
