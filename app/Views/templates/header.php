<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Web System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Dynamic Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1d3557;">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">
                <i class="bi bi-mortarboard-fill"></i> ITE311-ZURITA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url() ?>">
                                <i class="bi bi-house"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php
                        $role = session()->get('role');
                        if ($role === 'admin'): ?>
                            <!-- Admin Menu Items -->
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-people"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-book"></i> Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-gear"></i> Settings
                                </a>
                            </li>
                        
                        <?php elseif ($role === 'teacher'): ?>
                            <!-- Teacher Menu Items -->
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-book"></i> My Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-people"></i> Students
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-clipboard-check"></i> Assignments
                                </a>
                            </li>
                        
                        <?php else: ?>
                            <!-- Student Menu Items -->
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-book"></i> My Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-search"></i> Browse Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">
                                    <i class="bi bi-trophy"></i> My Grades
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url() ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('about') ?>">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('contact') ?>">Contact</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Right side menu -->
                <ul class="navbar-nav">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                                <span class="badge bg-<?= $role === 'admin' ? 'danger' : ($role === 'teacher' ? 'warning' : 'success') ?>" ms-1">
                                    <?= ucfirst($role) ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('register') ?>">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
