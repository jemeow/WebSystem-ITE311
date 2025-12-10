<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'form']);
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access your profile.');
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $data['user'] = $user;
        $data['validation'] = \Config\Services::validation();
        
        return view('profile/edit', $data);
    }

    /**
     * Update user profile
     */
    public function update()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access your profile.');
        }

        $userId = session()->get('id');

        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z\s]+$/]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]|regex_match[/^[a-zA-Z0-9.@]+$/]"
        ];

        $errors = [
            'name' => [
                'regex_match' => 'Name can only contain letters and spaces (no special characters allowed).'
            ],
            'email' => [
                'regex_match' => 'Email can only contain letters, numbers, @ and . symbols.'
            ]
        ];

        // Check if password is being changed
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email')
        ];

        // Add password to update if provided
        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        // Disable model validation to avoid conflicts
        $this->userModel->skipValidation(true);
        
        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            session()->set([
                'name' => $updateData['name'],
                'email' => $updateData['email']
            ]);

            return redirect()->to(site_url('profile/edit'))->with('success', 'Profile updated successfully.');
        }

        // Re-enable validation
        $this->userModel->skipValidation(false);

        return redirect()->back()->withInput()->with('error', 'Failed to update profile.');
    }
}
