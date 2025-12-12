<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?? 'ITE311-ZURITA' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-nav {
            background: #133980;
            box-shadow: none;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .header-nav .navbar-brand {
            font-size: 1.25rem;
            font-weight: 400;
            color: #fff !important;
        }
        .header-nav .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.5rem 1rem;
            border-radius: 2px;
            margin: 0 0.2rem;
            transition: background 0.2s;
            font-weight: 400;
        }
        .header-nav .nav-link:hover,
        .header-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.2);
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0;
            box-shadow: none;
            background: white;
        }
        .btn {
            border-radius: 0;
            padding: 0.5rem 1rem;
            font-weight: 400;
            transition: background 0.15s;
            border: none;
            font-size: 0.875rem;
        }
        .btn-primary {
            background: #2C5F8D;
            color: white;
        }
        .btn-primary:hover {
            background: #234a6d;
            color: white;
        }
        .btn-success {
            background: #48A868;
            color: white;
        }
        .btn-success:hover {
            background: #3d8f57;
            color: white;
        }
        .btn-outline-secondary {
            border: 1px solid #e5e7eb;
            color: #6b7280;
            background: white;
        }
        .btn-outline-secondary:hover {
            background: #f9fafb;
            color: #374151;
        }
        .table {
            border: none;
        }
        .table thead th {
            border-bottom: 1px solid #e5e7eb;
            background: white;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.85rem;
            padding: 0.75rem;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #f9fafb;
        }
        .table td {
            border-top: 1px solid #f3f4f6;
            padding: 0.75rem;
            vertical-align: middle;
            color: #374151;
            font-size: 0.9rem;
        }
        h1, h2, h3, h4, h5 {
            color: #1f2937;
            font-weight: 400;
        }
        .text-muted {
            color: #6b7280 !important;
        }
        .alert {
            border: none;
            border-radius: 2px;
            border-left: 3px solid #2C5F8D;
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
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">
                            <i class="bi bi-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">
                            <i class="bi bi-clipboard-check"></i> Enrollments
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('profile/edit') ?>">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name') ?? 'User') ?>
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
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
