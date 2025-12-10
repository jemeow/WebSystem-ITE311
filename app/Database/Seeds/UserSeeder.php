<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Jesse Zurita',
                'email' => 'jesse@gmail.com',
                'role' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ogille Dane',
                'email' => 'ogillee@gmail.com',
                'role' => 'teacher',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Crystal Herda',
                'email' => 'tally@gmail.com',
                'role' => 'student',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert multiple users
        $this->db->table('users')->insertBatch($data);
    }
}
