<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\DBHandler;
use Hafiz\Libraries\FileHandler;

/**
 * Make command class that create a Entity class
 * from user input with specific namespace
 * or from a table with all attributes
 * name of entity will be translated as singular
 *
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
    protected $name = 'create:entity';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a Entity file skeleton or from database Table.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'create:entity [entity_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'entity_name' => 'The Entity file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set entity Namespace',
        '-t' => 'Set entity Database table',

    ];

    /**
     * Creates a new entity file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);
        $file = new FileHandler();

        $name = array_shift($params);
        $ns = $params['-n'] ?? CLI::getOption('n');
        $table = $params['-t'] ?? CLI::getOption('t');

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameEntity'), null, 'required|string');
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

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

                CLI::write('Created file: ' . CLI::color($filepath, 'green'));
            }
        }
    }
}
