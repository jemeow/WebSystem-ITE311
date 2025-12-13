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
        try {
            $user = $this->userModel->verifyCredentials($email, $password);
        } catch (\Throwable $e) {
            log_message('critical', 'Login failed due to database error: {message}', ['message' => $e->getMessage()]);
            return redirect()
                ->to(site_url('login'))
                ->withInput()
                ->with('error', 'Database connection error. Please start MySQL (XAMPP) or fix the database port/config.');
        }

        if ($user) {
            $role = $user['role'];
            if ($role === 'user') {
                $role = 'student';
            }

            // Set session data
            $sessionData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $role,
                'isLoggedIn' => true,
                'authenticated' => true
            ];

            session()->set($sessionData);

            // Redirect based on role
            if ($role === 'admin') {
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
            // Notify all admins about new student registration
            $notificationModel = new \App\Models\NotificationModel();
            $admins = $this->userModel->where('role', 'admin')->findAll();
            
            foreach ($admins as $admin) {
                $notificationModel->insert([
                    'user_id' => $admin['id'],
                    'message' => 'New student registered: ' . $userData['name'] . ' (' . $userData['email'] . ')',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
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

        if ($role === 'user') {
            $role = 'student';
            session()->set('role', $role);
        }

        // Get pending enrollments count for header
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $pendingCount = 0;
        
        if ($role === 'admin') {
            // Admin sees all pending enrollments
            $pendingCount = count($enrollmentModel->getAllPendingEnrollments());
        } else {
            // Students/Teachers see their own pending enrollments
            $pendingCount = count($enrollmentModel->getPendingEnrollments($userId));
        }

        // Initialize data array
        $data = [
            'pendingCount' => $pendingCount,
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
                $courseModel = new \App\Models\CourseModel();
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $data['totalUsers'] = $this->userModel->countAll();
                $data['totalAdmins'] = $this->userModel->where('role', 'admin')->countAllResults();
                $data['totalTeachers'] = $this->userModel->where('role', 'teacher')->countAllResults();
                $data['totalStudents'] = $this->userModel->where('role', 'student')->countAllResults();
                $data['totalCourses'] = $courseModel->countAll();
                $data['totalEnrollments'] = $enrollmentModel->where('status', 'approved')->countAllResults();
                $data['recentUsers'] = $this->userModel->orderBy('created_at', 'DESC')->findAll(5);
                break;

            case 'teacher':
                // Teacher sees their courses and students
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                
                // Get teacher's assigned courses (courses they teach)
                $teacherCourses = $courseModel->where('teacher_id', $userId)
                                              ->where('status', 'active')
                                              ->findAll();
                
                // Get course IDs for teacher's courses
                $courseIds = array_column($teacherCourses, 'id');
                
                // Get pending enrollment requests for teacher's courses
                $pendingCount = 0;
                $totalStudents = 0;
                if (!empty($courseIds)) {
                    $pendingCount = $enrollmentModel->where('status', 'pending')
                                                   ->whereIn('course_id', $courseIds)
                                                   ->countAllResults();
                    
                    // Count unique students enrolled in teacher's courses
                    $enrolledStudents = $enrollmentModel->distinct()
                                                       ->select('user_id')
                                                       ->where('status', 'approved')
                                                       ->whereIn('course_id', $courseIds)
                                                       ->findAll();
                    $totalStudents = count($enrolledStudents);
                }
                
                // Teachers should not enroll in courses - they only teach
                $data['message'] = 'Welcome to your Teacher Dashboard';
                $data['stats'] = [
                    'courses' => count($teacherCourses),
                    'students' => $totalStudents,
                    'assignments' => 0 // TODO: Implement assignments count when assignment feature is added
                ];
                $data['teacherCourses'] = $teacherCourses;
                $data['pendingCount'] = $pendingCount;
                $data['enrolledCourses'] = [];
                $data['pendingEnrollments'] = [];
                $data['availableCourses'] = [];
                break;

            case 'student':
                // Student sees their enrolled courses and progress
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                
                // Get approved enrolled courses
                $enrolledCourses = $enrollmentModel->getUserEnrollments($userId);
                
                // Initialize session counter for auto-refresh
                session()->set('last_approved_count', count($enrolledCourses));
                
                // Get pending enrollments
                $pendingEnrollments = $enrollmentModel->getPendingEnrollments($userId);
                
                // Get enrolled and pending course IDs
                $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
                $pendingCourseIds = array_column($pendingEnrollments, 'course_id');
                $unavailableCourseIds = array_merge($enrolledCourseIds, $pendingCourseIds);
                
                // Get available courses (not enrolled or pending)
                $allCourses = $courseModel->getActiveCourses();
                $availableCourses = array_filter($allCourses, function($course) use ($unavailableCourseIds) {
                    return !in_array($course['id'], $unavailableCourseIds);
                });
                
                $data['message'] = 'Welcome to your Student Dashboard';
                $data['stats'] = [
                    'enrolledCourses' => count($enrolledCourses),
                    'completedLessons' => 0, // TODO: Implement completed lessons count
                    'pendingAssignments' => 0 // TODO: Implement pending assignments count
                ];
                $data['enrolledCourses'] = $enrolledCourses;
                $data['pendingEnrollments'] = $pendingEnrollments;
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
