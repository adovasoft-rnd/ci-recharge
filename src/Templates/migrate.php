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
        //disable Foreign Key Check
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
	    {attributes}
	    ]);
        {keys}
        $this->forge->createTable('{table}');

        //enable Foreign Key Check
        $this->db->enableForeignKeyChecks();
	}

    //--------------------------------------------------------------------

    public function down()
    {
        //disable Foreign Key Check
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('{table}');

        //enable Foreign Key Check
        $this->db->enableForeignKeyChecks();
    }
}
