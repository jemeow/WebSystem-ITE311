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

            // Create notification for student
            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->insert([
                'user_id' => $enrollment['user_id'],
                'message' => 'You have been enrolled in ' . $enrollment['course_name'] . ' (' . $enrollment['course_code'] . ')',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Notify all admins that a student has been enrolled
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $notificationModel->insert([
                    'user_id' => $admin['id'],
                    'message' => 'Student enrolled: ' . $enrollment['student_name'] . ' was enrolled in ' . $enrollment['course_name'] . ' (' . $enrollment['course_code'] . ') by a teacher.',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

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
    
    /**
     * Show teacher's page to enroll students in their courses
     */
    public function manageStudents()
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
        
        // Get all active students
        $data['students'] = $this->userModel->where('role', 'student')
                                           ->where('status', 'active')
                                           ->findAll();
        
        return view('teacher/manage_students', $data);
    }
    
    /**
     * Get student enrollments for a teacher's course (AJAX)
     */
    public function getStudentEnrollments()
    {
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(401);
        }

        $studentId = $this->request->getPost('student_id');
        $teacherId = session()->get('id');
        
        if (!$studentId || !is_numeric($studentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student ID.'
            ])->setStatusCode(400);
        }
        
        // Get only teacher's courses
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
        
        // Get student's enrollments in teacher's courses
        $enrollments = $this->enrollmentModel->whereIn('course_id', $courseIds)
                                            ->where('user_id', $studentId)
                                            ->findAll();
        
        $enrolledCourseIds = array_map('intval', array_column($enrollments, 'course_id'));
        
        return $this->response->setJSON([
            'success' => true,
            'enrolled_course_ids' => $enrolledCourseIds
        ]);
    }
    
    /**
     * Enroll a student in teacher's course (AJAX)
     */
    public function enrollStudent()
    {
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $studentId = $this->request->getPost('student_id');
        $courseId = $this->request->getPost('course_id');
        $teacherId = session()->get('id');
        
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
        
        // Verify course belongs to teacher
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['teacher_id'] != $teacherId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only enroll students in your own courses.'
            ])->setStatusCode(403);
        }
        
        // Verify student exists
        $student = $this->userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found.'
            ])->setStatusCode(404);
        }
        
        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course.'
            ])->setStatusCode(400);
        }
        
        // Enroll student with approved status
        $enrollmentData = [
            'user_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'approved'
        ];
        
        if ($this->enrollmentModel->insert($enrollmentData)) {
            // Log to history
            $this->historyModel->logAction([
                'enrollment_id' => $this->enrollmentModel->getInsertID(),
                'user_id' => $studentId,
                'course_id' => $courseId,
                'action' => 'approved',
                'admin_id' => $teacherId,
                'admin_name' => session()->get('name') . ' (Teacher)',
                'student_name' => $student['name'],
                'course_name' => $course['course_name'],
                'course_code' => $course['course_code'],
            ]);
            
            // Create notification for student
            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->insert([
                'user_id' => $studentId,
                'message' => 'You have been enrolled in ' . $course['course_name'] . ' (' . $course['course_code'] . ') by your teacher',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Notify all admins that a student has been enrolled
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $notificationModel->insert([
                    'user_id' => $admin['id'],
                    'message' => 'Student enrolled: ' . $student['name'] . ' was enrolled in ' . $course['course_name'] . ' (' . $course['course_code'] . ') by a teacher.',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student enrolled successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll student.'
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Unenroll a student from teacher's course (AJAX)
     */
    public function unenrollStudent()
    {
        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ])->setStatusCode(403);
        }

        $studentId = $this->request->getPost('student_id');
        $courseId = $this->request->getPost('course_id');
        $teacherId = session()->get('id');
        
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
        
        // Verify course belongs to teacher
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['teacher_id'] != $teacherId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only unenroll students from your own courses.'
            ])->setStatusCode(403);
        }
        
        // Get student info
        $student = $this->userModel->find($studentId);
        
        // Unenroll student
        if ($this->enrollmentModel->unenrollUser($studentId, $courseId)) {
            // Create notification for student
            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->insert([
                'user_id' => $studentId,
                'message' => 'You have been unenrolled from ' . $course['course_name'] . ' (' . $course['course_code'] . ')',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
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
