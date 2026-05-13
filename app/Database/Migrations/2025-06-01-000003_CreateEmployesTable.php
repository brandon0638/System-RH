<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'employe'],
            'departement_id' => ['type' => 'INTEGER', 'null' => true],
            'date_embauche' => ['type' => 'DATE', 'null' => true],
            'actif' => ['type' => 'INTEGER', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('departement_id', 'departements', 'id');
        $this->forge->createTable('employes');
    }

    public function down()
    {
        $this->forge->dropTable('employes');
    }
}