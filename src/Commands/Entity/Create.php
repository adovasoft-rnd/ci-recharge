<?php namespace Hafiz\Commands\Entity;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 *
 * Creates a new migration file.
 *
 * @package CodeIgniter\Commands
 */
class Create extends BaseCommand
{

    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'CI4-Recharge';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'make:entity';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Creates a custom Entity file.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'make:config [entity_name] [Options]';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'entity_name' => 'The database entity file name',
    ];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [
        '-n' => 'Set entity namespace',
    ];

    /**
     * Creates a new entity file with the current timestamp.
     *
     * @param array $params
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            CLI::write('Folder name: ' . CLI::color('Entities', 'light_yellow') . ' is essential.');
            $name = CLI::prompt(lang('Entity.nameEntity'));
        }

        if (empty($name)) {
            CLI::error(lang('Entity.badCreateName'));
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
            $ins = "App";
        }


        // Always use UTC/GMT so global teams can work together
        $fileName = pascalize($name);

        // full path
        $path = $homepath . '/Entities/' . $fileName . '.php';

        // Class name should be Pascal case

        $name = $fileName;
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ins\Entities;

use CodeIgniter\Entity;

/**
 * @class $name Entity.
 * @author CI-Recharge
 * @package $ins
 * @created $date
 */

class $name extends Entity
{
    /**
     * Database Table Column names
     * index = column 
     * value = default
     * Eg: ['balance' => 0.00, 'name' => null]
     */
    protected \$attributes = [];
    
    /**
     * Database Table Column To Property
     * Mapper
     * index = property
     * value = column
     * Eg: ['balance' => 'saving', 'phone' => 'mobile']
     */
    protected \$datamap = [];
    
    /**
     * Property That will use timestamp
     */
    protected \$dates = [];
    
    /**
     * Property Types Casted
     * Eg: ['is_banned' => 'boolean',
     * 'is_banned_nullable' => '?boolean']
     */
    protected \$casts = [];

}

EOD;

        if (!write_file($path, $template)) {
            CLI::error(lang('Seeds.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
