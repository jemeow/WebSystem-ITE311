<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Web System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }

        // Function to load notifications
        function loadNotifications() {
            $.get('<?= site_url('notifications') ?>', function(response) {
                if (response.success) {
                    // Update badge count
                    if (response.unread_count > 0) {
                        $('#notificationBadge').text(response.unread_count).removeClass('d-none');
                    } else {
                        $('#notificationBadge').addClass('d-none');
                    }
                    
                    // Update notification list
                    let notificationsHtml = '';
                    if (response.notifications && response.notifications.length > 0) {
                        response.notifications.forEach(function(notification) {
                            const isReadClass = notification.is_read ? '' : 'bg-light';
                            const readButton = !notification.is_read ? 
                                `<button type="button" class="btn-close mark-read" data-id="${notification.id}" aria-label="Mark as read"></button>` : '';
                            
                            // Format the date (you might want to format this properly)
                            const date = new Date(notification.created_at);
                            const timeAgo = formatTimeAgo(date);
                            
                            notificationsHtml += `
                                <a class="dropdown-item p-3 border-bottom ${isReadClass}" href="#" data-id="${notification.id}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bi bi-info-circle-fill text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">${notification.message}</p>
                                            <small class="text-muted">${timeAgo}</small>
                                        </div>
                                        ${readButton}
                                    </div>
                                </a>
                            `;
                        });
                    } else {
                        notificationsHtml = `
                            <div class="text-center p-4">
                                <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">No notifications yet</p>
                            </div>
                        `;
                    }
                    
                    $('#notificationList').html(notificationsHtml);
                }
            }).fail(function() {
                console.error('Failed to load notifications');
            });
        }
        
        // Format time ago
        function formatTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60,
                second: 1
            };
            
            for (const [unit, secondsInUnit] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / secondsInUnit);
                if (interval >= 1) {
                    return interval === 1 ? `1 ${unit} ago` : `${interval} ${unit}s ago`;
                }
            }
            
            return 'just now';
        }
        
        // Mark notification as read
        $(document).on('click', '.mark-read, .dropdown-item[data-id]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const notificationId = $(this).data('id');
            if (!notificationId) return;

            $.ajax({
                url: '<?= site_url('notifications/mark_read') ?>/' + notificationId,
                type: 'POST',
                headers: {
                    '<?= config('Security')->headerName ?>': getCookie('<?= config('Security')->cookieName ?>')
                },
                dataType: 'json',
                success: function(response) {
                if (response.success) {
                    // Update UI
                    $(`[data-id="${notificationId}"]`).removeClass('bg-light')
                        .find('.mark-read').remove();
                    
                    // Update badge
                    if (response.unread_count > 0) {
                        $('#notificationBadge').text(response.unread_count).removeClass('d-none');
                    } else {
                        $('#notificationBadge').addClass('d-none');
                    }
                }
                }
            });
        });
        
        // Mark all as read
        $('#markAllRead').on('click', function(e) {
            e.preventDefault();
            
            // Get all unread notification IDs
            const unreadIds = [];
            $('.dropdown-item.bg-light').each(function() {
                const id = $(this).data('id');
                if (id) unreadIds.push(id);
            });
            
            if (unreadIds.length === 0) return;
            
            // Mark each unread notification as read
            Promise.all(unreadIds.map(id => 
                $.ajax({
                    url: '<?= site_url('notifications/mark_read') ?>/' + id,
                    type: 'POST',
                    headers: {
                        '<?= config('Security')->headerName ?>': getCookie('<?= config('Security')->cookieName ?>')
                    }
                })
            )).then(() => {
                // Update UI
                $('.dropdown-item').removeClass('bg-light');
                $('.mark-read').remove();
                $('#notificationBadge').addClass('d-none');
            });
        });
        
        // Load notifications on page load
        let notificationEventSource = null;
        let notificationPollIntervalId = null;

        function startPollingFallback() {
            if (notificationPollIntervalId) return;
            loadNotifications();
            notificationPollIntervalId = setInterval(loadNotifications, 30000);
        }

        function stopPollingFallback() {
            if (notificationPollIntervalId) {
                clearInterval(notificationPollIntervalId);
                notificationPollIntervalId = null;
            }
        }

        function startNotificationStream() {
            if (notificationEventSource) return;
            if (!('EventSource' in window)) {
                startPollingFallback();
                return;
            }

            loadNotifications();
            stopPollingFallback();

            notificationEventSource = new EventSource('<?= site_url('notifications/stream') ?>');
            notificationEventSource.addEventListener('notifications', function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data && data.success) {
                        // Update badge
                        if (data.unread_count > 0) {
                            $('#notificationBadge').text(data.unread_count).removeClass('d-none').show();
                        } else {
                            $('#notificationBadge').addClass('d-none').hide();
                        }

                        // Reuse existing renderer by faking the same response shape
                        loadNotifications();
                    }
                } catch (e) {}
            });

            notificationEventSource.onerror = function() {
                try { notificationEventSource.close(); } catch (e) {}
                notificationEventSource = null;
                startPollingFallback();
            };
        }

        startNotificationStream();
    });
    </script>
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
                                <a class="nav-link" href="<?= site_url('student/courses') ?>">
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
                        <!-- Notification Dropdown -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <?php if (isset($unreadNotificationCount) && $unreadNotificationCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge">
                                        <?= $unreadNotificationCount ?>
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg-end p-0" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <h6 class="mb-0">Notifications</h6>
                                    <small><a href="#" id="markAllRead" class="text-decoration-none">Mark all as read</a></small>
                                </div>
                                <div id="notificationList">
                                    <?php if (!empty($notifications)): ?>
                                        <?php foreach ($notifications as $notification): ?>
                                            <a class="dropdown-item p-3 border-bottom <?= $notification['is_read'] ? '' : 'bg-light' ?>" href="#" data-id="<?= $notification['id'] ?>">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="bi bi-info-circle-fill text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="mb-1"><?= esc($notification['message']) ?></p>
                                                        <small class="text-muted"><?= date('M d, Y h:i A', strtotime($notification['created_at'])) ?></small>
                                                    </div>
                                                    <?php if (!$notification['is_read']): ?>
                                                        <button type="button" class="btn-close mark-read" data-id="<?= $notification['id'] ?>" aria-label="Mark as read"></button>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center p-4">
                                            <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2">No notifications yet</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-center p-2 border-top">
                                    <a href="#" class="text-decoration-none">View all notifications</a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- User Dropdown -->
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
