<?php namespace {namespace}\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
* Short description of this class usages
*
* @class {name}
* @generated_by CI-Recharge
* @package {namespace}
* @extend Migration
* @created_at {created_at}
*/

class {name} extends Migration
{
	public function up()
	{
	    $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],

             //


            'created_at' => [
                'type' => 'DATETIME',
                'null' > false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id'); // Primary Key
        $this->forge->addUniqueKey(); // Primary Key
        $this->forge->addKey(); // Index Key
        $this->forge->addForeignKey(); //Foreign Key
        
        $this->forge->createTable('');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('');
	}
}
