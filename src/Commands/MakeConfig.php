<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\FileHandler;

/**
 * Creates a new configuration file.
 * @package CodeIgniter\Commands
 * @extends BaseCommand
 */
class MakeConfig extends BaseCommand
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
    protected $name = 'make:config';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a configuration file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:config [config_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'config_name' => 'The Configuration file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set Configuration namespace',
    ];

    /**
     * Creates a new configuration file with The current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        $name = array_shift($params);

        //if namespace is given
        $ns = $params['-n'] ?? CLI::getOption('n');

        if (empty($name))
            $name = CLI::prompt(lang('Recharge.configName'));

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        helper(['inflector', 'filesystem']);

        $file = new FileHandler();

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'Config');

        //class & file name
        $name = pascalize($name);

        //target Dir
        if ($nsinfo['default']) {
            $targetDir = $nsinfo['path'] . '/';
            $ns = $nsinfo['ns'];
        } else {
            $targetDir = $nsinfo['path'] . '/Config/';
            $ns = $nsinfo['ns'] . '\Config';
        }

        $data = ['namespace' => $ns, 'name' => $name, 'created_at' => date("d F, Y h:i:s A")];

        $filepath = $targetDir . $name . '.php';

        //check a directory exist
        if ($file->checkFileExist($filepath) == true) {
            $template = $file->renderTemplate('config', $data);

            if (!write_file($filepath, $template)) {
                CLI::error(lang('Recharge.writeError', [$filepath]));
                return;
            }

            CLI::write('Created file: ' . CLI::color(basename($filepath), 'green'));
        }
    }
}
