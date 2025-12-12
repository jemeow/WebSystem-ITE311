<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleToCoursesTable extends Migration
{
    public function up()
    {
        $fields = [
            'schedule_days' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => 'Days of the week (e.g., Mon,Wed,Fri)',
            ],
            'schedule_start_time' => [
                'type' => 'TIME',
                'null' => true,
                'comment' => 'Class start time',
            ],
            'schedule_end_time' => [
                'type' => 'TIME',
                'null' => true,
                'comment' => 'Class end time',
            ],
        ];
        
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['schedule_days', 'schedule_start_time', 'schedule_end_time']);
    }
}
