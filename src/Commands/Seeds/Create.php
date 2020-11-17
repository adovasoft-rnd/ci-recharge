<?php namespace Hafiz\Commands\Seeds;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new seeder file.
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
    protected $name = 'make:seed';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a seeder file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:seed [seed_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'seed_name' => 'The Database Seeder file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set Seeder namespace',
    ];

    /**
     * Creates a new seeder file with The current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Seeds.nameSeed'));
        }

        if (empty($name)) {
            CLI::error(lang('Seeds.badCreateName'));
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


        // Always use UTC/GMT so global teams can work togeTher
        $fileName = pascalize($name) . ((stripos('seeder', $name) == false) ? 'Seeder' : '');

        // full path
        $path = $homepath . '/Database/Seeds/' . $fileName . '.php';

        // Class name should be Pascal case

        $name = $fileName;
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ns\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use ReflectionException;

/**
 * @class $name
 * @author CI-Recharge
 * @package $ns
 * @extend Seeder
 * @created $date
 */

class $name extends Seeder
{
    public function run()
    {
        \$data = [];
    
        // Using Model
        \$model = new ExampleModel();
        foreach (\$data as \$datum) {
            try {
                \$model->save(\$datum);
            } catch (ReflectionException \$e) {
                throw new Exception(\$e->getMessage());
            }
        }
        
        //Using Query Builder Class
        try {
            \$this->db->table('users')->insertBatch(\$data);
        } catch (ReflectionException \$e) {
                throw new Exception(\$e->getMessage());
        }
    }
}

EOD;

        if (!write_file($path, $template)) {
            CLI::error(lang('Seeds.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
