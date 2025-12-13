<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtColumns extends Migration
{
    public function up()
    {
        $tables = [
            'users',
            'courses',
            'enrollments',
            'materials',
            'lessons',
            'quizzes',
            'submissions',
            'notifications',
        ];

        foreach ($tables as $table) {
            if (!$this->db->tableExists($table)) {
                continue;
            }

            if ($this->db->fieldExists('deleted_at', $table)) {
                continue;
            }

            $this->forge->addColumn($table, [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'courses',
            'enrollments',
            'materials',
            'lessons',
            'quizzes',
            'submissions',
            'notifications',
        ];

        foreach ($tables as $table) {
            if (!$this->db->tableExists($table)) {
                continue;
            }

            if (!$this->db->fieldExists('deleted_at', $table)) {
                continue;
            }

            $this->forge->dropColumn($table, 'deleted_at');
        }
    }
}
