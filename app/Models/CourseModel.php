<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['course_code', 'course_name', 'description', 'teacher_id', 'credits', 'status'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'course_code' => 'required|min_length[3]|max_length[20]|is_unique[courses.course_code]',
        'course_name' => 'required|min_length[3]|max_length[255]',
        'credits' => 'required|integer|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'course_code' => [
            'required' => 'Course code is required',
            'is_unique' => 'This course code already exists'
        ],
        'course_name' => [
            'required' => 'Course name is required'
        ]
    ];

    /**
     * Get all active courses
     */
    public function getActiveCourses()
    {
        return $this->select('courses.*, users.name as teacher_name')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
                    ->where('courses.status', 'active')
                    ->findAll();
    }

    /**
     * Get courses by teacher
     */
    public function getCoursesByTeacher($teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                    ->where('courses.status', 'active')
                    ->findAll();
    }

    /**
     * Get course with teacher details
     */
    public function getCourseWithTeacher($courseId)
    {
        return $this->join('users', 'users.id = courses.teacher_id', 'left')
                    ->select('courses.*, users.name as teacher_name')
                    ->find($courseId);
    }
}
