<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\EnrollmentHistoryModel;

class AdminEnrollment extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $historyModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        $this->historyModel = new EnrollmentHistoryModel();
        helper(['url', 'form']);
    }

    /**
     * Show enrollment dashboard with statistics
     */
    public function dashboard()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        // Get statistics
        $data['totalEnrollments'] = $this->enrollmentModel->countAllResults();
        $data['totalStudents'] = $this->userModel->where('role', 'student')->where('status', 'active')->countAllResults();
        $data['totalCourses'] = $this->courseModel->where('status', 'active')->countAllResults();
        
        // Get enrollment trends
        $data['enrollmentStats'] = $this->enrollmentModel->getEnrollmentStatistics();
        
        // Get most popular courses
        $data['popularCourses'] = $this->enrollmentModel->getPopularCourses(5);
        
        // Get recent enrollments
        $data['recentEnrollments'] = $this->enrollmentModel->getRecentEnrollments(10);
        
        // Get courses with low enrollment
        $data['lowEnrollmentCourses'] = $this->enrollmentModel->getLowEnrollmentCourses(3);
        
        return view('admin/enrollment_dashboard', $data);
    }

    /**
     * Show enrollment management page
     */
    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['students'] = $this->userModel->where('role', 'student')
                                            ->where('status', 'active')
                                            ->findAll();
        $data['courses'] = $this->courseModel->getActiveCourses();
        
        return view('admin/enrollments', $data);
    }

    /**
     * Get student's enrollments (AJAX)
     */
    public function getStudentEnrollments()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(401);
        }

        $studentId = $this->request->getPost('student_id');
        
        if (!$studentId || !is_numeric($studentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student ID.'
            ])->setStatusCode(400);
        }

        $enrollments = $this->enrollmentModel->getUserEnrollments($studentId);
        $enrolledCourseIds = array_column($enrollments, 'course_id');
        
        return $this->response->setJSON([
            'success' => true,
            'enrollments' => $enrollments,
            'enrolledCourseIds' => $enrolledCourseIds
        ]);
    }

    /**
     * Enroll a student in a course (Admin action)
     */
    public function enrollStudent()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Admin access only.'
            ])->setStatusCode(401);
        }

        $studentId = $this->request->getPost('student_id');
        $courseId = $this->request->getPost('course_id');
        
        // Validate inputs
        if (!$studentId || !is_numeric($studentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student ID.'
            ])->setStatusCode(400);
        }

        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ])->setStatusCode(400);
        }

        $studentId = (int) $studentId;
        $courseId = (int) $courseId;

        // Verify student exists and is a student
        $student = $this->userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found.'
            ])->setStatusCode(404);
        }

        // Verify course exists and is active
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found or inactive.'
            ])->setStatusCode(404);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // If course has a teacher assigned, enrollment goes to pending for teacher approval
        // If no teacher assigned, admin can directly approve
        $enrollmentStatus = !empty($course['teacher_id']) ? 'pending' : 'approved';
        
        $enrollmentData = [
            'user_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => $enrollmentStatus
        ];

        if ($this->enrollmentModel->enrollUser($enrollmentData)) {
            $message = !empty($course['teacher_id']) 
                ? $student['name'] . ' enrollment request submitted for ' . $course['course_name'] . '. Waiting for teacher approval.'
                : $student['name'] . ' enrolled in ' . $course['course_name'] . ' successfully.';
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'enrollment' => [
                    'id' => $this->enrollmentModel->getInsertID(),
                    'course_code' => $course['course_code'],
                    'course_name' => $course['course_name'],
                    'enrollment_date' => date('M d, Y')
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll student. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Unenroll a student from a course (Admin action)
     */
    public function unenrollStudent()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Admin access only.'
            ])->setStatusCode(401);
        }

        $studentId = $this->request->getPost('student_id');
        $courseId = $this->request->getPost('course_id');
        
        if (!$studentId || !is_numeric($studentId) || !$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid parameters.'
            ])->setStatusCode(400);
        }

        if ($this->enrollmentModel->unenrollUser($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student unenrolled successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll student.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Show pending enrollments page
     */
    public function pending()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        return view('admin/pending_enrollments');
    }

    /**
     * Get all pending enrollments (AJAX)
     */
    public function getPendingEnrollments()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $pendingEnrollments = $this->enrollmentModel->getAllPendingEnrollments();
        
        return $this->response->setJSON([
            'success' => true,
            'enrollments' => $pendingEnrollments
        ]);
    }

    /**
     * Approve enrollment (AJAX)
     */
    public function approveEnrollment()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $enrollmentId = $this->request->getPost('enrollment_id');
        
        if (!$enrollmentId || !is_numeric($enrollmentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid enrollment ID.'
            ])->setStatusCode(400);
        }

        // Get enrollment details before approving
        $enrollment = $this->enrollmentModel->select('enrollments.*, users.name as student_name, courses.course_name, courses.course_code')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->find($enrollmentId);

        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        if ($this->enrollmentModel->approveEnrollment($enrollmentId)) {
            // Log to history
            $this->historyModel->logAction([
                'enrollment_id' => $enrollmentId,
                'user_id' => $enrollment['user_id'],
                'course_id' => $enrollment['course_id'],
                'action' => 'approved',
                'admin_id' => session()->get('id'),
                'admin_name' => session()->get('name'),
                'student_name' => $enrollment['student_name'],
                'course_name' => $enrollment['course_name'],
                'course_code' => $enrollment['course_code'],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment approved successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to approve enrollment.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Reject enrollment (AJAX)
     */
    public function rejectEnrollment()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $enrollmentId = $this->request->getPost('enrollment_id');
        
        if (!$enrollmentId || !is_numeric($enrollmentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid enrollment ID.'
            ])->setStatusCode(400);
        }

        // Get enrollment details before rejecting
        $enrollment = $this->enrollmentModel->select('enrollments.*, users.name as student_name, courses.course_name, courses.course_code')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->find($enrollmentId);

        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Log to history before deleting
        $this->historyModel->logAction([
            'enrollment_id' => $enrollmentId,
            'user_id' => $enrollment['user_id'],
            'course_id' => $enrollment['course_id'],
            'action' => 'rejected',
            'admin_id' => session()->get('id'),
            'admin_name' => session()->get('name'),
            'student_name' => $enrollment['student_name'],
            'course_name' => $enrollment['course_name'],
            'course_code' => $enrollment['course_code'],
        ]);

        // Reject means delete the enrollment request
        if ($this->enrollmentModel->delete($enrollmentId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment rejected and removed.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to reject enrollment.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Show enrollment history
     */
    public function history()
    {
        if (session()->get('role') !== 'admin') {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ])->setStatusCode(403);
            }
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            $filters = [
                'action' => $this->request->getGet('action'),
                'search' => $this->request->getGet('search'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to' => $this->request->getGet('date_to'),
            ];

            $history = $this->historyModel->getHistory(1000, 0, $filters); // Get up to 1000 records for client-side filtering
            $stats = $this->historyModel->getStatistics();

            return $this->response->setJSON([
                'success' => true,
                'history' => $history,
                'stats' => $stats
            ]);
        }

        // For non-AJAX requests, return the view
        // Get filters from query string
        $filters = [
            'action' => $this->request->getGet('action'),
            'search' => $this->request->getGet('search'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
        ];

        // Pagination
        $perPage = 20;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $data['history'] = $this->historyModel->getHistory($perPage, $offset, $filters);
        $data['total'] = $this->historyModel->getHistoryCount($filters);
        $data['stats'] = $this->historyModel->getStatistics();
        $data['filters'] = $filters;
        $data['pager'] = \Config\Services::pager();
        $data['currentPage'] = $page;
        $data['perPage'] = $perPage;

        return view('admin/enrollment_history', $data);
    }
}
