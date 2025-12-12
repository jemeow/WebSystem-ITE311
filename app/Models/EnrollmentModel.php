<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status'];
    
    protected $useTimestamps = false;

    /**
     * Enroll a user in a course
     */
    public function enrollUser($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all courses a user is enrolled in (approved only)
     */
    public function getUserEnrollments($userId)
    {
        return $this->select('enrollments.*, courses.course_code, courses.course_name, courses.description, courses.credits, users.name as teacher_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
                    ->where('enrollments.user_id', $userId)
                    ->where('enrollments.status', 'approved')
                    ->where('courses.status', 'active')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Check if user is already enrolled in a specific course
     */
    public function isAlreadyEnrolled($userId, $courseId)
    {
        $enrollment = $this->where('user_id', $userId)
                          ->where('course_id', $courseId)
                          ->first();
        
        return $enrollment !== null;
    }

    /**
     * Get enrollment count for a course (approved only)
     */
    public function getCourseEnrollmentCount($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->where('status', 'approved')
                    ->countAllResults();
    }

    /**
     * Get pending enrollments for a user
     */
    public function getPendingEnrollments($userId)
    {
        return $this->select('enrollments.*, courses.course_code, courses.course_name, courses.description, courses.credits, users.name as teacher_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
                    ->where('enrollments.user_id', $userId)
                    ->where('enrollments.status', 'pending')
                    ->where('courses.status', 'active')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get all pending enrollments (for admin)
     */
    public function getAllPendingEnrollments()
    {
        return $this->select('enrollments.*, users.name as student_name, users.email, courses.course_code, courses.course_name')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.status', 'pending')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Approve enrollment
     */
    public function approveEnrollment($enrollmentId)
    {
        return $this->update($enrollmentId, ['status' => 'approved']);
    }

    /**
     * Reject enrollment
     */
    public function rejectEnrollment($enrollmentId)
    {
        return $this->update($enrollmentId, ['status' => 'rejected']);
    }

    /**
     * Get all enrolled students for a course
     */
    public function getEnrolledStudents($courseId)
    {
        return $this->select('enrollments.*, users.name, users.email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->where('users.role', 'student')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Unenroll a user from a course
     */
    public function unenrollUser($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->delete();
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStatistics()
    {
        return $this->select('DATE(enrollment_date) as date, COUNT(*) as count')
                    ->groupBy('DATE(enrollment_date)')
                    ->orderBy('date', 'DESC')
                    ->limit(30)
                    ->findAll();
    }

    /**
     * Get most popular courses by enrollment count
     */
    public function getPopularCourses($limit = 5)
    {
        return $this->select('courses.id, courses.course_code, courses.course_name, courses.description, COUNT(enrollments.id) as enrollment_count')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('courses.status', 'active')
                    ->groupBy('courses.id')
                    ->orderBy('enrollment_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get recent enrollments with student and course details
     */
    public function getRecentEnrollments($limit = 10)
    {
        return $this->select('enrollments.*, users.name as student_name, users.email, courses.course_code, courses.course_name')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get courses with low enrollment
     */
    public function getLowEnrollmentCourses($threshold = 3)
    {
        return $this->db->table('courses')
                    ->select('courses.id, courses.course_code, courses.course_name, courses.description, COUNT(enrollments.id) as enrollment_count')
                    ->join('enrollments', 'enrollments.course_id = courses.id', 'left')
                    ->where('courses.status', 'active')
                    ->groupBy('courses.id')
                    ->having('enrollment_count <=', $threshold)
                    ->orderBy('enrollment_count', 'ASC')
                    ->get()
                    ->getResultArray();
    }
}
