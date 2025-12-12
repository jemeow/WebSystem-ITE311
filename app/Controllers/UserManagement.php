<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserManagement extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'form']);
    }

    /**
     * Display all users with their status
     */
    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $filter = $this->request->getGet('filter');
        $role = $this->request->getGet('role');
        
        // Start with base query
        if ($filter === 'active') {
            $data['users'] = $this->userModel->getActiveUsers();
        } elseif ($filter === 'inactive') {
            $data['users'] = $this->userModel->getInactiveUsers();
        } else {
            $data['users'] = $this->userModel->getAllUsersWithStatus();
        }
        
        // Apply role filter if specified
        if ($role && in_array($role, ['admin', 'teacher', 'student'])) {
            $data['users'] = array_filter($data['users'], function($user) use ($role) {
                return $user['role'] === $role;
            });
        }

        $data['filter'] = $filter;
        $data['role'] = $role;
        
        return view('admin/user_management', $data);
    }

    /**
     * Deactivate a user (soft delete)
     */
    public function deactivate($userId)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        // Prevent admin from deactivating themselves
        if ($userId == session()->get('id')) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($this->userModel->deactivateUser($userId)) {
            return redirect()->back()->with('success', 'User deactivated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to deactivate user.');
    }

    /**
     * Activate a user
     */
    public function activate($userId)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        if ($this->userModel->activateUser($userId)) {
            return redirect()->back()->with('success', 'User activated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to activate user.');
    }

    /**
     * Show create user form
     */
    public function create()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['validation'] = \Config\Services::validation();
        
        return view('admin/user_create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z\s]+$/]',
            'email' => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9.@]+$/]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[student,teacher,admin]'
        ];

        $errors = [
            'name' => [
                'regex_match' => 'Name can only contain letters and spaces (no special characters allowed).'
            ],
            'email' => [
                'regex_match' => 'Email can only contain letters, numbers, @ and . symbols.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'status' => 'active'
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to(site_url('admin/users'))->with('success', 'User created successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user.');
    }

    /**
     * Show edit form for a user
     */
    public function edit($userId)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $data['user'] = $this->userModel->find($userId);
        
        if (!$data['user']) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Prevent admin from editing their own account
        if ($userId == session()->get('id')) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot edit your own account.');
        }

        // Check if editing own account
        $data['isOwnAccount'] = ($userId == session()->get('id'));
        
        return view('admin/user_edit', $data);
    }

    /**
     * Update user information
     */
    public function update($userId)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z\s]+$/]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]|regex_match[/^[a-zA-Z0-9.@]+$/]",
            'role' => 'required|in_list[student,teacher,admin]'
        ];

        $errors = [
            'name' => [
                'regex_match' => 'Name can only contain letters and spaces (no special characters allowed).'
            ],
            'email' => [
                'regex_match' => 'Email can only contain letters, numbers, @ and . symbols.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the user being edited
        $userBeingEdited = $this->userModel->find($userId);
        if (!$userBeingEdited) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Prevent admin from editing their own account
        if ($userId == session()->get('id')) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot edit your own account.');
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        // Check if password is being changed (only for non-admin users)
        if ($userBeingEdited['role'] !== 'admin') {
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_new_password');
            
            if (!empty($newPassword)) {
                // Validate password length
                if (strlen($newPassword) < 6) {
                    return redirect()->back()->withInput()->with('errors', ['new_password' => 'Password must be at least 6 characters.']);
                }
                
                // Check if passwords match
                if ($newPassword !== $confirmPassword) {
                    return redirect()->back()->withInput()->with('errors', ['confirm_new_password' => 'Passwords do not match.']);
                }
                
                // Add password to update data
                $updateData['password'] = $newPassword;
            }
        }

        // Check if role is being changed
        $newRole = $this->request->getPost('role');
        $roleChanged = ($newRole !== $userBeingEdited['role']);

        // Prevent admin from changing their own role
        if ($userId == session()->get('id')) {
            if ($roleChanged) {
                return redirect()->back()->withInput()->with('error', 'You cannot change your own role.');
            }
        } else {
            // If role is being changed for another user, verify admin password
            if ($roleChanged) {
                $adminPassword = $this->request->getPost('admin_password');
                
                if (empty($adminPassword)) {
                    return redirect()->back()->withInput()->with('error', 'Password confirmation is required to change user roles.');
                }

                // Verify current admin's password
                $currentAdmin = $this->userModel->find(session()->get('id'));
                if (!$currentAdmin || !password_verify($adminPassword, $currentAdmin['password'])) {
                    return redirect()->back()->withInput()->with('error', 'Invalid password. Role change denied.');
                }
            }
            
            // Role verification passed or no role change, proceed with update
            $updateData['role'] = $newRole;
        }

        // Disable model validation to avoid conflicts with our controller validation
        $this->userModel->skipValidation(true);
        
        $updateResult = $this->userModel->update($userId, $updateData);
        
        // Re-enable validation
        $this->userModel->skipValidation(false);

        if ($updateResult) {
            // If admin updated their own info, update session
            if ($userId == session()->get('id')) {
                session()->set('name', $updateData['name']);
                session()->set('email', $updateData['email']);
            }
            
            $message = $roleChanged ? 
                'User updated successfully. Role changed from ' . ucfirst($userBeingEdited['role']) . ' to ' . ucfirst($newRole) . '.' :
                'User updated successfully.';
            
            return redirect()->to(site_url('admin/users'))->with('success', $message);
        }

        // Get model errors if any
        $modelErrors = $this->userModel->errors();
        $errorMessage = !empty($modelErrors) ? implode(', ', $modelErrors) : 'Failed to update user.';
        
        return redirect()->back()->withInput()->with('error', $errorMessage);
    }

    /**
     * Delete a user permanently (optional - use with caution)
     */
    public function permanentDelete($userId)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        // Prevent admin from deleting themselves
        if ($userId == session()->get('id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Instead of permanent delete, deactivate the user
        if ($this->userModel->deactivateUser($userId)) {
            return redirect()->back()->with('success', 'User has been deactivated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to deactivate user.');
    }
}
