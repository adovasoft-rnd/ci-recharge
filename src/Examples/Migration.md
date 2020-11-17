# Implementation Of Migration File
#### Create a Migration file

##### Command
```$xslt
php spark make:migrate migrate_Name
```
##### Example
```
php spark make:migrate TextForm
```
##### Output
```
<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * @class TextForm
 * @author CI-Recharge
 * @package App
 * @extends Migration
 * @created 17 November, 2020 05:30:20 AM
 */
class TextForm extends Migration
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
        
        $this->forge->addPrimaryKey(['id']); // Primary Key
        $this->forge->addKey([]); // Index Key
        $this->forge->addForeignKey(); //Foreign Key
        
        $this->forge->createTable('TextForm');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('TextForm');
	}
}
```
#### Create a migrate file specific namespace
##### Command
```$xslt
php spark make:migrate migrate_name -n namespace_name
```
##### Example
```
php spark make:migrate TextForm -n robin
```

##### Output
```
<?php namespace robin\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * @class TextForm
 * @author CI-Recharge
 * @package robin
 * @extends Migration
 * @created 17 November, 2020 05:33:31 AM
 */
class TextForm extends Migration
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
        
        $this->forge->addPrimaryKey(['id']); // Primary Key
        $this->forge->addKey([]); // Index Key
        $this->forge->addForeignKey(); //Foreign Key
        
        $this->forge->createTable('TextForm');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('TextForm');
	}
}

```