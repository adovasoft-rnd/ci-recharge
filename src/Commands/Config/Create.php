<?php namespace Hafiz\Commands\Config;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new configuration file.
 * @package CodeIgniter\Commands
 * @extends BaseCommand
 */

class Create extends BaseCommand
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
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameConfig'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badCreateName'));
            return;
        }

        $ns = $params['-n'] ?? CLI::getOption('n');

        /** @var string real path $homepath */
        $homepath = APPPATH;

        if (!empty($ns)) {
            // Get all namespaces
            $namespaces = Services::autoloader()->getNamespace();

            foreach ($namespaces as $namespace => $path) {
                if ($namespace === $ns) {
                    $homepath = realpath(reset($path));
                    break;
                }
            }
        } else {
            $ns = "Config";
        }


        // Always use UTC/GMT so global teams can work together
        $fileName = ucfirst($name);

        // full path
        $path = $homepath . '/Config/' . $fileName . '.php';

        // Class name should be Pascal case
        $name = ucfirst($name);
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ns;

use CodeIgniter\Config\BaseConfig;

/**
 * @class $name configuration.
 * @author CI-Recharge
 * @package $ns
 * @extend BaseConfig
 * @created $date
 */

class $name extends BaseConfig
{
		//
}

EOD;

        if (!write_file($path, $template)) {
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
