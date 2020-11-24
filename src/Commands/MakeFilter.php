<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\FileHandler;

/**
 * Creates a new Filter file.
 * @package CodeIgniter\Commands
 * @extend BaseCommand
 */
class MakeFilter extends BaseCommand
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
    protected $name = 'make:filter';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a filter file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:filter [filter_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'filter_name' => 'The Configuration file name',
    ];

    /**
     * The Command's Option
     * @var array
     */
    protected $options = [
        '-n' => 'Set Configuration namespace',
    ];

    /**
     * Creates a new Filter file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        $name = array_shift($params);

        //if namespace is given
        $ns = $params['-n'] ?? CLI::getOption('n');

        if (empty($name))
            $name = CLI::prompt(lang('Recharge.filterName'));

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        helper(['inflector', 'filesystem']);

        $file = new FileHandler();

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'App');
        //class & file name
        $name = pascalize($name) . ((stripos('filter', $name) == false) ? 'Filter' : '');
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Filters/';
        $filepath = $targetDir . $name . '.php';

        if ($file->verifyDirectory($filepath)) {

            $data = ['{namespace}' => $ns, '{name}' => $name, '{created_at}' => date("d F, Y h:i:s A")];

            //check a directory exist
            if ($file->checkFileExist($filepath) == true) {
                $template = $file->renderTemplate('filter', $data);

                if (!write_file($filepath, $template)) {
                    CLI::error(lang('Recharge.writeError', [$filepath]));
                    return;
                }

                CLI::write('Created file: ' . CLI::color(basename($filepath), 'green'));
            }
        }
    }
}
