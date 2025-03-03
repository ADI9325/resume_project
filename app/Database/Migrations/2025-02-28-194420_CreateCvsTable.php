<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCvsTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'uploaded_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true); 
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('user_id'); 
        $this->forge->createTable('cvs');
    }

    public function down() {
        $this->forge->dropTable('cvs');
    }
}