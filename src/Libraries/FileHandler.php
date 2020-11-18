<?php


namespace Hafiz\Libraries;


class FileHandler
{
    public function __construct()
    {

    }

    /**
     * @param string $table
     * @param string $fields
     * @param string $keys
     */
    public function writeTable(string $table, string $fields, string $keys)
    {
        helper(['filesystem', 'inflector']);
        $fileName = date('Y-m-d-His') . '_create_' . $table . '_table.php';
        $targetDir = ROOTPATH . 'app/Database/Migrations/';
        $filePath = $targetDir . $fileName;

        $replace = ['{migrate}', '{fields}', '{keys}', '{table}'];

        $with = [$fields, $keys, $table];
        $newTemplate = $this->migrationTemplate();

        $finalFile = str_replace($replace, $with, $newTemplate);
        file_put_contents($filePath, $finalFile);
    }

    /**
     * @return string
     */
    protected function migrationTemplate()
    {
        return "<?php namespace App\Database\Migrations;\n\n" .
            "use CodeIgniter\Database\Migration;\n\n" .
            "/*\n" .
            " * @class {migrate} Migration\n" .
            " * @author Mohammad Hafijul Islam <hafijul233@gmail.com>\n" .
            " * @license Free to use and abuse\n" .
            " * @version 1.0.0 Beta\n" .
            " * @created_by SQL2Migration Generator\n" .
            " * @created_at " . date("d F, Y h:i:s A") . "\n" .
            " */\n\n" .
            "class Create{migrate} extends Migration\n" .
            "{\n\n\tpublic function up() \n\t{\n\t\t" .
            "\$this->forge->addField([" .
            "\t\t\t{fields}\n" .
            "\t]);\n\n\n\t\t" .
            "\t\t{keys}\n" .
            "\t\t\$this->forge->createTable('{table}');\n" .
            "\n\t}\n\n" .
            "\t//--------------------------------------------------------------------\n" .
            "\n\tpublic function down()\n" .
            "\t{\n" .
            "\t\t\$this->forge->dropTable('{table}');\n" .
            "\t}\n}";
    }
}