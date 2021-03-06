<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\DBHandler;
use Hafiz\Libraries\FileHandler;

/**
 * Make Commadn for Seeder File Generation Class
 * Seeder can be skeleton aor loaded from a database table
 *
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class MakeSeeder extends BaseCommand
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
    protected $name = 'create:seed';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a Seeder file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'create:seed [seed_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'seed_name' => 'The Seeder file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set seed namespace',
        '-t' => 'Set seed Database table',

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
            $name = CLI::prompt(lang('Recharge.nameSeed'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'App');

        //class & file name
        $name = singular(pascalize($name)) . ((stripos($name, 'seeder') == false) ? 'Seeder' : '');
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Database/Seeds/';
        $filepath = $targetDir . $name . '.php';

        if ($file->verifyDirectory($filepath)) {
            //do we have to add table info
            if (!empty($table)) {
                $db = new DBHandler();
                if ($db->checkTableExist($table))
                    $properties = $db->generateRowArray($table);
            }

            $data = ['{namespace}' => $ns, '{name}' => $name, '{created_at}' => date("d F, Y h:i:s A"),
                '{seeder}' => $properties ?? NULL, '{table}' => $table ?? NULL];

            //check a directory exist
            if ($file->checkFileExist($filepath) == true) {
                $template = $file->renderTemplate('seed', $data);


                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }

                CLI::write('Created file: ' . CLI::color($filepath, 'green'));
            }
        }
    }
}
