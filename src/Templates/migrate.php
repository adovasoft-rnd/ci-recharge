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
	    {attributes}
	    ]);
        {keys}
        $this->forge->createTable('{table}');
	}

    //--------------------------------------------------------------------

    public function down()
    {
        $this->forge->dropTable('{table}');
    }
}
