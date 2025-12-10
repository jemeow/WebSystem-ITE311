<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get teacher user ID
        $teacherUser = $this->db->table('users')->where('role', 'teacher')->get()->getRowArray();
        $teacherId = $teacherUser ? $teacherUser['id'] : null;

        $data = [
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Computer Science',
                'description' => 'Fundamental concepts of computer science including algorithms, data structures, and programming basics.',
                'teacher_id' => $teacherId,
                'credits' => 3,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_code' => 'WEB201',
                'course_name' => 'Web Development Fundamentals',
                'description' => 'Learn HTML, CSS, JavaScript and modern web development practices.',
                'teacher_id' => $teacherId,
                'credits' => 4,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_code' => 'DB301',
                'course_name' => 'Database Management Systems',
                'description' => 'Study of relational databases, SQL, normalization, and database design principles.',
                'teacher_id' => $teacherId,
                'credits' => 3,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_code' => 'NET401',
                'course_name' => 'Computer Networks',
                'description' => 'Understanding network protocols, architecture, and security fundamentals.',
                'teacher_id' => $teacherId,
                'credits' => 3,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'course_code' => 'SE501',
                'course_name' => 'Software Engineering',
                'description' => 'Software development lifecycle, methodologies, testing, and project management.',
                'teacher_id' => $teacherId,
                'credits' => 4,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert courses
        $this->db->table('courses')->insertBatch($data);
    }
}
