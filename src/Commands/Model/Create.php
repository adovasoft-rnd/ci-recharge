<?php namespace Hafiz\Commands\Model;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

/**
 * Creates a new Model file.
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
    protected $name = 'make:model';

    /**
     * The Command's short description
     * @var string
     */
    protected $description = 'Creates a model file.';

    /**
     * The Command's usage
     * @var string
     */
    protected $usage = 'make:model [model_name] [Options]';

    /**
     * The Command's Arguments
     * @var array
     */
    protected $arguments = [
        'model_name' => 'The database model file name',
    ];

    /**
     * The Command's Options
     * @var array
     */
    protected $options = [
        '-n' => 'Set model namespace',
    ];

    /**
     * Creates a new model file with The current timestamp.
     * @param array $params
     * @return void
     */
    public function run(array $params = [])
    {
        helper(['inflector', 'filesystem']);

        $name = array_shift($params);

        if (empty($name)) {
            $name = CLI::prompt(lang('Recharge.nameModel'));
        }

        if (empty($name)) {
            CLI::error(lang('Recharge.badModelName'));
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
        $fileName = pascalize($name) . ((stripos('seeder', $name) == false) ? 'Model' : '');

        // full path
        $path = $homepath . '/Models/' . $fileName . '.php';

        // Class name should be Pascal case

        $name = $fileName;
        $date = date("d F, Y h:i:s A");
        $template = <<<EOD
<?php namespace $ns\Models;

use CodeIgniter\Model;

/**
 * @class $name.
 * @author CI-Recharge
 * @package $ns
 * @extend Model
 * @created $date
 */

class $name extends Model
{
    /**
     * Table Configuration
     */
    //protected \$DBGroup = '';
    protected \$table      = '';
    protected \$primaryKey = 'id';
    
    /**
     * Model & Table Column Customization
     */
    protected \$allowedFields = ['field1', 'field2'];
    protected \$useTimestamps = false;
    //protected \$dateFormat = '';
    protected \$createdField  = 'created_at';
    protected \$updatedField  = 'updated_at';
    protected \$deletedField  = 'deleted_at';
    
    /**
     * Return Configuration
     */
    protected \$returnType     = 'object';
    protected \$useSoftDeletes = true;
    protected \$validationRules    = [];
    protected \$validationMessages = [];
    protected \$skipValidation     = true;

    /**
     * Event /Observer Configurations
     */
    protected \$allowCallbacks = true;
    
    protected \$beforeInsert = [];
    
    protected \$afterInsert = [];
    
    protected \$beforeUpdate = [];
    
    protected \$afterUpdate = [];
    
    protected \$afterFind = [];
    
    protected \$afterDelete = [];
}

EOD;

        if (!write_file($path, $template)) {
            CLI::error(lang('Recharge.writeError', [$path]));
            return;
        }

        CLI::write('Created file: ' . CLI::color(str_replace($homepath, $ns, $path), 'green'));
    }

}
