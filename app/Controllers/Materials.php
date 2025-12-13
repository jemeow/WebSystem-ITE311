<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;
    
    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        helper(['url', 'form', 'filesystem', 'download']);
    }

    /**
     * Test endpoint to verify routing
     */
    public function test()
    {
        log_message('info', 'TEST ENDPOINT HIT');
        return json_encode(['status' => 'test works']);
    }
    
    /**
     * Display upload form and handle file upload
     */
    public function upload($courseId)
    {
        // Set JSON header immediately
        $this->response->setContentType('application/json');
        
        log_message('info', '========== UPLOAD METHOD CALLED ==========');
        log_message('info', 'Course ID: ' . $courseId);
        log_message('info', 'Request Method: ' . $this->request->getMethod());
        log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'yes' : 'no'));
        log_message('info', 'Files: ' . json_encode($_FILES));
        
        try {
            // Security: Check if user is admin or teacher
            if (!in_array(session()->get('role'), ['admin', 'teacher'])) {
                log_message('error', 'Access denied');
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
            }

            // Get course details
            $course = $this->courseModel->find($courseId);
            if (!$course) {
                log_message('error', 'Course not found');
                return $this->response->setJSON(['success' => false, 'message' => 'Course not found']);
            }

            // If teacher, verify they teach this course
            if (session()->get('role') === 'teacher' && $course['teacher_id'] != session()->get('id')) {
                log_message('error', 'Unauthorized');
                return $this->response->setJSON(['success' => false, 'message' => 'You can only upload materials for your own courses']);
            }

            // Handle POST request (file upload)
            if (strtolower($this->request->getMethod()) === 'post') {
                log_message('info', 'Processing upload');
                return $this->processUploadAJAX($courseId, $course);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method: ' . $this->request->getMethod()]);
            
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Process AJAX file upload
     */
    private function processUploadAJAX($courseId, $course)
    {
        try {
            log_message('info', 'Processing AJAX upload');
            
            // Check if file was uploaded
            if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
                $error = isset($_FILES['material_file']) ? 'Upload error code: ' . $_FILES['material_file']['error'] : 'No file received';
                log_message('error', 'File error: ' . $error);
                return $this->response->setJSON(['success' => false, 'message' => $error]);
            }

            $file = $this->request->getFile('material_file');
            log_message('info', 'File: ' . $file->getName() . ', Size: ' . $file->getSize());
            
            // Validation
            if ($file->getSize() > 102400000) { // 100MB
                log_message('error', 'File too large');
                return $this->response->setJSON(['success' => false, 'message' => 'File is too large (max 100MB)']);
            }

            $allowedExt = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'rar'];
            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowedExt)) {
                log_message('error', 'Invalid extension: ' . $ext);
                return $this->response->setJSON(['success' => false, 'message' => 'File type not allowed']);
            }

            // Create upload directory
            $uploadPath = WRITEPATH . 'uploads/materials/' . $courseId . '/';
            log_message('info', 'Upload path: ' . $uploadPath);
            
            if (!is_dir($uploadPath)) {
                if (!@mkdir($uploadPath, 0777, true)) {
                    log_message('error', 'Failed to create directory');
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to create upload directory']);
                }
                log_message('info', 'Directory created');
            }

            // Move file
            $newName = $file->getRandomName();
            log_message('info', 'Moving file to: ' . $uploadPath . $newName);
            
            if (!$file->move($uploadPath, $newName)) {
                log_message('error', 'Failed to move file');
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to upload file']);
            }
            
            log_message('info', 'File moved successfully');

            // Save to database
            $materialData = [
                'course_id' => $courseId,
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/materials/' . $courseId . '/' . $newName,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('info', 'Inserting to database');
            
            if (!$this->materialModel->insert($materialData)) {
                log_message('error', 'Database insert failed');
                @unlink($uploadPath . $newName);
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to save material to database']);
            }

            // Notify all enrolled students
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrolledStudents = $enrollmentModel->where('course_id', $courseId)
                                               ->where('status', 'approved')
                                               ->findAll();
            
            if (!empty($enrolledStudents)) {
                $notificationModel = new \App\Models\NotificationModel();
                foreach ($enrolledStudents as $enrollment) {
                    $notificationModel->insert([
                        'user_id' => $enrollment['user_id'],
                        'message' => 'New material uploaded in ' . $course['course_name'] . ': ' . $file->getClientName(),
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            log_message('info', 'Upload successful!');
            return $this->response->setJSON(['success' => true, 'message' => 'Material uploaded successfully!']);
            
        } catch (\Exception $e) {
            log_message('error', 'Exception in processUploadAJAX: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a material
     */
    public function delete($materialId)
    {
        // Security: Check if user is admin or teacher
        if (!in_array(session()->get('role'), ['admin', 'teacher'])) {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        // Get material with course info
        $material = $this->materialModel->getMaterialWithCourse($materialId);
        
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // If teacher, verify they teach this course
        $course = $this->courseModel->find($material['course_id']);
        if (session()->get('role') === 'teacher' && $course['teacher_id'] != session()->get('id')) {
            return redirect()->back()->with('error', 'You can only delete materials from your own courses.');
        }

        // Delete from database
        if ($this->materialModel->delete($materialId)) {
            return redirect()->back()->with('success', 'Material deleted successfully!');
        }

        return redirect()->back()->with('error', 'Failed to delete material.');
    }

    /**
     * Download a material (for enrolled students)
     */
    public function download($materialId)
    {
        // Security: Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to download materials.');
        }

        // Get material details
        $material = $this->materialModel->getMaterialWithCourse($materialId);
        
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        $userId = session()->get('id');
        $userRole = session()->get('role');

        if ($userRole === 'user') {
            $userRole = 'student';
            session()->set('role', $userRole);
        }

        // Admin and teachers can download any material
        // Students can only download if enrolled in the course
        if ($userRole === 'student') {
            if (!$this->materialModel->userHasAccess($userId, $materialId)) {
                return redirect()->back()->with('error', 'You must be enrolled in this course to download materials.');
            }
        }

        // Check if file exists
        $filePath = WRITEPATH . $material['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Force download
        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }

    /**
     * View materials for a specific course (for students)
     */
    public function viewCourseMaterials($courseId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id');
        $userRole = session()->get('role');

        if ($userRole === 'user') {
            $userRole = 'student';
            session()->set('role', $userRole);
        }

        // Get course details
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Check if student is enrolled
        if ($userRole === 'student') {
            $approvedEnrollment = $this->enrollmentModel
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('status', 'approved')
                ->first();

            if (!$approvedEnrollment) {
                return redirect()->back()->with('error', 'You must be enrolled in this course to view materials.');
            }
        }

        // Get materials
        $data['course'] = $course;
        $data['materials'] = $this->materialModel->getMaterialsByCourse($courseId);
        
        return view('student/course_materials', $data);
    }
}
