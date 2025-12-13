<!-- Notifications Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill fs-5"></i>
        <span id="notificationBadge" class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 5px; left: 80%; font-size: 0.65rem; display: none;">
            0
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationList" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
        <li><h6 class="dropdown-header">Notifications</h6></li>
        <li><hr class="dropdown-divider"></li>
        <li class="text-center py-3 text-muted" id="noNotifications">No new notifications</li>
    </ul>
</li>
