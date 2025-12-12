<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'created_at'];
    
    protected $useTimestamps = false;

    /**
     * Insert a new material record
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     */
    public function getMaterialsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get material with course information
     */
    public function getMaterialWithCourse($materialId)
    {
        return $this->select('materials.*, courses.course_code, courses.course_name')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->where('materials.id', $materialId)
                    ->first();
    }

    /**
     * Get all materials for courses a student is enrolled in
     */
    public function getMaterialsForEnrolledCourses($userId)
    {
        return $this->select('materials.*, courses.course_code, courses.course_name')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->join('enrollments', 'enrollments.course_id = courses.id')
                    ->where('enrollments.user_id', $userId)
                    ->where('enrollments.status', 'approved')
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Check if user has access to material (enrolled in course)
     */
    public function userHasAccess($userId, $materialId)
    {
        $material = $this->select('materials.*, enrollments.user_id')
                        ->join('enrollments', 'enrollments.course_id = materials.course_id')
                        ->where('materials.id', $materialId)
                        ->where('enrollments.user_id', $userId)
                        ->where('enrollments.status', 'approved')
                        ->first();
        
        return $material !== null;
    }

    /**
     * Delete material and return file path
     */
    public function deleteMaterial($materialId)
    {
        $material = $this->find($materialId);
        if ($material && $this->delete($materialId)) {
            return $material['file_path'];
        }
        return false;
    }
}
