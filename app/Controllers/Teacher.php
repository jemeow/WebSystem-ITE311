<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\MaterialModel;

class Teacher extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $materialModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        $this->materialModel = new MaterialModel();
        helper(['url', 'form']);
    }

    /**
     * Display teacher's courses list
     */
    public function courses()
    {
        // Check if user is teacher
        if (session()->get('role') !== 'teacher') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $teacherId = session()->get('id');

        // Get teacher's assigned courses
        $data['courses'] = $this->courseModel->where('teacher_id', $teacherId)
                                             ->orderBy('course_code', 'ASC')
                                             ->findAll();

        // Get enrollment counts for each course
        foreach ($data['courses'] as &$course) {
            $course['enrollment_count'] = $this->enrollmentModel
                ->where('course_id', $course['id'])
                ->where('status', 'approved')
                ->countAllResults();
            
            // Get material count
            $course['material_count'] = $this->materialModel
                ->where('course_id', $course['id'])
                ->countAllResults();
        }

        return view('teacher/courses', $data);
    }

    /**
     * View specific course details with enrolled students
     */
    public function viewCourse($courseId)
    {
        // Check if user is teacher
        if (session()->get('role') !== 'teacher') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $teacherId = session()->get('id');

        // Get course details
        $course = $this->courseModel->find($courseId);
        
        if (!$course) {
            return redirect()->to('/teacher/courses')->with('error', 'Course not found.');
        }

        // Verify this teacher owns the course
        if ($course['teacher_id'] != $teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'You can only view your own courses.');
        }

        $data['course'] = $course;

        // Get enrolled students
        $data['enrolledStudents'] = $this->enrollmentModel
            ->select('enrollments.*, users.name, users.email')
            ->join('users', 'users.id = enrollments.user_id')
            ->where('enrollments.course_id', $courseId)
            ->where('enrollments.status', 'approved')
            ->orderBy('users.name', 'ASC')
            ->findAll();

        // Get pending enrollment requests
        $data['pendingEnrollments'] = $this->enrollmentModel
            ->select('enrollments.*, users.name, users.email')
            ->join('users', 'users.id = enrollments.user_id')
            ->where('enrollments.course_id', $courseId)
            ->where('enrollments.status', 'pending')
            ->orderBy('enrollments.enrollment_date', 'DESC')
            ->findAll();

        // Get course materials
        $data['materials'] = $this->materialModel->getMaterialsByCourse($courseId);

        return view('teacher/course_detail', $data);
    }
}
