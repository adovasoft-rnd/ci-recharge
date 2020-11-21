<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\DBHandler;
use Hafiz\Libraries\FileHandler;

/**
 * Creates a new Entity file.
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class MakeMigration extends BaseCommand
{

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
    protected $description = 'Creates a Model file [NB: FOLDER NAMED `Entities` IS NECESSARY].';

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
        'migrate_name' => 'The database model file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set migrate namespace',
        '-t' => 'Set migrate Database table',

    ];

    /**
     * Creates a new entity file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        $name = array_shift($params);
        $ns = $params['-n'] ?? CLI::getOption('n');
        $table = $params['-t'] ?? CLI::getOption('t');

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.migrateName'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        helper(['inflector', 'filesystem']);

        $file = new FileHandler();

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'App');

        //class & file name

        $migrateName = pascalize($name);
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Database/Migrations/';
        $config = config('Migrations');
        $fileName = gmdate($config->timestampFormat) . underscore($name);
        $filepath = $targetDir . $fileName . '.php';

        if ($file->verifyDirectory($filepath)) {
            //do we have to add table info
            if (!empty($table)) {
                $db = new DBHandler();
                if ($db->checkTableExist($table)) {
                    $properties = $db->getTableInfos($table);
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
            if ($file->checkFileExist($filepath) == true) {
                $template = $file->renderTemplate('migrate', $data);


                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }

                CLI::write('Created file: ' . CLI::color(basename($filepath), 'green'));
            }
        }
    }
}
