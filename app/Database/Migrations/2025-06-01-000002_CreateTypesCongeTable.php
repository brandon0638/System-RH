<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesCongeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 50],
            'total_jours_par_an' => ['type' => 'INTEGER', 'default' => 0],
            'couleur' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'actif' => ['type' => 'INTEGER', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('types_conge');
    }

    public function down()
    {
        $this->forge->dropTable('types_conge');
    }
}