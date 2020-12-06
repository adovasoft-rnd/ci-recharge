<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\DBHandler;
use Hafiz\Libraries\FileHandler;

/**
 * Make Command Class for creating a migration Instance skeleton class
 * Model can be created as skeleton or
 * user can point a Table to get property for model
 *
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class MakeMigration extends BaseCommand
{

    protected $db = null;

    protected $file = null;

    /**
     * The group command is heading under all
     * commands will be listed
     * @var string
     */
    protected $group = 'CI4-Recharge';

    /**
     * The Command's name
     * @var string
     */
    protected $name = 'make:migrate';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a Migration file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:migrate [migrate_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'migrate_name' => 'The Migration file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set migrate namespace',
        '-t' => 'Set migrate Database table',
        '-all' => 'Set migration for Complete database'
    ];

    /**
     * Creates a new migration file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);
        $this->file = new FileHandler();
        $this->db = new DBHandler();

        $name = array_shift($params);
        $ns = $params['-n'] ?? CLI::getOption('n');
        $table = $params['-t'] ?? CLI::getOption('t');
        $alltables = $params['-all'] ?? CLI::getOption('all');

        if (empty($name) && is_null($alltables))
            $name = CLI::prompt(lang('Recharge.migrateName'), null, 'string');

        if (empty($name) && is_null($alltables)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        //namespace locator
        $nsinfo = $this->file->getNamespaceInfo($ns, 'App');
        //class & file name
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Database/Migrations/';
        $config = config('Migrations');
        $timestamp = gmdate($config->timestampFormat);

        //if all database migration creation selected

        if (is_bool($alltables) && $alltables === true) {
            $tableNames = $this->db->getTableNames();

            foreach ($tableNames as $tableName) {
                //disable framework generation tables
                if ($tableName == 'migrations' || $tableName == 'ci_sessions') continue;

                $this->generateMigration($timestamp, $ns, $targetDir, NULL, $tableName);
            }
        } else
            $this->generateMigration($timestamp, $ns, $targetDir, $name, $table);
    }

    /**
     * @param string $timestamp
     * @param string $ns
     * @param string $targetDir
     * @param string|null $name
     * @param null $table
     */
    public function generateMigration(string $timestamp, string $ns, string $targetDir, string $name = NULL, $table = NULL): void
    {
        if (empty($name)) {
            $fileName = $timestamp . 'create_' . underscore($table) . '_table.php';
            $migrateName = pascalize('create_' . underscore($table) . '_table');
        } else {
            $fileName = $timestamp . underscore($name) . '.php';
            $migrateName = pascalize($name);
        }

        $filepath = $targetDir . $fileName;

        if ($this->file->verifyDirectory($filepath)) {
            //do we have to add table info
            if (!empty($table)) {
                if ($this->db->checkTableExist($table)) {
                    $properties = $this->db->getTableInfos($table);
                    extract($properties);
                }
            }

            $data = [
                '{namespace}' => $ns,
                '{name}' => $migrateName,
                '{created_at}' => date("d F, Y h:i:s A"),
                '{attributes}' => $attributes ?? NULL,
                '{table}' => $table ?? NULL,
                '{keys}' => $keys ?? NULL,
            ];

            //check a directory exist
            if ($this->file->checkFileExist($filepath) == true) {
                $template = $this->file->renderTemplate('migrate', $data);
                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }
                CLI::write('Created file: ' . CLI::color($filepath, 'green'));
            }
        }
    }
}
