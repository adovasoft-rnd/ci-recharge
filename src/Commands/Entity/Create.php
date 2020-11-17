<?php namespace Hafiz\Commands\Entity;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new Entity file.
 * @package CodeIgniter\Commands
 * @extend BaseCommand
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
    ];

    /**
     * Creates a new entity file with the current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameEntity'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badEntityName'));
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
            $ns = "App";
        }


        // Always use UTC/GMT so global teams can work together
        $fileName = pascalize($name);

        // Full path
        $path = $homepath . '/Entities/' . $fileName . '.php';

        // Class name should be Pascal case

        $name = $fileName;
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ns\Entities;

use CodeIgniter\Entity;

/**
 * @class $name
 * @author CI-Recharge
 * @package $ns
 * @extend Entity
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
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
