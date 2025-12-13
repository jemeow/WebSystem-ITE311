<script>
// ===== Notification System =====

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return '';
}

let notificationEventSource = null;
let notificationPollIntervalId = null;

function startNotificationPollingFallback() {
    if (notificationPollIntervalId) return;
    fetchNotifications();
    notificationPollIntervalId = setInterval(fetchNotifications, 30000);
}

function stopNotificationPollingFallback() {
    if (notificationPollIntervalId) {
        clearInterval(notificationPollIntervalId);
        notificationPollIntervalId = null;
    }
}

function startNotificationStream() {
    if (notificationEventSource) return;

    if (!('EventSource' in window)) {
        startNotificationPollingFallback();
        return;
    }

    // Ensure we have initial data even before the first SSE message arrives
    fetchNotifications();
    stopNotificationPollingFallback();

    notificationEventSource = new EventSource('<?= site_url('notifications/stream') ?>');

    notificationEventSource.addEventListener('notifications', function(event) {
        try {
            const data = JSON.parse(event.data);
            if (data && data.success) {
                updateNotificationBadge(data.unread_count);
                updateNotificationList(data.notifications || []);
            }
        } catch (e) {
            // Ignore parse errors
        }
    });

    notificationEventSource.addEventListener('close', function() {
        // Server asked us to reconnect; EventSource will usually reconnect automatically
    });

    notificationEventSource.onerror = function() {
        // If SSE fails (proxy, server, etc.), fallback to polling
        try {
            notificationEventSource.close();
        } catch (e) {}
        notificationEventSource = null;
        startNotificationPollingFallback();
    };
}

/**
 * Fetch notifications from server and update UI
 */
function fetchNotifications() {
    $.ajax({
        url: '<?= site_url('notifications') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Notifications response:', response);
            if (response.success) {
                console.log('Unread count:', response.unread_count);
                console.log('Notifications:', response.notifications);
                updateNotificationBadge(response.unread_count);
                updateNotificationList(response.notifications);
            } else {
                console.error('Notification fetch failed:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to fetch notifications:', {
                status: xhr.status,
                statusText: xhr.statusText,
                error: error,
                response: xhr.responseText
            });
        }
    });
}

/**
 * Update notification badge with unread count
 */
function updateNotificationBadge(count) {
    const badge = $('#notificationBadge');
    if (count > 0) {
        badge.text(count).show();
    } else {
        badge.hide();
    }
}

/**
 * Update notification dropdown list
 */
function updateNotificationList(notifications) {
    const notificationList = $('#notificationList');
    const noNotifications = $('#noNotifications');
    
    // Remove existing notification items (keep header and divider)
    notificationList.find('.notification-item').remove();
    
    if (notifications.length === 0) {
        noNotifications.show();
    } else {
        noNotifications.hide();
        
        // Add each notification to the list
        notifications.forEach(function(notification) {
            const isUnread = notification.is_read == 0;
            const notificationItem = $('<li>', {
                class: 'notification-item'
            });

            const notificationContent = $('<div>', {
                class: 'px-3 py-2 border-bottom ' + (isUnread ? 'bg-light' : '')
            }).html(`
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="alert alert-info py-2 px-2 mb-1" role="alert">
                            <p class="mb-0 small ${isUnread ? 'fw-bold' : ''}">${notification.message}</p>
                        </div>
                        <small class="text-muted" data-timestamp="${notification.created_at}">${formatNotificationDate(notification.created_at)}</small>
                    </div>
                    ${isUnread ? `<button type="button" class="btn btn-sm btn-outline-secondary mark-read-btn" data-id="${notification.id}">Mark as Read</button>` : ''}
                </div>
            `);
            
            notificationItem.append(notificationContent);
            notificationList.append(notificationItem);
        });
        
        // Add divider at the end
        notificationList.append('<li><hr class="dropdown-divider"></li>');
    }
}

/**
 * Format notification date with accurate live time
 */
function formatNotificationDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffSecs = Math.floor(diffMs / 1000);
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    const diffWeeks = Math.floor(diffMs / 604800000);
    const diffMonths = Math.floor(diffMs / 2592000000);
    
    if (diffSecs < 10) return 'Just now';
    if (diffSecs < 60) return diffSecs + ' seconds ago';
    if (diffMins === 1) return '1 minute ago';
    if (diffMins < 60) return diffMins + ' minutes ago';
    if (diffHours === 1) return '1 hour ago';
    if (diffHours < 24) return diffHours + ' hours ago';
    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 7) return diffDays + ' days ago';
    if (diffWeeks === 1) return '1 week ago';
    if (diffWeeks < 4) return diffWeeks + ' weeks ago';
    if (diffMonths === 1) return '1 month ago';
    if (diffMonths < 12) return diffMonths + ' months ago';
    
    // For older notifications, show actual date with time
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleString('en-US', options);
}

/**
 * Mark notification as read
 */
function markNotificationAsRead(notificationId) {
    const csrfHeader = '<?= config('Security')->headerName ?>';
    const csrfCookieName = '<?= config('Security')->cookieName ?>';
    $.ajax({
        url: '<?= site_url('notifications/mark_read/') ?>' + notificationId,
        type: 'POST',
        headers: {
            [csrfHeader]: getCookie(csrfCookieName)
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Refresh notifications
                fetchNotifications();
            } else {
                alert('Failed to mark notification as read');
            }
        },
        error: function(xhr) {
            console.error('Failed to mark notification as read:', xhr);
            alert('An error occurred');
        }
    });
}

// Event handler for mark as read buttons (using event delegation)
$(document).on('click', '.mark-read-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    const notificationId = $(this).data('id');
    markNotificationAsRead(notificationId);
});

// Fetch notifications on page load
$(document).ready(function() {
    startNotificationStream();
    
    // Update relative times every 10 seconds without full refresh
    setInterval(updateNotificationTimes, 10000);
});

/**
 * Update notification times without fetching from server
 * This keeps the "X minutes ago" text accurate in real-time
 */
function updateNotificationTimes() {
    $('.notification-item').each(function() {
        const timeElement = $(this).find('small.text-muted');
        const timestamp = timeElement.data('timestamp');
        
        if (timestamp) {
            timeElement.text(formatNotificationDate(timestamp));
        }
    });
}
</script>
