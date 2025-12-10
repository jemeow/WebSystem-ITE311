<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersTableTeacherToInstructor extends Migration
{
    public function up()
    {
        // Update any existing 'teacher' roles to 'instructor'
        $this->db->query("UPDATE users SET role = 'instructor' WHERE role = 'teacher'");
        
        // Alter the ENUM to include instructor instead of teacher
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin') DEFAULT 'student'");
    }

    public function down()
    {
        // Revert instructor roles back to teacher
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role = 'instructor'");
        
        // Revert the ENUM back to teacher
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'teacher', 'admin') DEFAULT 'student'");
    }
}
