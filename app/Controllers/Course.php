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
        
        // Security Check 3: Only students can enroll
        if (session()->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can enroll in courses.'
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

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Enroll the user
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->enrollmentModel->enrollUser($enrollmentData)) {
            // Get the updated course details with teacher info
            $enrolledCourse = $this->courseModel->getCourseWithTeacher($courseId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in ' . $course['course_name'],
                'course' => $enrolledCourse
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
}
