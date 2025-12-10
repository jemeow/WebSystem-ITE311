<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends Controller
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'html', 'form']);
    }

    public function login()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];

        return view('auth/login', $data);
    }

    public function register()
    {
        $data = [
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register', $data);
    }

    public function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Verify user credentials
        $user = $this->userModel->verifyCredentials($email, $password);

        if ($user) {
            // Set session data
            $sessionData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true,
                'authenticated' => true
            ];

            session()->set($sessionData);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                return redirect()->to(site_url('admin/dashboard'))->with('success', 'Welcome back, ' . $user['name'] . '!');
            }
            
            // Redirect all other users (teacher/student) to dashboard
            return redirect()->to(site_url('dashboard'))->with('success', 'Welcome back, ' . $user['name'] . '!');
        }

        return redirect()->to(site_url('login'))->withInput()->with('error', 'Invalid email or password. Please try again.');
    }

    public function processRegister()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|regex_match[/^[a-zA-Z\s]+$/]',
            'email' => 'required|valid_email|is_unique[users.email]|max_length[100]|regex_match[/^[a-zA-Z0-9.@]+$/]',
            'password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[password]'
        ];

        $errors = [
            'name' => [
                'regex_match' => 'Name can only contain letters and spaces (no special characters allowed).'
            ],
            'email' => [
                'regex_match' => 'Email can only contain letters, numbers, @ and . symbols.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'student'
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to(site_url('login'))->with('success', 'Registration successful! Please login with your credentials.');
        }

        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }

    public function dashboard()
    {
        // Authorization check - ensure user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access the dashboard.');
        }

        // Check if user is authenticated through login page
        if (!session()->get('authenticated')) {
            session()->destroy();
            return redirect()->to(site_url('login'))->with('error', 'Please login to access the dashboard.');
        }

        // Get user role from session
        $role = session()->get('role');
        $userId = session()->get('id');

        // Initialize data array
        $data = [
            'user' => [
                'id' => $userId,
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'role' => $role
            ]
        ];

        // Fetch role-specific data from database
        switch ($role) {
            case 'admin':
                // Admin sees all users, courses, and statistics
                $data['totalUsers'] = $this->userModel->countAll();
                $data['totalAdmins'] = $this->userModel->where('role', 'admin')->countAllResults();
                $data['totalTeachers'] = $this->userModel->where('role', 'teacher')->countAllResults();
                $data['totalStudents'] = $this->userModel->where('role', 'student')->countAllResults();
                $data['recentUsers'] = $this->userModel->orderBy('created_at', 'DESC')->findAll(5);
                break;

            case 'teacher':
                // Teacher sees their courses and students
                $data['message'] = 'Welcome to your Teacher Dashboard';
                $data['stats'] = [
                    'courses' => 0, // TODO: Implement course count
                    'students' => 0, // TODO: Implement enrolled students count
                    'assignments' => 0 // TODO: Implement assignments count
                ];
                break;

            case 'student':
                // Student sees their enrolled courses and progress
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                
                // Get enrolled courses
                $enrolledCourses = $enrollmentModel->getUserEnrollments($userId);
                
                // Get enrolled course IDs
                $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
                
                // Get available courses (not enrolled yet)
                $allCourses = $courseModel->getActiveCourses();
                $availableCourses = array_filter($allCourses, function($course) use ($enrolledCourseIds) {
                    return !in_array($course['id'], $enrolledCourseIds);
                });
                
                $data['message'] = 'Welcome to your Student Dashboard';
                $data['stats'] = [
                    'enrolledCourses' => count($enrolledCourses),
                    'completedLessons' => 0, // TODO: Implement completed lessons count
                    'pendingAssignments' => 0 // TODO: Implement pending assignments count
                ];
                $data['enrolledCourses'] = $enrolledCourses;
                $data['availableCourses'] = array_values($availableCourses);
                break;

            default:
                return redirect()->to('/logout')->with('error', 'Invalid user role.');
        }

        return view('auth/dashboard', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'You have been logged out successfully.');
    }
}
