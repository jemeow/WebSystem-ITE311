<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\EnrollmentModel;

class CourseManagement extends BaseController
{
    protected $courseModel;
    protected $userModel;
    protected $enrollmentModel;
    
    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->userModel = new UserModel();
        $this->enrollmentModel = new EnrollmentModel();
        helper(['url', 'form']);
    }

    /**
     * Show course management page
     */
    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['courses'] = $this->courseModel->select('courses.*, users.name as teacher_name')
                                             ->join('users', 'users.id = courses.teacher_id', 'left')
                                             ->findAll();
        
        // Get enrollment counts for each course
        foreach ($data['courses'] as &$course) {
            $course['enrollment_count'] = $this->enrollmentModel->getCourseEnrollmentCount($course['id']);
        }
        
        // Get all active teachers for assignment modal
        $data['teachers'] = $this->userModel->where('role', 'teacher')
                                            ->where('status', 'active')
                                            ->findAll();
        
        return view('admin/course_management', $data);
    }

    /**
     * Show assign teachers page
     */
    public function assignTeachers()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['courses'] = $this->courseModel->select('courses.*, users.name as teacher_name')
                                             ->join('users', 'users.id = courses.teacher_id', 'left')
                                             ->orderBy('courses.course_code', 'ASC')
                                             ->findAll();
        
        $data['teachers'] = $this->userModel->where('role', 'teacher')
                                            ->where('status', 'active')
                                            ->orderBy('name', 'ASC')
                                            ->findAll();
        
        return view('admin/assign_teachers', $data);
    }

    /**
     * Show create course form
     */
    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['teachers'] = $this->userModel->where('role', 'teacher')
                                            ->where('status', 'active')
                                            ->findAll();
        
        return view('admin/course_create', $data);
    }

    /**
     * Store new course
     */
    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $rules = [
            'course_code' => 'required|min_length[3]|max_length[20]|is_unique[courses.course_code]',
            'course_name' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'teacher_id' => 'permit_empty|numeric',
            'credits' => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_code' => $this->request->getPost('course_code'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null,
            'credits' => $this->request->getPost('credits'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->courseModel->insert($data)) {
            return redirect()->to('/admin/courses')->with('success', 'Course created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create course.');
        }
    }

    /**
     * Show edit course form
     */
    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['course'] = $this->courseModel->find($id);
        
        if (!$data['course']) {
            return redirect()->to('/admin/courses')->with('error', 'Course not found.');
        }

        $data['teachers'] = $this->userModel->where('role', 'teacher')
                                            ->where('status', 'active')
                                            ->findAll();
        
        $data['enrollments'] = $this->enrollmentModel->getEnrolledStudents($id);
        
        return view('admin/course_edit', $data);
    }

    /**
     * Update course
     */
    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/admin/courses')->with('error', 'Course not found.');
        }

        $rules = [
            'course_code' => "required|min_length[3]|max_length[20]|is_unique[courses.course_code,id,{$id}]",
            'course_name' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'teacher_id' => 'permit_empty|numeric',
            'credits' => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_code' => $this->request->getPost('course_code'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null,
            'credits' => $this->request->getPost('credits'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->courseModel->update($id, $data)) {
            return redirect()->to('/admin/courses')->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update course.');
        }
    }

    /**
     * Delete course
     */
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $course = $this->courseModel->find($id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ]);
        }

        // Check if course has enrollments
        $enrollmentCount = $this->enrollmentModel->getCourseEnrollmentCount($id);
        if ($enrollmentCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Cannot delete course with {$enrollmentCount} active enrollment(s). Please unenroll all students first."
            ]);
        }

        if ($this->courseModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Course deleted successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete course.'
            ]);
        }
    }

    /**
     * Toggle course status
     */
    public function toggleStatus($id)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $course = $this->courseModel->find($id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ]);
        }

        $newStatus = $course['status'] === 'active' ? 'inactive' : 'active';
        
        if ($this->courseModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Course status changed to {$newStatus}.",
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update course status.'
            ]);
        }
    }

    /**
     * Assign teacher to course (AJAX)
     */
    public function assignTeacher()
    {
        // Log for debugging
        log_message('info', 'assignTeacher called');
        log_message('info', 'Role: ' . session()->get('role'));
        
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
        }

        $courseId = $this->request->getPost('course_id');
        $teacherId = $this->request->getPost('teacher_id');

        log_message('info', 'Course ID: ' . $courseId);
        log_message('info', 'Teacher ID: ' . $teacherId);

        if (!$courseId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required.'
            ]);
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ]);
        }

        // If teacher_id is empty, remove teacher assignment
        if (empty($teacherId)) {
            if ($this->courseModel->update($courseId, ['teacher_id' => null])) {
                log_message('info', 'Teacher removed successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Teacher removed from course.',
                    'teacher_name' => 'No teacher assigned'
                ]);
            }
        } else {
            // Verify teacher exists
            $teacher = $this->userModel->where('id', $teacherId)
                                      ->where('role', 'teacher')
                                      ->where('status', 'active')
                                      ->first();
            
            if (!$teacher) {
                log_message('error', 'Invalid teacher ID: ' . $teacherId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid teacher selected.'
                ]);
            }

            if ($this->courseModel->update($courseId, ['teacher_id' => $teacherId])) {
                log_message('info', 'Teacher assigned successfully: ' . $teacher['name']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Teacher assigned successfully!',
                    'teacher_name' => $teacher['name']
                ]);
            }
        }

        log_message('error', 'Failed to assign teacher for unknown reason');
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to assign teacher.'
        ]);
    }
}
