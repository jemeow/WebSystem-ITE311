<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $courses = $this->courseModel->getActiveCourses();

        return view('courses/index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Enroll a student in a course (AJAX endpoint)
     */
    public function enroll()
    {
        // Security Check 1: Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ])->setStatusCode(401);
        }

        // Security Check 2: Verify CSRF token (CodeIgniter handles this automatically if enabled)
        
        // Security Check 3: Only students and teachers can self-enroll
        $userRole = session()->get('role');
        if ($userRole !== 'student' && $userRole !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students and teachers can enroll in courses.'
            ])->setStatusCode(403);
        }

        // Get course_id from POST request
        $courseId = $this->request->getPost('course_id');
        
        // Security Check 4: Validate input - ensure course_id is an integer
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ])->setStatusCode(400);
        }

        $courseId = (int) $courseId;

        // Security Check 5: Use session user ID, never trust client-supplied user ID
        $userId = session()->get('id');

        // Security Check 6: Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Check if course is active
        if ($course['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This course is not currently available for enrollment.'
            ])->setStatusCode(400);
        }

        // Check if teacher is trying to enroll in their own course or any course
        if ($userRole === 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Teachers cannot enroll in courses.'
            ])->setStatusCode(403);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Enroll the user with pending status
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];

        if ($this->enrollmentModel->enrollUser($enrollmentData)) {
            // Get the updated course details with teacher info
            $enrolledCourse = $this->courseModel->getCourseWithTeacher($courseId);
            
            // Create notification for student
            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->insert([
                'user_id' => $userId,
                'message' => 'You have submitted an enrollment request for ' . $course['course_name'] . '. Waiting for approval.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Notify the teacher of this course
            if (!empty($course['teacher_id'])) {
                $userModel = new \App\Models\UserModel();
                $student = $userModel->find($userId);
                $notificationModel->insert([
                    'user_id' => $course['teacher_id'],
                    'message' => 'New enrollment request from ' . $student['name'] . ' for ' . $course['course_name'],
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Notify all admins
            $admins = $userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $notificationModel->insert([
                    'user_id' => $admin['id'],
                    'message' => 'New enrollment request from ' . $student['name'] . ' for ' . $course['course_name'],
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request submitted! Waiting for admin/teacher approval.',
                'course' => $enrolledCourse,
                'status' => 'pending'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Unenroll from a course
     */
    public function unenroll()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ])->setStatusCode(401);
        }

        if (session()->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can unenroll from courses.'
            ])->setStatusCode(403);
        }

        $courseId = $this->request->getPost('course_id');
        
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ])->setStatusCode(400);
        }

        $userId = session()->get('id');

        if ($this->enrollmentModel->unenrollUser($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully unenrolled from the course.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Check enrollment status for auto-refresh (AJAX endpoint)
     */
    public function checkStatus()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized.'
            ])->setStatusCode(401);
        }

        $userId = session()->get('id');
        
        // Get pending enrollments count
        $pendingEnrollments = $this->enrollmentModel->getPendingEnrollments($userId);
        $pendingCount = count($pendingEnrollments);
        
        // Get approved enrollments count (to detect newly approved)
        // We'll use session to track previous count
        $previousApprovedCount = session()->get('last_approved_count') ?? 0;
        $approvedEnrollments = $this->enrollmentModel->getUserEnrollments($userId);
        $currentApprovedCount = count($approvedEnrollments);
        
        // Calculate newly approved
        $approvedCount = max(0, $currentApprovedCount - $previousApprovedCount);
        
        // Update session
        session()->set('last_approved_count', $currentApprovedCount);
        
        return $this->response->setJSON([
            'success' => true,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'totalEnrolled' => $currentApprovedCount
        ]);
    }

    public function search()
    {
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ])->setStatusCode(401);
            }

            return redirect()->to('/login');
        }

        $searchTerm = trim((string) ($this->request->getGetPost('search_term') ?? $this->request->getGetPost('q') ?? ''));

        $builder = $this->courseModel->builder();
        $builder->select('courses.*, users.name as teacher_name');
        $builder->join('users', 'users.id = courses.teacher_id', 'left');

        if ($searchTerm !== '') {
            $builder
                ->groupStart()
                ->like('courses.course_name', $searchTerm)
                ->orLike('courses.course_code', $searchTerm)
                ->orLike('courses.description', $searchTerm)
                ->groupEnd();
        }

        if ($builder->db->fieldExists('status', 'courses')) {
            $builder->where('courses.status', 'active');
        }

        $courses = $builder->get()->getResultArray();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($courses);
        }

        return view('courses/search_results', [
            'courses' => $courses,
            'searchTerm' => $searchTerm,
        ]);
    }
}
