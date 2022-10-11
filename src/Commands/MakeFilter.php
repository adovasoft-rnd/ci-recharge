<?php namespace Hafiz\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Hafiz\Libraries\FileHandler;

/**
 * Make command class for a Filter skeleton
 * class generation
 * skeleton has session and validation instance
 * already implemented
 *
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
    protected $name = 'create:filter';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a skeleton Filter file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'create:filter [filter_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'filter_name' => 'The Filter file name',
    ];

    /**
     * The Command's Option
     * @var array
     */
    protected $options = [
        '-n' => 'Set Filter namespace',
    ];

    /**
     * Creates a new Filter file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        /**
         *
         */
        helper(['inflector', 'filesystem']);
        $file = new FileHandler();

        $name = array_shift($params);

        //if namespace is given
        $ns = $params['-n'] ?? CLI::getOption('n');

        if (empty($name))
            $name = CLI::prompt(lang('Recharge.filterName'), null, 'required|string');

        if (empty($name)) {
            CLI::error(lang('Recharge.badName'));
            return;
        }

        //namespace locator
        $nsinfo = $file->getNamespaceInfo($ns, 'App');
        $ns = $nsinfo['ns'];
        $targetDir = $nsinfo['path'] . '/Filters/';

        //class & file name
        $name = pascalize($name) . ((stripos($name, 'filter') == false) ? 'Filter' : '');
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

                CLI::write('Created file: ' . CLI::color($filepath, 'green'));
            }
        }
    }
}
