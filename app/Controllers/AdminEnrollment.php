<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;

class AdminEnrollment extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
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

        // Enroll the student
        $enrollmentData = [
            'user_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->enrollmentModel->enrollUser($enrollmentData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $student['name'] . ' enrolled in ' . $course['course_name'] . ' successfully.',
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
}
