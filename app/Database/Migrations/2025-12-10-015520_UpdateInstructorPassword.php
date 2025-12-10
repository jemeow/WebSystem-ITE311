<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateInstructorPassword extends Migration
{
    public function up()
    {
        // Update instructor account password to match new credential (instructor123)
        $this->db->table('users')
            ->where('email', 'ogillee@gmail.com')
            ->where('role', 'instructor')
            ->update([
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function down()
    {
        // Revert to old teacher password
        $this->db->table('users')
            ->where('email', 'ogillee@gmail.com')
            ->where('role', 'instructor')
            ->update([
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
}
