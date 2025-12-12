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
        $scheduleDays = $this->request->getPost('schedule_days');
        $startTime = $this->request->getPost('schedule_start_time');
        $endTime = $this->request->getPost('schedule_end_time');

        log_message('info', '=== ASSIGN TEACHER REQUEST ===' );
        log_message('info', 'Course ID: ' . var_export($courseId, true));
        log_message('info', 'Teacher ID: ' . var_export($teacherId, true));
        log_message('info', 'Schedule Days: ' . var_export($scheduleDays, true));
        log_message('info', 'Start Time: ' . var_export($startTime, true));
        log_message('info', 'End Time: ' . var_export($endTime, true));

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

        // If teacher_id is empty, remove teacher assignment and schedule
        if (empty($teacherId)) {
            $updateData = [
                'teacher_id' => null,
                'schedule_days' => null,
                'schedule_start_time' => null,
                'schedule_end_time' => null
            ];
            if ($this->courseModel->update($courseId, $updateData)) {
                log_message('info', 'Teacher removed successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Teacher removed from course.',
                    'teacher_name' => 'No teacher assigned'
                ]);
            }
        } else {
            // Validate schedule when assigning teacher
            if (!$scheduleDays || !$startTime || !$endTime) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Schedule information is required when assigning a teacher.'
                ]);
            }

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

            // Check for schedule conflicts with other courses taught by the same teacher
            $conflict = $this->checkScheduleConflict($teacherId, $scheduleDays, $startTime, $endTime, $courseId);
            if ($conflict) {
                log_message('warning', 'Schedule conflict detected for teacher ID: ' . $teacherId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Schedule conflict! ' . $teacher['name'] . ' already teaches ' . 
                                $conflict['course_name'] . ' (' . $conflict['course_code'] . ') on ' . 
                                $conflict['schedule_days'] . ' at ' . 
                                date('g:i A', strtotime($conflict['schedule_start_time'])) . ' - ' . 
                                date('g:i A', strtotime($conflict['schedule_end_time'])) . '.'
                ]);
            }

            // Update course with teacher and schedule
            $updateData = [
                'teacher_id' => $teacherId,
                'schedule_days' => $scheduleDays,
                'schedule_start_time' => $startTime,
                'schedule_end_time' => $endTime
            ];

            log_message('info', 'Update data: ' . json_encode($updateData));
            
            $updateResult = $this->courseModel->update($courseId, $updateData);
            log_message('info', 'Update result: ' . ($updateResult ? 'SUCCESS' : 'FAILED'));
            
            if ($updateResult) {
                // Verify the update
                $verifiedCourse = $this->courseModel->find($courseId);
                log_message('info', 'Verified schedule_days: ' . var_export($verifiedCourse['schedule_days'], true));
                log_message('info', 'Verified start_time: ' . var_export($verifiedCourse['schedule_start_time'], true));
                log_message('info', 'Verified end_time: ' . var_export($verifiedCourse['schedule_end_time'], true));
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Teacher assigned successfully with schedule!',
                    'teacher_name' => $teacher['name'],
                    'schedule' => [
                        'days' => $verifiedCourse['schedule_days'],
                        'start' => $verifiedCourse['schedule_start_time'],
                        'end' => $verifiedCourse['schedule_end_time']
                    ]
                ]);
            }
        }

        log_message('error', 'Failed to assign teacher for unknown reason');
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to assign teacher.'
        ]);
    }

    /**
     * Check for schedule conflicts
     * 
     * @param int $teacherId The teacher's ID
     * @param string $scheduleDays Comma-separated days (e.g., "Mon, Wed, Fri")
     * @param string $startTime Start time (e.g., "08:00")
     * @param string $endTime End time (e.g., "10:00")
     * @param int|null $excludeCourseId Course ID to exclude from conflict check (for updates)
     * @return array|false Returns conflicting course data or false if no conflict
     */
    private function checkScheduleConflict($teacherId, $scheduleDays, $startTime, $endTime, $excludeCourseId = null)
    {
        // Get all courses taught by this teacher with schedules
        $query = $this->courseModel
            ->where('teacher_id', $teacherId)
            ->where('schedule_days IS NOT NULL')
            ->where('schedule_start_time IS NOT NULL')
            ->where('schedule_end_time IS NOT NULL');
        
        // Exclude current course if updating
        if ($excludeCourseId) {
            $query->where('id !=', $excludeCourseId);
        }
        
        $teacherCourses = $query->findAll();

        // Convert new schedule days to array
        $newDays = array_map('trim', explode(',', $scheduleDays));

        foreach ($teacherCourses as $course) {
            // Convert existing schedule days to array
            $existingDays = array_map('trim', explode(',', $course['schedule_days']));

            // Check if there's any day overlap
            $dayOverlap = array_intersect($newDays, $existingDays);
            
            if (!empty($dayOverlap)) {
                // There's a day overlap, now check time overlap
                $newStart = strtotime($startTime);
                $newEnd = strtotime($endTime);
                $existingStart = strtotime($course['schedule_start_time']);
                $existingEnd = strtotime($course['schedule_end_time']);

                // Check if times overlap
                // Times overlap if: new start < existing end AND new end > existing start
                if ($newStart < $existingEnd && $newEnd > $existingStart) {
                    // Conflict found!
                    return $course;
                }
            }
        }

        return false;
    }
}
