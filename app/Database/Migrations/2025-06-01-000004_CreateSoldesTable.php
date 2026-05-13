<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'employe_id' => ['type' => 'INTEGER'],
            'type_conge_id' => ['type' => 'INTEGER'],
            'annee' => ['type' => 'INTEGER'],
            'total_jours' => ['type' => 'INTEGER', 'default' => 0],
            'pris_jours' => ['type' => 'INTEGER', 'default' => 0],
            'restant_jours' => ['type' => 'INTEGER', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employe_id', 'employes', 'id');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id');
        $this->forge->createTable('soldes');
    }

    public function down()
    {
        $this->forge->dropTable('soldes');
    }
}