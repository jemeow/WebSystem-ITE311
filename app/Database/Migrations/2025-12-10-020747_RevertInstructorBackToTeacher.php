<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RevertInstructorBackToTeacher extends Migration
{
    public function up()
    {
        // Update any existing 'instructor' roles to 'teacher'
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role = 'instructor'");
        
        // Alter the ENUM to include teacher instead of instructor
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'teacher', 'admin') DEFAULT 'student'");
        
        // Update the password back to teacher123 for the teacher account
        $this->db->table('users')
            ->where('email', 'ogillee@gmail.com')
            ->where('role', 'teacher')
            ->update([
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function down()
    {
        // Revert teacher roles back to instructor
        $this->db->query("UPDATE users SET role = 'instructor' WHERE role = 'teacher'");
        
        // Revert the ENUM back to instructor
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin') DEFAULT 'student'");
        
        // Revert password back to instructor123
        $this->db->table('users')
            ->where('email', 'ogillee@gmail.com')
            ->where('role', 'instructor')
            ->update([
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
}
