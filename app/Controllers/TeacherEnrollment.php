<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\EnrollmentHistoryModel;

class TeacherEnrollment extends BaseController
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
     * Show teacher enrollment management page
     */
    public function index()
    {
        // Check if user is teacher
        if (session()->get('role') !== 'teacher') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Teacher only.');
        }

        $teacherId = session()->get('id');
        
        // Get teacher's courses
        $data['courses'] = $this->courseModel->where('teacher_id', $teacherId)
                                            ->where('status', 'active')
                                            ->findAll();
        
        return view('teacher/enrollments', $data);
    }

    /**
     * Get pending enrollments for teacher's courses (AJAX)
     */
    public function getPendingEnrollments()
    {
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $teacherId = session()->get('id');
        
        // Get teacher's course IDs
        $teacherCourses = $this->courseModel->where('teacher_id', $teacherId)
                                           ->where('status', 'active')
                                           ->findAll();
        
        $courseIds = array_column($teacherCourses, 'id');
        
        if (empty($courseIds)) {
            return $this->response->setJSON([
                'success' => true,
                'enrollments' => []
            ]);
        }
        
        // Get pending enrollments for teacher's courses
        $pendingEnrollments = $this->enrollmentModel->select('enrollments.*, users.name as student_name, users.email, courses.course_code, courses.course_name')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.status', 'pending')
                    ->whereIn('enrollments.course_id', $courseIds)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
        
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
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $enrollmentId = $this->request->getPost('enrollment_id');
        $teacherId = session()->get('id');

        if (!$enrollmentId || !is_numeric($enrollmentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid enrollment ID.'
            ])->setStatusCode(400);
        }

        // Get enrollment details and verify teacher owns the course
        $enrollment = $this->enrollmentModel->select('enrollments.*, users.name as student_name, courses.course_name, courses.course_code, courses.teacher_id')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->find($enrollmentId);

        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Verify that this teacher owns the course
        if ($enrollment['teacher_id'] != $teacherId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only approve enrollments for your courses.'
            ])->setStatusCode(403);
        }

        if ($this->enrollmentModel->approveEnrollment($enrollmentId)) {
            // Log to history
            $this->historyModel->logAction([
                'enrollment_id' => $enrollmentId,
                'user_id' => $enrollment['user_id'],
                'course_id' => $enrollment['course_id'],
                'action' => 'approved',
                'admin_id' => $teacherId,
                'admin_name' => session()->get('name') . ' (Teacher)',
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
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $enrollmentId = $this->request->getPost('enrollment_id');
        $teacherId = session()->get('id');

        if (!$enrollmentId || !is_numeric($enrollmentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid enrollment ID.'
            ])->setStatusCode(400);
        }

        // Get enrollment details and verify teacher owns the course
        $enrollment = $this->enrollmentModel->select('enrollments.*, users.name as student_name, courses.course_name, courses.course_code, courses.teacher_id')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->find($enrollmentId);

        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Verify that this teacher owns the course
        if ($enrollment['teacher_id'] != $teacherId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only reject enrollments for your courses.'
            ])->setStatusCode(403);
        }

        if ($this->enrollmentModel->rejectEnrollment($enrollmentId)) {
            // Log to history
            $this->historyModel->logAction([
                'enrollment_id' => $enrollmentId,
                'user_id' => $enrollment['user_id'],
                'course_id' => $enrollment['course_id'],
                'action' => 'rejected',
                'admin_id' => $teacherId,
                'admin_name' => session()->get('name') . ' (Teacher)',
                'student_name' => $enrollment['student_name'],
                'course_name' => $enrollment['course_name'],
                'course_code' => $enrollment['course_code'],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment rejected successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to reject enrollment.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Get enrollment history for teacher's courses
     */
    public function history()
    {
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $teacherId = session()->get('id');
        
        // Get teacher's course IDs
        $teacherCourses = $this->courseModel->where('teacher_id', $teacherId)
                                           ->where('status', 'active')
                                           ->findAll();
        
        $courseIds = array_column($teacherCourses, 'id');
        
        if (empty($courseIds)) {
            return $this->response->setJSON([
                'success' => true,
                'history' => [],
                'statistics' => [
                    'total' => 0,
                    'approved' => 0,
                    'rejected' => 0
                ]
            ]);
        }
        
        // Get filters
        $filters = [
            'action' => $this->request->getGet('action'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'search' => $this->request->getGet('search'),
            'course_ids' => $courseIds // Only show history for teacher's courses
        ];
        
        // Get history with filters
        $history = $this->historyModel->getHistory($filters);
        
        // Get statistics for teacher's courses
        $statistics = $this->historyModel->getStatistics($courseIds);
        
        return $this->response->setJSON([
            'success' => true,
            'history' => $history,
            'statistics' => $statistics
        ]);
    }
}
