<?php namespace Hafiz\Libraries;

use CodeIgniter\CLI\CLI;
use Config\Services;

class FileHandler
{
    /**
     * @param string $table
     * @param string $fields
     * @param string $keys
     */
    public function writeTable(string $table, string $fields, string $keys)
    {
        helper('inflector');
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

    /**
     * @param string $path
     * @return bool
     */
    public function checkFileExist(string $path): bool
    {
        if (is_file($path)) {
            $permission = CLI::prompt("File already exists.Overwrite? ", ['yes', 'no'], 'required|in_list[yes,no]');
            if ($permission == 'no') {
                CLI::error("Task Cancelled.");
                exit(1);
            }
        }
        return true;
    }

    /**
     * Create new Directory and verify
     * if that already exist or not
     *
     * @param string $path
     * @return bool
     */
    public function verifyDirectory(string $path = ''): bool
    {
        $targetDir = $this->pathFromLocation($path);
        $permission = null;

        //if folder already exists
        if ($this->checkFolderExist($path) == true)
            return true;

        //create a folder
        if (!mkdir($targetDir, 0755, true) == true) {
            CLI::error("Directory Location is not writable.");
            return false;
        }

        return true;
    }

    /**
     * Take a File Location with file name then remove file name
     * return a Directory Location
     *
     * @param string $filePath
     * @return string
     */
    protected function pathFromLocation(string $filePath): string
    {
        return dirname($filePath);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function checkFolderExist(string $path = ''): bool
    {
        $path = realpath($this->pathFromLocation($path));

        if (!is_dir($path)) {
            CLI::error("Directory: " . $path . " is Invalid or doesn't Exists.");

            return false;
        }

        return true;
    }

    /**
     * Given a Namespace name detect Real path
     * Or return the Path of Default once.
     * @param string $space
     * @param string $default
     * @return array $pathinfo
     */
    public function getNamespaceInfo($space = null, string $default = 'App'): array
    {
        // Get all namespaces
        $namespaces = Services::autoloader()->getNamespace();

        if (!is_null($space)) {
            if (key_exists($space, $namespaces)) {
                return ['ns' => $space,
                    'path' => realpath(reset($namespaces[$space])),
                    'default' => false];
            }
            CLI::error("Namespace not found in AutoLoader. Using Default [$default]");
        }

        return ['ns' => $default,
            'path' => realpath(reset($namespaces[$default])),
            'default' => true];
    }

    /**
     * @param string $parent
     * @param string $default
     * @return array
     */
    public function getParentNamespace(string $parent, string $default): array
    {

    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function renderTemplate(string $template, array $data): string
    {
        $templateDir = realpath(__DIR__ . '/../Templates/') . '/';
        $skeleton = file_get_contents($templateDir . $template . '.php');

        return str_replace(array_keys($data), array_values($data), $skeleton);
    }
}