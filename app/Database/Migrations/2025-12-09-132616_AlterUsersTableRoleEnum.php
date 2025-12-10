<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersTableRoleEnum extends Migration
{
    public function up()
    {
        // Update any existing 'instructor' roles to 'teacher'
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role = 'instructor'");
        
        // Alter the ENUM to include teacher instead of instructor
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'teacher', 'admin') DEFAULT 'student'");
    }

    public function down()
    {
        // Revert back to instructor
        $this->db->query("UPDATE users SET role = 'instructor' WHERE role = 'teacher'");
        $this->db->query("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin') DEFAULT 'student'");
    }
}
