<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];
 
     protected $session;
    
    /**
     * Notification model instance
     *
     * @var \App\Models\NotificationModel
     */
    protected $notificationModel;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Load notification model
        $this->notificationModel = new \App\Models\NotificationModel();
        
        // Load session service
        $this->session = service('session');
        
        // Share notification data with all views if user is logged in
        if ($this->session->has('id') || $this->session->has('user_id')) {
            $this->shareNotificationData();
        }
    }
    
    /**
     * Share notification data with all views
     */
    protected function shareNotificationData()
    {
        $unreadCount = 0;
        $notifications = [];
        
        if ($this->session->has('id') || $this->session->has('user_id')) {
            $userId = $this->session->get('id') ?? $this->session->get('user_id');
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            $notifications = $this->notificationModel->getNotificationsForUser($userId);
        }
        
        // Make data available to all views
        $data = [
            'unreadNotificationCount' => $unreadCount,
            'notifications' => $notifications
        ];
        
        // Share with all views
        $this->setViewData($data);
    }
    
    /**
     * Set view data that should be available to all views
     * 
     * @param array $data Data to be made available to views
     */
    protected function setViewData(array $data = [])
    {
        $view = service('renderer');
        $view->setData($data);
    }
}
