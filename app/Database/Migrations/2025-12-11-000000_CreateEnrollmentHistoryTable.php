<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Nullable in case enrollment is deleted
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['approved', 'rejected'],
            ],
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Admin who performed the action',
            ],
            'admin_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Admin name at time of action',
            ],
            'student_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Student name at time of action',
            ],
            'course_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Course name at time of action',
            ],
            'course_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'Course code at time of action',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Optional notes or reason',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('course_id');
        $this->forge->addKey('admin_id');
        $this->forge->addKey('action');
        $this->forge->addKey('created_at');
        
        $this->forge->createTable('enrollment_history');
    }

    public function down()
    {
        $this->forge->dropTable('enrollment_history');
    }
}
