<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            // For AJAX requests, return JSON error
            if ($request->isAJAX()) {
                return service('response')
                    ->setJSON(['success' => false, 'message' => 'Authentication required'])
                    ->setStatusCode(401);
            }
            
            // Store the intended URL to redirect back after login
            session()->set('redirect_url', current_url());
            
            // Redirect to login with error message
            return redirect()->to(site_url('login'))->with('error', 'Please login to access this page.');
        }

        // Optional: Check for specific role requirements
        if ($arguments !== null) {
            $role = session()->get('role');
            
            // If specific roles are required, check if user has one of them
            if (!in_array($role, $arguments)) {
                // For AJAX requests, return JSON error
                if ($request->isAJAX()) {
                    return service('response')
                        ->setJSON(['success' => false, 'message' => 'Permission denied'])
                        ->setStatusCode(403);
                }
                
                // Redirect based on role
                if ($role === 'admin') {
                    return redirect()->to(site_url('admin/dashboard'))->with('error', 'You do not have permission to access this page.');
                }
                return redirect()->to(site_url('dashboard'))->with('error', 'You do not have permission to access this page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the controller
    }
}
