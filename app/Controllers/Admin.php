<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'form']);
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to(site_url('dashboard'))->with('error', 'Access denied. Admin only.');
        }

        // Check if user is authenticated through login page
        if (!session()->get('authenticated')) {
            session()->destroy();
            return redirect()->to(site_url('login'))->with('error', 'Please login to access the dashboard.');
        }

        // Get statistics
        $data['totalUsers'] = $this->userModel->countAll();
        $data['totalAdmins'] = $this->userModel->where('role', 'admin')->countAllResults();
        $data['totalTeachers'] = $this->userModel->where('role', 'teacher')->countAllResults();
        $data['totalStudents'] = $this->userModel->where('role', 'student')->countAllResults();
        $data['activeUsers'] = $this->userModel->where('status', 'active')->countAllResults();
        $data['inactiveUsers'] = $this->userModel->where('status', 'inactive')->countAllResults();
        
        // Get pending enrollment count
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $data['pendingCount'] = count($enrollmentModel->getAllPendingEnrollments());
        
        // Get recent users
        $data['recentUsers'] = $this->userModel->orderBy('created_at', 'DESC')->findAll(10);
        
        return view('admin/dashboard', $data);
    }
}
