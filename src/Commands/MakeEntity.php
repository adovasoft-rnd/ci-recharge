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
class MakeEntity extends BaseCommand
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
    protected $name = 'make:entity';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a entity file [NB: FOLDER NAMED `Entities` IS NECESSARY].';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:entity [entity_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'entity_name' => 'The database entity file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set entity namespace',
        '-t' => 'Set entity Database table',

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
            $name = CLI::prompt(lang('Recharge.nameEntity'));
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
        $name = singular(pascalize($name));
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Entities/';
        $filepath = $targetDir . $name . '.php';

        if ($file->verifyDirectory($filepath)) {
            //do we have to add table info
            if (!empty($table)) {
                $db = new DBHandler();
                if ($db->checkTableExist($table)) {
                    $properties = $db->getEntityProperties($table);
                    extract($properties);
                }
            }

            $data = ['{namespace}' => $ns, '{name}' => $name, '{created_at}' => date("d F, Y h:i:s A"),
                '{attributes}' => $attributes ?? NULL, '{dates}' => $dates ?? NULL, '{casts}' => $casts ?? NULL];

            //check a directory exist
            if ($file->checkFileExist($filepath) == true) {
                $template = $file->renderTemplate('entity', $data);


                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }

                CLI::write('Created file: ' . CLI::color(basename($filepath), 'green'));
            }
        }
    }
}
