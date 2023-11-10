<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Changeslugtodescription extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('posts', 'slug');
        $this->forge->addColumn('posts', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('posts', 'description');
        $this->forge->addColumn('posts', [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
    }
}
