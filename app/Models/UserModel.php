<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[student,teacher,admin]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 3 characters',
            'max_length' => 'Name cannot exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Role must be either student, teacher, or admin'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    public function findUserByNameOrEmail($identifier)
    {
        return $this->where('name', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();
    }

    public function verifyCredentials($identifier, $password)
    {
        $user = $this->findUserByNameOrEmail($identifier);

        if ($user && password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['status'] === 'inactive') {
                return false;
            }
            return $user;
        }

        return false;
    }

    public function getUserProfile($userId)
    {
        return $this->select('id, name, email, created_at, updated_at')
                    ->where('id', $userId)
                    ->first();
    }

    public function updateProfile($userId, $data)
    {
        $allowedFields = ['name', 'email'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            return $this->update($userId, $updateData);
        }

        return false;
    }

    public function changePassword($userId, $newPassword)
    {
        $data = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($userId, $data);
    }

    /**
     * Deactivate a user (soft delete)
     */
    public function deactivateUser($userId)
    {
        return $this->update($userId, ['status' => 'inactive']);
    }

    /**
     * Activate a user
     */
    public function activateUser($userId)
    {
        return $this->update($userId, ['status' => 'active']);
    }

    /**
     * Get only active users
     */
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get only inactive users
     */
    public function getInactiveUsers()
    {
        return $this->where('status', 'inactive')->findAll();
    }

    /**
     * Get all users with status filter option
     */
    public function getAllUsersWithStatus($status = null)
    {
        if ($status !== null) {
            return $this->where('status', $status)->findAll();
        }
        return $this->findAll();
    }
}
