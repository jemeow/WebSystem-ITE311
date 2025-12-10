<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class TestUsers extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();
        
        return view('test_users', $data);
    }
}
