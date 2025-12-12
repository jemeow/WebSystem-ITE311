<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentHistoryModel extends Model
{
    protected $table            = 'enrollment_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enrollment_id',
        'user_id',
        'course_id',
        'action',
        'admin_id',
        'admin_name',
        'student_name',
        'course_name',
        'course_code',
        'notes',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    /**
     * Get all history records with pagination
     */
    public function getHistory($limit = 50, $offset = 0, $filters = [])
    {
        $builder = $this->orderBy('created_at', 'DESC');
        
        // Apply filters
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }
        
        if (!empty($filters['admin_id'])) {
            $builder->where('admin_id', $filters['admin_id']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['course_id'])) {
            $builder->where('course_id', $filters['course_id']);
        }
        
        // Filter by multiple course IDs (for teachers)
        if (!empty($filters['course_ids']) && is_array($filters['course_ids'])) {
            $builder->whereIn('course_id', $filters['course_ids']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('student_name', $filters['search'])
                ->orLike('course_name', $filters['search'])
                ->orLike('course_code', $filters['search'])
                ->orLike('admin_name', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->findAll($limit, $offset);
    }

    /**
     * Get total count with filters
     */
    public function getHistoryCount($filters = [])
    {
        $builder = $this->builder();
        
        // Apply same filters as getHistory
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }
        
        if (!empty($filters['admin_id'])) {
            $builder->where('admin_id', $filters['admin_id']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['course_id'])) {
            $builder->where('course_id', $filters['course_id']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('student_name', $filters['search'])
                ->orLike('course_name', $filters['search'])
                ->orLike('course_code', $filters['search'])
                ->orLike('admin_name', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get statistics
     */
    public function getStatistics($courseIds = null)
    {
        $approvedBuilder = $this->where('action', 'approved');
        $rejectedBuilder = $this->where('action', 'rejected');
        $todayApprovedBuilder = $this->where('action', 'approved')->where('DATE(created_at)', date('Y-m-d'));
        $todayRejectedBuilder = $this->where('action', 'rejected')->where('DATE(created_at)', date('Y-m-d'));
        
        // Filter by course IDs if provided (for teachers)
        if (!empty($courseIds) && is_array($courseIds)) {
            $approvedBuilder->whereIn('course_id', $courseIds);
            $rejectedBuilder->whereIn('course_id', $courseIds);
            $todayApprovedBuilder->whereIn('course_id', $courseIds);
            $todayRejectedBuilder->whereIn('course_id', $courseIds);
        }
        
        return [
            'total_approved' => $approvedBuilder->countAllResults(),
            'total_rejected' => $rejectedBuilder->countAllResults(),
            'today_approved' => $todayApprovedBuilder->countAllResults(),
            'today_rejected' => $todayRejectedBuilder->countAllResults(),
        ];
    }

    /**
     * Log enrollment action
     */
    public function logAction($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
}
