<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;

class Student extends BaseController
{
    protected $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        helper(['url', 'form']);
    }

    public function courses()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');
        if ($role === 'user') {
            $role = 'student';
            session()->set('role', $role);
        }

        if ($role !== 'student') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Students only.');
        }

        $userId = session()->get('id');

        $data['enrolledCourses'] = $this->enrollmentModel->getUserEnrollments($userId);
        $data['pendingEnrollments'] = $this->enrollmentModel->getPendingEnrollments($userId);

        return view('student/courses', $data);
    }
}
