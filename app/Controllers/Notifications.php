<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }
    
    /**
     * Get notifications for the current user (AJAX endpoint)
     * Returns JSON response with unread count and list of notifications
     */
    public function get()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('id');
        
        // Get unread count
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        // Get notifications list
        $notifications = $this->notificationModel->getNotificationsForUser($userId);
        
        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Mark a notification as read (AJAX endpoint)
     * 
     * @param int $id
     */
    public function mark_as_read($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        }
        
        $userId = session()->get('id');
        
        // Verify the notification belongs to the current user
        $notification = $this->notificationModel->find($id);
        
        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ])->setStatusCode(404);
        }
        
        if ($notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Forbidden'
            ])->setStatusCode(403);
        }
        
        // Mark as read
        $result = $this->notificationModel->markAsRead($id);
        
        if ($result) {
            // Get updated unread count
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $unreadCount
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update notification'
            ])->setStatusCode(500);
        }
    }

    /**
     * Stream notifications in real-time via Server-Sent Events (SSE)
     */
    public function stream()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setBody('Unauthorized');
        }

        $userId = (int) session()->get('id');

        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        @ini_set('zlib.output_compression', '0');
        @ini_set('output_buffering', '0');
        @ini_set('implicit_flush', '1');
        @ob_implicit_flush(true);

        while (ob_get_level() > 0) {
            @ob_end_flush();
        }

        // Prevent the session from blocking other requests while this stream is open
        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_write_close();
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        // How long to keep the connection open before letting EventSource reconnect
        @set_time_limit(0);
        $endAt = time() + 300; // 5 minutes

        $lastHash = null;
        $lastPing = 0;

        // Send an initial event immediately
        $payload = [
            'success' => true,
            'unread_count' => $this->notificationModel->getUnreadCount($userId),
            'notifications' => $this->notificationModel->getNotificationsForUser($userId),
        ];
        $lastHash = md5(json_encode($payload));
        echo "event: notifications\n";
        echo 'data: ' . json_encode($payload) . "\n\n";
        @flush();

        while (time() < $endAt) {
            if (connection_aborted()) {
                break;
            }

            // Poll for changes (simple approach)
            $payload = [
                'success' => true,
                'unread_count' => $this->notificationModel->getUnreadCount($userId),
                'notifications' => $this->notificationModel->getNotificationsForUser($userId),
            ];
            $hash = md5(json_encode($payload));

            if ($hash !== $lastHash) {
                $lastHash = $hash;
                echo "event: notifications\n";
                echo 'data: ' . json_encode($payload) . "\n\n";
                @flush();
            }

            // Keepalive ping every 15 seconds so proxies don't kill the connection
            if (time() - $lastPing >= 15) {
                $lastPing = time();
                echo "event: ping\n";
                echo "data: {}\n\n";
                @flush();
            }

            usleep(2000000); // 2 seconds
        }

        // Ask client to reconnect
        echo "event: close\n";
        echo "data: {}\n\n";
        @flush();
        exit;
    }
}
